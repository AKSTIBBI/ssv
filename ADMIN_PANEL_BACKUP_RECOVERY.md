# 🔄 Admin Panel Data Backup & Recovery Guide

## 📦 Backup Strategies

### Strategy 1: Manual Daily Backup
```bash
# Create backup folder
mkdir -p real/backups

# Backup all JSON files
cp real/json/*.json real/backups/backup_$(date +%Y%m%d_%H%M%S)/

# Add to Windows Task Scheduler or cron job
```

### Strategy 2: Automated Backup Function
```php
// Add to helpers.php
function backup_json_files($backup_dir = 'real/backups') {
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backup_path = $backup_dir . '/backup_' . $timestamp;
    
    if (mkdir($backup_path, 0755, true)) {
        copy(FACULTY_JSON, $backup_path . '/facultyData.json');
        copy(NOTICES_JSON, $backup_path . '/notices.json');
        copy(FEES_JSON, $backup_path . '/fees.json');
        copy(FINANCIALS_JSON, $backup_path . '/financials.json');
        return $backup_path;
    }
    return false;
}

// Use in admin pages before save:
backup_json_files();
save_json_file(FACULTY_JSON, $updated_data);
```

### Strategy 3: Version Control Backup
```bash
# Initialize git in json directory
cd real/json
git init
git add *.json
git commit -m "Initial faculty data"

# After changes
git add *.json
git commit -m "Updated faculty list"

# View history
git log facultyData.json

# Restore old version
git checkout 6f0a9b -- facultyData.json
```

---

## 🗂️ Backup Directory Structure

```
real/backups/
├── backup_2024-01-15_09-30-45/
│   ├── facultyData.json
│   ├── notices.json
│   ├── fees.json
│   └── financials.json
├── backup_2024-01-14_14-22-10/
│   ├── facultyData.json
│   ├── notices.json
│   ├── fees.json
│   └── financials.json
└── ...
```

---

## 💾 Current JSON Files to Backup

| File | Purpose | Rows |
|------|---------|------|
| `real/json/facultyData.json` | Faculty list | ~17 |
| `real/json/notices.json` | Noticeboard | ~5-10 |
| `real/json/fees.json` | Fee structure | 1 |
| `real/json/financials.json` | Financials | Variable |
| `real/json/toppersData.json` | Toppers | ~10-20 |
| `real/json/photos.json` | Photo gallery | Variable |
| `real/json/videos.json` | Video gallery | Variable |

---

## 🔏 Backup Encryption (Optional)

```bash
# Backup with password encryption
tar czf - real/json | openssl enc -e -aes-256-cbc -out backup.tar.gz.enc

# Restore from encrypted backup
openssl enc -d -aes-256-cbc -in backup.tar.gz.enc | tar xz
```

---

## 🔍 How to Recover from Backup

### Scenario 1: File Corrupted
```bash
# 1. List available backups
ls -la real/backups/

# 2. Get timestamp of good backup
cd real/backups/backup_2024-01-15_09-30-45

# 3. Restore single file
cp facultyData.json ../../json/

# 4. Verify integrity
php -r "echo json_encode(json_decode(file_get_contents('../../json/facultyData.json'))) ? 'OK' : 'ERROR';"

# 5. Restart admin panel
```

### Scenario 2: Accidental Deletion
```bash
# 1. Check recent backups
ls -lt real/backups | head -5

# 2. Restore entire directory
cp real/backups/backup_2024-01-15_09-30-45/* real/json/

# 3. Verify all files
ls -la real/json/

# 4. Test admin panel
```

### Scenario 3: Wrong Data Type Saved
```bash
# 1. Identify backup before change
grep -r "faculty_name" real/backups/*/facultyData.json

# 2. Find correct backup
ls -lt real/backups/ | grep "2024-01-15"

# 3. Restore and verify
cp real/backups/backup_2024-01-15_09-30-45/facultyData.json real/json/

# 4. Check file validity
php -r "
\$data = json_decode(file_get_contents('real/json/facultyData.json'), true);
echo 'Records: ' . count(\$data) . PHP_EOL;
echo 'First record: ' . json_encode(\$data[0]) . PHP_EOL;
"
```

---

## 🛠️ Backup Creation Script

**File: `real/php/backup_manager.php`**

