@extends('admin.layouts.app')

@section('title', 'Settings - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Settings</h1>
        <p class="text-gray-500">Manage your account information and security settings</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fa fa-user mr-2 text-gray-600"></i>Profile Information
        </h4>
        
        <form action="{{ route('admin.update-profile') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <input type="text" value="{{ ucfirst(str_replace('_', ' ', $admin->role ?? 'admin')) }}" disabled 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                        <input type="text" value="{{ $admin->created_at->format('M d, Y') }}" disabled 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-500">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fa fa-shield-alt mr-2 text-gray-600"></i>Two-Factor Authentication (2FA)
        </h4>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div>
                    <h5 class="font-medium text-blue-800">Enable Two-Factor Authentication</h5>
                    <p class="text-sm text-blue-600">Add an extra layer of security to your account with 2FA.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="2fa-toggle" class="sr-only peer" 
                           {{ $admin->two_factor_enabled ? 'checked' : '' }}
                           onchange="toggle2FA()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            @if($admin->two_factor_enabled)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fa fa-check-circle text-green-600 mr-2"></i>
                    <span class="text-green-800 font-medium">Two-Factor Authentication is enabled</span>
                </div>
                <p class="text-sm text-green-600 mt-1">Your account is protected with 2FA.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Security Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fa fa-shield-alt mr-2 text-gray-600"></i>Security Settings
        </h4>
        
        <form action="{{ route('admin.update-password') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password" placeholder="Enter current password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter new password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-key mr-2"></i>Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Account Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fa fa-exclamation-triangle mr-2 text-gray-600"></i>Account Actions
        </h4>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div>
                    <h5 class="font-medium text-yellow-800">Logout from all devices</h5>
                    <p class="text-sm text-yellow-600">This will log you out from all devices and require you to log in again.</p>
                </div>
                <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i>Logout All
                </button>
            </div>
            
            @if($admin->canDeleteAccounts())
            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                <div>
                    <h5 class="font-medium text-red-800">Delete Account</h5>
                    <p class="text-sm text-red-600">This action cannot be undone. All your data will be permanently deleted.</p>
                </div>
                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-trash mr-2"></i>Delete Account
                </button>
            </div>
            @else
            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div>
                    <h5 class="font-medium text-gray-600">Delete Account</h5>
                    <p class="text-sm text-gray-500">Super admins cannot delete their accounts for security reasons.</p>
                </div>
                <button class="bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed" disabled>
                    <i class="fa-solid fa-trash mr-2"></i>Delete Account
                </button>
            </div>
            @endif
        </div>
    </div>

    <script>
        function toggle2FA() {
            const toggle = document.getElementById('2fa-toggle');
            const isEnabled = toggle.checked;
            
            if (confirm(isEnabled ? 
                'Are you sure you want to enable Two-Factor Authentication? You will need to set up an authenticator app.' : 
                'Are you sure you want to disable Two-Factor Authentication? This will make your account less secure.')) {
                
                // In a real implementation, this would be an AJAX call to update the 2FA status
                fetch('/admin/toggle-2fa', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        enabled: isEnabled
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating 2FA settings: ' + data.message);
                        toggle.checked = !isEnabled; // Revert the toggle
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating 2FA settings');
                    toggle.checked = !isEnabled; // Revert the toggle
                });
            } else {
                toggle.checked = !isEnabled; // Revert the toggle
            }
        }
    </script>
@endsection 