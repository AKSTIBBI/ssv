# SQL Server Setup (Step 1)

This project now supports DB-first storage for:
- SMS settings
- Notices
- Faculties
- Fees
- Financial documents
- Toppers
- Photos
- Videos
with JSON fallback.

## 1) Create table
Run:

- [`sqlsrv_schema.sql`](/c:/xampp/htdocs/Project_SSV_Website/real/php/sql/sqlsrv_schema.sql)

## 2) Set environment variables (Apache/PHP)

- `SSV_SQLSRV_ENABLED=1`
- `SSV_SQLSRV_HOST=localhost`
- `SSV_SQLSRV_DATABASE=SSV`
- `SSV_SQLSRV_USERNAME=your_user`
- `SSV_SQLSRV_PASSWORD=your_password`

If username/password are left empty, integrated auth is attempted.

## 3) Restart Apache/PHP

After restart:
- Admin SMS settings page reads/writes DB first.
- JSON (`real/json/sms_config.json`) stays as fallback/backup.

## 4) Verify

1. Open `Admin -> SMS Settings`
2. Save changes
3. Confirm row exists in `dbo.sms_config` with `config_key = 'schoolpro_sms'`

4. Add/Edit/Delete a notice from `Admin -> Notices`
5. Confirm data appears in `dbo.notices`

6. Add/Edit/Delete a faculty from `Admin -> Faculties`
7. Confirm data appears in `dbo.faculties`

8. Add/Edit/Delete a fee row from `Admin -> Fees`
9. Confirm data appears in `dbo.fees`

10. Add/Edit/Delete a file entry from `Admin -> Financials`
11. Confirm data appears in `dbo.financial_documents`

12. Add/Edit/Delete topper entries from `Admin -> Toppers`
13. Confirm data appears in `dbo.toppers`

14. Add/Edit/Delete gallery photos from `Admin -> Photos`
15. Confirm data appears in `dbo.photos`

16. Add/Edit/Delete gallery videos from `Admin -> Videos`
17. Confirm data appears in `dbo.videos`
