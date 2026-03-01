<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function financial_defaults($overrides = []) {
    $base = [
        'id' => '',
        'title' => '',
        'category' => 'Other',
        'description' => '',
        'document_url' => '',
        'date_added' => get_current_date(),
        'date_published' => '',
        'visibility' => 'public',
        'status' => 'active',
        'date_modified' => ''
    ];
    return array_merge($base, is_array($overrides) ? $overrides : []);
}

function financials_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.financial_documents', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.financial_documents (
        id INT IDENTITY(1,1) PRIMARY KEY,
        document_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        category NVARCHAR(100) NOT NULL,
        [description] NVARCHAR(MAX) NULL,
        document_url NVARCHAR(1000) NOT NULL,
        date_added NVARCHAR(30) NULL,
        date_published NVARCHAR(30) NULL,
        visibility NVARCHAR(30) NOT NULL DEFAULT 'public',
        [status] NVARCHAR(30) NOT NULL DEFAULT 'active',
        date_modified NVARCHAR(30) NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function financial_row_to_array($row) {
    return financial_defaults([
        'id' => (string)($row['document_id'] ?? ''),
        'title' => (string)($row['title'] ?? ''),
        'category' => (string)($row['category'] ?? 'Other'),
        'description' => (string)($row['description'] ?? ''),
        'document_url' => (string)($row['document_url'] ?? ''),
        'date_added' => (string)($row['date_added'] ?? ''),
        'date_published' => (string)($row['date_published'] ?? ''),
        'visibility' => (string)($row['visibility'] ?? 'public'),
        'status' => (string)($row['status'] ?? 'active'),
        'date_modified' => (string)($row['date_modified'] ?? '')
    ]);
}

function financials_get_all() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        financials_ensure_db_table($conn);
        $sql = "SELECT document_id, title, category, [description], document_url, date_added, date_published, visibility, [status], date_modified
                FROM dbo.financial_documents
                WHERE deleted = 0
                ORDER BY id DESC";
        $stmt = @sqlsrv_query($conn, $sql);
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = financial_row_to_array($row);
            }
            if (!empty($rows)) {
                return $rows;
            }
        }
    }

    $json = get_json_data(FINANCIALS_JSON, []);
    if (!is_array($json)) return [];
    $mapped = [];
    foreach ($json as $row) {
        $mapped[] = financial_defaults($row);
    }
    return $mapped;
}

function financials_save_json_fallback($docs) {
    $payload = [];
    foreach ((array)$docs as $doc) {
        $payload[] = financial_defaults($doc);
    }
    return save_json_file(FINANCIALS_JSON, $payload);
}

function financials_add($data) {
    $doc = financial_defaults($data);
    if ($doc['id'] === '') {
        $doc['id'] = uniqid('doc_');
    }

    $conn = sqlsrv_get_connection();
    if ($conn) {
        financials_ensure_db_table($conn);
        $sql = "INSERT INTO dbo.financial_documents
                (document_id, title, category, [description], document_url, date_added, date_published, visibility, [status], date_modified, deleted, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, SYSUTCDATETIME(), SYSUTCDATETIME())";
        $params = [
            $doc['id'], $doc['title'], $doc['category'], $doc['description'], $doc['document_url'],
            $doc['date_added'], $doc['date_published'], $doc['visibility'], $doc['status'], $doc['date_modified']
        ];
        $stmt = @sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) return false;
    }

    $all = financials_get_all();
    array_unshift($all, $doc);
    financials_save_json_fallback($all);
    return true;
}

function financials_update($id, $data) {
    $id = trim((string)$id);
    if ($id === '') return false;

    $all = financials_get_all();
    $target = null;
    foreach ($all as $doc) {
        if ((string)($doc['id'] ?? '') === $id) {
            $target = $doc;
            break;
        }
    }
    if (!$target) return false;

    $updatedDoc = array_merge($target, (array)$data);
    $updatedDoc = financial_defaults($updatedDoc);
    $updatedDoc['id'] = $id;
    $updatedDoc['date_modified'] = get_current_date();

    $conn = sqlsrv_get_connection();
    if ($conn) {
        financials_ensure_db_table($conn);
        $sql = "UPDATE dbo.financial_documents
                SET title=?, category=?, [description]=?, document_url=?, date_published=?, visibility=?, [status]=?, date_modified=?, updated_at=SYSUTCDATETIME()
                WHERE document_id=?";
        $params = [
            $updatedDoc['title'],
            $updatedDoc['category'],
            $updatedDoc['description'],
            $updatedDoc['document_url'],
            $updatedDoc['date_published'],
            $updatedDoc['visibility'],
            $updatedDoc['status'],
            $updatedDoc['date_modified'],
            $id
        ];
        $stmt = @sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) return false;
    }

    foreach ($all as &$doc) {
        if ((string)($doc['id'] ?? '') === $id) {
            $doc = $updatedDoc;
            break;
        }
    }
    financials_save_json_fallback($all);
    return true;
}

function financials_delete($id) {
    $id = trim((string)$id);
    if ($id === '') return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        financials_ensure_db_table($conn);
        $sql = "UPDATE dbo.financial_documents SET deleted=1, updated_at=SYSUTCDATETIME() WHERE document_id=?";
        @sqlsrv_query($conn, $sql, [$id]);
    }

    $all = financials_get_all();
    $found = false;
    foreach ($all as $i => $doc) {
        if ((string)($doc['id'] ?? '') === $id) {
            array_splice($all, $i, 1);
            $found = true;
            break;
        }
    }
    if ($found) financials_save_json_fallback($all);
    return $found;
}

