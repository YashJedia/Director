/**
 * REPORT REVISION SYSTEM - API & TESTING GUIDE
 * 
 * This file demonstrates how to:
 * 1. Test the revision endpoint
 * 2. Query reports with revision status
 * 3. Handle revision scenarios
 */

// ============================================
// 1. SEND REPORT FOR REVISION - API REQUEST
// ============================================

POST /admin/reports/{reportId}/send-for-revision
Content-Type: application/json
Authorization: Bearer <admin-token>

Request Body:
{
    "revision_reason": "Please revise the following sections:\n1. Section I - Languages data needs verification\n2. Section III - Bible course numbers incomplete\n3. Add details to Section VII - Organizational concerns"
}

Response (Success):
{
    "status": "success",
    "message": "Report has been sent back for revision. Email notification sent to John Doe"
}

Response (Error - Validation):
{
    "message": "The given data was invalid.",
    "errors": {
        "revision_reason": [
            "The revision reason field is required."
        ]
    }
}

Response (Error - Permission):
{
    "status": "error",
    "message": "You can only send reports for languages assigned to you for revision."
}


// ============================================
// 2. FORM-BASED REQUEST (HTML FORM)
// ============================================

<form method="POST" action="/admin/reports/123/send-for-revision">
    @csrf
    <textarea name="revision_reason" required maxlength="1000">
        Your revision feedback here...
    </textarea>
    <button type="submit">Send for Revision</button>
</form>


// ============================================
// 3. DATABASE QUERIES - REPORTING & ANALYSIS
// ============================================

// Get all reports sent for revision
SELECT * FROM reports WHERE revision_requested = true;

// Get reports in revision status for a specific admin
SELECT r.* FROM reports r
JOIN languages l ON r.language_id = l.id
WHERE l.assigned_admin_id = 1
AND r.revision_requested = true;

// Get recent revisions (last 30 days)
SELECT * FROM reports 
WHERE revision_requested = true
AND revision_requested_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
ORDER BY revision_requested_at DESC;

// Get user's reports sent for revision
SELECT * FROM reports 
WHERE user_id = 5
AND revision_requested = true
ORDER BY revision_requested_at DESC;


// ============================================
// 4. LARAVEL ELOQUENT QUERY EXAMPLES
// ============================================

use App\Models\Report;
use App\Models\Admin;
use App\Models\User;

// Get all reports sent for revision
$revisionsRequested = Report::where('revision_requested', true)->get();

// Get reports sent for revision by a specific admin
$admin = Admin::find(1);
$adminRevisions = Report::whereHas('language', function($query) use ($admin) {
    $query->where('assigned_admin_id', $admin->id);
})
->where('revision_requested', true)
->with(['user', 'language', 'reviewer'])
->orderBy('revision_requested_at', 'desc')
->get();

// Get recent revisions with admin details
$recentRevisions = Report::where('revision_requested', true)
    ->with(['user', 'language', 'reviewer'=>function($q) {
        $q->select('id', 'name', 'email');
    }])
    ->orderBy('revision_requested_at', 'desc')
    ->limit(20)
    ->get();

// Check if report has been sent for revision
$report = Report::find(1);
if ($report->revision_requested) {
    echo "Sent for revision on: " . $report->revision_requested_at->format('Y-m-d H:i:s');
    echo "Reason: " . $report->revision_reason;
}

// Get count of reports awaiting revision from users
$reviewsAwaitingAction = Report::where('revision_requested', true)
    ->where('status', 'draft')
    ->count();

// Get reports that were revised but resubmitted
$resubmittedAfterRevision = Report::where('revision_requested', true)
    ->where('status', 'submitted')
    ->whereDate('updated_at', '>', DB::raw('revision_requested_at'))
    ->get();


// ============================================
// 5. TESTING SCENARIOS
// ============================================

/* SCENARIO 1: Admin sends report for revision */
1. Create a report as user
2. Submit the report
3. As admin, call:
   POST /admin/reports/{id}/send-for-revision
   Body: {"revision_reason": "Please fix the data..."}
