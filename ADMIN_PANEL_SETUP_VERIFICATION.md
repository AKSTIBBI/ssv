# ✅ Admin Panel Setup Verification Guide

## 🎯 Complete Setup Verification in 5 Minutes

### Step 1: Verify Files Exist
```
Check if these files exist:
✓ real/php/config.php
✓ real/php/helpers.php
✓ real/php/auth.php
✓ real/php/admin_login.php
✓ real/php/admin_dashboard.php
✓ real/php/admin_faculties.php
✓ real/json/facultyData.json
✓ index.html (updated)
```

### Step 2: Check File Permissions
```bash
# Should be readable/writable
ls -l real/json/facultyData.json
chmod 644 real/json/facultyData.json  # if needed

# Directory should be accessible
ls -ld real/json/
chmod 755 real/json/  # if needed
```

### Step 3: Test Access Path
```
Website: http://localhost/Project_SSV_Website/index.html
Admin:   http://localhost/Project_SSV_Website/real/php/admin_login.php

Both should work without errors
```

---

## 🧪 Step-by-Step Testing

### Test 1: Website Access ✓
```
1. Open: http://localhost/Project_SSV_Website/index.html
2. Page loads without errors
3. Navigation menu visible
4. "Login" dropdown shows
5. Status: __ PASS  __ FAIL
```

### Test 2: Login Navigation ✓
```
1. Hover over "Login" in navbar
2. See dropdown menu
3. See "Admin Panel Login" option
4. Click it
5. Opens new tab with admin login page
6. Status: __ PASS  __ FAIL
```

### Test 3: Unauthed Page Access
```
1. Try to access: http://localhost/Project_SSV_Website/real/php/admin_dashboard.php
2. Should redirect to admin_login.php
3. Status: __ PASS  __ FAIL
```

### Test 4: Login with Demo Credentials ✓
```
1. Go to: http://localhost/Project_SSV_Website/real/php/admin_login.php
2. Email: admin@example.com
3. Password: admin123
4. Click LOGIN
5. See dashboard after 1 second
6. Session created
7. Status: __ PASS  __ FAIL
```

### Test 5: Dashboard Display ✓
```
1. After login, dashboard shows:
   - Faculty count
   - Notices count
   - Toppers count
   - Photos count
2. Quick links working
3. System info displayed
4. Status: __ PASS  __ FAIL
```

### Test 6: Navigate to Faculties ✓
```
1. Click "Faculties" in admin nav
2. Faculty list page loads
3. Shows existing faculties (should have 17)
4. Shows "+ Add Faculty" button
5. Table with columns: Name, Designation, Image, Actions
6. Edit and Delete buttons visible
7. Status: __ PASS  __ FAIL
```

### Test 7: Add Faculty ✓
```
1. Click "+ Add Faculty"
2. Form with 3 fields:
   - Name (required)
   - Title/Designation (required)
   - Image Path (required)
3. Form clears after submit
4. Redirects to list after 2 seconds
5. New faculty appears in list
6. JSON file updated
7. Status: __ PASS  __ FAIL
```

### Test 8: Edit Faculty ✓
```
1. Click pencil icon next to faculty
2. Form pre-fills with data
3. Update a field
4. Click "Update Faculty"
5. Redirects to list
6. Changes appear in list
7. JSON file updated
8. Status: __ PASS  __ FAIL
```

### Test 9: Delete Faculty ✓
```
1. Click trash icon next to faculty
2. Confirmation dialog appears
3. Click OK
4. Faculty removed from list
5. JSON file updated
6. Success message shown
7. Status: __ PASS  __ FAIL
```

### Test 10: Logout ✓
```
1. Click Logout button
2. Redirected to login page
3. Session destroyed
4. Cannot access dashboard directly
5. Must login again
6. Status: __ PASS  __ FAIL
```

---

## 📋 Verification Checklist

| Test | Expected | Result |
|------|----------|--------|
| Website loads | No errors | __ PASS __ FAIL |
| Admin link visible | Login dropdown | __ PASS __ FAIL |
| Admin page accessible | Login form shows | __ PASS __ FAIL |
| Demo login works | Dashboard loads | __ PASS __ FAIL |
| Faculty list shows | 17 members | __ PASS __ FAIL |
| Add faculty works | New data saved | __ PASS __ FAIL |
| Edit faculty works | Data updates | __ PASS __ FAIL |
| Delete faculty works | Data removed | __ PASS __ FAIL |
| JSON file updates | Faculty cached | __ PASS __ FAIL |
| Session timeout | Auto-logout | __ PASS __ FAIL |
| CSRF protection | Form validation | __ PASS __ FAIL |
| Mobile responsive | Works on mobile | __ PASS __ FAIL |

---

## 🔍 Debug Checklist

If something doesn't work:

