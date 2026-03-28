# Aggregated Reports Feature - Complete Implementation Guide

## Overview
The Aggregated Reports feature allows Super Admins to view consolidated metrics from all submitted reports organized by quarters and languages. This document outlines the complete architecture and can be used as a template for implementing similar features in the Admin panel.

---

## 1. ROUTE DEFINITION

**File:** `routes/web.php` (Line 49)

```php
Route::get('/reports-aggregated', [AdminController::class, 'viewAggregatedReports'])->name('reports.aggregated');
```

- **Route Name:** `admin.reports.aggregated`
- **Path:** `/admin/reports-aggregated`
- **Access:** Only authenticated admins with `auth:admin` middleware
- **Controller Method:** `AdminController::viewAggregatedReports()`

---

## 2. CONTROLLER METHOD

**File:** `app/Http/Controllers/AdminController.php` (Lines 1818-2060)

### Method Signature
```php
public function viewAggregatedReports()
```

### Security Check
```php
$admin = Auth::guard('admin')->user();
if (!$admin->isSuperAdmin()) {
    abort(403, 'Unauthorized access');
}
```
- Only Super Admins can access this feature
- Regular Admins get a 403 Forbidden response

### Data Collection Phase

#### Step 1: Fetch All Submitted Reports
```php
$submittedReports = Report::where('submitted_to_super_admin', true)
    ->with(['language', 'user'])
    ->get();
```
- Gets all reports marked as submitted to Super Admin
- Eagerly loads language and user relationships

#### Step 2: Define Quarters
```php
$quarters = ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'];
```

#### Step 3: Get Unique Languages
```php
$languages = $submittedReports->pluck('language')->unique('id')->sortBy('name');
```
- Extracts unique languages from submitted reports
- Maintains alphabetical order by name

### Field Configuration Structure

The feature uses a **dynamic field mapping system** that defines:
1. Which database columns contain the data
2. Whether data is language-specific
3. How to calculate aggregates

```php
$sections = [
    'Ministry' => [
        'Number of Languages' => [
            'end_2024' => 'languages_previous_year',            // Single column
            'goal' => ['languages_goal_q1', 'languages_goal_q2', 'languages_goal_q3', 'languages_goal_q4'],  // Per quarter
            'achieved' => ['languages_achieved_q1', 'languages_achieved_q2', 'languages_achieved_q3', 'languages_achieved_q4'],
            'by_language' => false,  // NOT language-specific (single aggregate)
        ],
        'Bible Mentors' => [
            'end_2024' => null,
            'goal' => ['volunteers_mentors_goal_q1', ...],
            'achieved' => ['volunteers_mentors_achieved_q1', ...],
            'by_language' => true,  // Language-specific (breakdown per language)
        ],
        // ... more fields
    ],
    'Outreach & Engagement' => [
        // ... section fields
    ],
    // ... more sections
];
```

### Data Processing Algorithm

#### Phase 1: Initialize Data Structure
```php
$aggregatedData = [];

foreach ($quarters as $quarterIndex => $quarter) {
    $aggregatedData[$quarter] = [];
    
    foreach ($sections as $sectionName => $fields) {
        $aggregatedData[$quarter][$sectionName] = [];
        
        foreach ($fields as $fieldLabel => $fieldConfig) {
            $aggregatedData[$quarter][$sectionName][$fieldLabel] = [
                'by_language' => $fieldConfig['by_language'],
                'data' => []
            ];
        }
    }
}
```

#### Phase 2A: Language-Specific Fields Processing
For fields where `by_language: true`:

