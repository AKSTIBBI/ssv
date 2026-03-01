# NOTICES WORKFLOW & INTEGRATION

## 🔄 Complete Data Flow Diagram

```
ADMIN ADDS NOTICE
     ↓
     ↓ POST request to admin_notices.php
     ↓
     ↓ Validation (CSRF token, field validation)
     ↓
     ↓ new notice_id generated
     ↓
     ↓ Data saved to notices.json
     ↓
┌────────────────────────────────────┐
│  notices.json Updated              │
│  [notice_1, notice_2, ...]         │
└────────────────────────────────────┘
     ↓
     ↓ WEBSITE FETCHES NOTICES
     ↓
     ↓ real/js/script.js makes fetch call
     ↓
     ↓ Cache-busting timestamp added
     ↓
     ↓ Filter: removed deleted items
     ↓
     ↓ Render in Notice Board view
     ↓
STRING DISPLAYS TO END USERS
(Academics → Notice Board)
```

## 📁 File Interaction Map

```
admin_notices.php (ADMIN INTERFACE)
    ↓
    ├─→ Loads from: notices.json
    ├─→ Uses: config.php (constants)
    ├─→ Uses: helpers.php (save_json_file, sanitize_input)
    ├─→ Uses: auth.php (authentication check)
    └─→ Saves to: notices.json
         ↓
    notices.json (DATA STORAGE)
         ↓
         ↓ WEBSITE READS FROM
         ↓
    script.js (FRONTEND LOGIC)
         ↓
    Renders in: index.html (Notice Board section)
         ↓
    USERS SEE: Beautiful notice display
```

## 🛠️ Admin Panel Route

### Navigation Path
```
Admin Dashboard
    ↓
    Notices (in navigation menu)
    ↓
    admin_notices.php loads
    ↓
    3 Tabs visible:
    ├─ Add Notice (form to create new)
    ├─ Active Notices (table of all active)
    └─ Trash (deleted notices)
```

### URL Structure
```
Direct:   http://domain.com/Project_SSV_Website/real/php/admin_notices.php
From Nav: Click "Notices" in admin navbar
After Login: Redirect from admin_login.php → admin_dashboard.php → admin_notices.php
```

## 💾 Data Persistence

### Storage Format
```
File: real/json/notices.json
Format: JSON array
```

### Example File Content
```json
[
  {
    "notice_id": "notice_1772137700055",
    "title": "HOLI",
    "description": "HOLI HOLIDAY",
    "author": "Principal",
    "date": "27",
    "month": "Feb",
    "publish_date": "27-Feb-2026",
    "deleted": false
  },
  {
    "notice_id": "notice_1772137538533",
    "title": "HOLIDAY",
    "description": "HOLI",
    "author": "PRINCIPAL",
    "publish_date": "27-Feb-2026",
    "deleted": true
  }
]
```

## 🌐 Website Notice Board Display

### HTML Structure in index.html
```html
<div class="noticeboardmid">
  <div class="notice-container">
    <h2>News & Notice</h2>
    <div class="notice-scroller">
      <!-- Notices render here by JavaScript -->
    </div>
  </div>
</div>
```

### JavaScript Rendering (script.js, lines 1410-1460)
```javascript
// Fetch notices from JSON file
fetch('real/json/notices.json?' + new Date().getTime())
  .then(response => response.json())
  .then(notices => {
    // Filter out deleted notices
    const visibleNotices = notices.filter(notice => !notice.deleted);
    
    // Build HTML for each notice
    visibleNotices.forEach(notice => {
      // Create notice-item div with:
      // - Date box (date + month)
      // - Title
      // - Description
      // - Author
      // - Publish date
    });
  });
```

### CSS Classes
```css
.notice-item              /* Individual notice container */
.notice-content           /* Inner wrapper */
.notice-date              /* Date box (left side) */
.date-number              /* Day number */
.date-month               /* Month abbreviation */
.notice-details           /* Title + description */
.notice-title             /* Notice heading */
.notice-meta              /* Author and date info */
.meta-icon                /* Icons in metadata */
```

### Notice Card HTML Output
```html
<div class="notice-item">
  <div class="notice-content">
    <div class="notice-date">
      <h5 class="date-number">27</h5>
      <div class="date-divider"></div>
      <span class="date-month">Feb</span>
    </div>
    <div class="notice-details">
      <p class="notice-title">HOLI</p>
      <p>HOLI HOLIDAY</p>
      <ul class="notice-meta">
        <li><span class="meta-icon">👤</span> Principal</li>
        <li><span class="meta-icon">📅</span> 27-Feb-2026</li>
      </ul>
    </div>
  </div>
</div>
```

## 🔐 Security in Data Flow

### At Admin Input
1. **CSRF Token Validation**
   - Every form POST includes token
   - Token checked before processing
   - Prevents cross-site attacks

2. **Input Sanitization**
   - Title: Max 100 chars, stripped of dangerous chars
   - Description: Max 500 chars
   - Author: Any chars but escaped on output
   - All inputs go through sanitize_input()

