@extends('user.layouts.app')

@section('title', 'My Languages - GlobalRize User Portal')

@section('page_title', 'My Languages')

@section('content')
<!-- Top Bar -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-1">My Assigned Languages</h1>
        <p class="text-gray-500">Manage and track your language reporting responsibilities</p>
    </div>
    <div class="flex items-center space-x-4">
        <span class="bg-white border border-gray-300 text-gray-700 rounded-full px-4 py-2 text-sm font-medium">Q3 2025</span>
    </div>
</div>

<!-- Language Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-language text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Total Languages</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $languageStats['total_languages'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-check-circle text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Active Languages</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $languageStats['active_languages'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-file-lines text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Q3 Reports</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $languageStats['reports_this_quarter'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
            <div>
                <h6 class="text-gray-600 text-sm font-medium">Completion Rate</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $languageStats['completion_rate'] }}%</h4>
            </div>
        </div>
    </div>
</div>

<!-- Language List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900">Language Details</h4>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Showing {{ $assignedLanguages->count() }} languages</span>
        </div>
    </div>
    
    @if($assignedLanguages->count() > 0)
        <div class="space-y-6">
            @foreach($assignedLanguages as $language)
            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fa fa-language text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h6 class="text-xl font-bold text-gray-900">{{ $language->name }}</h6>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ ucfirst($language->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Assigned Date:</span>
                                    <span class="font-medium ml-2">{{ $language->created_at->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Last Updated:</span>
                                    <span class="font-medium ml-2">{{ $language->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @if($language->description)
                            <div class="mt-3">
                                <span class="text-gray-500">Description:</span>
                                <p class="text-gray-700 mt-1">{{ $language->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('user.reports.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fa-solid fa-plus mr-2"></i>Create Report
                        </a>
                        <button class="text-gray-600 hover:text-gray-800 px-3 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50 transition-colors duration-200">
                            <i class="fa-solid fa-eye mr-1"></i>View Reports
                        </button>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">Quick Actions:</span>
                            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fa-solid fa-download mr-1"></i>Export Data
                            </button>
                            <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                                <i class="fa-solid fa-history mr-1"></i>History
                            </button>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <div>Reports: 0</div>
                            <div>Last Report: Never</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fa fa-language text-4xl mb-4"></i>
            <h5 class="text-lg font-medium text-gray-900 mb-2">No languages assigned yet</h5>
            <p class="text-gray-500 mb-4">You haven't been assigned any languages for reporting yet.</p>
            <div class="space-y-2 text-sm text-gray-600">
                <p>• Contact your administrator to get assigned languages</p>
                <p>• Languages will appear here once assigned</p>
                <p>• You'll be able to create quarterly reports for each language</p>
            </div>
        </div>
    @endif
</div>

<!-- Language Management Tips -->
@if($assignedLanguages->count() > 0)
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
    <h4 class="text-lg font-bold text-blue-900 mb-4">
        <i class="fa fa-lightbulb mr-2"></i>Language Management Tips
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
        <div>
            <h6 class="font-semibold mb-2">Quarterly Reporting</h6>
            <ul class="space-y-1">
                <li>• Create reports for each language every quarter</li>
                <li>• Track progress and achievements</li>
                <li>• Submit reports before deadlines</li>
            </ul>
        </div>
        <div>
            <h6 class="font-semibold mb-2">Best Practices</h6>
            <ul class="space-y-1">
                <li>• Keep language data up to date</li>
                <li>• Use consistent reporting formats</li>
                <li>• Document significant changes</li>
            </ul>
        </div>
    </div>
    </div>
@endif


@endsection