```php
if ($fieldConfig['by_language']) {
    // 1. Iterate through each language
    foreach ($languages as $language) {
        $languageKey = $language->name;
        
        // 2. Find the specific report for this language and quarter
        $report = $submittedReports->filter(function($r) use ($language, $quarter) {
            return $r->language_id === $language->id && $r->quarter === $quarter;
        })->first();
        
        // 3. Extract values from report (if exists)
        if ($report) {
            $endValue = $fieldConfig['end_2024'] ? ($report->{$fieldConfig['end_2024']} ?? 0) : 0;
            $goalValue = $report->{$fieldConfig['goal'][$quarterIndex]} ?? 0;
            $achievedValue = $report->{$fieldConfig['achieved'][$quarterIndex]} ?? 0;
            
            // 4. Calculate achievement percentage
            $percentage = ($goalValue > 0) ? round(($achievedValue / $goalValue) * 100, 2) : 0;
            
            // 5. Store in aggregated data
            $aggregatedData[$quarter][$sectionName][$fieldLabel]['data'][$languageKey] = [
                'end_2024' => $endValue,
                'goal' => $goalValue,
                'achieved' => $achievedValue,
                'percentage' => $percentage,
            ];
        }
    }
    
    // 6. Calculate totals across all languages
    $totalEnd = 0;
    $totalGoal = 0;
    $totalAchieved = 0;
    
    foreach ($languages as $language) {
        $languageKey = $language->name;
        $totalEnd += $aggregatedData[$quarter][$sectionName][$fieldLabel]['data'][$languageKey]['end_2024'];
        $totalGoal += $aggregatedData[$quarter][$sectionName][$fieldLabel]['data'][$languageKey]['goal'];
        $totalAchieved += $aggregatedData[$quarter][$sectionName][$fieldLabel]['data'][$languageKey]['achieved'];
    }
    
    $totalPercentage = ($totalGoal > 0) ? round(($totalAchieved / $totalGoal) * 100, 2) : 0;
    
    // 7. Add totals row
    $aggregatedData[$quarter][$sectionName][$fieldLabel]['data']['Total'] = [
        'end_2024' => $totalEnd,
        'goal' => $totalGoal,
        'achieved' => $totalAchieved,
        'percentage' => $totalPercentage,
    ];
}
```

#### Phase 2B: Non-Language-Specific Fields Processing
For fields where `by_language: false`:

```php
else {
    // 1. Aggregate across ALL reports for this quarter
    $totalEnd = 0;
    $totalGoal = 0;
    $totalAchieved = 0;
    
    foreach ($submittedReports->where('quarter', $quarter) as $report) {
        $endValue = $fieldConfig['end_2024'] ? ($report->{$fieldConfig['end_2024']} ?? 0) : 0;
        $goalValue = $report->{$fieldConfig['goal'][$quarterIndex]} ?? 0;
        $achievedValue = $report->{$fieldConfig['achieved'][$quarterIndex]} ?? 0;
        
        $totalEnd += $endValue;
        $totalGoal += $goalValue;
        $totalAchieved += $achievedValue;
    }
    
    // 2. Calculate single aggregated percentage
    $totalPercentage = ($totalGoal > 0) ? round(($totalAchieved / $totalGoal) * 100, 2) : 0;
    
    // 3. Store single data point (No "Total" row needed)
    $aggregatedData[$quarter][$sectionName][$fieldLabel]['data'] = [
        'end_2024' => $totalEnd,
        'goal' => $totalGoal,
        'achieved' => $totalAchieved,
        'percentage' => $totalPercentage,
    ];
}
```

### Return Statement
```php
return view('admin.reports-aggregated', [
    'aggregatedData' => $aggregatedData,
    'languages' => $languages,
    'quarters' => $quarters,
    'submittedReports' => $submittedReports,
]);
```

---

## 3. DATA STRUCTURE IN DETAIL

### Final Data Structure Example

After processing, the data looks like:

```php
$aggregatedData = [
    'Q1 2025' => [
        'Ministry' => [
            'Bible Mentors' => [                    // Language-specific field
                'by_language' => true,
                'data' => [
                    'Spanish' => [
                        'end_2024' => 5,
                        'goal' => 10,
                        'achieved' => 8,
                        'percentage' => 80.0,
                    ],
                    'French' => [
                        'end_2024' => 3,
                        'goal' => 5,
                        'achieved' => 4,
                        'percentage' => 80.0,
                    ],
                    'Total' => [                    // Cumulative total
                        'end_2024' => 8,            // 5 + 3
                        'goal' => 15,               // 10 + 5
                        'achieved' => 12,           // 8 + 4
                        'percentage' => 80.0,       // (12 / 15) * 100
                    ]
                ]
            ],
            'Number of Languages' => [             // Non-language-specific
                'by_language' => false,
                'data' => [
                    'end_2024' => 25,               // Sum of all reports' values
                    'goal' => 50,
                    'achieved' => 40,
                    'percentage' => 80.0,
                ]
            ]
        ]
    ]
]
```

