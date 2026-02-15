@extends('admin.layouts.app')

@section('title', 'User Management - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">User Management</h1>
        <p class="text-gray-500">Manage user roles and permissions for the GlobalRize system.</p>
    </div>

    <!-- Create Users -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>User Management
                </h4>
                <p class="text-gray-600">Create and manage user accounts in the system.</p>
            </div>
            <div class="ml-6">
                <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Create User
                </a>
            </div>
        </div>
    </div>

    <!-- All Users and Admins -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h4 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-users text-green-600 mr-2"></i>All Users and Admins
        </h4>
        
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 bg-white rounded-lg shadow-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Languages</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($users as $user)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">User</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            {{ $user->assignedLanguages->count() }} languages
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($user->isInvited() && !$user->hasSetPassword()) bg-yellow-100 text-yellow-800
                                @elseif($user->hasSetPassword()) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($user->isInvited() && !$user->hasSetPassword()) Invited
                                @elseif($user->hasSetPassword()) Active
                                @else Inactive @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="openLanguageModal({{ $user->id }})" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-language mr-1"></i>Languages
                                </button>
                                @if(auth('admin')->user()->canDeleteAccounts())
                                <button onclick="removeUser({{ $user->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Remove
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    @foreach($admins as $admin)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $admin->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $admin->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($admin->role === 'super_admin') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">-</td>
                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <span class="text-gray-400 text-xs">No actions</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Language Access Modal -->
    <div id="language-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Manage Language Access</h3>
                    <button onclick="closeLanguageModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="language-form" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Languages to Revoke</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach($languages as $language)
                            <label class="flex items-center">
                                <input type="checkbox" name="language_ids[]" value="{{ $language->id }}" class="mr-3">
                                <span class="text-sm text-gray-700">{{ $language->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeLanguageModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Revoke Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentUserId = null;

        // Open language modal
        function openLanguageModal(userId) {
            currentUserId = userId;
            document.getElementById('language-form').action = `/admin/users/${userId}/revoke-language-access`;
            document.getElementById('language-modal').classList.remove('hidden');
        }

        // Close language modal
        function closeLanguageModal() {
            document.getElementById('language-modal').classList.add('hidden');
            currentUserId = null;
        }

        // Remove user
        function removeUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = csrfToken;
                
                form.appendChild(methodField);
                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection 