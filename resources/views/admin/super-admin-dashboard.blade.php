@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
            <p class="text-gray-500">Super Admin Dashboard - Language Management</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.admins.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-users mr-2"></i>Manage Admins
            </a>
            <a href="{{ route('admin.create-language') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>Add New Language
            </a>
            <a href="{{ route('admin.language-assignment') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-link mr-2"></i>Assign Languages
            </a>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Total Languages</h6>
                    <h4 class="text-3xl font-bold text-gray-900">{{ $totalLanguages }}</h4>
                    <p class="text-gray-500 text-sm mt-1">All languages in system</p>
                </div>
                <div class="text-blue-600 text-2xl">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Active Languages</h6>
                    <h4 class="text-3xl font-bold text-gray-900">{{ $activeLanguages }}</h4>
                    <p class="text-gray-500 text-sm mt-1">Currently active</p>
                </div>
                <div class="text-green-600 text-2xl">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Assigned Languages</h6>
                    <h4 class="text-3xl font-bold text-gray-900">{{ $assignedLanguages }}</h4>
                    <p class="text-gray-500 text-sm mt-1">Assigned to admins</p>
                </div>
                <div class="text-purple-600 text-2xl">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Total Admins</h6>
                    <h4 class="text-3xl font-bold text-gray-900">{{ $admins->count() }}</h4>
                    <p class="text-gray-500 text-sm mt-1">Regular admins</p>
                </div>
                <div class="text-orange-600 text-2xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-xl font-bold text-gray-900">All Languages</h4>
                <p class="text-gray-500 text-sm">Manage and assign languages to admins</p>
            </div>
            <a href="{{ route('admin.language-assignment') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                View All <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if($languages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($languages->take(10) as $language)
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
            @if($languages->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.language-assignment') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View all {{ $languages->count() }} languages <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @endif
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

    <!-- Recent Admins -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Admins</h4>
        <p class="text-gray-500 text-sm mb-6">Regular admins who can be assigned languages</p>
        
        @if($admins->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($admins->take(6) as $admin)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold mr-3">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-900">{{ $admin->name }}</h5>
                                <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $admin->assignedLanguages->count() }} language(s) assigned
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fa-solid fa-users text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600 font-medium mb-1">No admins found</p>
                <p class="text-gray-400 text-sm">Invite admins to get started</p>
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

