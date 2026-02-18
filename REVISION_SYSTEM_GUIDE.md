# Report Revision System - Implementation Guide

## Overview
A new feature has been added to allow admins to send submitted reports back to users for revision. When a report is sent for revision, the user receives an email notification with the specific feedback from the admin.

## Database Changes

### New Migration File
- **File**: `database/migrations/2026_02_18_000000_add_revision_fields_to_reports_table.php`
- **New Fields Added to `reports` Table**:
  - `revision_requested` (boolean, default: false) - Indicates if revision has been requested
  - `revision_requested_at` (timestamp, nullable) - When the revision was requested
  - `revision_reason` (text, nullable) - The admin's feedback/reason for requesting revision

## Code Changes

### 1. Report Model Update
**File**: `app/Models/Report.php`

- Added new fields to the `$fillable` array:
  - `revision_requested`
  - `revision_requested_at`
  - `revision_reason`

- Added new casts handling:
  ```php
  'revision_requested' => 'boolean',
  'revision_requested_at' => 'datetime',
  ```

### 2. Mail Template
**File**: `app/Mail/ReportRevisionRequestMail.php`

A new Mailable class that handles sending revision request notifications to users with:
- Report title and quarter
- Language information
- Admin feedback
- Direct link to view reports
- Professional HTML email template

**View Template**: `resources/views/emails/report-revision-request.blade.php`

A beautifully formatted email notification with:
- Clear alert about revision being requested
- Report details summary
- Highlighted revision feedback
- Clear call-to-action button
- Instructions on next steps

### 3. AdminController Method
**File**: `app/Http/Controllers/AdminController.php`

New method: `sendForRevision(Request $request, $reportId)`

**Functionality**:
- Validates the revision reason input (required, max 1000 chars)
- Checks admin has permission for the report's language
- Updates report with:
  - `revision_requested = true`
  - `revision_requested_at = current timestamp`
  - `revision_reason = admin's feedback`
  - `status = 'draft'` - Resets report to draft so user can edit
- Sends email to the user
- Returns success message

**Validation Rules**:
```php
'revision_reason' => 'required|string|max:1000'
```

### 4. Route
**File**: `routes/web.php`

New route added to admin routes:
```php
Route::post('/reports/{report}/send-for-revision', [AdminController::class, 'sendForRevision'])
    ->name('reports.send-for-revision');
```

## How to Use in Frontend

### 1. Admin Action Button
In your admin reports management UI, add a "Send for Revision" button next to each submitted report:

```html
<form action="{{ route('admin.reports.send-for-revision', $report->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-warning" onclick="showRevisionModal({{ $report->id }})">
        Send for Revision
    </button>
</form>
```

### 2. Modal/Form for Revision Reason
Create a modal that appears when admin clicks "Send for Revision":

```html
<div id="revisionModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Report for Revision</h5>
            </div>
            <div class="modal-body">
                <form id="revisionForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="revisionReason">Revision Feedback <span class="text-danger">*</span></label>
                        <textarea 
                            id="revisionReason"
                            name="revision_reason" 
                            class="form-control" 
                            rows="6"
                            placeholder="Provide specific feedback about what needs to be revised..."
                            required>
                        </textarea>
                        <small class="form-text text-muted">Max 1000 characters</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="revisionForm" class="btn btn-warning">Send for Revision</button>
            </div>
        </div>
    </div>
</div>
```

### 3. JavaScript Helper
```javascript
function showRevisionModal(reportId) {
    const form = document.getElementById('revisionForm');
    form.action = `/admin/reports/${reportId}/send-for-revision`;
    $('#revisionModal').modal('show');
}
```

## User Experience

### Before Revision Request
1. User submits report
2. Admin reviews report
3. Admin finds issues that need correction

### After Revision Request
1. Admin clicks "Send for Revision"
2. Admin enters specific feedback about what needs to be revised
3. System sends email to user with:
   - Clear notification that revision is needed
   - Admin's feedback highlighting the issues
   - Direct link to edit the report
4. User receives email and logs in
5. User navigates to reports section
6. User sees report is back in "Draft" status
7. User edits the report based on the feedback
8. User resubmits the report
9. Admin can review again

## Database Query Examples

### Check if Report Was Requested for Revision
```php
$reportsForRevision = Report::where('revision_requested', true)->get();
```

### Get Reports Requiring Revision by Admin
```php
$adminId = auth('admin')->id();
$revisionsRequested = Report::whereHas('language', function($q) use ($adminId) {
    $q->where('assigned_admin_id', $adminId);
})
->where('revision_requested', true)
->get();
```

### Get Revision History
```php
$revisionHistory = Report::where('revision_requested', true)
    ->orderBy('revision_requested_at', 'desc')
    ->get();
```

## Email Configuration

Make sure your `.env` file has proper mail configuration:
```
MAIL_DRIVER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Report Management System"
```

## Status Flow Diagram

```
Draft → Submit → Submitted
         ↑
         └─ Send for Revision (revision_requested = true)
```

## Important Notes

1. **Status Reset**: When a report is sent for revision, the status is automatically reset to "draft" so users can edit it.

2. **Revision Flag**: The `revision_requested` flag remains `true` even after the user resubmits, creating an audit trail.

3. **Permission Check**: Only admins assigned to the report's language can send it for revision.

4. **Email Notification**: The user is always notified via email with the exact feedback provided.

5. **Char Limit**: The revision reason is limited to 1000 characters to keep feedback concise and actionable.

## Testing the Feature

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Test with Tinker
```php
php artisan tinker
$report = Report::first();
$admin = Admin::first();
```

### 3. Manual Testing
1. Create a test report as a user
2. Submit the report
3. Log in as admin
4. Find the report in admin panel
5. Click "Send for Revision"
6. Enter feedback
7. Check user's email for notification

## Future Enhancements

Potential improvements that could be added:
- Track revision history (multiple revisions)
- Revision counters
- Automatic escalation after X revisions
- Admin dashboard widget showing reports pending user revision
- User preference for revision notification method
- Revision deadline reminders