### 1. File Permissions
```bash
# Check PHP can read/write JSON
ls -la real/json/
chmod 755 real/json/
chmod 644 real/json/*.json
```

### 2. Check PHP Errors
```bash
# Check if PHP is running
php -v

# Check error log
tail -f real/php/logs/admin.log
```

### 3. Test File Access
```bash
# Try to read JSON file
cat real/json/facultyData.json

# Check if file is valid JSON
php -r "json_decode(file_get_contents('real/json/facultyData.json'));"
```

### 4. Clear Browser Cache
```
1. Press Ctrl+Shift+Delete
2. Clear all browsing data
3. Reload page
```

### 5. Check Config File
```php
// real/php/config.php
// Should have:
define('ADMIN_EMAIL', 'admin@example.com');
define('ADMIN_PASSWORD', 'admin123');
define('FACULTY_JSON', BASE_PATH . '/json/facultyData.json');
```

---

## 🛠️ Common Issues & Fixes

### Issue: Login page shows "404 Not Found"
```
Cause: Wrong path
Fix:   Use: http://localhost/Project_SSV_Website/real/php/admin_login.php
       NOT: http://localhost/admin_login.php
```

### Issue: Login fails with correct credentials
```
Cause: Config not loaded
Fix:   Check config.php exists and has:
       define('ADMIN_EMAIL', '...');
       define('ADMIN_PASSWORD', '...');
```

### Issue: Faculty data not saving
```
Cause: File permissions
Fix:   chmod 755 real/json/
       chmod 644 real/json/*.json
```

### Issue: JSON file corrupted
```
Cause: Manual edit or write error
Fix:   Restore from backup or use admin panel to re-add data
```

### Issue: Session expires too quickly
```
Cause: SESSION_TIMEOUT too low
Fix:   Edit real/php/config.php
       define('SESSION_TIMEOUT', 3600); // 1 hour
```

### Issue: Images not displaying
```
Cause: Wrong path format
Fix:   Use relative path: images/faculties/photo.jpg
       Not: /Project_SSV_Website/real/images/...
       Not: C:\xampp\...
```

---

## 📊 Data Verification

### Check Faculty JSON File
```bash
# View faculty data
cat real/json/facultyData.json

# Should contain array of faculties:
[
  {
    "name": "G S Kalyani",
    "title": "Director",
    "image": "images/faculties/director.jpeg"
  },
  ...
]
```

### Verify Data in Admin Panel
```
1. Login to admin
2. Click Faculties
3. Count displayed faculties
4. Should be ~17 members
5. Names should match JSON file
```

---

## 🔐 Security Verification

### Test CSRF Protection
```
1. Check form has CSRF token:
   <input type="hidden" name="csrf_token" value="...">
2. Try to submit without token (should fail)
3. Try to submit with wrong token (should fail)
4. Try to submit with correct token (should work)
```

### Test Session Security
```
1. Login and get session
2. Close browser (or wait 1 hour)
3. Try to access admin page
4. Should redirect to login
5. Session properly destroyed
```

### Test Input Validation
```
1. Try to add faculty with empty name
2. Should show error
3. Try to add with name only 255+ chars
4. Should show error
5. Only valid data gets saved
```

---

## 📈 Performance Verification

### Page Load Times
```
Admin Login:    < 500ms
Dashboard:      < 500ms
Faculty List:   < 500ms
Add Faculty:    < 1000ms (including JSON write)
```

### Memory Usage
```
Per page:       < 2MB
Login form:     < 1MB
Dashboard:      < 1.5MB
Faculty page:   < 2MB
```

---

## ✅ Final Sign-Off

Once all tests pass:

- [ ] All files verified
- [ ] Permissions correct
- [ ] website navigation works
- [ ] Login page accessible
- [ ] Demo credentials work
- [ ] Faculty CRUD works
- [ ] Data persists
- [ ] JSON updates
- [ ] Session works
- [ ] Logout works
- [ ] Mobile responsive
- [ ] No errors in browser console
- [ ] No errors in server log
- [ ] Performance acceptable

**Status:** __ READY FOR PRODUCTION

---

## 🚀 Next Steps After Verification

1. Update credentials in `config.php`
2. Test with new credentials
3. Set file backups
4. Monitor logs
5. Train staff
6. Deploy to production

---

## 📞 Emergency Checklist

If admin panel doesn't work:

1. [ ] Check files exist
2. [ ] Check file permissions (755/644)
3. [ ] Check config.php constants
4. [ ] Clear browser cache
5. [ ] Check PHP version (7.0+)
6. [ ] Check JSON syntax
7. [ ] Review error logs
8. [ ] Test with curl:
```bash
curl -X GET http://localhost/Project_SSV_Website/real/php/admin_login.php
```

---

**Verification Date:** _______________
**Verified By:** _______________
**Status:** __ PASS __ FAIL

If FAIL, document issues and review debug section above.
