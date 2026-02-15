@extends('user.layouts.app')

@section('title', 'User Dashboard - GlobalRize Reporting')

@section('page_title', 'Language Team Leader')

@section('content')
<!-- Top Bar -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ Auth::user()->name }}</h1>
                        <p class="text-gray-500">User Dashboard - Track your quarterly reporting activities</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.reports.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>New Quarterly Report
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-gray-600 text-sm font-medium mb-2">All Reports</h6>
                <h4 class="text-3xl font-bold text-gray-900">{{ $reportStats['submitted_reports'] }}</h4>
                <p class="text-gray-500 text-sm mt-1">Reports submitted so far</p>
            </div>
            <div class="text-gray-400 text-2xl">
                <i class="fa-solid fa-file-lines"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-gray-600 text-sm font-medium mb-2">Draft Reports</h6>
                <h4 class="text-3xl font-bold text-gray-900">{{ $reportStats['draft_reports'] }}</h4>
                <p class="text-gray-500 text-sm mt-1">Half filled reports</p>
            </div>
            <div class="text-gray-400 text-2xl">
                <i class="fa-solid fa-edit"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <h6 class="text-gray-600 text-sm font-medium mb-2">Report for revision</h6>
                <h4 class="text-3xl font-bold text-gray-900">{{ $reportStats['revision_reports'] }}</h4>
                <p class="text-gray-500 text-sm mt-1">Reports not approved by admin but sent back for revision</p>
            </div>
            <div class="text-gray-400 text-2xl">
                <i class="fa-solid fa-rotate-left"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reports -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900">Recent Reports</h4>
        <a href="{{ route('user.reports') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">View All</a>
    </div>
    
    <div class="space-y-4">
        @forelse(array_slice($userReports, 0, 3) as $report)
        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div>
                    <h6 class="font-medium text-gray-900">{{ $report['title'] }}</h6>
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
            <div class="text-right">
                <div class="text-sm text-gray-500">{{ $report['updated_at']->diffForHumans() }}</div>
                @if($report['score'])
                <div class="text-lg font-bold text-green-600">{{ $report['score'] }}/10</div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-file-lines text-4xl mb-4"></i>
            <p>No reports found</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Assigned Languages -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-1">
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

<!-- Profile Summary -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h4 class="text-xl font-bold text-gray-900 mb-1">
        <i class="fa fa-user mr-2 text-gray-600"></i>Profile Summary
    </h4>
    <p class="text-gray-500 mb-6">Your account information and preferences</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Member since:</span>
                    <span class="font-medium">{{ Auth::user()->created_at->format('M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last login:</span>
                    <span class="font-medium">
                        @if(Auth::user()->last_login_at)
                            @if(Auth::user()->last_login_at->isToday())
                                Today
                            @elseif(Auth::user()->last_login_at->isYesterday())
                                Yesterday
                            @else
                                {{ Auth::user()->last_login_at->format('M d, Y') }}
                            @endif
                        @else
                            Never
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Account status:</span>
                    <span class="bg-{{ Auth::user()->status === 'active' ? 'green' : (Auth::user()->status === 'suspended' ? 'red' : 'gray') }}-100 text-{{ Auth::user()->status === 'active' ? 'green' : (Auth::user()->status === 'suspended' ? 'red' : 'gray') }}-800 text-xs font-medium px-2 py-1 rounded-full">
                        {{ ucfirst(Auth::user()->status ?? 'active') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
            <h6 class="font-semibold text-gray-900">Recent Activity</h6>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                <div class="flex items-center space-x-3 text-sm">
                    <div class="w-2 h-2 bg-{{ $activity['color'] }} rounded-full"></div>
                    <span class="text-gray-600">{{ $activity['message'] }}</span>
                    <span class="text-gray-400">{{ $activity['time'] }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500">No recent activity</div>
                @endforelse
            </div>
        </div>
    </div>
</div>


@endsection 