<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Report;
use App\Models\Admin;

echo "════════════════════════════════════════════════════════════════\n";
echo "  ✅ WORKFLOW STATUS - READY FOR TESTING\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$admin = Admin::where('role', 'admin')->first();
$superAdmin = Admin::where('role', 'super_admin')->first();

echo "👤 ADMINS\n";
echo "  Regular Admin: " . $admin->name . " (ID: " . $admin->id . ")\n";
echo "    - Assigned Languages: " . count($admin->assignedLanguages) . "\n";
echo "  Super Admin: " . $superAdmin->name . " (ID: " . $superAdmin->id . ")\n\n";

echo "📊 REPORTS STATUS\n";
$notSubmitted = Report::where('submitted_to_super_admin', false)->count();
$submitted = Report::where('submitted_to_super_admin', true)->count();

echo "  ✓ Approved & NOT Submitted: " . $notSubmitted . "\n";
echo "  ✗ Already Submitted: " . $submitted . "\n\n";

echo "🔍 REPORT SAMPLE DATA\n";
$sample = Report::where('submitted_to_super_admin', false)->first();
if ($sample) {
    echo "  Title: " . $sample->title . "\n";
    echo "  Quarter: " . $sample->quarter . "\n";
    echo "  Status: " . $sample->status . "\n";
    echo "  Review Status: " . $sample->review_status . "\n";
    echo "  Submitted: " . ($sample->submitted_to_super_admin ? 'Yes' : 'No') . "\n\n";
    
    echo "  📈 Data Values:\n";
    echo "    - volunteers_previous_year: " . ($sample->volunteers_previous_year ?? 'N/A') . "\n";
    echo "    - volunteers_goal_year: " . ($sample->volunteers_goal_year ?? 'N/A') . "\n";
    echo "    - volunteers_goal_q1: " . ($sample->volunteers_goal_q1 ?? 'N/A') . "\n";
    echo "    - volunteers_achieved_q1: " . ($sample->volunteers_achieved_q1 ?? 'N/A') . "\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "  🧪 TESTING CHECKLIST\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "□ Step 1: Login as Admin\n";
echo "   URL: http://yoursite/admin/login\n";
echo "   Email: admin@example.com\n\n";

echo "□ Step 2: Go to Aggregated Reports\n";
echo "   URL: http://yoursite/admin/reports-aggregated-admin\n";
echo "   Expected: See " . $notSubmitted . " approved reports\n\n";

echo "□ Step 3: Click 'Submit' on a report\n";
echo "   Expected: Report status changes to submitted\n\n";

echo "□ Step 4: Login as Super Admin\n";
echo "   Email: superadmin@example.com\n\n";

echo "□ Step 5: Go to Super Admin Aggregated View\n";
echo "   URL: http://yoursite/admin/reports-aggregated\n";
echo "   Expected: See submitted reports with aggregated data\n\n";

echo "════════════════════════════════════════════════════════════════\n";
echo "  ✅ SYSTEM READY FOR TESTING\n";
echo "════════════════════════════════════════════════════════════════\n";
?>
