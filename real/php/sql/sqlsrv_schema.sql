-- SQL Server schema for incremental migration (Step 1: SMS config)
-- Run this in your target database (e.g., SSV).

IF OBJECT_ID('dbo.sms_config', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.sms_config (
        id INT IDENTITY(1,1) PRIMARY KEY,
        config_key NVARCHAR(100) NOT NULL UNIQUE,
        config_value NVARCHAR(MAX) NULL,
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

IF OBJECT_ID('dbo.notices', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.notices (
        id INT IDENTITY(1,1) PRIMARY KEY,
        notice_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        description NVARCHAR(MAX) NOT NULL,
        author NVARCHAR(255) NULL,
        [date] NVARCHAR(10) NULL,
        [month] NVARCHAR(10) NULL,
        publish_date NVARCHAR(30) NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

IF OBJECT_ID('dbo.faculties', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.faculties (
        id INT IDENTITY(1,1) PRIMARY KEY,
        faculty_id NVARCHAR(80) NOT NULL UNIQUE,
        [name] NVARCHAR(255) NOT NULL,
        [title] NVARCHAR(255) NOT NULL,
        [image] NVARCHAR(500) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

IF OBJECT_ID('dbo.fees', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.fees (
        id INT IDENTITY(1,1) PRIMARY KEY,
        fee_id NVARCHAR(80) NOT NULL UNIQUE,
        class_name NVARCHAR(100) NOT NULL UNIQUE,
        monthly_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
        annual_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
        special_charges NVARCHAR(500) NULL,
        discount DECIMAL(6,2) NOT NULL DEFAULT 0,
        description NVARCHAR(MAX) NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

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
END;

IF OBJECT_ID('dbo.toppers', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.toppers (
        id INT IDENTITY(1,1) PRIMARY KEY,
        topper_id NVARCHAR(80) NOT NULL UNIQUE,
        session_year INT NOT NULL,
        student_name NVARCHAR(255) NOT NULL,
        class_name NVARCHAR(255) NOT NULL,
        rank_text NVARCHAR(100) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

IF OBJECT_ID('dbo.photos', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.photos (
        id INT IDENTITY(1,1) PRIMARY KEY,
        photo_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        category NVARCHAR(100) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;

IF OBJECT_ID('dbo.videos', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.videos (
        id INT IDENTITY(1,1) PRIMARY KEY,
        video_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        video_path NVARCHAR(1000) NOT NULL,
        category NVARCHAR(100) NOT NULL,
        is_youtube BIT NOT NULL DEFAULT 0,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END;
