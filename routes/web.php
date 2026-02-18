<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Admin invitation acceptance routes (public)
    Route::get('/invitation/{token}', [AdminController::class, 'showAdminInvitationAcceptance'])->name('invitation.accept');
    Route::post('/invitation/{token}', [AdminController::class, 'acceptAdminInvitation'])->name('invitation.set-password');
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/user-management', [AdminController::class, 'userManagement'])->name('user-management');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/quarterly-report', [AdminController::class, 'quarterlyReport'])->name('quarterly-report');
        Route::get('/language-assignment', [AdminController::class, 'languageAssignment'])->name('language-assignment');
        Route::get('/create-language', [AdminController::class, 'createLanguage'])->name('create-language');
        Route::post('/store-language', [AdminController::class, 'storeLanguage'])->name('store-language');
        Route::post('/assign-language', [AdminController::class, 'assignLanguageToUser'])->name('assign-language');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('update-profile');
        Route::post('/update-password', [AdminController::class, 'updatePassword'])->name('update-password');
        
        // Report management
        Route::get('/reports/create', [AdminController::class, 'showCreateReport'])->name('reports.create');
        Route::post('/reports/store', [AdminController::class, 'storeReport'])->name('reports.store');
        Route::get('/reports/data', [AdminController::class, 'getReportData'])->name('reports.data');
        Route::get('/reports/{report}/edit', [AdminController::class, 'showEditReport'])->name('reports.edit');
        Route::put('/reports/{report}/update', [AdminController::class, 'updateReport'])->name('reports.update');
        Route::delete('/reports/{report}', [AdminController::class, 'deleteReport'])->name('reports.delete');
        Route::post('/reports/{report}/review', [AdminController::class, 'reviewReport'])->name('reports.review');
        Route::post('/reports/{report}/send-for-revision', [AdminController::class, 'sendForRevision'])->name('reports.send-for-revision');
        Route::post('/reports/{report}/comment', [AdminController::class, 'addComment'])->name('reports.comment');
        Route::post('/reports/{report}/submit-to-super-admin', [AdminController::class, 'submitToSuperAdmin'])->name('reports.submit-to-super-admin');
        
        // Language management
        Route::get('/languages/{language}/edit', [AdminController::class, 'showEditLanguage'])->name('languages.edit');
        Route::put('/languages/{language}/update', [AdminController::class, 'updateLanguage'])->name('languages.update');
        Route::delete('/languages/{language}', [AdminController::class, 'deleteLanguage'])->name('languages.delete');
        
        // Admin Management (Super Admin only)
        Route::get('/admins', [AdminController::class, 'indexAdmins'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{admin}/edit', [AdminController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');
        
        // User management
        Route::get('/users', [AdminController::class, 'userManagement'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'showEditUser'])->name('users.edit');
        Route::put('/users/{user}/update', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'removeUser'])->name('users.remove');
        Route::post('/users/{user}/revoke-language-access', [AdminController::class, 'revokeLanguageAccess'])->name('users.revoke-language-access');
        
        // Keep old routes for backward compatibility (can be removed later)
        Route::get('/user-management', [AdminController::class, 'userManagement'])->name('user-management');
        Route::get('/invite-user', [AdminController::class, 'showInviteUser'])->name('invite-user.show');
        Route::post('/invite-user', [AdminController::class, 'inviteUser'])->name('invite-user');
        Route::get('/invite-admin', [AdminController::class, 'showInviteAdmin'])->name('invite-admin.show');
        Route::post('/invite-admin', [AdminController::class, 'inviteAdmin'])->name('invite-admin');
        
        // Security
        Route::post('/toggle-2fa', [AdminController::class, 'toggle2FA'])->name('toggle-2fa');
    });
});

// User Routes
Route::prefix('user')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserController::class, 'login']);
    
    // Invitation acceptance routes (public)
    Route::get('/invitation/{token}', [UserController::class, 'showInvitationAcceptance'])->name('user.invitation.accept');
    Route::post('/invitation/{token}', [UserController::class, 'acceptInvitation'])->name('user.invitation.set-password');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/reports', [UserController::class, 'reports'])->name('user.reports');
        Route::get('/languages', [UserController::class, 'languages'])->name('user.languages');
        Route::get('/help', [UserController::class, 'help'])->name('user.help');
        
        // Profile management routes
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
        Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('user.profile.change-password');
        Route::post('/profile/update-avatar', [UserController::class, 'updateAvatar'])->name('user.profile.update-avatar');
        
        // Report management routes (no deletion allowed)
        Route::get('/reports/create', [UserController::class, 'showCreateForm'])->name('user.reports.create');
        Route::post('/reports/store', [UserController::class, 'storeReport'])->name('user.reports.store');
        Route::get('/reports/{report}/edit', [UserController::class, 'showEditForm'])->name('user.reports.edit');
        Route::put('/reports/{report}/update', [UserController::class, 'updateReport'])->name('user.reports.update');
        Route::post('/reports/{report}/submit', [UserController::class, 'submitReport'])->name('user.reports.submit');
        
        Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
    });
});
