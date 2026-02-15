@extends('user.layouts.app')

@section('title', 'My Reports - GlobalRize User Portal')

@section('page_title', 'My Reports')

@section('content')
@if(session('success'))
    <div class="p-4 mb-6 bg-green-50 rounded-lg border border-green-200">
        <div class="flex items-center">
            <i class="mr-2 text-green-600 fa-solid fa-check-circle"></i>
            <span class="text-sm text-green-800">{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Top Bar -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="mb-1 text-3xl font-bold text-gray-900">My Reports</h1>
        <p class="text-gray-500">Manage and track your quarterly reporting activities</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.reports.create') }}" class="px-6 py-2 font-medium text-white bg-green-600 rounded-lg transition-colors duration-200 hover:bg-green-700">
            <i class="mr-2 fa-solid fa-plus"></i>Create New Quarterly Report
        </a>
    </div>
</div>

<!-- Report Filters -->
<div class="p-6 mb-8 bg-white rounded-lg border border-gray-200 shadow-sm">
    <h4 class="mb-4 text-lg font-bold text-gray-900">Filter Reports</h4>
    <form method="GET" action="{{ route('user.reports') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="due" {{ request('status') === 'due' ? 'selected' : '' }}>Due</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="revision_needed" {{ request('status') === 'revision_needed' ? 'selected' : '' }}>Revision Needed</option>
            </select>
        </div>
        <div class="flex-1">
            <label class="block mb-2 text-sm font-medium text-gray-700">Quarter</label>
            <select class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option>All Quarters</option>
                <option>Q1 2025</option>
                <option>Q2 2025</option>
                <option>Q3 2025</option>
                <option>Q4 2025</option>
            </select>
        </div>
        <div class="flex-1">
            <label class="block mb-2 text-sm font-medium text-gray-700">Language</label>
            <select class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option>All Languages</option>
                @foreach($assignedLanguages as $language)
                    <option>{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="px-6 py-2 font-medium text-white whitespace-nowrap bg-gray-600 rounded-lg transition-colors duration-200 hover:bg-gray-700">
                <i class="mr-2 fa-solid fa-filter"></i>Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Report Statistics -->
<div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center">
            <div class="flex justify-center items-center mr-4 w-12 h-12 text-blue-600 bg-blue-100 rounded-lg">
                <i class="text-xl fa-solid fa-file-lines"></i>
            </div>
            <div>
                <h6 class="text-sm font-medium text-gray-600">Total Reports</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $reportStats['total'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center">
            <div class="flex justify-center items-center mr-4 w-12 h-12 text-green-600 bg-green-100 rounded-lg">
                <i class="text-xl fa-solid fa-check-circle"></i>
            </div>
            <div>
                <h6 class="text-sm font-medium text-gray-600">Submitted</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $reportStats['submitted'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center">
            <div class="flex justify-center items-center mr-4 w-12 h-12 text-yellow-600 bg-yellow-100 rounded-lg">
                <i class="text-xl fa-solid fa-edit"></i>
            </div>
            <div>
                <h6 class="text-sm font-medium text-gray-600">Draft</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $reportStats['draft'] }}</h4>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center">
            <div class="flex justify-center items-center mr-4 w-12 h-12 text-purple-600 bg-purple-100 rounded-lg">
                <i class="text-xl fa-solid fa-clock"></i>
            </div>
            <div>
                <h6 class="text-sm font-medium text-gray-600">Under Review</h6>
                <h4 class="text-2xl font-bold text-gray-900">{{ $reportStats['under_review'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Languages -->
<div class="p-6 mb-8 bg-white rounded-lg border border-gray-200 shadow-sm">
    <h4 class="mb-4 text-xl font-bold text-gray-900">
        <i class="mr-2 text-gray-600 fa fa-language"></i>My Assigned Languages
    </h4>
    <p class="mb-6 text-gray-500">Languages you can create reports for</p>
    
    @if($assignedLanguages->count() > 0)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach($assignedLanguages as $language)
            <div class="p-4 rounded-lg border border-gray-200 transition-all duration-200 hover:border-green-300 hover:shadow-md">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex justify-center items-center w-10 h-10 text-blue-600 bg-blue-100 rounded-full">
                        <i class="text-lg fa fa-language"></i>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        {{ ucfirst($language->status) }}
                    </span>
                </div>
                <h6 class="mb-1 font-semibold text-gray-900">{{ $language->name }}</h6>
                <a href="{{ route('user.reports.create') }}" class="inline-block px-3 py-2 w-full text-sm font-medium text-center text-white bg-blue-600 rounded transition-colors duration-200 hover:bg-blue-700">
                    <i class="mr-1 fa-solid fa-plus"></i>Create Report
                </a>
            </div>
            @endforeach
        </div>
    @else
        <div class="py-8 text-center text-gray-500">
            <i class="mb-4 text-4xl fa fa-language"></i>
            <h5 class="mb-2 text-lg font-medium text-gray-900">No languages assigned yet</h5>
            <p class="text-gray-500">Contact your administrator to get assigned languages for reporting.</p>
        </div>
    @endif
</div>

<!-- Reports List -->
<div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900">All Reports</h4>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Showing {{ count($reports) }} reports</span>
        </div>
    </div>
    
    <div class="space-y-4">
        @forelse($reports as $report)
        <div class="p-6 rounded-lg border border-gray-200 transition-colors duration-200 hover:bg-gray-50">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="flex justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg">
                        <i class="text-xl fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 font-semibold text-gray-900">{{ $report['title'] }}</h6>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>{{ $report['quarter'] }}</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                @if($report['status'] === 'submitted') bg-green-100 text-green-800
                                @elseif($report['status'] === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($report['status']) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    @if($report['score'])
                    <div class="text-right">
                        <div class="text-lg font-bold text-green-600">{{ $report['score'] }}/10</div>
                        <div class="text-xs text-gray-500">Score</div>
                    </div>
                    @endif
                    
                    <div class="text-right">
                        <div class="text-sm text-gray-500">{{ $report['updated_at']->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $report['updated_at']->format('H:i') }}</div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($report['status'] === 'submitted')
                            <button class="px-3 py-1 text-sm text-gray-600 rounded border border-gray-300 transition-colors duration-200 hover:text-gray-800 hover:bg-gray-50">
                                <i class="mr-1 fa-solid fa-eye"></i>View
                            </button>
                            <a href="{{ route('user.reports.edit', $report['id']) }}" class="px-3 py-1 text-sm text-gray-600 rounded border border-gray-300 transition-colors duration-200 hover:text-gray-800 hover:bg-green-50">
                                <i class="mr-1 fa-solid fa-edit"></i>Edit
                            </a>
                        @elseif($report['status'] === 'draft')
                            <a href="{{ route('user.reports.edit', $report['id']) }}" class="px-3 py-1 text-sm text-gray-600 rounded border border-gray-300 transition-colors duration-200 hover:text-gray-800 hover:bg-green-50">
                                <i class="mr-1 fa-solid fa-edit"></i>Continue
                            </a>
                            <form method="POST" action="{{ route('user.reports.submit', $report['id']) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm text-green-600 rounded border border-green-300 transition-colors duration-200 hover:text-green-700 hover:bg-green-50">
                                    <i class="mr-1 fa-solid fa-paper-plane"></i>Submit
                                </button>
                            </form>
                        @else
                            <button class="px-3 py-1 text-sm text-gray-600 rounded border border-gray-300 transition-colors duration-200 hover:text-gray-800 hover:bg-gray-50">
                                <i class="mr-1 fa-solid fa-eye"></i>View
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-gray-500">
            <i class="mb-4 text-4xl fa-solid fa-file-lines"></i>
            <h5 class="mb-2 text-lg font-medium text-gray-900">No reports found</h5>
            <p class="text-gray-500">Get started by creating your first report using one of the templates above.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection 