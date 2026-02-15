@extends('admin.layouts.app')

@section('title', 'Edit Admin - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Edit Admin</h1>
                <p class="text-gray-500">Update admin account information.</p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Admins
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Edit Admin Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $admin->name) }}" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                           placeholder="John Doe">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $admin->email) }}" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                           placeholder="admin@example.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" 
                            id="role" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('role') border-red-500 @enderror"
                            {{ $admin->isSuperAdmin() ? 'disabled' : '' }}>
                        <option value="">Select Role</option>
                        <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                    </select>
                    @if($admin->isSuperAdmin())
                        <input type="hidden" name="role" value="super_admin">
                        <p class="text-yellow-600 text-xs mt-1">Super Admin role cannot be changed.</p>
                    @endif
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-gray-400">(Leave blank to keep current)</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('password') border-red-500 @enderror"
                           placeholder="Minimum 8 characters">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="md:col-span-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Re-enter new password">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.admins.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                    <i class="fa-solid fa-save mr-2"></i>Update Admin
                </button>
            </div>
        </form>
    </div>
@endsection

