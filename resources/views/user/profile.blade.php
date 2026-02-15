@extends('user.layouts.app')

@section('title', 'Profile - GlobalRize User Portal')

@section('page_title', 'Profile Settings')

@section('content')
<!-- Welcome Message -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        @if(Auth::user()->avatar)
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-full object-cover">
        @else
                    <i class="fa fa-user text-2xl"></i>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-green-100">
                    @if(Auth::user()->job_title && Auth::user()->department)
                        {{ Auth::user()->job_title }} at {{ Auth::user()->department }}
                    @elseif(Auth::user()->job_title)
                        {{ Auth::user()->job_title }}
                    @elseif(Auth::user()->department)
                        {{ Auth::user()->department }} Team
                    @else
                        Welcome to your GlobalRize User Portal
                    @endif
                </p>
                <p class="text-green-100 text-sm mt-1">
                    @if(Auth::user()->location)
                        📍 {{ Auth::user()->location }}
                    @endif
                    @if($userStats['total_reports'] > 0)
                        • 📊 {{ $userStats['total_reports'] }} report(s) created
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Top Bar -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-1">Profile Settings</h1>
    <p class="text-gray-500">Manage your account information and preferences</p>
</div>

<!-- Profile Information -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-user mr-2 text-gray-600"></i>Personal Information
    </h4>
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600 mr-2"></i>
                <span class="text-red-800 text-sm">Please fix the following errors:</span>
            </div>
            <ul class="mt-2 text-red-700 text-sm">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ Auth::user()->phone }}" placeholder="+1 (555) 123-4567" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" name="department" value="{{ Auth::user()->department }}" placeholder="e.g., Marketing, Sales, IT" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                    <input type="text" name="job_title" value="{{ Auth::user()->job_title }}" placeholder="e.g., Manager, Specialist, Analyst" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ Auth::user()->location }}" placeholder="e.g., New York, Remote" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
            <textarea name="bio" rows="3" placeholder="Tell us about yourself..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ Auth::user()->bio }}</textarea>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Profile Picture -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-image mr-2 text-gray-600"></i>Profile Picture
    </h4>
    
    <div class="flex items-center space-x-6">
        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile Picture" class=" h-40 object-cover">
            @else
                <i class="fa fa-user text-3xl text-gray-400"></i>
            @endif
        </div>
        
        <div class="flex-1">
            <p class="text-gray-600 mb-4">Upload a profile picture to personalize your account. Supported formats: JPG, PNG, GIF (max 2MB)</p>
            <form action="{{ route('user.profile.update-avatar') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                @csrf
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-upload mr-2"></i>Upload
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Profile Completion -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-chart-pie mr-2 text-gray-600"></i>Profile Completion
    </h4>
    
    @php
        $profileFields = ['name', 'email', 'phone', 'department', 'job_title', 'location', 'bio', 'avatar'];
        $completedFields = 0;
        $user = Auth::user();
        
        foreach ($profileFields as $field) {
            if ($user->$field && trim($user->$field) !== '') {
                $completedFields++;
            }
        }
        
        $completionPercentage = round(($completedFields / count($profileFields)) * 100);
    @endphp
    
    <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Profile Completion</span>
            <span class="text-sm font-medium text-gray-900">{{ $completionPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($profileFields as $field)
            <div class="text-center">
                <div class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center {{ $user->$field && trim($user->$field) !== '' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                    <i class="fa-solid fa-{{ $field === 'avatar' ? 'image' : ($field === 'bio' ? 'file-text' : 'check') }} text-sm"></i>
                </div>
                <span class="text-xs text-gray-600 capitalize">{{ str_replace('_', ' ', $field) }}</span>
            </div>
        @endforeach
    </div>
    
    @if($completionPercentage < 100)
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500 mb-2">Complete your profile to unlock additional features</p>
            <button onclick="document.querySelector('input[name=\'phone\']').focus()" class="text-green-600 hover:text-green-700 text-sm font-medium">
                <i class="fa-solid fa-edit mr-1"></i>Complete Profile
            </button>
        </div>
    @else
        <div class="mt-4 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full">
                <i class="fa-solid fa-trophy mr-2"></i>
                <span class="text-sm font-medium">Profile Complete!</span>
            </div>
        </div>
    @endif
</div>

<!-- Account Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-file-lines text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Total Reports</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $userStats['total_reports'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-check-circle text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Submitted Reports</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $userStats['submitted_reports'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-edit text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Draft Reports</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $userStats['draft_reports'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Languages -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-language mr-2 text-gray-600"></i>My Assigned Languages
    </h4>
    <p class="text-gray-500 mb-6">Languages you are responsible for reporting on</p>
    
    @if($assignedLanguages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($assignedLanguages as $language)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                        <i class="fa fa-language text-lg"></i>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                        {{ ucfirst($language->status) }}
                    </span>
                </div>
                <h6 class="font-semibold text-gray-900 mb-1">{{ $language->name }}</h6>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Assigned: {{ $language->created_at->format('M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fa fa-language text-4xl mb-4"></i>
            <h5 class="text-lg font-medium text-gray-900 mb-2">No languages assigned yet</h5>
            <p class="text-gray-500">Contact your administrator to get assigned languages for reporting.</p>
        </div>
    @endif
</div>

<!-- Recent Reports -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-file-lines mr-2 text-gray-600"></i>Recent Reports
    </h4>
    
    @if(count($recentReports) > 0)
        <div class="space-y-4">
            @foreach(array_slice($recentReports, 0, 5) as $report)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-{{ $report['status'] === 'submitted' ? 'green' : ($report['status'] === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $report['status'] === 'submitted' ? 'green' : ($report['status'] === 'draft' ? 'yellow' : 'blue') }}-600 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-{{ $report['status'] === 'submitted' ? 'check' : ($report['status'] === 'draft' ? 'edit' : 'eye') }}"></i>
                    </div>
                    <div>
                        <h6 class="font-medium text-gray-900">{{ $report['title'] }}</h6>
                        <p class="text-sm text-gray-500">{{ $report['quarter'] }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-{{ $report['status'] === 'submitted' ? 'green' : ($report['status'] === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $report['status'] === 'submitted' ? 'green' : ($report['status'] === 'draft' ? 'yellow' : 'blue') }}-800 text-xs font-medium px-2 py-1 rounded-full">
                        {{ ucfirst($report['status']) }}
                    </span>
                    <a href="{{ route('user.reports.edit', $report['id']) }}" class="text-gray-600 hover:text-gray-800 px-3 py-1 text-sm border border-gray-300 rounded hover:bg-green-50 transition-colors duration-200">
                        <i class="fa-solid fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('user.reports') }}" class="text-green-600 hover:text-green-700 font-medium">
                View All Reports <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fa fa-file-lines text-4xl mb-4"></i>
            <h5 class="text-lg font-medium text-gray-900 mb-2">No reports yet</h5>
            <p class="text-gray-500 mb-4">Start creating your first quarterly report to track your progress.</p>
            <a href="{{ route('user.reports.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>Create First Report
            </a>
        </div>
    @endif
</div>

<!-- Two-Factor Authentication -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-shield-alt mr-2 text-gray-600"></i>Two-Factor Authentication
    </h4>
    
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h6 class="font-medium text-gray-900">Two-Factor Authentication</h6>
                <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-shield-alt mr-2"></i>Enable 2FA
            </button>
        </div>
    </div>
</div>

<!-- Password Change -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-lock mr-2 text-gray-600"></i>Change Password
    </h4>
    
    <form action="{{ route('user.profile.change-password') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                <input type="password" name="current_password" placeholder="Enter current password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                <input type="password" name="password" placeholder="Enter new password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-key mr-2"></i>Change Password
            </button>
        </div>
    </form>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-history mr-2 text-gray-600"></i>Recent Activity
    </h4>
    
    <div class="space-y-4">
        @foreach($recentActivity as $activity)
        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
            <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-{{ $activity['type'] === 'profile_updated' ? 'user-edit' : ($activity['type'] === 'password_changed' ? 'key' : 'user-plus') }}"></i>
            </div>
            <div class="flex-1">
                <h6 class="font-medium text-gray-900">{{ $activity['message'] }}</h6>
                <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
            </div>
            <div class="text-{{ $activity['color'] }}">
                <i class="fa-solid fa-circle text-xs"></i>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save profile changes (optional feature)
    const profileForm = document.querySelector('form[action*="profile/update"]');
    const inputs = profileForm.querySelectorAll('input, textarea');
    
    // Add change detection
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // You could implement auto-save here
            console.log('Profile field changed:', input.name);
        });
    });
    
    // Profile completion animation
    const completionBar = document.querySelector('.bg-green-600.h-2');
    if (completionBar) {
        setTimeout(() => {
            completionBar.style.transition = 'width 1s ease-in-out';
        }, 500);
    }
    
    // File upload preview
    const avatarInput = document.querySelector('input[name="avatar"]');
    const avatarPreview = document.querySelector('.w-24.h-24 img, .w-24.h-24 i');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview.tagName === 'IMG') {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Replace icon with image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Picture';
                        img.className = 'w-full h-full object-cover';
                        avatarPreview.parentNode.replaceChild(img, avatarPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection 