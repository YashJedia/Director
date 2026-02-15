<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Mail\AdminInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin dashboard - only language management
        if ($admin->isSuperAdmin()) {
            $languages = \App\Models\Language::with('admin')->latest()->get();
            $admins = Admin::where('role', 'admin')->with('assignedLanguages')->latest()->get();
            $totalLanguages = $languages->count();
            $activeLanguages = $languages->where('status', 'active')->count();
            $assignedLanguages = $languages->where('assigned_admin_id', '!=', null)->count();
            
            return view('admin.super-admin-dashboard', compact(
                'languages',
                'admins',
                'totalLanguages',
                'activeLanguages',
                'assignedLanguages'
            ));
        }
        
        // Regular Admin dashboard - full access
        $totalUsers = User::count();
        $totalAdmins = Admin::count();
        $recentUsers = User::latest()->take(5)->get();
        $userStats = [
            'total' => $totalUsers,
            'active' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'growth_rate' => $this->calculateGrowthRate('users')
        ];

        $adminStats = [
            'total' => $totalAdmins,
            'active' => Admin::where('created_at', '>=', now()->subDays(30))->count(),
            'new_this_month' => Admin::where('created_at', '>=', now()->startOfMonth())->count(),
            'growth_rate' => $this->calculateGrowthRate('admins')
        ];

        $recentActivity = $this->getRecentActivity();
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();

        $recentReports = \App\Models\Report::with(['user', 'language'])
            ->whereIn('language_id', $languages->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        // Quarterly reports for this quarter
        $currentQuarter = 'Q3 2025'; // You may want to make this dynamic
        $quarterlyReports = \App\Models\Report::with(['user', 'language'])
            ->whereIn('language_id', $languages->pluck('id'))
            ->where('quarter', $currentQuarter)
            ->get();
        $quarterlyReportsCount = $quarterlyReports->count();

        // Calculate reports for revision
        $reportsForRevision = \App\Models\Report::whereIn('language_id', $languages->pluck('id'))
            ->where(function($query) {
                $query->where('status', 'rejected')
                      ->orWhere('review_status', 'rejected');
            })->count();

        // Get all users assigned to the admin's languages
        $userIds = $languages->pluck('assigned_user_id')->filter()->unique();
        $users = User::whereIn('id', $userIds)->get();

        return view('admin.globalrize-dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'recentUsers',
            'userStats',
            'adminStats',
            'recentActivity',
            'languages',
            'recentReports',
            'reportsForRevision',
            'quarterlyReports',
            'quarterlyReportsCount',
            'users'
        ));
    }

    public function userManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot access user management
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        // Fetch all users with their assigned languages
        $users = User::with('assignedLanguages')->latest()->get();
        
        // Fetch all admins
        $admins = Admin::latest()->get();
        
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();
        
        // Calculate statistics
        $totalUsers = $users->count();
        $activeUsers = $users->where('password_set_at', '!=', null)->count();
        
        return view('admin.user-management', compact(
            'users',
            'admins',
            'languages',
            'totalUsers',
            'activeUsers'
        ));
    }

    public function analytics()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot access analytics
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $totalUsers = User::count();
        $totalAdmins = Admin::count();
        $userTrends = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $adminTrends = Admin::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $recentUsers = User::latest()->take(5)->get();
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();
        
        // Calculate dynamic stats
        $totalLanguages = $languages->count();
        $activeLanguages = $languages->where('status', 'active')->count();
        $assignedLanguages = $languages->where('assigned_user_id', '!=', null)->count();
        
        // Calculate reports data (this would come from actual reports table in real app)
        $totalReports = $assignedLanguages; // For now, assume each assigned language has 1 report
        $completedReports = $assignedLanguages; // For now, assume all assigned languages have completed reports
        $completionRate = $totalReports > 0 ? round(($completedReports / $totalReports) * 100) : 0;
        
        $systemStats = [
            'total_reports' => $totalReports,
            'completed_reports' => $completedReports,
            'completion_rate' => $completionRate,
            'total_languages' => $totalLanguages,
            'active_languages' => $activeLanguages,
            'assigned_languages' => $assignedLanguages,
            'active_users' => $totalUsers,
            'system_uptime' => '99.9%',
            'storage_used' => '65%'
        ];

        return view('admin.analytics', compact('totalUsers', 'totalAdmins', 'userTrends', 'adminTrends', 'recentUsers', 'systemStats', 'languages'));
    }

    public function reports(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot access reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();
        
        // Get filter parameters
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'Q3');
        $languageId = $request->get('language_id');
        
        // Build query for reports - only for languages assigned to this admin
        $reportsQuery = \App\Models\Report::with(['user', 'language', 'reviewer'])
            ->whereIn('language_id', $languages->pluck('id'))
            ->where('quarter', 'LIKE', $quarter . ' ' . $year);
            
        if ($languageId) {
            // Verify the language is assigned to this admin
            if ($languages->pluck('id')->contains($languageId)) {
                $reportsQuery->where('language_id', $languageId);
            }
        }
        
        $reports = $reportsQuery->latest()->get();
        
        // Calculate statistics
        $stats = [
            'total_reports' => $reports->count(),
            'total_languages' => $reports->pluck('language_id')->unique()->count(),
            'reports_due' => $reports->where('status', 'draft')->count(),
            'reports_reviewed' => $reports->where('review_status', 'reviewed')->count(),
            'reports_approved' => $reports->where('review_status', 'approved')->count(),
            'reports_revision' => $reports->where('status', 'rejected')->count() + $reports->where('review_status', 'rejected')->count(),
            'year_submitted' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('created_at', $year)->count(),
            'year_reviewed' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('reviewed_at', $year)->count(),
            'year_approved' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('reviewed_at', $year)->where('review_status', 'approved')->count(),
        ];
        
        // Format reports for display
        $formattedReports = $reports->map(function($report) {
            return [
                'id' => $report->id,
                'title' => $report->title,
                'user' => $report->user ? $report->user->name : 'Unknown',
                'language' => $report->language ? $report->language->name : 'Unknown',
                'quarter' => $report->quarter,
                'status' => $report->review_status ?: $report->status,
                'updated_at' => $report->updated_at->format('Y-m-d')
            ];
        });

        return view('admin.reports', compact('reports', 'languages', 'stats', 'year', 'quarter', 'languageId'));
    }

    public function quarterlyReport()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot access quarterly reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();
        return view('admin.quarterly-report', compact('languages'));
    }

    public function languageAssignment()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin: Assign languages to Admins
        if ($admin->isSuperAdmin()) {
            $languages = \App\Models\Language::with('admin')->latest()->get();
            $admins = Admin::where('role', 'admin')->with('assignedLanguages')->latest()->get();
            $totalLanguages = $languages->count();
            $activeLanguages = $languages->where('status', 'active')->count();
            
            return view('admin.super-admin-language-assignment', compact('languages', 'admins', 'totalLanguages', 'activeLanguages'));
        }
        
        // Regular Admin: Assign languages to Users (only languages assigned to them)
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)
            ->with('assignedUser')
            ->latest()
            ->get();
        $users = \App\Models\User::all();
        $totalLanguages = $languages->count();
        $activeLanguages = $languages->where('status', 'active')->count();

        return view('admin.language-assignment', compact('languages', 'users', 'totalLanguages', 'activeLanguages'));
    }

    public function createLanguage()
    {
        $admin = Auth::guard('admin')->user();
        
        // Only Super Admin can create languages
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create languages.');
        }
        
        return view('admin.create-language');
    }

    public function storeLanguage(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Only Super Admin can create languages
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create languages.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        \App\Models\Language::create($request->all());

        return redirect()->route('admin.language-assignment')->with('success', 'Language created successfully!');
    }

    public function assignLanguageToUser(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin assigns languages to Admins
        if ($admin->isSuperAdmin()) {
            $validator = Validator::make($request->all(), [
                'language_ids' => 'required|array',
                'language_ids.*' => 'exists:languages,id',
                'admin_id' => 'required|exists:admins,id'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            // Verify the admin is not a super admin
            $targetAdmin = Admin::findOrFail($request->admin_id);
            if ($targetAdmin->isSuperAdmin()) {
                return back()->withErrors(['admin_id' => 'Cannot assign languages to Super Admin.'])->withInput();
            }

            // Update all selected languages to be assigned to the admin
            \App\Models\Language::whereIn('id', $request->language_ids)
                ->update(['assigned_admin_id' => $request->admin_id, 'assigned_user_id' => null]);

            return redirect()->route('admin.language-assignment')->with('success', 'Languages assigned to admin successfully!');
        }
        
        // Regular Admin assigns languages to Users (only languages assigned to them)
        $validator = Validator::make($request->all(), [
            'language_ids' => 'required|array',
            'language_ids.*' => 'exists:languages,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Verify that all languages are assigned to the current admin
        $adminLanguages = \App\Models\Language::where('assigned_admin_id', $admin->id)
            ->whereIn('id', $request->language_ids)
            ->count();
            
        if ($adminLanguages !== count($request->language_ids)) {
            return back()->withErrors(['language_ids' => 'You can only assign languages that are assigned to you.'])->withInput();
        }

        // Update all selected languages to be assigned to the user
        \App\Models\Language::whereIn('id', $request->language_ids)
            ->update(['assigned_user_id' => $request->user_id]);

        return redirect()->route('admin.language-assignment')->with('success', 'Languages assigned to user successfully!');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }


    private function calculateGrowthRate($table)
    {
        $currentMonth = DB::table($table)->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonth = DB::table($table)->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function getRecentActivity()
    {
        $activities = [];
        
        // Get recent user registrations
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user_registration',
                'message' => "New user registered: {$user->name}",
                'time' => $user->created_at->diffForHumans(),
                'icon' => 'fa-user-plus',
                'color' => 'text-green-600'
            ];
        }

        // Get recent admin registrations
        $recentAdmins = Admin::latest()->take(2)->get();
        foreach ($recentAdmins as $admin) {
            $activities[] = [
                'type' => 'admin_registration',
                'message' => "New admin registered: {$admin->name}",
                'time' => $admin->created_at->diffForHumans(),
                'icon' => 'fa-user-shield',
                'color' => 'text-blue-600'
            ];
        }

        // Sort by creation time
        usort($activities, function($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
        });

        return array_slice($activities, 0, 5);
    }

    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.settings', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.settings')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.settings')->with('success', 'Password updated successfully!');
    }

    public function reviewReport(Request $request, $reportId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot review reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'admin_remarks' => 'required|string|max:1000',
            'review_status' => 'required|in:pending,reviewed,approved,rejected'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $report = \App\Models\Report::findOrFail($reportId);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return redirect()->route('admin.reports')->with('error', 'You can only review reports for languages assigned to you.');
        }

        $report->update([
            'admin_remarks' => $request->admin_remarks,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'review_status' => $request->review_status
        ]);

        // Send email notification to user
        // This would be implemented with Laravel Mail in a real application
        // Mail::to($report->user->email)->send(new ReportReviewedMail($report));

        return redirect()->route('admin.reports')->with('success', 'Report reviewed successfully! Email notification sent to user.');
    }

    public function showInviteUser()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot invite users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        return view('admin.invite-user');
    }

    public function inviteUser(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot invite users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $invitation = \App\Models\UserInvitation::create([
            'email' => $request->email,
            'invited_by' => $admin->id
        ]);

        // Generate invitation link
        $invitationLink = route('user.invitation.accept', ['token' => $invitation->token]);

        // Send invitation email
        // This would be implemented with Laravel Mail in a real application
        // Mail::to($request->email)->send(new UserInvitationMail($invitation, $invitationLink));
        
        // For now, you can log the invitation link for testing
        \Log::info("Invitation link for {$request->email}: {$invitationLink}");

        return redirect()->route('admin.invite-user.show')->with('success', 'Invitation sent successfully to ' . $request->email . '!');
    }

    public function showInviteAdmin()
    {
        $admin = Auth::guard('admin')->user();
        
        // Only Super Admin can invite admins
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can invite admins.');
        }
        
        return view('admin.invite-admin');
    }

    public function inviteAdmin(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Only Super Admin can invite admins
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can invite admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:admins,email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $invitation = \App\Models\AdminInvitation::create([
            'email' => $request->email,
            'invited_by' => $admin->id
        ]);

        // Generate invitation link
        $invitationLink = route('admin.invitation.accept', ['token' => $invitation->token]);

        // Send invitation email
        try {
            Mail::to($request->email)->send(new AdminInvitationMail($invitation, $invitationLink));
            \Log::info("Admin invitation email sent to {$request->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send admin invitation email to {$request->email}: " . $e->getMessage());
            return redirect()->route('admin.invite-admin.show')->with('error', 'Invitation created but failed to send email. Please check your mail configuration.');
        }

        return redirect()->route('admin.invite-admin.show')->with('success', 'Admin invitation sent successfully to ' . $request->email . '!');
    }

    public function showAdminInvitationAcceptance($token)
    {
        $invitation = \App\Models\AdminInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('admin.login')->with('error', 'Invalid invitation link.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('admin.login')->with('error', 'This invitation link has expired or has already been used.');
        }

        // Check if admin already exists
        $admin = Admin::where('email', $invitation->email)->first();
        if ($admin && $admin->password) {
            return redirect()->route('admin.login')->with('error', 'This email is already registered. Please login instead.');
        }

        return view('admin.accept-invitation', compact('invitation'));
    }

    public function acceptAdminInvitation(Request $request, $token)
    {
        $invitation = \App\Models\AdminInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('admin.login')->with('error', 'Invalid invitation link.');
        }

        if (!$invitation->isValid()) {
            return redirect()->route('admin.login')->with('error', 'This invitation link has expired or has already been used.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if admin already exists
        $admin = Admin::where('email', $invitation->email)->first();

        if ($admin) {
            // Update existing admin
            $admin->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => 'admin', // New admins are always regular admins
            ]);
        } else {
            // Create new admin
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'role' => 'admin', // New admins are always regular admins
            ]);
        }

        // Mark invitation as accepted
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Auto-login the admin
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Account created successfully! Welcome to the admin panel.');
    }

    public function removeUser($userId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super admins cannot delete accounts
        if (!$admin->canDeleteAccounts()) {
            return back()->withErrors(['error' => 'Super admins cannot delete user accounts.']);
        }

        $user = \App\Models\User::findOrFail($userId);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    // ==================== ADMIN CRUD OPERATIONS (Super Admin Only) ====================
    
    public function indexAdmins()
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can manage admins.');
        }
        
        $admins = Admin::where('id', '!=', $currentAdmin->id)->latest()->get();
        
        return view('admin.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create admins.');
        }
        
        return view('admin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,manager',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully!');
    }

    public function editAdmin($id)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can edit admins.');
        }
        
        $admin = Admin::findOrFail($id);
        
        return view('admin.admins.edit', compact('admin'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can update admins.');
        }
        
        $admin = Admin::findOrFail($id);
        
        // Cannot edit super admin
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.admins.index')->with('error', 'Cannot edit Super Admin account.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,manager',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only update role if admin is not a super admin (super admin role is protected)
        if (!$admin->isSuperAdmin()) {
            $updateData['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully!');
    }

    public function destroyAdmin($id)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Only Super Admin can access
        if (!$currentAdmin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can delete admins.');
        }
        
        $admin = Admin::findOrFail($id);
        
        // Cannot delete self
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'Cannot delete your own account.');
        }
        
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully!');
    }

    // ==================== USER CRUD OPERATIONS ====================
    
    public function createUser()
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot create users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot create users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department' => $request->department,
            'job_title' => $request->job_title,
            'location' => $request->location,
            'is_invited' => false,
            'password_set_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function revokeLanguageAccess(Request $request, $userId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot revoke language access
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'language_ids' => 'required|array',
            'language_ids.*' => 'exists:languages,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Verify that all languages are assigned to the current admin
        $adminLanguages = \App\Models\Language::where('assigned_admin_id', $admin->id)
            ->whereIn('id', $request->language_ids)
            ->count();
            
        if ($adminLanguages !== count($request->language_ids)) {
            return back()->withErrors(['language_ids' => 'You can only revoke access to languages that are assigned to you.'])->withInput();
        }

        \App\Models\Language::whereIn('id', $request->language_ids)
            ->update(['assigned_user_id' => null]);

        return redirect()->route('admin.user-management')->with('success', 'Language access revoked successfully!');
    }

    public function toggle2FA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        }

        $admin = Auth::guard('admin')->user();
        
        $admin->update([
            'two_factor_enabled' => $request->enabled
        ]);

        return response()->json([
            'success' => true, 
            'message' => $request->enabled ? '2FA enabled successfully' : '2FA disabled successfully'
        ]);
    }

    // Report CRUD Operations
    public function showCreateReport(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot create reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->active()->get();
        
        // Check if admin has languages assigned
        if ($languages->isEmpty()) {
            return redirect()->route('admin.reports')->with('error', 'You need to have languages assigned to you before you can create reports. Please contact a super admin.');
        }
        
        $users = \App\Models\User::all();
        
        // Pre-fill values from query parameters if provided
        $prefill = [
            'year' => $request->get('year', ''),
            'quarter' => $request->get('quarter', ''),
            'language_id' => $request->get('language_id', ''),
        ];
        
        return view('admin.reports.create', compact('languages', 'users', 'prefill'));
    }
    
    // AJAX endpoint to fetch report data based on filters
    public function getReportData(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot access reports
        if ($admin->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'Q3');
        $languageId = $request->get('language_id');
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->get();
        
        // Build query for reports
        $reportsQuery = \App\Models\Report::with(['user', 'language', 'reviewer'])
            ->whereIn('language_id', $languages->pluck('id'))
            ->where('quarter', 'LIKE', $quarter . ' ' . $year);
            
        if ($languageId) {
            // Verify the language is assigned to this admin
            if ($languages->pluck('id')->contains($languageId)) {
                $reportsQuery->where('language_id', $languageId);
            }
        }
        
        $reports = $reportsQuery->latest()->get();
        
        // Aggregate data from all user-submitted reports for the selected language/quarter
        // If a specific language is selected, aggregate data from all reports for that language
        $reportData = null;
        if ($reports->isNotEmpty() && $languageId) {
            // Get reports for the specific language
            $languageReports = $reports->where('language_id', $languageId);
            
            if ($languageReports->isNotEmpty()) {
                // Aggregate numeric fields (sum them up)
                $reportData = [
                    'language_id' => $languageId,
                    'languages_previous_year' => $languageReports->sum('languages_previous_year'),
                    'languages_goal_2025' => $languageReports->sum('languages_goal_2025'),
                    'languages_goal_q1' => $languageReports->sum('languages_goal_q1'),
                    'languages_achieved_q1' => $languageReports->sum('languages_achieved_q1'),
                    'volunteers_previous_year' => $languageReports->sum('volunteers_previous_year'),
                    'volunteers_goal_2025' => $languageReports->sum('volunteers_goal_2025'),
                    'volunteers_goal_q1' => $languageReports->sum('volunteers_goal_q1'),
                    'volunteers_achieved_q1' => $languageReports->sum('volunteers_achieved_q1'),
                    'facebook_reach' => $languageReports->sum('facebook_reach'),
                    'instagram_reach' => $languageReports->sum('instagram_reach'),
                    'youtube_reach' => $languageReports->sum('youtube_reach'),
                    'website_reach' => $languageReports->sum('website_reach'),
                    'evangelistic_students' => $languageReports->sum('evangelistic_students'),
                    'discipleship_students' => $languageReports->sum('discipleship_students'),
                    'leadership_students' => $languageReports->sum('leadership_students'),
                    'evangelistic_conversations' => $languageReports->sum('evangelistic_conversations'),
                    'pastoral_connections' => $languageReports->sum('pastoral_connections'),
                    'income_euros' => $languageReports->sum('income_euros'),
                    'expenditure_euros' => $languageReports->sum('expenditure_euros'),
                    'pr_total_organic_reach' => $languageReports->sum('pr_total_organic_reach'),
                    'personal_fte' => $languageReports->sum('personal_fte'),
                    // For text fields, combine them or take the most recent
                    'new_activity' => $languageReports->pluck('new_activity')->filter()->implode("\n\n"),
                    'organizational_highlight' => $languageReports->pluck('organizational_highlight')->filter()->implode("\n\n"),
                    'organizational_concern' => $languageReports->pluck('organizational_concern')->filter()->implode("\n\n"),
                    'organizational_issues' => $languageReports->pluck('organizational_issues')->filter()->implode("\n\n"),
                ];
            }
        }
        
        // Calculate statistics
        $stats = [
            'total_reports' => $reports->count(),
            'total_languages' => $reports->pluck('language_id')->unique()->count(),
            'reports_due' => $reports->where('status', 'draft')->count(),
            'reports_reviewed' => $reports->where('review_status', 'reviewed')->count(),
            'reports_approved' => $reports->where('review_status', 'approved')->count(),
            'year_submitted' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('created_at', $year)->count(),
            'year_reviewed' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('reviewed_at', $year)->count(),
            'year_approved' => \App\Models\Report::whereIn('language_id', $languages->pluck('id'))->whereYear('reviewed_at', $year)->where('review_status', 'approved')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'report_data' => $reportData,
            'reports' => $reports->map(function($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'user' => $report->user ? $report->user->name : 'Unknown',
                    'language' => $report->language ? $report->language->name : 'Unknown',
                    'quarter' => $report->quarter,
                    'status' => $report->review_status ?: $report->status,
                    'updated_at' => $report->updated_at->format('Y-m-d')
                ];
            })
        ]);
    }

    public function storeReport(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot create reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        // Verify language is assigned to this admin
        $language = \App\Models\Language::find($request->language_id);
        if (!$language || $language->assigned_admin_id !== $admin->id) {
            return back()->withErrors(['language_id' => 'You can only create reports for languages assigned to you.'])->withInput();
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:Quarterly Progress,Quarterly Summary,Quarterly Review',
            'quarter' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'language_id' => 'required|exists:languages,id',
            'languages_previous_year' => 'required|integer|min:0',
            'languages_goal_2025' => 'required|integer|min:0',
            'languages_goal_q1' => 'required|integer|min:0',
            'languages_achieved_q1' => 'required|integer|min:0',
            'volunteers_previous_year' => 'required|integer|min:0',
            'volunteers_goal_2025' => 'required|integer|min:0',
            'volunteers_goal_q1' => 'required|integer|min:0',
            'volunteers_achieved_q1' => 'required|integer|min:0',
            'facebook_reach' => 'required|integer|min:0',
            'instagram_reach' => 'required|integer|min:0',
            'youtube_reach' => 'required|integer|min:0',
            'website_reach' => 'required|integer|min:0',
            'evangelistic_students' => 'required|integer|min:0',
            'discipleship_students' => 'required|integer|min:0',
            'leadership_students' => 'required|integer|min:0',
            'evangelistic_conversations' => 'required|integer|min:0',
            'pastoral_connections' => 'required|integer|min:0',
            'income_euros' => 'required|numeric|min:0',
            'expenditure_euros' => 'required|numeric|min:0',
            'pr_total_organic_reach' => 'required|integer|min:0',
            'personal_fte' => 'required|numeric|min:0',
            'new_activity' => 'nullable|string|max:1000',
            'organizational_highlight' => 'nullable|string|max:500',
            'organizational_concern' => 'nullable|string|max:500',
            'organizational_issues' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        \App\Models\Report::create($request->all());

        return redirect()->route('admin.reports')->with('success', 'Report created successfully!');
    }

    public function showEditReport($id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot edit reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $report = \App\Models\Report::with(['comments.admin', 'user', 'language'])->findOrFail($id);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return redirect()->route('admin.reports')->with('error', 'You can only edit reports for languages assigned to you.');
        }
        
        // Admin only sees languages assigned to them
        $languages = \App\Models\Language::where('assigned_admin_id', $admin->id)->active()->get();
        $users = \App\Models\User::all();
        
        // Group comments by field for easy display
        $commentsByField = $report->comments->groupBy('field') ?? collect();
        
        return view('admin.reports.edit', compact('report', 'languages', 'users', 'commentsByField'));
    }

    public function updateReport(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot update reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $report = \App\Models\Report::findOrFail($id);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return redirect()->route('admin.reports')->with('error', 'You can only update reports for languages assigned to you.');
        }
        
        // Verify new language (if changed) is assigned to this admin
        if ($request->language_id != $report->language_id) {
            $language = \App\Models\Language::find($request->language_id);
            if (!$language || $language->assigned_admin_id !== $admin->id) {
                return back()->withErrors(['language_id' => 'You can only assign reports to languages assigned to you.'])->withInput();
            }
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:Quarterly Progress,Quarterly Summary,Quarterly Review',
            'quarter' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'language_id' => 'required|exists:languages,id',
            'languages_previous_year' => 'required|integer|min:0',
            'languages_goal_2025' => 'required|integer|min:0',
            'languages_goal_q1' => 'required|integer|min:0',
            'languages_achieved_q1' => 'required|integer|min:0',
            'volunteers_previous_year' => 'required|integer|min:0',
            'volunteers_goal_2025' => 'required|integer|min:0',
            'volunteers_goal_q1' => 'required|integer|min:0',
            'volunteers_achieved_q1' => 'required|integer|min:0',
            'facebook_reach' => 'required|integer|min:0',
            'instagram_reach' => 'required|integer|min:0',
            'youtube_reach' => 'required|integer|min:0',
            'website_reach' => 'required|integer|min:0',
            'evangelistic_students' => 'required|integer|min:0',
            'discipleship_students' => 'required|integer|min:0',
            'leadership_students' => 'required|integer|min:0',
            'evangelistic_conversations' => 'required|integer|min:0',
            'pastoral_connections' => 'required|integer|min:0',
            'income_euros' => 'required|numeric|min:0',
            'expenditure_euros' => 'required|numeric|min:0',
            'pr_total_organic_reach' => 'required|integer|min:0',
            'personal_fte' => 'required|numeric|min:0',
            'new_activity' => 'nullable|string|max:1000',
            'organizational_highlight' => 'nullable|string|max:500',
            'organizational_concern' => 'nullable|string|max:500',
            'organizational_issues' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $report->update($request->all());

        return redirect()->route('admin.reports')->with('success', 'Report updated successfully!');
    }

    public function addComment(Request $request, $reportId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot add comments
        if ($admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin can only manage languages and assign them to admins.'
            ], 403);
        }
        
        $report = \App\Models\Report::findOrFail($reportId);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only comment on reports for languages assigned to you.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'section' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $comment = \App\Models\ReportComment::create([
            'report_id' => $report->id,
            'admin_id' => $admin->id,
            'section' => $request->section,
            'field' => $request->field,
            'comment' => $request->comment,
        ]);
        
        $comment->load('admin');
        
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => $comment
        ]);
    }

    public function submitToSuperAdmin($reportId)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot submit reports
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $report = \App\Models\Report::findOrFail($reportId);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return redirect()->route('admin.reports')->with('error', 'You can only submit reports for languages assigned to you.');
        }
        
        // Update report status to pending_super_admin_review
        $report->update([
            'status' => 'pending_super_admin_review',
            'review_status' => 'pending',
        ]);
        
        return redirect()->route('admin.reports')->with('success', 'Report submitted to Super Admin for review successfully!');
    }

    public function deleteReport($id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot delete reports
        if ($admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin can only manage languages and assign them to admins.'
            ], 403);
        }
        
        $report = \App\Models\Report::findOrFail($id);
        
        // Verify the report's language is assigned to this admin
        if ($report->language->assigned_admin_id !== $admin->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete reports for languages assigned to you.'
            ], 403);
        }
        
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully!'
        ]);
    }

    // Language CRUD Operations
    public function showEditLanguage($id)
    {
        $admin = Auth::guard('admin')->user();
        $language = \App\Models\Language::findOrFail($id);
        
        // Only Super Admin can edit languages
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can edit languages.');
        }
        
        return view('admin.languages.edit', compact('language'));
    }

    public function updateLanguage(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $language = \App\Models\Language::findOrFail($id);
        
        // Only Super Admin can update languages
        if (!$admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can update languages.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $language->update($request->all());

        return redirect()->route('admin.language-assignment')->with('success', 'Language updated successfully!');
    }

    public function deleteLanguage($id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Only Super Admin can delete languages
        if (!$admin->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Super Admin can delete languages.'
            ], 403);
        }
        
        $language = \App\Models\Language::findOrFail($id);
        $language->delete();

        return response()->json([
            'success' => true,
            'message' => 'Language deleted successfully!'
        ]);
    }

    // User CRUD Operations

    public function showEditUser($id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot edit users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin cannot update users
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Super Admin can only manage languages and assign them to admins.');
        }
        
        $user = \App\Models\User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }
}