```php
<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'auth.php';

require_admin_login();

$action = $_GET['action'] ?? '';
$backup_dir = 'backups';

// Create backups directory if not exists
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

if ($action === 'create') {
    $timestamp = date('Y-m-d_H-i-s');
    $backup_path = $backup_dir . '/backup_' . $timestamp;
    
    if (mkdir($backup_path, 0755, true)) {
        $files_to_backup = [
            FACULTY_JSON,
            NOTICES_JSON,
            FEES_JSON,
            FINANCIALS_JSON
        ];
        
        $success_count = 0;
        foreach ($files_to_backup as $file) {
            if (file_exists($file)) {
                $filename = basename($file);
                if (copy($file, $backup_path . '/' . $filename)) {
                    $success_count++;
                }
            }
        }
        
        send_response('success', "Backup created: {$success_count} files", [
            'backup_path' => $backup_path,
            'timestamp' => $timestamp
        ]);
    }
} elseif ($action === 'list') {
    $backups = array_filter(
        scandir($backup_dir),
        fn($item) => strpos($item, 'backup_') === 0
    );
    
    $backup_list = [];
    foreach ($backups as $backup) {
        $path = $backup_dir . '/' . $backup;
        $size = 0;
        foreach (glob($path . '/*') as $file) {
            $size += filesize($file);
        }
        
        $backup_list[] = [
            'name' => $backup,
            'path' => $path,
            'size' => format_file_size($size),
            'created' => date('Y-m-d H:i:s', 
                strtotime(str_replace('_', ' ', 
                    substr($backup, 7))
                )
            )
        ];
    }
    
    send_response('success', 'Backups found', [
        'backups' => $backup_list
    ]);
} elseif ($action === 'restore') {
    $backup_file = basename($_GET['file'] ?? '');
    $backup_path = $backup_dir . '/' . $backup_file;
    
    if (is_dir($backup_path)) {
        $success = true;
        foreach (glob($backup_path . '/*.json') as $file) {
            $filename = basename($file);
            $target = dirname(FACULTY_JSON) . '/' . $filename;
            if (!copy($file, $target)) {
                $success = false;
            }
        }
        
        if ($success) {
            log_message('Restored backup: ' . $backup_file);
            send_response('success', 'Backup restored successfully');
        } else {
            send_response('error', 'Failed to restore backup');
        }
    } else {
        send_response('error', 'Backup not found');
    }
} else {
    send_response('error', 'Invalid action');
}
?>
```

---

## 📅 Backup Schedule

### Recommended Schedule
```
Daily:   Automatic backup at 2 AM
Weekly:  Full backup with archive compression
Monthly: Archive to external storage
Yearly:  Deep archive with encryption
```

### Cron Job Setup (Linux/Mac)
```bash
# Add to crontab
crontab -e

# Daily backup at 2 AM
0 2 * * * cd /var/www/html/Project_SSV_Website && php real/php/backup_manager.php?action=create

# Weekly cleanup (keep last 30 backups)
0 3 * * 0 find /var/www/html/Project_SSV_Website/real/backups -type d -mtime +30 -exec rm -rf {} \;
```

### Windows Task Scheduler
```batch
# Create: backup_daily.bat
@echo off
cd C:\xampp\htdocs\Project_SSV_Website
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (mkdir backups\backup_%%c%%a%%b_%time:~0,2%%time:~3,2%%time:~6,2%)
xcopy /Y real\json\*.json backups\backup_%%c%%a%%b_%time:~0,2%%time:~3,2%%time:~6,2%\

# Schedule in Task Scheduler:
# - Trigger: Daily at 2:00 AM
# - Action: Run backup_daily.bat
```

---

## 🔍 Integrity Check Script

**File: `real/php/check_integrity.php`**

```php
<?php
require_once 'config.php';
require_once 'helpers.php';

$files_to_check = [
    FACULTY_JSON,
    NOTICES_JSON,
    FEES_JSON,
    FINANCIALS_JSON,
    TOPPERS_JSON
];

$report = [
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'OK',
    'files' => []
];

foreach ($files_to_check as $file) {
    $file_report = [
        'file' => basename($file),
        'exists' => file_exists($file),
        'readable' => file_exists($file) && is_readable($file),
        'size' => file_exists($file) ? filesize($file) : 0,
        'valid_json' => false,
        'record_count' => 0,
        'last_modified' => file_exists($file) ? date('Y-m-d H:i:s', filemtime($file)) : null
    ];
    
    if ($file_report['readable']) {
        $data = json_decode(file_get_contents($file), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $file_report['valid_json'] = true;
            $file_report['record_count'] = count($data);
        } else {
            $file_report['error'] = json_last_error_msg();
            $report['status'] = 'ERROR';
        }
    } else {
        $report['status'] = 'ERROR';
    }
    
    $report['files'][] = $file_report;
}

// Output report
header('Content-Type: application/json');
echo json_encode($report, JSON_PRETTY_PRINT);

// Log if errors
if ($report['status'] === 'ERROR') {
    log_message('Integrity check failed: ' . json_encode($report));
}
?>
```

