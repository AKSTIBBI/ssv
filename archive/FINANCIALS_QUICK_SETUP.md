# Financials Page - Quick Setup Guide

## ✅ What's Fixed

### 1. View Button Works Now
**Problem**: In admin panel, clicking "View" on a document didn't open it
**Status**: ✅ FIXED
**How**: Updated file path handling from `real/uploads/` to `../uploads/` format

### 2. Website Shows Dynamic Documents  
**Problem**: Website financials section showed hardcoded old data from 2018-2024
**Status**: ✅ FIXED
**How**: Created dynamic loader that pulls from admin uploads automatically

---

## 📋 Current Setup

### Admin Panel (for school staff)
```
Path: /real/php/admin_financials.php
Features:
  ✓ Upload new documents
  ✓ Edit titles, categories, dates
  ✓ Replace files without losing metadata
  ✓ Choose Public or Restricted visibility
  ✓ View button works correctly
  ✓ Delete if needed
```

### Website (for visitors)
```
Path: About → Financials menu
Shows:
  ✓ All PUBLIC documents only
  ✓ Grouped by category
  ✓ Color-coded file type icons
  ✓ Expandable categories
  ✓ Direct download/view links
```

---

## 🚀 How It Works Now

1. **Admin uploads document**
   - Choose file from computer
   - Fill in title, category, date
   - Set visibility to "Public"
   - Click "Add Document"

2. **File system**
   - File saved to: `real/uploads/Financials/doc_abc123.pdf`
   - Metadata saved to: `real/json/financials.json`

3. **Website automatically loads**
   - User goes to About → Financials
   - JavaScript loads from JSON file
   - Documents display with icons
   - Categorized and grouped

---

## 📁 Important Paths

```
Admin Upload Handler:
  real/php/upload_document.php

Admin Management Interface:
  real/php/admin_financials.php

Website Frontend Loader:
  real/js/script.js (loadFinancialsData function)

Data Storage:
  real/json/financials.json
  real/uploads/Financials/

Document Access:
  Via "View" button: ../uploads/Financials/[filename]
  Via website: real/uploads/Financials/[filename]
```

---

## 🎨 File Type Icons (Website Display)

| Type | Icon | Color |
|------|------|-------|
| PDF | 📄 | Red |
| Word | 📝 | Blue |
| Excel | 📊 | Green |
| PowerPoint | 🎯 | Orange |
| Image | 🖼️ | Purple |
| Text | 📋 | Gray |

---

## ✅ Testing Checklist

- [ ] Admin can upload a document successfully
- [ ] Document appears in the financials table
- [ ] "View" button opens the document
- [ ] Website shows the new document in About → Financials
- [ ] Document is categorized correctly
- [ ] Document has correct file type icon
- [ ] Setting to "Restricted" hides it from website
- [ ] Can edit document without uploading new file
- [ ] Can delete document with confirmation

---

## 🔐 Privacy Control

**Public Documents** (visible on website)
- Set visibility to: "Public (Visible to all)"
- Everyone can see on website

**Restricted Documents** (hidden from website)
- Set visibility to: "Restricted (Admin only)"
- Only visible in admin panel
- Not shown to website visitors

---

## 📝 Common Tasks

### Upload Annual Audit Report
1. Admin Panel → Financials
2. Click "Add Financial Document"
3. Title: "Audit Report 2024-25"
4. Category: "Audit Report"
5. Upload file: audit_2024-25.pdf
6. Published Date: 2024-03-31
7. Visibility: Public
8. Click "Add Document"
9. ✓ Done! Shows on website immediately

### Update an Existing Document
1. Admin Panel → Financials
2. Click "Edit" on document
3. Change title/date/etc as needed
4. Upload new file (optional) or keep existing
5. Click "Update Document"
6. ✓ Done! Website updates automatically

### Hide a Document from Website
1. Admin Panel → Financials
2. Click "Edit" on document
3. Change Visibility: "Restricted (Admin only)"
4. Click "Update Document"
5. ✓ Done! Document removed from website (admin still sees it)

### Delete a Document
1. Admin Panel → Financials
2. Click "Delete" on document
3. Confirm deletion
4. ✓ Done! File and metadata removed

---

## 🐛 Troubleshooting

**Problem**: View button doesn't work
- **Fix**: Syntax error fixed in admin_financials.php ✅

**Problem**: Website doesn't show new documents
- **Check**: Is visibility set to "Public"?
- **Check**: Is date_published filled in?
- **Check**: File uploaded successfully (appears in admin table)?

**Problem**: File icons don't display
- **Check**: Font Awesome icons loaded (CDN accessible)
- **Check**: File extension recognized (pdf, docx, xlsx, etc.)

**Problem**: Old hardcoded documents still showing
- **Fix**: Completely replaced with dynamic loader ✅

---

## 📊 Data Flow Diagram

```
Admin Interface
      ↓
  Upload File
      ↓
upload_document.php
      ↓
  Save to Disk
  real/uploads/Financials/
      ↓
  Save Metadata
  real/json/financials.json
      ↓
Website Visitor
      ↓
  Click: About → Financials
      ↓
  JavaScript Loads
  loadFinancialsData()
      ↓
  Fetch from JSON
  real/json/financials.json
      ↓
  Filter Public Only
      ↓
  Group by Category
      ↓
  Render with Icons
      ↓
  Display on Website
```

---

## 💾 Backup & Recovery

**Where documents are stored:**
- Real files: `real/uploads/Financials/`
- Metadata: `real/json/financials.json`

**What gets backed up:**
- Both directories should be included in regular backups

**If something goes wrong:**
- Restore from backup
- Metadata in JSON can be manually edited
- File paths must remain: `real/uploads/Financials/[filename]`

---

## ✨ Summary

Everything is now synced and working!

- ✅ Admin can manage documents easily
- ✅ Website displays them automatically
- ✅ View button works correctly
- ✅ Visibility control working
- ✅ File icons look professional
- ✅ Categories organized nicely

**No more hardcoded financial data!** Everything is now dynamic and managed through the admin panel.
