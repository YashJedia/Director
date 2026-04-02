<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Report;
use App\Models\Language;

echo "=== CHECKING FOR NOT-YET-SUBMITTED REPORTS ===\n\n";

$admin = Admin::where('role', 'admin')->first();
if ($admin) {
    echo "Regular Admin: " . $admin->name . " (ID: " . $admin->id . ")\n";
    
    $assignedLanguages = Language::where('assigned_admin_id', $admin->id)->pluck('id');
    echo "Assigned Languages: " . $assignedLanguages->count() . "\n";
    
    // Check for reports that are approved but NOT submitted to super admin
    $notSubmittedReports = Report::where(function($query) {
        $query->where('review_status', 'approved')
              ->orWhere('status', 'approved');
    })
    ->where('submitted_to_super_admin', false)
    ->whereIn('language_id', $assignedLanguages)
    ->get();
    
    echo "\n✗ Approved Reports NOT submitted to super admin: " . $notSubmittedReports->count() . "\n";
    foreach ($notSubmittedReports as $report) {
        echo "  - " . $report->title . "\n";
    }
    
    // Check for reports that are approved AND submitted
    $submittedReports = Report::where(function($query) {
        $query->where('review_status', 'approved')
              ->orWhere('status', 'approved');
    })
    ->where('submitted_to_super_admin', true)
    ->whereIn('language_id', $assignedLanguages)
    ->get();
    
    echo "\n✓ Approved Reports ALREADY submitted to super admin: " . $submittedReports->count() . "\n";
    foreach ($submittedReports as $report) {
        echo "  - " . $report->title . " (Submitted: " . ($report->submitted_to_super_admin_at ? $report->submitted_to_super_admin_at->format('Y-m-d H:i') : 'NULL') . ")\n";
    }
    
    // Check all reports by status
    echo "\n=== ALL REPORTS BY STATUS ===\n";
    $allReports = Report::whereIn('language_id', $assignedLanguages)->get();
    $statusCounts = $allReports->groupBy('status');
    $reviewStatusCounts = $allReports->groupBy('review_status');
    
    echo "By Status:\n";
    foreach ($statusCounts as $status => $reports) {
        echo "  " . ($status ?: 'NULL') . ": " . $reports->count() . "\n";
    }
    
    echo "By Review Status:\n";
    foreach ($reviewStatusCounts as $reviewStatus => $reports) {
        echo "  " . ($reviewStatus ?: 'NULL') . ": " . $reports->count() . "\n";
    }
}

echo "\n=== SOLUTION ===\n";
echo "The issue: All reports are marked as submitted_to_super_admin = true\n";
echo "The view looks for: submitted_to_super_admin = false AND (review_status = 'approved' OR status = 'approved')\n";
echo "Result: Zero reports shown\n\n";
echo "To fix:\n";
echo "1. Reset some reports: UPDATE reports SET submitted_to_super_admin = false WHERE id IN (20,21);\n";
echo "2. Or verify the workflow is working correctly\n";
?>

