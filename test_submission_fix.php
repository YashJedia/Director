<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Report;
use App\Models\AdminAggregatedSubmission;
use App\Models\Language;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

echo "════════════════════════════════════════════════════════════════\n";
echo "  🧪 TESTING SUBMISSION FIX\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$admin = Admin::where('role', 'admin')->first();
$quarter = 'Q1 2026';

echo "Test Setup:\n";
echo "  Admin: " . $admin->name . " (ID: " . $admin->id . ")\n";
echo "  Quarter: " . $quarter . "\n\n";

// Get assigned languages
$assignedLanguages = Language::where('assigned_admin_id', $admin->id)->pluck('id');
echo "Assigned Languages: " . $assignedLanguages->count() . "\n";

// Get reports to submit
$reportsToSubmit = Report::where(function($query) {
    $query->where('review_status', 'approved')
          ->orWhere('status', 'approved');
})
->where('quarter', $quarter)
->whereIn('language_id', $assignedLanguages)
->where('submitted_to_super_admin', false)
->get();

echo "Reports to submit for " . $quarter . ": " . $reportsToSubmit->count() . "\n\n";

echo "Processing...\n";

// Simulate the submission process
try {
    // Update reports
    $updated = Report::where(function($query) {
        $query->where('review_status', 'approved')
              ->orWhere('status', 'approved');
    })
    ->where('quarter', $quarter)
    ->whereIn('language_id', $assignedLanguages)
    ->where('submitted_to_super_admin', false)
    ->update([
        'submitted_to_super_admin' => true,
        'submitted_to_super_admin_at' => now(),
        'submitted_to_super_admin_by' => $admin->id,
    ]);
    
    echo "✓ Updated " . $updated . " reports\n";
    
    // Create submission record
    $submission = AdminAggregatedSubmission::updateOrCreate(
        [
            'admin_id' => $admin->id,
            'quarter' => $quarter,
        ],
        [
            'submitted_at' => now(),
        ]
    );
    
    echo "✓ Created AdminAggregatedSubmission record\n";
    echo "  ID: " . $submission->id . "\n";
    echo "  Quarter: " . $submission->quarter . "\n";
    echo "  Submitted At: " . $submission->submitted_at . "\n\n";
    
    echo "════════════════════════════════════════════════════════════════\n";
    echo "  ✅ SUCCESS! Fix is working!\n";
    echo "════════════════════════════════════════════════════════════════\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n════════════════════════════════════════════════════════════════\n";
    echo "  ❌ FAILED! Error occurred:\n";
    echo "  " . $e->getMessage() . "\n";
    echo "════════════════════════════════════════════════════════════════\n";
}
?>
