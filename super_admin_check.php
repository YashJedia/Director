<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Report;

$currentYear = date('Y');
$quarters = ['Q1 ' . $currentYear, 'Q2 ' . $currentYear, 'Q3 ' . $currentYear, 'Q4 ' . $currentYear];

echo "=== SUPER ADMIN AGGREGATED VIEW ===\n\n";

foreach ($quarters as $quarter) {
    $reports = Report::where('submitted_to_super_admin', true)
        ->where('quarter', $quarter)
        ->with(['language', 'user'])
        ->get();
    
    echo "$quarter: " . $reports->count() . " reports\n";
    foreach ($reports as $report) {
        echo "  - " . $report->title . " (Language: " . $report->language->name . ", Leader: " . $report->user->name . ")\n";
    }
}

echo "\n=== REPORT DATA SAMPLE ===\n";
$sample = Report::where('submitted_to_super_admin', true)
    ->where('quarter', 'Q1 ' . $currentYear)
    ->first();

if ($sample) {
    echo "\nSample Report: " . $sample->title . "\n";
    echo "  volunteers_previous_year: " . ($sample->volunteers_previous_year ?? '0') . "\n";
    echo "  volunteers_goal_year: " . ($sample->volunteers_goal_year ?? '0') . "\n";
    echo "  volunteers_goal_q1: " . ($sample->volunteers_goal_q1 ?? '0') . "\n";
    echo "  volunteers_achieved_q1: " . ($sample->volunteers_achieved_q1 ?? '0') . "\n";
} else {
    echo "No sample data found\n";
}

?>