---

## 4. BLADE VIEW

**File:** `resources/views/admin/reports-aggregated.blade.php`

### Layout Components

#### A. Header Section
- Title: "Aggregated Reports by Quarter"
- Back button to dashboard
- Subtitle describing the view

#### B. Quarter Navigation Tabs
```blade
@foreach($quarters as $index => $quarter)
    <button 
        onclick="switchQuarter('{{ $quarter }}')"
        class="quarter-btn ... {{ $index === 0 ? 'bg-blue-600 text-white shadow-lg active' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}"
        data-quarter="{{ $quarter }}"
    >
        {{ $quarter }}
    </button>
@endforeach
```
- Dynamic button creation (one per quarter)
- Q1 2025 is active by default
- Uses `switchQuarter()` JavaScript function for tab switching

#### C. Quarter Tables Container
Each quarter gets its own hidden div that's shown/hidden via JavaScript:

```blade
@foreach($aggregatedData as $quarter => $sections)
    <div id="quarter-{{ str_replace(' ', '-', $quarter) }}" 
         class="quarter-content ... {{ $loop->first ? '' : 'hidden' }}">
        <!-- Section tables go here -->
    </div>
@endforeach
```

#### D. Section Tables - Language-Specific Fields

For fields with `by_language: true`:

```blade
@foreach($fieldData['data'] as $languageName => $values)
    <tr class="border-b border-gray-200 hover:bg-blue-50">
        <!-- First row of a field shows the field label with rowspan -->
        @if($isFirstInField)
            <td rowspan="{{ count($fieldData['data']) }}" class="...">
                {{ $fieldLabel }}
            </td>
            @php $isFirstInField = false; @endphp
        @endif
        
        <!-- Language name (bold and highlighted for 'Total' row) -->
        <td class="... {{ $languageName === 'Total' ? 'font-bold text-blue-800 bg-blue-50' : 'text-gray-600' }}">
            {{ $languageName }}
        </td>
        
        <!-- Data columns: End 2024, Goal, Achieved, Percentage -->
        <td>{{ is_numeric($values['end_2024']) ? number_format($values['end_2024'], 0) : '-' }}</td>
        <td>{{ is_numeric($values['goal']) ? number_format($values['goal'], 0) : '-' }}</td>
        <td>{{ is_numeric($values['achieved']) ? number_format($values['achieved'], 0) : '-' }}</td>
        
        <!-- Percentage with color coding -->
        <td class="... {{ $values['percentage'] >= 100 ? 'text-green-700' : ($values['percentage'] >= 75 ? 'text-yellow-700' : 'text-red-700') }}">
            @if($values['goal'] > 0)
                {{ $values['percentage'] }}%
            @else
                N/A
            @endif
        </td>
    </tr>
@endforeach
```

**Table Structure:**
```
| Field Label | Language/Metric | End 2024 | Goal | Achieved | % |
|-------------|-----------------|----------|------|----------|---|
| Bible Mentors | Spanish | 5 | 10 | 8 | 80% |
| | French | 3 | 5 | 4 | 80% |
| | **Total** | **8** | **15** | **12** | **80%** |
```

#### E. Section Tables - Non-Language-Specific Fields

For fields with `by_language: false`:

```blade
<tr class="border-b border-gray-200 hover:bg-blue-50">
    <td class="font-semibold text-gray-800 border-r border-gray-300 bg-gray-50">
        {{ $fieldLabel }}
    </td>
    <td class="border-r border-gray-300 text-gray-600">
        -
    </td>
    <td>{{ is_numeric($fieldData['data']['end_2024']) ? number_format($fieldData['data']['end_2024'], 0) : '-' }}</td>
    <td>{{ is_numeric($fieldData['data']['goal']) ? number_format($fieldData['data']['goal'], 0) : '-' }}</td>
    <td>{{ is_numeric($fieldData['data']['achieved']) ? number_format($fieldData['data']['achieved'], 0) : '-' }}</td>
    <td>{{ $fieldData['data']['percentage'] }}%</td>
</tr>
```

**Table Structure:**
```
| Field Label | Language/Metric | End 2024 | Goal | Achieved | % |
|-------------|-----------------|----------|------|----------|---|
| Number of Languages | - | 25 | 50 | 40 | 80% |
```

#### F. Submitted Reports Details
Shows a list of all submitted reports with:
- Report title
- Leader name
- Language
- Quarter
- Status badge
- View and Delete buttons

### JavaScript Quarter Switching

```javascript
function switchQuarter(quarter) {
    // 1. Hide all quarter contents
    const allContents = document.querySelectorAll('.quarter-content');
    allContents.forEach(content => {
        content.classList.add('hidden');
    });

    // 2. Reset all buttons to inactive state
    const allButtons = document.querySelectorAll('.quarter-btn');
    allButtons.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'active');
        btn.classList.add('bg-gray-200', 'text-gray-800');
    });

    // 3. Show selected quarter
    const quarterId = 'quarter-' + quarter.replace(/\s+/g, '-');
    const selectedContent = document.getElementById(quarterId);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }

    // 4. Activate selected button
    const selectedButton = document.querySelector(`[data-quarter="${quarter}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('bg-gray-200', 'text-gray-800');
        selectedButton.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'active');
    }
}
```

---

## 5. KEY FEATURES & CALCULATIONS

### Quarter Filtering
- **Method:** Filters reports by `report.quarter` field (e.g., "Q1 2025")
- **Quarterly Field Mapping:** Uses array indexing to access quarter-specific columns
  ```php
  $quarterIndex = 0; // For "Q1 2025"
  $goalColumn = $fieldConfig['goal'][$quarterIndex];  // e.g., 'languages_goal_q1'
  ```

### Cumulative Data Calculation

#### For Language-Specific Fields:
1. **Per Language:** Extract values from that language's report for the quarter
2. **Totalization:** Sum all language values
3. **Percentage:** (Total Achieved / Total Goal) × 100

```
Spanish: achieved=8, goal=10
French: achieved=4, goal=5
Total: achieved=12, goal=15
Percentage: (12/15)*100 = 80%
```

#### For Non-Language-Specific Fields:
1. **Aggregation:** Sum values from ALL reports for the quarter
2. **Percentage:** (Total Achieved / Total Goal) × 100

```
Report 1: achieved=20, goal=30
Report 2: achieved=10, goal=20
Total: achieved=30, goal=50
Percentage: (30/50)*100 = 60%
```

### Percentage Color Coding
- **Green (≥100%):** Goal exceeded or met
- **Yellow (75-99%):** Most of goal achieved
- **Red (<75%):** Less than 75% of goal achieved

### Number Formatting
- Uses `number_format($value, 0)` for thousands separators
- Shows "-" for non-numeric or null values
- Shows "N/A" for percentages when goal is 0

---

## 6. DATABASE SCHEMA REQUIREMENTS

The Report model must have the following columns:

### For All Fields:
- `submitted_to_super_admin` (boolean)
- `quarter` (string, e.g., "Q1 2025")
- `language_id` (foreign key)
- `user_id` (foreign key)

### Ministry Section:
- `languages_previous_year`
- `languages_goal_q1`, `languages_goal_q2`, `languages_goal_q3`, `languages_goal_q4`
- `languages_achieved_q1`, `languages_achieved_q2`, `languages_achieved_q3`, `languages_achieved_q4`
- Similar pattern for: `volunteers_*`, `volunteers_mentors_*`, `volunteers_chatters_*`, `volunteers_creators_*`

### Outreach & Engagement Section:
- `evangelistic_students_goal_q1`, ..., `evangelistic_students_achieved_q1`, ...
- `discipleship_students_goal_q1`, ..., `discipleship_students_achieved_q1`, ...
- `leadership_students_goal_q1`, ..., `leadership_students_achieved_q1`, ...
- `evangelistic_conversations_goal_q1`, ..., `evangelistic_conversations_achieved_q1`, ...
- `pastoral_connections_goal_q1`, ..., `pastoral_connections_achieved_q1`, ...

### Social Media Reach Section:
- `facebook_reach_goal_q1`, ..., `facebook_reach_achieved_q1`, ...
- `instagram_reach_goal_q1`, ..., `instagram_reach_achieved_q1`, ...
- `youtube_reach_goal_q1`, ..., `youtube_reach_achieved_q1`, ...
- `website_reach_goal_q1`, ..., `website_reach_achieved_q1`, ...

### Financial & Operations Section:
- `income_euros_goal_q1`, ..., `income_euros_achieved_q1`, ...
- `expenditure_euros_goal_q1`, ..., `expenditure_euros_achieved_q1`, ...
- `pr_total_organic_reach_goal_q1`, ..., `pr_total_organic_reach_achieved_q1`, ...
- `personal_fte_goal_q1`, ..., `personal_fte_achieved_q1`, ...

---

## 7. REPLICATION CHECKLIST FOR ADMIN PANEL

To replicate this feature for the Admin panel:

- [ ] **Create Route:**
  - Add route in `routes/web.php` (middleware: `auth:admin`, not restricted to super admin)
  - Example: `Route::get('/admin-reports-aggregated', [AdminController::class, 'viewAdminAggregatedReports'])->name('admin-reports.aggregated');`

- [ ] **Create Controller Method:**
  - Copy `viewAggregatedReports()` as template
  - Modify queries to filter by current admin's language assignments or team scope
  - Change: `where('submitted_to_super_admin', true)` to appropriate scope
  - Add: filtering by current admin's accessible languages/reports

- [ ] **Create Blade View:**
  - Copy `reports-aggregated.blade.php` structure
  - Create new file: `admin/reports-admin-aggregated.blade.php`
  - Keep the same table structure and JavaScript

- [ ] **Adjust Security:**
  - Remove `isSuperAdmin()` check if regular admins should access
  - Add logic to scope data to admin's accessible languages/team

- [ ] **Adjust Data Scope:**
  - Instead of all submitted reports: filter by current admin's scope
  - Could be: reports for languages assigned to admin
  - Or: reports from admins in the same team/region

- [ ] **Add Navigation Link:**
  - Add link in admin dashboard/sidebar pointing to new route

- [ ] **Database Considerations:**
  - Ensure Report table has appropriate fields to determine scope
  - Possible fields: `admin_id`, `language_id`, `team_id`, etc.

---

## 8. SECTIONS & FIELDS COMPLETE REFERENCE

```
Ministry:
  - Number of Languages (non-language-specific)
  - Number of Volunteers (non-language-specific)
  - Bible Mentors (language-specific)
  - Chat Volunteers (language-specific)
  - Content Creators (language-specific)

Outreach & Engagement:
  - Evangelistic Students (language-specific)
  - Discipleship Students (language-specific)
  - Leadership Students (language-specific)
  - Evangelistic Conversations (language-specific)
  - Pastoral Connections (language-specific)

Social Media Reach:
  - Facebook Reach (language-specific)
  - Instagram Reach (language-specific)
  - YouTube Reach (language-specific)
  - Website Reach (language-specific)

Financial & Operations:
  - Income (€) (language-specific)
  - Expenditure (€) (language-specific)
  - PR Total Organic Reach (language-specific)
  - Personnel FTE (language-specific)
```

---

## Summary

The Aggregated Reports feature is a sophisticated data aggregation system that:
1. **Fetches** all reports submitted to Super Admin
2. **Filters** data by quarter and language
3. **Calculates** cumulative totals and percentages
4. **Displays** data in organized, color-coded tables
5. **Supports** quarter-based navigation via JavaScript

This architecture can be easily adapted for Admin-level reporting by changing the data query scope and removing the Super Admin restriction.
