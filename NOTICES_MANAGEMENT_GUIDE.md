# Notices & Events Management Guide

## 📋 Overview

The **Notices & Events Management System** is a complete admin panel module that allows administrators to manage news, events, and important announcements that are displayed on the school website's "Academics/Notice Board" page.

## 🎯 Key Features

### ✅ Complete CRUD Operations
- **Create** - Add new notices/events
- **Read** - View all active and deleted notices
- **Update** - Edit existing notices
- **Delete** - Soft delete notices to trash
- **Restore** - Restore deleted notices from trash

### ✅ Advanced Features
- Real-time synchronization with website
- Tabbed interface: Add • Active • Trash
- Notice metadata: Date, Month, Author, Publish Date
- Character limits for validation
- CSRF token protection
- Success/Error messages
- Responsive design
- Deleted notices soft-delete (not permanently removed)

### ✅ End-User Display
- Notices displayed on "Academics/Notice Board" page
- Timeline view with date display
- Notice metadata (author, publication date)
- Auto-filtered to show only active notices
- Beautiful notice card layout

## 📂 File Structure

```
real/
├── php/
│   ├── admin_notices.php         # Main notices management interface
│   ├── config.php                # Configuration (NOTICES_JSON defined)
│   ├── helpers.php               # Helper functions
│   └── auth.php                  # Authentication
├── json/
│   └── notices.json              # Notices data storage
└── js/
    └── script.js                 # Frontend display logic (lines 1410-1460)
```

## 🚀 How to Access

1. **Login to Admin Panel**: Navigate to `real/php/admin_login.php`
2. **Go to Dashboard**: After login, click "Dashboard"
3. **Click Notices**: In the navigation menu, click "Notices"

**Direct URL**: `http://your-domain.com/Project_SSV_Website/real/php/admin_notices.php`

## 📝 Adding a Notice

### Step-by-Step Guide

1. **Click "Add Notice" Tab**
   - You'll see the form to add a new notice/event

2. **Fill in Required Fields**
   - **Title** (Required, max 100 characters)
     - Examples: "Holi Holiday", "Annual Sports Day 2026"
   - **Description** (Required, max 500 characters)
     - Provide detailed information about the notice/event

3. **Optional Fields**
   - **Author Name**: Who released this notice (defaults to your login name)
   - **Date**: Day number (1-31)
   - **Month**: Month abbreviation (Jan, Feb, Mar, etc.)
   - **Publish Date**: Full date format (27-Feb-2026)

4. **Submit Form**
   - Click "Add Notice" button
   - Success message appears: "Notice added successfully!"
   - Notice appears in "Active Notices" tab

### Example Entry

```
Title: "Annual Sports Day 2026"
Description: "The annual sports day will be held on March 15, 2026. 
All class representatives should register their teams by March 10, 2026. 
For more details, contact the PE department."
Author: "Sports Committee"
Date: "15"
Month: "Mar"
Publish Date: "27-Feb-2026"
```

## ✏️ Editing a Notice

1. **Go to "Active Notices" Tab**
2. **Find the Notice**: Scroll through the table
3. **Click "Edit" Button**: For the notice you want to modify
4. **Edit Modal Opens**: With current data pre-filled
5. **Make Changes**: Update any field
6. **Click "Update Notice"**: Saves changes
7. **Success Message**: "Notice updated successfully!"

## 🗑️ Deleting a Notice

### Soft Delete (Recommended)
1. Go to "Active Notices" tab
2. Click "Delete" button for the notice
3. Confirm deletion in popup
4. Notice moves to "Trash" tab (not permanently deleted)

### Permanent Restoration
1. Go to "Trash" tab
2. Click "Restore" button
3. Notice moves back to "Active Notices"

## 📊 Notice Data Structure

Each notice in `notices.json` contains:

```json
{
  "notice_id": "notice_1772137700055",
  "title": "HOLI",
  "description": "HOLI HOLIDAY",
  "author": "Principal",
  "date": "27",
  "month": "Feb",
  "publish_date": "27-Feb-2026",
  "deleted": false
}
```

### Field Descriptions

| Field | Type | Length | Purpose |
|-------|------|--------|---------|
| notice_id | String | Auto | Unique identifier (timestamp-based) |
| title | String | Max 100 | Notice/event title |
| description | String | Max 500 | Detailed information |
| author | String | Any | Person/department releasing notice |
| date | String | 1-2 | Day number (1-31) |
| month | String | 3 | Month abbreviation (Jan-Dec) |
| publish_date | String | 11 | Full date format (DD-MMM-YYYY) |
| deleted | Boolean | - | Deletion flag (true/false) |

## 🌐 Website Display

### Where Notices Appear
- **URL**: `http://your-domain.com/Project_SSV_Website/index.html`
- **Navigation**: Academics → Notice Board
- **Content ID**: `noticeBoard`

### Display Format
Notices appear in a timeline view showing:
- Date box (day and month)
- Notice title
- Notice description
- Author name
- Publication date

### Filtering Logic
- **Active Only**: Website shows only notices where `deleted` = false
- **Auto-Sync**: Changes in admin panel appear immediately on website
- **Cache-Busting**: Timestamps prevent browser caching issues

### JavaScript Implementation
```javascript
// Located in real/js/script.js (lines 1410-1460)
fetch('real/json/notices.json?' + new Date().getTime())
  .then(response => response.json())
  .then(notices => {
    const visibleNotices = notices.filter(notice => !notice.deleted);
    // Display in notice board
  });
```

## 🔒 Security Features

### CSRF Protection
- All forms include CSRF tokens
- Tokens validated server-side before processing

### Input Validation
- Titles: Max 100 characters, required
- Descriptions: Max 500 characters, required
- Author: Optional, any characters
- Dates: Number validation
- All inputs sanitized before storage

### Authentication
- Admin login required
- Session timeout: 1 hour (3600 seconds)
- Automatic redirect to login if session expires

### XSS Prevention
- All output escaped with `htmlspecialchars()`
- Prevents script injection attacks

## 📋 Admin Interface Tabs

### Tab 1: Add Notice
- Clean form for creating new notices
- Character counters for text fields
- Optional date fields with helpful placeholders
- Submit and Reset buttons

### Tab 2: Active Notices
- Table of all active (not deleted) notices
- Shows: Title, Description snippet, Author, Date
- Action buttons:
  - **Edit**: Modify notice details
  - **Delete**: Move to trash

### Tab 3: Trash
- Shows all deleted notices
- Soft-delete approach (can be restored)
- Action buttons:
  - **Restore**: Return to active notices

## ⚙️ Technical Details

### Backend Functions (PHP)

**Add Notice**
```php
// POST: action=add
// Validates title and description
// Creates unique notice_id
// Saves to notices.json
```

**Edit Notice**
```php
// POST: action=edit
// Updates notice by notice_id
// Re-validates all fields
```

**Delete Notice**
```php
// POST: action=delete
// Sets deleted flag to true
// Keeps data intact
```

**Restore Notice**
```php
// POST: action=restore
// Sets deleted flag to false
```

### Database (JSON)
- **File**: `real/json/notices.json`
- **Format**: JSON array of objects
- **Size**: Typically < 100 KB (can store 1000+ notices)
- **Permissions**: Must be writable (chmod 644 or 755)

### Frontend Integration
- **File**: `real/js/script.js`
- **Lines**: 1410-1460 (notice board rendering)
- **Cache-Busting**: Timestamp query param on fetch calls

## 🐛 Troubleshooting

### Issue: Changes Not Appearing on Website
**Solution**: 
- Check browser cache (Ctrl+Shift+Delete)
- Verify notices are not marked as deleted
- Check if notices.json file exists and is readable

### Issue: "Failed to save notice" Error
**Solution**:
- Check file permissions: `chmod 755 real/json/`
- Verify notices.json is writable
- Clear temp files

### Issue: Session Timeout
**Solution**:
- Login again
- Session timeout is 1 hour
- Session resets on every action

### Issue: Character Limit Exceeded
**Solution**:
- Title: Max 100 characters
- Description: Max 500 characters
- Clear and try again with shorter text

## 📊 Statistics and Monitoring

### View Counts
- Active Notices Count: Displayed in "Active Notices" tab title
- Trash Count: Displayed in "Trash" tab title
- Total: Sum of active and deleted

### Recent Activity
- Check `real/php/logs/admin.log` for activity logs
- Each CRUD operation is logged with timestamp

## 🎓 Best Practices

### For Notice Titles
- Keep concise and descriptive
- Use proper capitalization: "Annual Sports Day" (not "annual sports day")
- Include dates if relevant: "Holi Holiday - March 2026"

### For Descriptions
- Write clear, grammatically correct text
- Include important details: dates, deadlines, requirements
- Mention contact person if applicable
- Keep it professional

### For Author Field
- Use position/role: "Principal", "Director", "Sports Committee"
- Makes notice source clear to parents
- Leave blank if you want auto-filled name

### For Dating
- Use consistent date format
- Match system dates for clarity
- Update publish date to today's date

## 📈 Analytics

### Track Notice Impact
- Monitor which notices get the most engagement
- Use publication dates to identify peak announcement times
- Adjust announcement schedule based on school calendar

### Archival
- Old notices can be marked as deleted
- Never permanently removes data (recoverable from trash)
- Maintains historical record of announcements

## 🔄 Integration with Other Modules

### Related Admin Modules
1. **Admin Faculties** - Faculty information
2. **Admin Fees** - Fee structures
3. **Admin Financials** - Legal documents
4. **Admin Uploads** - File uploads

### Data Sync
- All modules use same JSON-based architecture
- Changes reflect immediately on website
- No database sync delays

## 📞 Support

### Common Questions

**Q: Can I upload attachments with notices?**
- A: Use the "Uploads" module to upload files separately, then reference in notice description.

**Q: Can I schedule notices for future date?**
- A: Currently manual. Set a reminder to publish when needed.

**Q: How long are notices kept?**
- A: Indefinitely. Soft-deleted notices can be restored anytime.

**Q: Who can see notices?**
- A: All website visitors. Only active (non-deleted) notices visible on website.

### File Permissions

Ensure these file permissions are set correctly:

```bash
# Make JSON files writable
chmod 644 real/json/notices.json

# Make directories readable/executable
chmod 755 real/json/
chmod 755 real/php/
```

## ✅ Checklist

Use this checklist when managing notices:

- [ ] Title is clear and descriptive
- [ ] Description is complete and accurate
- [ ] Author attribution is correct
- [ ] Date information is accurate
- [ ] Publish date is set to today or appropriate date
- [ ] No sensitive information in notice
- [ ] Spelling and grammar are correct
- [ ] Notice is active (not deleted)
- [ ] Changes appear on website within 1 minute
- [ ] Archives old notices periodically

## 🎉 Summary

The Notices & Events Management System provides a complete, user-friendly interface for managing school announcements and events. With real-time synchronization, comprehensive security, and soft-delete functionality, administrators can confidently manage all notices while maintaining data integrity.

**Key Takeaway**: Every notice created in the admin panel instantly appears on the website's Notice Board page for all visitors to see.

---

**Last Updated**: February 27, 2026
**Version**: 1.0
**Admin Panel URL**: `/Project_SSV_Website/real/php/admin_notices.php`
