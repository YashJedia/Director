<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Language;

class UserController extends Controller
{
    // Handle report submission
    public function submitReport(Request $request, $reportId)
    {
        $user = Auth::user();
        $report = Report::where('id', $reportId)->where('user_id', $user->id)->firstOrFail();
        $report->status = 'submitted';
        $report->submitted_at = now();
        $report->save();
        return redirect()->route('user.reports')->with('success', 'Report submitted successfully.');
    }
    public function showLoginForm()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Update last login time
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();
            
            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Dynamically calculate current quarter and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $currentQuarterNum = 1;
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $currentQuarterNum = 2;
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $currentQuarterNum = 3;
        } else {
            $currentQuarterNum = 4;
        }
        
        $currentQuarter = 'Q' . $currentQuarterNum . ' ' . $currentYear;
        
        // Simulate user report data (in a real app, this would come from a reports table)
        $userReports = $this->getUserReports($user->id);
        $reportStats = [
            'total_reports' => count($userReports),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft')),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'revision_reports' => count(array_filter($userReports, fn($r) => isset($r['revision_requested']) && $r['revision_requested'] === true)),
            'this_quarter' => count(array_filter($userReports, fn($r) => $r['quarter'] === $currentQuarter))
        ];

        // Add userStats for header compatibility
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];

        $recentActivity = $this->getUserRecentActivity($user->id);
        $quickActions = $this->getQuickActions();

        // Get assigned languages for the user
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();

        return view('user.dashboard', compact(
            'userReports',
            'reportStats',
            'userStats',
            'recentActivity',
            'quickActions',
            'assignedLanguages',
            'currentQuarter'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        
        // Get user statistics
        $userStats = [
            'total_reports' => $this->getUserReportCount($user->id),
            'submitted_reports' => $this->getUserSubmittedReportCount($user->id),
            'draft_reports' => $this->getUserDraftReportCount($user->id)
        ];

        $recentActivity = $this->getUserProfileActivity($user->id);

        // Get assigned languages for the user
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();

        // Get recent reports for the user
        $recentReports = $this->getUserReports($user->id);

        return view('user.profile', compact('userStats', 'recentActivity', 'assignedLanguages', 'recentReports'));
    }

    public function reports()
    {
        $user = Auth::user();
        
        // Get user's reports
        $reports = $this->getUserReports($user->id);
        
        // Filter reports based on request parameters
        $statusFilter = request('status', 'all');
        $quarterFilter = request('quarter', 'all');
        
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'revision_needed') {
                // Revision needed = revision_requested is true
                $reports = array_filter($reports, fn($r) => 
                    (isset($r['revision_requested']) && $r['revision_requested'] === true)
                );
            } elseif ($statusFilter === 'due') {
                // Due = draft reports that are due (for now, we'll show all draft reports)
                // You can add date logic here if needed
                $reports = array_filter($reports, fn($r) => ($r['status'] ?? '') === 'draft');
            } else {
                $reports = array_filter($reports, fn($r) => ($r['status'] ?? '') === $statusFilter);
            }
        }
        
        if ($quarterFilter !== 'all') {
            $reports = array_filter($reports, fn($r) => $r['quarter'] === $quarterFilter);
        }

        $reportTemplates = $this->getReportTemplates();
        $reportStats = [
            'total' => count($reports),
            'submitted' => count(array_filter($reports, fn($r) => $r['status'] === 'submitted')),
            'draft' => count(array_filter($reports, fn($r) => $r['status'] === 'draft')),
            'under_review' => count(array_filter($reports, fn($r) => $r['status'] === 'under_review'))
        ];

        // Get assigned languages for the user
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();

        // Add userStats for header compatibility
        $userStats = [
            'total_reports' => count($reports),
            'submitted_reports' => count(array_filter($reports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($reports, fn($r) => $r['status'] === 'draft'))
        ];

        return view('user.reports', compact('reports', 'reportTemplates', 'reportStats', 'assignedLanguages', 'userStats'));
    }

    /**
     * Show the create report form
     */
    public function showCreateForm()
    {
        $user = Auth::user();
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();
        $reportTemplates = $this->getReportTemplates();
        
        // Add userStats for header compatibility
        $userReports = $this->getUserReports($user->id);
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];
        
        return view('user.reports.create', compact('assignedLanguages', 'reportTemplates', 'userStats'));
    }

    /**
     * Show the edit report form
     */
    public function showEditForm($reportId)
    {
        $user = Auth::user();
        $report = Report::with('language')->where('id', $reportId)->where('user_id', $user->id)->first();
        
        if (!$report) {
            return redirect()->route('user.reports')->with('error', 'Report not found or access denied.');
        }
        
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();
        $reportTemplates = $this->getReportTemplates();
        
        // Add userStats for header compatibility
        $userReports = $this->getUserReports($user->id);
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];
        
        return view('user.reports.edit', compact('report', 'assignedLanguages', 'reportTemplates', 'userStats'));
    }

    /**
     * Show the report view (read-only)
     */
    public function showReport($reportId)
    {
        $user = Auth::user();
        $report = Report::with('language')->where('id', $reportId)->where('user_id', $user->id)->first();
        
        if (!$report) {
            return redirect()->route('user.reports')->with('error', 'Report not found or access denied.');
        }
        
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();
        
        // Add userStats for header compatibility
        $userReports = $this->getUserReports($user->id);
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];
        
        return view('user.reports.show', compact('report', 'assignedLanguages', 'userStats'));
    }

    public function languages()
    {
        $user = Auth::user();
        $assignedLanguages = $user->assignedLanguages()->where('status', 'active')->get();
        
        // Get language statistics
        $languageStats = [
            'total_languages' => $assignedLanguages->count(),
            'active_languages' => $assignedLanguages->where('status', 'active')->count(),
            'reports_this_quarter' => 0, // This would come from actual reports data
            'completion_rate' => 0 // This would be calculated from actual data
        ];

        // Add userStats for header compatibility
        $userReports = $this->getUserReports($user->id);
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];

        return view('user.languages', compact('assignedLanguages', 'languageStats', 'userStats'));
    }

    public function help()
    {
        $user = Auth::user();
        
        // Add userStats for header compatibility
        $userReports = $this->getUserReports($user->id);
        $userStats = [
            'total_reports' => count($userReports),
            'submitted_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'submitted')),
            'draft_reports' => count(array_filter($userReports, fn($r) => $r['status'] === 'draft'))
        ];
        
        return view('user.help', compact('userStats'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $user->update($request->only([
            'name', 'email', 'phone', 'department', 'job_title', 'location', 'bio'
        ]));

        // Log profile update activity
        $user->touch(); // Update the updated_at timestamp

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Log password change activity
        $user->touch(); // Update the updated_at timestamp

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            
            // Store in public/avatars directory
            $avatar->move(public_path('avatars'), $filename);
            
            // Update user avatar path
            $user->update(['avatar' => 'avatars/' . $filename]);
            
            // Log avatar update activity
            $user->touch(); // Update the updated_at timestamp
            
            return back()->with('success', 'Avatar updated successfully!');
        }

        return back()->withErrors(['avatar' => 'Please select a valid image file.']);
    }

    /**
     * Check if a report already exists for the given combination
     */
    private function checkReportExists($quarter, $language, $excludeId = null)
    {
        $query = Report::where('user_id', Auth::id())
            ->where('quarter', $quarter);
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        // Get the language ID from the language name
        $languageModel = Language::where('name', $language)->first();
        if ($languageModel) {
            $query->where('language_id', $languageModel->id);
        }
        
        return $query->exists();
    }

    /**
     * Store a new report (prevents duplicates)
     */
    public function storeReport(Request $request)
    {
        // Build validation rules for all quarterly fields
        $rules = [
            'type' => 'required|string',
            'quarter' => 'required|string',
            'language' => 'required|string',
            'title' => 'required|string|max:255',
            'volunteers_previous_year' => 'nullable|integer|min:0|max:999999',
            'volunteers_goal_2025' => 'nullable|integer|min:0|max:999999',
            'new_activity' => 'nullable|string|max:1000',
            'organizational_highlight' => 'nullable|string|max:500',
            'organizational_concern' => 'nullable|string|max:500',
            'organizational_issues' => 'nullable|string|max:500'
        ];

        // Add quarterly validation rules (Q1-Q4) for all metrics
        $quarterlyMetrics = [
            'languages' => 'integer',
            'volunteers' => 'integer',
            'volunteers_mentors' => 'integer',
            'volunteers_chatters' => 'integer',
            'volunteers_creators' => 'integer',
            'evangelistic_students' => 'integer',
            'discipleship_students' => 'integer',
            'leadership_students' => 'integer',
            'evangelistic_conversations' => 'integer',
            'pastoral_connections' => 'integer',
            'facebook_reach' => 'integer',
            'instagram_reach' => 'integer',
            'youtube_reach' => 'integer',
            'website_reach' => 'integer',
            'income_euros' => 'numeric',
            'expenditure_euros' => 'numeric',
            'pr_total_organic_reach' => 'integer',
            'personal_fte' => 'numeric',
        ];

        foreach ($quarterlyMetrics as $metric => $type) {
            for ($q = 1; $q <= 4; $q++) {
                if ($type === 'numeric') {
                    $rules["{$metric}_goal_q{$q}"] = 'nullable|numeric|min:0|max:999999999999.99';
                    $rules["{$metric}_achieved_q{$q}"] = 'nullable|numeric|min:0|max:999999999999.99';
                } else {
                    $rules["{$metric}_goal_q{$q}"] = 'nullable|integer|min:0|max:999999999';
                    $rules["{$metric}_achieved_q{$q}"] = 'nullable|integer|min:0|max:999999999';
                }
            }
        }

        $request->validate($rules, [
            'personal_fte_goal_q1.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q2.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q3.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q4.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q1.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q2.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q3.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q4.max' => 'Personal FTE cannot exceed 999,999.99',
        ]);

        // Check for duplicate report
        if ($this->checkReportExists($request->quarter, $request->language)) {
            return back()->withErrors([
                'duplicate' => 'A report already exists for the selected quarter and language. You can only edit existing reports.'
            ])->withInput();
        }

        // Get the language ID from the language name
        $language = Language::where('name', $request->language)->first();
        if (!$language) {
            return back()->withErrors([
                'language' => 'Selected language not found.'
            ])->withInput();
        }

        // Build report data - includes all quarterly fields
        $reportData = [
            'title' => $request->title,
            'quarter' => $request->quarter,
            'user_id' => Auth::id(),
            'language_id' => $language->id,
            'status' => 'draft',
            'volunteers_previous_year' => $request->volunteers_previous_year ?? 0,
            'volunteers_goal_2025' => $request->volunteers_goal_2025 ?? 0,
            'new_activity' => $request->new_activity,
            'organizational_highlight' => $request->organizational_highlight,
            'organizational_concern' => $request->organizational_concern,
            'organizational_issues' => $request->organizational_issues,
        ];

        // Add all quarterly field values
        foreach ($quarterlyMetrics as $metric => $type) {
            for ($q = 1; $q <= 4; $q++) {
                $goalKey = "{$metric}_goal_q{$q}";
                $achievedKey = "{$metric}_achieved_q{$q}";
                
                $reportData[$goalKey] = $request->input($goalKey) ?? ($type === 'numeric' ? 0.00 : 0);
                $reportData[$achievedKey] = $request->input($achievedKey) ?? ($type === 'numeric' ? 0.00 : 0);
            }
        }

        // Create the report
        $report = Report::create($reportData);

        // ...existing code...

        return redirect()->route('user.reports')->with('success', 'Report created successfully!');
    }

    /**
     * Update an existing report
     */
    public function updateReport(Request $request, $reportId)
    {
        // Build validation rules for all quarterly fields
        $rules = [
            'quarter' => 'required|string',
            'language' => 'required|string',
            'title' => 'required|string|max:255',
            'volunteers_previous_year' => 'nullable|integer|min:0|max:999999',
            'volunteers_goal_2025' => 'nullable|integer|min:0|max:999999',
            'new_activity' => 'nullable|string|max:1000',
            'organizational_highlight' => 'nullable|string|max:500',
            'organizational_concern' => 'nullable|string|max:500',
            'organizational_issues' => 'nullable|string|max:500'
        ];

        // Add quarterly validation rules (Q1-Q4) for all metrics
        $quarterlyMetrics = [
            'languages' => 'integer',
            'volunteers' => 'integer',
            'volunteers_mentors' => 'integer',
            'volunteers_chatters' => 'integer',
            'volunteers_creators' => 'integer',
            'evangelistic_students' => 'integer',
            'discipleship_students' => 'integer',
            'leadership_students' => 'integer',
            'evangelistic_conversations' => 'integer',
            'pastoral_connections' => 'integer',
            'facebook_reach' => 'integer',
            'instagram_reach' => 'integer',
            'youtube_reach' => 'integer',
            'website_reach' => 'integer',
            'income_euros' => 'numeric',
            'expenditure_euros' => 'numeric',
            'pr_total_organic_reach' => 'integer',
            'personal_fte' => 'numeric',
        ];

        foreach ($quarterlyMetrics as $metric => $type) {
            for ($q = 1; $q <= 4; $q++) {
                if ($type === 'numeric') {
                    $rules["{$metric}_goal_q{$q}"] = 'nullable|numeric|min:0|max:999999999999.99';
                    $rules["{$metric}_achieved_q{$q}"] = 'nullable|numeric|min:0|max:999999999999.99';
                } else {
                    $rules["{$metric}_goal_q{$q}"] = 'nullable|integer|min:0|max:999999999';
                    $rules["{$metric}_achieved_q{$q}"] = 'nullable|integer|min:0|max:999999999';
                }
            }
        }

        $request->validate($rules, [
            'personal_fte_goal_q1.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q2.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q3.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_goal_q4.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q1.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q2.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q3.max' => 'Personal FTE cannot exceed 999,999.99',
            'personal_fte_achieved_q4.max' => 'Personal FTE cannot exceed 999,999.99',
        ]);

        // Check for duplicate report (excluding current one)
        if ($this->checkReportExists($request->quarter, $request->language, $reportId)) {
            return back()->withErrors([
                'duplicate' => 'A report already exists for the selected quarter and language.'
            ])->withInput();
        }

        // Get the report
        $report = Report::where('id', $reportId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$report) {
            return back()->withErrors([
                'report' => 'Report not found or access denied.'
            ])->withInput();
        }

        // Get the language ID from the language name
        $language = Language::where('name', $request->language)->first();
        if (!$language) {
            return back()->withErrors([
                'language' => 'Selected language not found.'
            ])->withInput();
        }

        // Update the report with all quarterly fields
        $updateData = [
            'title' => $request->title,
            'quarter' => $request->quarter,
            'language_id' => $language->id,
            'volunteers_previous_year' => $request->volunteers_previous_year ?? 0,
            'volunteers_goal_2025' => $request->volunteers_goal_2025 ?? 0,
            'new_activity' => $request->new_activity,
            'organizational_highlight' => $request->organizational_highlight,
            'organizational_concern' => $request->organizational_concern,
            'organizational_issues' => $request->organizational_issues,
            'revision_requested' => false,
            'revision_reason' => null,
            'revision_requested_at' => null,
        ];

        // Add all quarterly field values
        foreach ($quarterlyMetrics as $metric => $type) {
            for ($q = 1; $q <= 4; $q++) {
                $goalKey = "{$metric}_goal_q{$q}";
                $achievedKey = "{$metric}_achieved_q{$q}";
                
                $updateData[$goalKey] = $request->input($goalKey) ?? ($type === 'numeric' ? 0.00 : 0);
                $updateData[$achievedKey] = $request->input($achievedKey) ?? ($type === 'numeric' ? 0.00 : 0);
            }
        }

        // Update the report and clear revision flag if it was set
        $report->update($updateData);

        return redirect()->route('user.reports')->with('success', 'Report updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user/login');
    }

    public function showInvitationAcceptance($token)
    {
        $invitation = \App\Models\UserInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('user.login')->with('error', 'Invalid invitation link.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('user.login')->with('error', 'This invitation link has expired or has already been used.');
        }

        // Check if user already exists
        $user = User::where('email', $invitation->email)->first();
        if ($user && $user->hasSetPassword()) {
            return redirect()->route('user.login')->with('error', 'This email is already registered. Please login instead.');
        }

        return view('user.accept-invitation', compact('invitation'));
    }

    public function acceptInvitation(Request $request, $token)
    {
        $invitation = \App\Models\UserInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('user.login')->with('error', 'Invalid invitation link.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('user.login')->with('error', 'This invitation link has expired or has already been used.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if user already exists
        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            // Update existing user
            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'is_invited' => true,
                'password_set_at' => now(),
            ]);
        } else {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'is_invited' => true,
                'password_set_at' => now(),
            ]);
        }

        // Mark invitation as accepted
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Auto-login the user
        Auth::login($user);
        
        // Update last login time
        $user->last_login_at = now();
        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully! Welcome to the platform.');
    }

    // Helper methods for simulating report data
    private function getUserReports($userId)
    {
        return Report::with('language')
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'quarter' => $report->quarter,
                    'language' => $report->language ? $report->language->name : 'Unknown',
                    'status' => $report->status,
                    'review_status' => $report->review_status ?? null,
                    'revision_requested' => $report->revision_requested ?? false,
                    'revision_reason' => $report->revision_reason ?? null,
                    'revision_requested_at' => $report->revision_requested_at ?? null,
                    'score' => $report->score,
                    'updated_at' => $report->updated_at,
                ];
            })
            ->toArray();
    }

    private function getUserReportCount($userId)
    {
        return count($this->getUserReports($userId));
    }

    private function getUserSubmittedReportCount($userId)
    {
        $reports = $this->getUserReports($userId);
        return count(array_filter($reports, fn($r) => $r['status'] === 'submitted'));
    }

    private function getUserDraftReportCount($userId)
    {
        $reports = $this->getUserReports($userId);
        return count(array_filter($reports, fn($r) => $r['status'] === 'draft'));
    }

    private function getUserRecentActivity($userId)
    {
        $activities = [];
        $userReports = $this->getUserReports($userId);
        $user = Auth::user();
        
        // Get recent reports and create activity entries
        foreach ($userReports as $report) {
            $status = $report['status'] ?? 'draft';
            $updatedAt = $report['updated_at'];
            
            if (!$updatedAt) {
                continue;
            }
            
            if ($status === 'submitted') {
                $activities[] = [
                    'type' => 'report_submitted',
                    'message' => 'Submitted report: ' . ($report['title'] ?? 'Untitled'),
                    'time' => $updatedAt->diffForHumans(),
                    'timestamp' => $updatedAt->timestamp,
                    'color' => 'green-500'
                ];
            } elseif ($status === 'draft') {
                $activities[] = [
                    'type' => 'report_started',
                    'message' => 'Started report draft: ' . ($report['title'] ?? 'Untitled'),
                    'time' => $updatedAt->diffForHumans(),
                    'timestamp' => $updatedAt->timestamp,
                    'color' => 'yellow-500'
                ];
            } elseif ($status === 'rejected' || ($report['review_status'] ?? '') === 'rejected') {
                $activities[] = [
                    'type' => 'report_revision',
                    'message' => 'Report needs revision: ' . ($report['title'] ?? 'Untitled'),
                    'time' => $updatedAt->diffForHumans(),
                    'timestamp' => $updatedAt->timestamp,
                    'color' => 'red-500'
                ];
            }
        }
        
        // Add profile update activity if user has updated profile recently
        if ($user->updated_at && $user->updated_at->gt($user->created_at)) {
            $activities[] = [
                'type' => 'profile_updated',
                'message' => 'Updated profile information',
                'time' => $user->updated_at->diffForHumans(),
                'timestamp' => $user->updated_at->timestamp,
                'color' => 'blue-500'
            ];
        }
        
        // Sort by timestamp (most recent first) and limit to 5
        usort($activities, function($a, $b) {
            return ($b['timestamp'] ?? 0) - ($a['timestamp'] ?? 0);
        });
        
        return array_slice($activities, 0, 5);
    }

    private function getQuickActions()
    {
        return [
            [
                'title' => 'Create New Report',
                'icon' => 'fa-plus',
                'action' => 'create_report'
            ],
            [
                'title' => 'Update Profile',
                'icon' => 'fa-user',
                'action' => 'update_profile'
            ]
        ];
    }

    private function getUserProfileActivity($userId)
    {
        $user = Auth::user();
        $activities = [];
        
        // Add account creation activity
        $activities[] = [
            'type' => 'account_created',
            'message' => 'Account created',
            'time' => $user->created_at->format('M d, Y'),
            'color' => 'text-purple-500'
        ];
        
        // Add profile update activity if any profile fields are filled
        if ($user->phone || $user->department || $user->job_title || $user->location || $user->bio) {
            $activities[] = [
                'type' => 'profile_updated',
                'message' => 'Profile information updated',
                'time' => $user->updated_at->format('M d, Y'),
                'color' => 'text-green-500'
            ];
        }
        
        // Add avatar activity if user has an avatar
        if ($user->avatar) {
            $activities[] = [
                'type' => 'avatar_updated',
                'message' => 'Profile picture updated',
                'time' => $user->updated_at->format('M d, Y'),
                'color' => 'text-blue-500'
            ];
        }
        
        // Add recent report activity
        $recentReports = $this->getUserReports($userId);
        if (!empty($recentReports)) {
            $latestReport = $recentReports[0];
            $activities[] = [
                'type' => 'report_activity',
                'message' => ucfirst($latestReport['status']) . ' report: ' . $latestReport['title'],
                'time' => 'Recently',
                'color' => 'text-yellow-500'
            ];
        }
        
        // Sort activities by time (newest first)
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        // Return only the last 5 activities
        return array_slice($activities, 0, 5);
    }

    private function getReportTemplates()
    {
        return [
            [
                'name' => 'Quarterly Progress',
                'description' => 'Standard quarterly progress report template with key metrics and achievements.',
                'icon' => 'fa-chart-line',
                'color' => 'text-green-600'
            ],
            [
                'name' => 'Quarterly Summary',
                'description' => 'Quarterly activity summary template for tracking goals and tasks.',
                'icon' => 'fa-calendar-week',
                'color' => 'text-blue-600'
            ],
            [
                'name' => 'Quarterly Review',
                'description' => 'Comprehensive quarterly review template with detailed analysis and insights.',
                'icon' => 'fa-chart-pie',
                'color' => 'text-purple-600'
            ]
        ];
    }

    public function getRevisionCount()
    {
        $user = Auth::user();
        
        // Get count of reports with revision_requested = true for this user
        $revisionCount = Report::where('user_id', $user->id)
            ->where('revision_requested', true)
            ->count();
        
        return response()->json([
            'success' => true,
            'revision_count' => $revisionCount
        ]);
    }
}