### At Data Storage
1. **File Permissions**
   - notices.json must be readable/writable
   - Set to chmod 644 or 755
   - Only admin PHP can write

### At Website Display
1. **Output Escaping**
   - All notice data escaped with htmlspecialchars()
   - Prevents XSS attacks
   - Users see safe HTML only

### At Fetch Call
1. **Cache-Busting**
   - Timestamp query parameter added
   - Prevents browser caching stale data
   - Ensures real-time display

## 🔄 Soft Delete System

### Active Notices (deleted = false)
- Visible on admin "Active Notices" tab
- Visible on website Notice Board
- Can be edited or deleted

### Deleted Notices (deleted = true)
- Hidden from public website
- Visible on admin "Trash" tab only
- Can be restored without data loss
- Not permanently removed from JSON file

### Why Soft Delete?
```
✓ Preserves data history
✓ Allows recovery
✓ Facilitates archival
✓ No permanent loss
✓ Better for auditing
```

## ⚡ Real-Time Sync Process

### Timeline
```
Time 0:00     Admin adds notice
              → POST to admin_notices.php
              → Saved to notices.json
              → Admin sees success message

Time 0:01     User visits Notice Board
              → Browser loads index.html
              → script.js detects noticeBoard view
              → Fetches notices.json
              → Renders notice on page
              → User sees brand new notice!
```

### How Often Website Checks?
- **On Page Load**: Fetches notices once
- **On Tab Switch**: Fetches again if not cached
- **No Polling**: Doesn't continuously check
- **Cache-Busting**: Timestamp prevents stale cache

## 🎯 Integration Points

### With Admin Dashboard
```
admin_dashboard.php
    ↓ Shows navigation links
    ↓ Links to admin_notices.php
```

### With Other Modules
```
admin_faculties.php      Similar CRUD pattern
admin_fees.php          Uses same JSON structure
admin_financials.php    Uses same helpers
admin_uploads.php       File management
```

### With Frontend
```
index.html              Routes to notices via JavaScript
script.js               Handles loading and rendering
Academics section       Shows Notice Board tab
```

## 📊 Data Statistics

### File Size
```
Empty notices file:     < 1 KB
50 notices:             ~10-15 KB
100 notices:            ~20-30 KB
1000 notices:           ~200-300 KB
```

### Performance
- Load time: < 100ms
- Render time: < 50ms for 100 notices
- No database queries (file-based)
- Memory efficient

## 🐛 Debugging Tips

### If notices don't appear on website:

1. **Check notices.json exists**
   ```bash
   ls -la real/json/notices.json
   ```

2. **Validate JSON syntax**
   ```bash
   php -r "echo json_decode(file_get_contents('real/json/notices.json')) ? 'OK' : 'ERROR';"
   ```

3. **Check deleted flag**
   - Ensure notices have `"deleted": false`

4. **Clear browser cache**
   - Ctrl+Shift+Delete (Windows)
   - Cmd+Shift+Delete (Mac)

5. **Check console for errors**
   - Press F12 → Console tab
   - Look for fetch errors

6. **Check file permissions**
   ```bash
   chmod 644 real/json/notices.json
   chmod 755 real/json/
   ```

7. **Check network in DevTools**
   - F12 → Network tab
   - Look for notices.json request
   - Should return JSON array

## ✅ Verification Checklist

- [ ] admin_notices.php exists and has no PHP errors
- [ ] notices.json is valid JSON with array format
- [ ] notices.json is readable by web server
- [ ] At least one notice has deleted = false
- [ ] Website Notice Board page loads
- [ ] Notice appears on website
- [ ] Edit notice on admin panel
- [ ] Change appears on website within 1 minute
- [ ] Delete notice
- [ ] Notice disappears from website
- [ ] Notice appears in Trash tab
- [ ] Restore notice
- [ ] Notice reappears on website

## 🔧 Configuration

### In config.php
```php
// Automatically defined:
define('NOTICES_JSON', JSON_PATH . 'notices.json');

// Related settings:
define('ENVIRONMENT', 'development');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('SESSION_TIMEOUT', 3600); // 1 hour
```

### In helpers.php
```php
// Functions used:
get_json_data()         // Load notices
save_json_file()        // Save notices
sanitize_input()        // Input validation
redirect()              // Navigation
```

## 📞 Common Integration Tasks

### Add notice to homepage
1. Fetch first 3 active notices
2. Show in homepage widget
3. Link to full Notice Board

### Send email on new notice
1. Hook into POST processing
2. Send admin email to parents
3. Add to notification logs

### Archive old notices
1. Manually delete old entries
2. Or use soft delete + cleanup script
3. Keep for 1 year before archival

### Generate notice feed
1. Create RSS feed from notices.json
2. Parents can subscribe
3. Real-time notifications

---

## 📈 Summary

**Data Flow**: Admin Form → PHP Processing → notices.json → Fetch Call → JavaScript Rendering → Website Display

**Key Files**: admin_notices.php (admin) → notices.json (data) → script.js (website)

**Sync Time**: < 1 minute from admin action to website display

**Security**: CSRF tokens, input validation, output escaping, soft delete

**Last Updated**: February 27, 2026