**Usage:**
```bash
# Run integrity check
curl http://localhost/Project_SSV_Website/real/php/check_integrity.php

# Output:
{
  "timestamp": "2024-01-15 10:30:00",
  "status": "OK",
  "files": [
    {
      "file": "facultyData.json",
      "exists": true,
      "readable": true,
      "size": 15234,
      "valid_json": true,
      "record_count": 17,
      "last_modified": "2024-01-15 09:30:00"
    }
  ]
}
```

---

## 🗑️ Backup Cleanup Script

```php
<?php
// Keep last 30 backups, remove older ones
$backup_dir = 'real/backups';
$max_backups = 30;

$backups = array_filter(
    scandir($backup_dir),
    fn($item) => strpos($item, 'backup_') === 0
);

// Sort by most recent
usort($backups, function($a, $b) {
    return filemtime($backup_dir . '/' . $b) - 
           filemtime($backup_dir . '/' . $a);
});

// Keep last 30, delete rest
if (count($backups) > $max_backups) {
    $to_delete = array_slice($backups, $max_backups);
    foreach ($to_delete as $old_backup) {
        $path = $backup_dir . '/' . $old_backup;
        array_map('unlink', glob($path . '/*'));
        rmdir($path);
        echo "Deleted old backup: $old_backup\n";
    }
}

// Calculate total size
$total_size = 0;
foreach ($backups as $backup) {
    $path = $backup_dir . '/' . $backup;
    foreach (glob($path . '/*') as $file) {
        $total_size += filesize($file);
    }
}

echo "Backup storage: " . formatBytes($total_size) . "\n";
?>
```

---

## 📊 Backup Status Dashboard

```php
<?php
// Add to admin_dashboard.php or create new page

$backup_dir = 'backups';
$backups = array_filter(
    scandir($backup_dir),
    fn($item) => strpos($item, 'backup_') === 0
);

usort($backups, function($a, $b) {
    return filemtime($backup_dir . '/' . $b) - 
           filemtime($backup_dir . '/' . $a);
});

$latest = $backups[0] ?? null;
$latest_time = $latest ? 
    filemtime($backup_dir . '/' . $latest) : 
    null;
?>

<div class="card">
    <h3>💾 Backup Status</h3>
    <p>Total Backups: <strong><?php echo count($backups); ?></strong></p>
    <p>Total Size: <strong><?php echo format_file_size($total_size); ?></strong></p>
    <p>Latest: <strong><?php 
        echo $latest_time ? date('Y-m-d H:i:s', $latest_time) : 'Never';
    ?></strong></p>
    
    <a href="backup_manager.php?action=create" class="btn">
        📥 Create Backup Now
    </a>
    <a href="backup_manager.php?action=list" class="btn">
        📋 View All Backups
    </a>
</div>
```

---

## ⚠️ Recovery Best Practices

1. **Always backup before updates**
   ```bash
   php backup_manager.php?action=create
   ```

2. **Test backups regularly**
   ```bash
   # Verify latest backup can be restored
   php check_integrity.php
   ```

3. **Keep multiple copies**
   - Local primary backup
   - Local rotation backup (weekly)
   - External storage backup (monthly)

4. **Document changes**
   - Log what was changed
   - Keep change history
   - Document recovery procedures

5. **Automate backup creation**
   - Daily automatic backups
   - Scheduled integrity checks
   - Automated cleanup of old backups

6. **Test recovery process**
   - Monthly restore practice
   - Document recovery time
   - Train staff on recovery

---

## 🚨 Disaster Recovery Plan

### Data Loss Scenario
```
1. Event occurs (corruption, deletion, malware)
       ↓
2. Stop all admin access
       ↓
3. Run integrity check (check_integrity.php)
       ↓
4. Identity latest good backup
       ↓
5. Restore from backup (backup_manager.php?action=restore)
       ↓
6. Verify data integrity
       ↓
7. Resume normal operations
       ↓
8. Document incident
```

### Estimated Recovery Time
- Detection: 5 minutes
- Assessment: 5 minutes
- Restore: 1-2 minutes
- Verification: 5 minutes
- **Total: 15-20 minutes**

---

## 📞 Emergency Contact List

- **Admin Email:** admin@example.com
- **Server Support:** IT Team
- **Database Host:** Local JSON files

---

## ✅ Backup Maintenance Checklist

- [ ] Check backup directory exists
- [ ] Verify permissions (755 on dirs, 644 on files)
- [ ] Create today's backup
- [ ] Verify backup integrity
- [ ] Check storage space available
- [ ] Review backup rotation policy
- [ ] Test restore process monthly
- [ ] Document any issues found
- [ ] Update recovery documentation

**Last Check:** _______________
**Status:** __ OK __ NEEDS ATTENTION

