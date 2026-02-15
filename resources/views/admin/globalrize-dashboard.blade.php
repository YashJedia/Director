@extends('admin.layouts.app')

@section('title', 'GlobalRize Reporting - Admin Dashboard')

@section('content')
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
            <p class="text-gray-500">International Director Dashboard</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="bg-white border border-gray-300 text-gray-700 rounded-full px-4 py-2 text-sm font-medium">Q3 2025</span>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>New Report
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Total Reports</h6>
                    <h4 class="text-3xl font-bold text-gray-900">2</h4>
                    <p class="text-gray-500 text-sm mt-1">All time reports created</p>
                </div>
                <div class="text-blue-600 text-2xl">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Draft Reports</h6>
                    <h4 class="text-3xl font-bold text-gray-900">0</h4>
                    <p class="text-gray-500 text-sm mt-1">Pending completion</p>
                </div>
                <div class="text-yellow-600 text-2xl">
                    <i class="fa-solid fa-edit"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Submitted</h6>
                    <h4 class="text-3xl font-bold text-gray-900">2</h4>
                    <p class="text-gray-500 text-sm mt-1">Completed reports</p>
                </div>
                <div class="text-green-600 text-2xl">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">This Quarter</h6>
                    <h4 class="text-3xl font-bold text-gray-900">2</h4>
                    <p class="text-gray-500 text-sm mt-1">Q3 2025 reports</p>
                </div>
                <div class="text-purple-600 text-2xl">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Recent Reports</h4>
        <p class="text-gray-500 text-sm mb-6">Your latest quarterly reporting activities</p>
        <div class="space-y-4">
            @foreach($recentReports as $report)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="text-blue-600 text-xl">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900">{{ $report->title }}</h5>
                        <p class="text-sm text-gray-500">Updated {{ $report->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($report->status === 'submitted') bg-blue-100 text-blue-800
                        @elseif($report->status === 'under_review') bg-yellow-100 text-yellow-800
                        @elseif($report->status === 'approved') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="border border-gray-300 text-gray-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="border border-gray-300 text-gray-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Quick Actions</h4>
        <p class="text-gray-500 text-sm mb-6">Administrative tools and user management</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.user-management') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-users text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Manage Users</span>
            </a>
            <a href="#" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-globe text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Language Settings</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-chart-line text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Analytics Overview</span>
            </a>
        </div>
    </div>

    <!-- Form Management Center -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <i class="fa-solid fa-search text-gray-600 mr-2"></i>
            <h4 class="text-xl font-bold text-gray-900">Form Management Center</h4>
        </div>
        <p class="text-gray-500 text-sm mb-6">Search and view specific forms by admin, year, and quarter.</p>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All years</option>
                <option>2025</option>
                <option>2024</option>
            </select>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All quarters</option>
                <option>Q1</option>
                <option>Q2</option>
                <option>Q3</option>
                <option>Q4</option>
            </select>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All users</option>
                <option>Maria Rodriguez</option>
                <option>John Smith</option>
            </select>
        </div>
        
        <!-- Search Results Summary -->
        <div class="flex items-center mb-6">
            <i class="fa-solid fa-folder text-gray-600 mr-2"></i>
            <span class="text-gray-700">2 forms found</span>
        </div>
        
        <!-- Form Entries -->
        <div class="space-y-4">
            @foreach($languages->take(2) as $language)
            <div class="border border-gray-200 rounded-lg p-4 relative">
                <div class="absolute top-4 right-4">
                    <span class="bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-full">1 form</span>
                </div>
                <div class="mb-3">
                    <h5 class="font-semibold text-gray-900">Maria Rodriguez</h5>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fa-solid fa-globe mr-1"></i>
                        <span>{{ $language->name }}</span>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fa-solid fa-calendar mr-1"></i>
                            <span>2025 Q3</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-full">submitted</span>
                            <button class="border border-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded hover:bg-gray-50 transition-colors duration-200 flex items-center">
                                <i class="fa-solid fa-eye mr-1"></i>View
                            </button>
                            <button class="border border-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded hover:bg-gray-50 transition-colors duration-200 flex items-center">
                                <i class="fa-solid fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection 