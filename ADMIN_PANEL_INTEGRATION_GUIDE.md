# 🔐 Admin Panel Integration with Website GUI

## 📍 Login Navigation

Your `index.html` has been updated. The **Admin Panel Login** now appears under the Login section in the navbar.

### Before:
```
Login
├── School Pro Login
└── Admin Login (disabled)
```

### After:
```
Login
├── School Pro Login
└── Admin Panel Login ← Opens PHP admin panel ✅
```

---

## 🚀 How to Access the Admin Panel

### Method 1: Through Website GUI (Recommended)
1. **Open your website**: `http://localhost/Project_SSV_Website/index.html`
2. **Hover over "Login"** in the navigation bar
3. **Click "Admin Panel Login"**
4. Opens in new tab → Admin login page
5. **Enter credentials:**
   - Email: `admin@example.com`
   - Password: `admin123`
6. **Click LOGIN** → Redirected to dashboard

### Method 2: Direct URL Access
Open in browser:
```
http://localhost/Project_SSV_Website/real/php/admin_login.php
```

---

## 🎯 Admin Panel Features

Once logged in, you can:

### 📋 Faculty Management
- **View all faculty members**
- **Add new faculty** (name, designation, photo)
- **Edit faculty information**
- **Delete faculty members**

### 📊 Dashboard
- See statistics (faculties, notices, toppers, photos)
- Quick navigation links
- System information

### 📌 Other Modules (Coming Soon)
- Notices management
- Fees management
- Financials management
- Upload manager

---

## 🔄 Integration Details

### Current Setup
```
Website (index.html)
    ↓
Login Dropdown
    ├── School Pro Login
    └── Admin Panel Login
        ↓
        Opens: real/php/admin_login.php (in new tab)
        ↓
        Admin Dashboard
```

### How It Works
When you click "Admin Panel Login":
1. Browser opens `real/php/admin_login.php` in new tab
2. User logs in with email and password
3. Session created (1 hour timeout)
4. Redirected to admin dashboard
5. Can manage faculty, notices, etc.

---

## 💡 Optional: Integrate Within Website (iFrame)

If you want the admin panel to appear **within your website** instead of a new tab:

### Option A: Create Admin Page in Website
Create file: `admin_page.html`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SSVET</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .admin-wrapper {
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }
        .back-link {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
        .back-link a {
            padding: 10px 20px;
            background-color: #244855;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }
        .back-link a:hover {
            background-color: #1c3a44;
        }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="index.html" onclick="loadContent('home')">← Back to Website</a>
    </div>
    <div class="admin-wrapper">
        <iframe src="real/php/admin_login.php"></iframe>
    </div>
</body>
</html>
```

Then update navigation in `index.html`:
```html
<li><a href="admin_page.html">Admin Panel Login</a></li>
```

---

## 🔑 Credentials & Security

### Default Credentials
```
Email: admin@example.com
Password: admin123
```

### Change Credentials (Production)
Edit file: `real/php/config.php`

```php
// Line 18-19
define('ADMIN_EMAIL', 'your-admin@example.com');
define('ADMIN_PASSWORD', 'your-secure-password');
```

⚠️ **IMPORTANT**: Use this in production:
```php
define('ADMIN_PASSWORD', password_hash('your-password', PASSWORD_BCRYPT));
```

---

## 📊 What You Can Manage

### Faculty Management
| Action | Steps |
|--------|-------|
| **View** | Click "Faculties" → See all members |
| **Add** | Click "+ Add Faculty" → Fill form → Save |
| **Edit** | Click pencil icon → Update → Save |
| **Delete** | Click trash icon → Confirm → Deleted |

### Faculty Data Structure
```json
{
    "name": "Ram Kumar Sharma",
    "title": "Principal (M.A., B.Ed.)",
    "image": "images/faculties/principal.jpg"
}
```

---

## 🔒 Security Features

✅ **Session Timeout** - Auto logout after 1 hour of inactivity
✅ **CSRF Protection** - Form submissions validated
✅ **Input Validation** - All data checked before saving
✅ **XSS Prevention** - User inputs sanitized
✅ **Activity Logging** - All actions recorded in logs
✅ **Error Messages** - User-friendly feedback

---

## 🎨 UI Navigation in Admin Panel

Once logged in, the admin panel shows:

```
Faculty Management
├── Dashboard (statistics)
├── Notices (manage notices)  
├── Faculties (CRUD operations) ← Main feature
├── Fees (manage fees)
├── Financials (manage financials)
└── Uploads (upload files)
```

---

## 📱 Mobile Access

The admin panel is fully responsive:
- ✅ Works on desktop
- ✅ Works on tablet
- ✅ Works on mobile
- Touch-friendly buttons
- Optimized navigation

**Mobile Access:**
```
http://localhost/Project_SSV_Website/real/php/admin_login.php
(on mobile browser)
```

---

## 🚀 Quick Start Checklist

- [x] Updated index.html with admin link
- [ ] Test login page loads
- [ ] Login with credentials
- [ ] Add a test faculty member
- [ ] Edit the faculty
- [ ] Delete the faculty
- [ ] Check dashboard statistics
- [ ] Update credentials for production
- [ ] Set proper file permissions
- [ ] Configure HTTPS (production)

---

## 🔧 Further Customization

### 1. Change Colors
Edit: `real/php/config.php`
```php
define('COLOR_PRIMARY', '#244855');    // Main color
define('COLOR_SECONDARY', '#f5a623');  // Accent
```

### 2. Customize Messages
All flash messages auto-hide after 5 seconds
Edit message text in admin_faculties.php:
```php
$flash_message = "Faculty added successfully!";
```

### 3. Add More Admin Pages
Copy `admin_faculties.php` structure:
```php
require_once 'auth.php';
require_admin_login();
// ... your code
```

---

## 📞 Troubleshooting

### Login Page Won't Load
**Solution:** Check file paths
```
Should be: /Project_SSV_Website/real/php/admin_login.php
NOT: /Project_SSV_Website/real/php/Admin_login.php (case-sensitive)
```

### Login Fails
**Solution:** Verify credentials in config.php
```
Email: admin@example.com
Password: admin123
```

### Can't Add Faculty
**Solution:** Check file permissions
```bash
chmod 644 real/json/facultyData.json
chmod 755 real/json/
```

### Faculty Image Not Showing
**Solution:** Use correct path format
```
✓ images/faculties/photo.jpg
✗ /Project_SSV_Website/real/images/faculties/photo.jpg
✗ C:/xampp/htdocs/...
```

---

## 📊 Data Flow

```
1. User visits index.html
   ↓
2. Clicks "Login" → "Admin Panel Login"
   ↓
3. Opens real/php/admin_login.php in new tab
   ↓
4. Enters email/password
   ↓
5. Session created → Redirected to dashboard
   ↓
6. Clicks "Faculties"
   ↓
7. Views/adds/edits/deletes faculty
   ↓
8. Data saved to real/json/facultyData.json
   ↓
9. Faculty data available on public website
```

---

## 🌐 File Structure

```
Project_SSV_Website/
├── index.html                    ← Website homepage
├── real/
│   ├── php/
│   │   ├── admin_login.php         ← Login page
│   │   ├── admin_dashboard.php     ← Dashboard
│   │   ├── admin_faculties.php     ← Faculty CRUD
│   │   ├── config.php              ← Configuration
│   │   ├── helpers.php             ← Utilities
│   │   ├── auth.php                ← Authentication
│   │   └── ... (other pages)
│   ├── json/
│   │   ├── facultyData.json        ← Faculty data (auto-saved)
│   │   └── ... (other data)
│   ├── images/
│   │   └── faculties/              ← Faculty photos
│   └── css/
│       └── ... (styles)
└── ... (other files)
```

---

## ✨ What's Next?

### Phase 1 (Complete)
- ✅ Faculty CRUD implemented
- ✅ Login/authentication setup
- ✅ Dashboard created
- ✅ Integration with website

### Phase 2 (Ready)
- → Implement notices management
- → Implement fees management
- → Implement financials

### Phase 3 (Future)
- → Image upload functionality
- → Pagination
- → Search/filter
- → Export to Excel/PDF

---

## 💾 Backup Recommendations

Regularly backup your data:

```bash
# Backup faculty data
cp real/json/facultyData.json real/json/facultyData.json.backup

# Backup notices
cp real/json/notices.json real/json/notices.json.backup

# Backup all JSON
cp -r real/json/ real/json_backup/
```

---

## 📝 Summary

Your website now has a **fully integrated admin panel** where:

1. ✅ Admin can login through website (new tab)
2. ✅ Manage faculty members (add/edit/delete)
3. ✅ Data persists in JSON files
4. ✅ Changes appear on website automatically
5. ✅ Session timeout (auto logout)
6. ✅ Secure CSRF protection

**You're ready to go!** 🎉

---

**Created:** February 27, 2026
**Status:** Integration Complete ✅
**Next:** Test and deploy!