4. Expected: 
   - Report status changed to 'draft'
   - revision_requested = true
   - revision_requested_at = current timestamp
   - Email sent to user
   - User receives email with feedback

/* SCENARIO 2: User edits and resubmits */
1. User receives revision request email
2. User logs in and sees report in draft status
3. User edits report based on feedback
4. User resubmits
5. Expected:
   - Report status = 'submitted' again
   - revision_requested still = true (audit trail)
   - Admin sees report in review list again

/* SCENARIO 3: Bulk revision requests */
$reportsToRevise = Report::where('status', 'submitted')
    ->where('score', '<', 5)
    ->get();

foreach ($reportsToRevise as $report) {
    $report->update([
        'revision_requested' => true,
        'revision_requested_at' => now(),
        'revision_reason' => 'Your report score is below acceptable threshold. Please provide more detailed information.',
        'status' => 'draft'
    ]);
    
    Mail::to($report->user->email)
        ->send(new ReportRevisionRequestMail(
            $report,
            'Your report score is below acceptable threshold. Please provide more detailed information.',
            Auth::guard('admin')->user()->name
        ));
}

/* SCENARIO 4: Permission check */
- Admin A has Language A assigned
- Admin A tries to send Language B report for revision
- Expected: Redirect to reports with error message
  "You can only send reports for languages assigned to you for revision."


// ============================================
// 6. VALIDATION RULES
// ============================================

revision_reason: required|string|max:1000

// Max length: 1000 characters
// Example valid inputs:
- "Please fix the numbers" ✓
- "Lorem ipsum dolor sit amet..." (50+ chars) ✓
- "" (empty) ✗ - required
- Very long text (1001+ chars) ✗ - max 1000


// ============================================
// 7. DEBUGGING & TROUBLESHOOTING
// ============================================

// Check if email was sent
Log::debug('Revision request sent', [
    'report_id' => $report->id,
    'user_id' => $report->user_id,
    'admin_id' => Auth::guard('admin')->id(),
    'timestamp' => now()
]);

// Verify report state after revision request
$report->refresh();
dd([
    'id' => $report->id,
    'status' => $report->status,
    'revision_requested' => $report->revision_requested,
    'revision_requested_at' => $report->revision_requested_at,
    'revision_reason' => $report->revision_reason,
]);

// Check email configuration
echo config('mail.driver'); // Should be 'smtp' or 'log' in testing
echo config('mail.from.address');
echo config('mail.from.name');

// Test email sending
use Illuminate\Support\Facades\Mail;

Mail::raw('Test message', function($message) {
    $message->to('user@example.com')->subject('Test Subject');
});


// ============================================
// 8. API RESPONSE EXAMPLES
// ============================================

// Success Response
HTTP/1.1 302 Found
Location: /admin/reports

Set-Cookie: XSRF-TOKEN=...; laravel_session=...

// Validation Error
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json

{
  "message": "The given data was invalid.",
  "errors": {
    "revision_reason": [
      "The revision reason field is required.",
      "The revision reason may not be greater than 1000 characters."
    ]
  }
}

// Authorization Error
HTTP/1.1 403 Forbidden
Content-Type: application/json

{
  "message": "You can only send reports for languages assigned to you for revision."
}

// Not Found Error
HTTP/1.1 404 Not Found


// ============================================
// 9. EVENT/LISTENER PATTERN (Optional Enhancement)
// ============================================

// You could add event handling in the future:

namespace App\Events;

class ReportSentForRevision
{
    public $report;
    public $admin;
    public $revisionReason;
    
    public function __construct($report, $admin, $revisionReason)
    {
        $this->report = $report;
        $this->admin = $admin;
        $this->revisionReason = $revisionReason;
    }
}

// Then in the controller:
event(new ReportSentForRevision($report, $admin, $request->revision_reason));

// Listen in a listener class:
class SendRevisionNotification implements ShouldQueue
{
    public function handle(ReportSentForRevision $event)
    {
        Mail::to($event->report->user->email)
            ->send(new ReportRevisionRequestMail(...));
    }
}
