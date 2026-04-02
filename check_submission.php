<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Report;
use App\Models\AdminAggregatedSubmission;
use App\Models\Admin;

echo "════════════════════════════════════════════════════════════════\n";
echo "  📊 CHECKING SUBMISSION STATUS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Check if reports were submitted
$admin = Admin::where('role', 'admin')->first();
echo "Admin: " . $admin->name . " (ID: " . $admin->id . ")\n\n";

echo "=== REPORTS STATUS ===\n";
$submitted = Report::where('submitted_to_super_admin', true)
    ->count();
$notSubmitted = Report::where('submitted_to_super_admin', false)
    ->count();

echo "Submitted to super admin: " . $submitted . "\n";
echo "NOT submitted: " . $notSubmitted . "\n\n";

echo "=== ADMIN AGGREGATED SUBMISSIONS ===\n";
$submissions = AdminAggregatedSubmission::where('admin_id', $admin->id)->get();
echo "Total submissions for this admin: " . $submissions->count() . "\n";

foreach ($submissions as $submission) {
    echo "  - Quarter: " . $submission->quarter . "\n";
    echo "    Submitted At: " . ($submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "    Created At: " . $submission->created_at->format('Y-m-d H:i:s') . "\n";
}

echo "\n=== SAMPLE SUBMITTED REPORT ===\n";
$sample = Report::where('submitted_to_super_admin', true)->first();
if ($sample) {
    echo "ID: " . $sample->id . "\n";
    echo "Title: " . $sample->title . "\n";
    echo "Quarter: " . $sample->quarter . "\n";
    echo "Submitted At: " . ($sample->submitted_to_super_admin_at ? $sample->submitted_to_super_admin_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "Submitted By (Admin ID): " . ($sample->submitted_to_super_admin_by ?? 'NULL') . "\n";
} else {
    echo "No submitted reports found\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
?>
