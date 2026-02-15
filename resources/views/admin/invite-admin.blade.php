@extends('admin.layouts.app')

@section('title', 'Invite Admin - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Invite Admin</h1>
                <p class="text-gray-500">Send an email invitation to a new admin to join the platform.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Dashboard
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

    <!-- Invite Admin Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-envelope text-purple-600 mr-2"></i>
                <h2 class="text-xl font-semibold text-gray-900">Admin Invitation</h2>
            </div>
            <p class="text-gray-600 text-sm">Enter the email address of the person you want to invite as an admin. They will receive an email with instructions to create their account.</p>
        </div>

        <form action="{{ route('admin.invite-admin') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                       placeholder="admin@example.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">The invitation will be sent to this email address.</p>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">What happens next?</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>The invited person will receive an email with an invitation link</li>
                                <li>They can click the link to create their admin account</li>
                                <li>The invitation link will expire in 7 days</li>
                                <li>You can view pending invitations on your dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                    <i class="fa-solid fa-envelope mr-2"></i>Send Invitation
                </button>
            </div>
        </form>
    </div>
@endsection

