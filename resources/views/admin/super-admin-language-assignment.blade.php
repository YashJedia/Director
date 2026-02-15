@extends('admin.layouts.app')

@section('title', 'Language Assignment - Super Admin - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Language Assignment</h1>
                <p class="text-gray-500">Assign languages to admins. Admins can then assign these languages to users.</p>
            </div>
            <a href="{{ route('admin.create-language') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>Add New Language
            </a>
        </div>
    </div>

    <!-- Admin Assignment Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-green-600 text-white rounded-lg flex items-center justify-center font-semibold mr-3">
                <i class="fa-solid fa-user-shield text-sm"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Assign Languages to Admin</h2>
        </div>
        
        <form action="{{ route('admin.assign-language') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Language Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Languages (Multiple)</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                        @php
                            $unassignedLanguages = $languages->where('assigned_admin_id', null);
                        @endphp
                        
                        @if($unassignedLanguages->count() > 0)
                            @foreach($unassignedLanguages as $language)
                                <label class="flex items-center mb-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="language_ids[]" value="{{ $language->id }}" 
                                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $language->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-check-circle text-green-500 text-2xl mb-2"></i>
                                <p class="text-gray-600">All languages are already assigned to admins!</p>
                            </div>
                        @endif
                    </div>
                    @if($unassignedLanguages->count() > 0)
                        <p class="text-xs text-gray-500 mt-2">Select multiple languages to assign to an admin</p>
                    @endif
                </div>

                <!-- Admin Selection -->
                <div>
                    <label for="admin_id" class="block text-sm font-medium text-gray-700 mb-2">Select Admin</label>
                    <select name="admin_id" id="admin_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Choose an admin</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Only regular admins can be assigned languages</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200"
                        {{ $unassignedLanguages->count() == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-link mr-2"></i>Assign Selected Languages to Admin
                </button>
            </div>
        </form>
    </div>

    <!-- Current Assignments Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-purple-600 text-white rounded-lg flex items-center justify-center font-semibold mr-3">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Current Language Assignments to Admins</h2>
        </div>
        
        @php
            $assignedLanguages = $languages->where('assigned_admin_id', '!=', null)->groupBy('assigned_admin_id');
        @endphp
        
        @if($assignedLanguages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignedLanguages as $adminId => $adminLanguages)
                    @php
                        $admin = $admins->find($adminId);
                    @endphp
                    @if($admin)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $admin->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                @foreach($adminLanguages as $language)
                                    <div class="flex items-center justify-between bg-gray-50 rounded px-3 py-2">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $language->name }}</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="bg-{{ $language->status == 'active' ? 'green' : 'red' }}-100 text-{{ $language->status == 'active' ? 'green' : 'red' }}-600 text-xs font-medium px-2 py-1 rounded-full">
                                                {{ ucfirst($language->status) }}
                                            </span>
                                            <a href="{{ route('admin.languages.edit', $language->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteLanguage({{ $language->id }})" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-xs text-gray-500">
                                {{ $adminLanguages->count() }} language(s) assigned
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fa-solid fa-users text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600 font-medium mb-1">No language assignments found</p>
                <p class="text-gray-400 text-sm">Assign languages to admins to see them here</p>
            </div>
        @endif
    </div>

    <!-- All Languages List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-8">
        <h4 class="text-xl font-bold text-gray-900 mb-6">All Languages</h4>
        
        @if($languages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($languages as $language)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $language->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($language->status === 'active') bg-green-100 text-green-800
                                        @elseif($language->status === 'inactive') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($language->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($language->admin)
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-user-shield text-blue-600 mr-2"></i>
                                            {{ $language->admin->name }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.languages.edit', $language->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <button onclick="deleteLanguage({{ $language->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fa-solid fa-globe text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600 font-medium mb-1">No languages found</p>
                <p class="text-gray-400 text-sm mb-4">Get started by creating your first language</p>
                <a href="{{ route('admin.create-language') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Add New Language
                </a>
            </div>
        @endif
    </div>

    <script>
        function deleteLanguage(languageId) {
            if (confirm('Are you sure you want to delete this language? This action cannot be undone.')) {
                fetch(`/admin/languages/${languageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting language: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting language');
                });
            }
        }
    </script>
@endsection

