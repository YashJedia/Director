# Report Revision System - Quick Reference Card

## 🎯 Quick Overview
Admin sends report → User gets email → User fixes it → Resubmits

---

## 🔑 Key Files

| File | Purpose |
|------|---------|
| `2026_02_18_000000_add_revision_fields_to_reports_table.php` | Migration (add 3 fields) |
| `app/Mail/ReportRevisionRequestMail.php` | Email class |
| `resources/views/emails/report-revision-request.blade.php` | Email template |
| `app/Models/Report.php` | Updated model |
| `app/Http/Controllers/AdminController.php` | New `sendForRevision()` method |
| `routes/web.php` | New route |

---

## 🚀 Quick Start

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Add UI Button
```html
<button onclick="openRevisionModal({{ $report->id }})">
    Send for Revision
</button>
```

### 3. Create Modal (see REVISION_FEATURE_FRONTEND_EXAMPLE.html)

### 4. Test
- Create report → Submit → Send for revision → Check email

---

## 📦 New Database Fields

```php
$table->boolean('revision_requested')->default(false);
$table->timestamp('revision_requested_at')->nullable();
$table->text('revision_reason')->nullable();
```

---

## 🔗 New Route

```
POST /admin/reports/{report}/send-for-revision
name: admin.reports.send-for-revision
```

---

## 📧 Mail Usage

```php
Mail::to($user->email)->send(
    new ReportRevisionRequestMail($report, $reason, $adminName)
);
```

---

## validation Rules

```php
'revision_reason' => 'required|string|max:1000'
```

---

## 🔍 What Happens When Admin Sends for Revision

1. ✅ Validates revision reason (required, max 1000 chars)
2. ✅ Checks admin has permission
3. ✅ Sets `revision_requested = true`
4. ✅ Records `revision_requested_at` timestamp
5. ✅ Stores `revision_reason` from admin input
6. ✅ Resets `status` to 'draft'
7. ✅ Sends email to user
8. ✅ Returns success message

---

## 🔐 Permission Checks

- Super Admin: ❌ BLOCKED (can't send for revision)
- Regular Admin: ✅ Can send only for their languages
- User: ✅ Receives email & can edit

---

## 📧 Email Template Variables

```php
$report          // Report object
$revisionReason  // Admin's feedback
$adminName       // Admin who sent it
```

---

## 🧪 Quick Test

```php
// In tinker or test:
$admin = Admin::first();
$report = Report::first();

// Manually test:
$report->update([
    'revision_requested' => true,
    'revision_requested_at' => now(),
    'revision_reason' => 'Test feedback'
]);

// Or use route:
post('/admin/reports/1/send-for-revision', [
    'revision_reason' => 'Please fix the data'
])
```

---

## 🎨 User Experience

```
┌─ Admin Action ──┐
│  • Reviews Report
│  • Clicks "Send for Revision"
│  • Types Feedback (max 1000 chars)
│  • Clicks "Send"
└──────┬──────────┘
       │
       ↓
┌─ System Action ─┐
│  • Validates
│  • Updates DB
│  • Resets to draft
│  • Sends email
└──────┬──────────┘
       │
       ↓
┌─ User Action ──┐
│  • Receives email
│  • Reads feedback
│  • Logs in
│  • Edits report
│  • Resubmits
└─────────────────┘
```

---

## 📊 Database Queries

```php
// Get all revision requests
Report::where('revision_requested', true)->get();

// Get admin's revisions sent
Report::whereHas('language', function($q) {
    $q->where('assigned_admin_id', auth('admin')->id());
})->where('revision_requested', true)->get();

// Get user's revisions
auth()->user()->reports()->where('revision_requested', true)->get();
```

---

## ⚙️ Configuration

### Email Setup (.env)
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_FROM_ADDRESS=noreply@example.com
```

### For Testing
```env
MAIL_DRIVER=log  # See emails in laravel.log
```

---

## 🎓 Model Relationships

```
Report
  ├── user()          → User (belongsTo)
  ├── language()      → Language (belongsTo)  
  ├── reviewer()      → Admin (belongsTo)
  └── comments()      → ReportComment (hasMany)
```

---

## 🔄 Report Status Flow

```
Draft → Submit → Submitted
                   ↑
                   └── Send for Revision → Draft (again)
```

---

## 💾 updateReport Adds

```php
$report->update([
    'revision_requested' => true,
    'revision_requested_at' => now(),
    'revision_reason' => $request->revision_reason,
    'status' => 'draft'
]);
```

---

## 🚨 Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| "revision reason is required" | Empty feedback | Fill in feedback |
| "revision reason may not be greater than 1000" | Too long | Shorten to 1000 chars |
| "You can only send reports for languages assigned to you" | Wrong language | Check language assignment |
| "Super Admin can only manage languages" | Wrong user role | Use regular admin account |

---

## 📱 Frontend Button Examples

### Simple Button
```html
<button class="btn btn-warning" data-toggle="modal" data-target="#revisionModal">
    Send for Revision
</button>
```

### With Icon
```html
<button class="btn btn-warning">
    <i class="fas fa-redo"></i> Send for Revision
</button>
```

### Disabled Condition
```html
@if($report->status == 'submitted')
<button>Send for Revision</button>
@endif
```

---

## 📋 Integration Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Check email config in `.env`
- [ ] Create/update reports view with button
- [ ] Add modal for feedback input
- [ ] Add character counter JS
- [ ] Test with real user flow
- [ ] Check email inbox
- [ ] Verify status changed to draft
- [ ] Test user re-editing
- [ ] Test re-submission

---

## 🐛 Debugging Tips

```php
// Check email was queued
Log::info('Revision sent', ['report' => $report->id]);

// Verify report state
dd($report->toArray());

// Test email sending
Mail::to('test@example.com')->send(
    new ReportRevisionRequestMail($report, 'test', 'Admin')
);

// Check if fields exist
Schema::hasColumn('reports', 'revision_requested');
```

---

## 📞 Common Questions

**Q: Can a Super Admin send for revision?**
A: No, only regular admins can.

**Q: Can admin revise someone else's language reports?**
A: No, only their assigned languages.

**Q: Does the report stay marked as revised after resubmission?**
A: Yes, `revision_requested` stays true (creates audit trail).

**Q: Is there a character limit?**
A: Yes, 1000 characters max for feedback.

**Q: Does user get email notification?**
A: Yes, always. It includes the feedback and a link to edit.

**Q: What happens to report status?**
A: Gets reset to 'draft' so user can edit.

**Q: Can admin see revision reason later?**
A: Yes, it's stored in `revision_reason` field.

---

## 🎯 Next Steps

1. ✅ Run migration
2. ✅ Test the feature
3. ✅ Add UI button/modal
4. ✅ Configure email (if not done)
5. ✅ Deploy to production
6. ✅ Monitor and gather feedback

---

**Quick Links:**
- 📖 Full Guide: `REVISION_SYSTEM_GUIDE.md`
- 🔧 Frontend Example: `REVISION_FEATURE_FRONTEND_EXAMPLE.html`
- 🧪 API/Testing: `REVISION_API_TESTING_GUIDE.js`
- 📝 Implementation: `IMPLEMENTATION_SUMMARY.md`

---

**Last Updated:** February 18, 2026  
**Status:** ✅ Ready for Production
