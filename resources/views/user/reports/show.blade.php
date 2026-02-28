@extends('user.layouts.app')

@section('title', 'View Report - GlobalRize User Portal')

@section('page_title', 'View Report')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Read-only view of report -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start">
            <i class="fa-solid fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
            <div class="text-blue-800 text-sm">
                <p class="font-medium">Report View (Read-only)</p>
                <p class="text-xs mt-1">Click Edit button to modify this report</p>
            </div>
        </div>
    </div>
    
    <!-- Basic Information -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Quarter</label>
                <p class="text-gray-900 font-medium">{{ $report->quarter }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Language</label>
                <p class="text-gray-900 font-medium">{{ $report->language->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Report Title</label>
                <p class="text-gray-900 font-medium">{{ $report->title }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full 
                    @if($report->submitted_to_super_admin) bg-green-100 text-green-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $report->submitted_to_super_admin ? 'Submitted' : 'Draft' }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Section I: Goal Progress -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Goal Progress - {{ $report->quarter }}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Languages Previous Year</label>
                <p class="text-gray-900 font-medium">{{ $report->languages_previous_year ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Languages Goal 2025</label>
                <p class="text-gray-900 font-medium">{{ $report->languages_goal_2025 ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Languages Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">
                    @php
                        $quarterNum = str_contains($report->quarter, 'Q1') ? 'q1' : (str_contains($report->quarter, 'Q2') ? 'q2' : (str_contains($report->quarter, 'Q3') ? 'q3' : 'q4'));
                    @endphp
                    {{ $report->{'languages_goal_' . $quarterNum} ?? '—' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Languages Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'languages_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers Previous Year</label>
                <p class="text-gray-900 font-medium">{{ $report->volunteers_previous_year ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers Goal 2025</label>
                <p class="text-gray-900 font-medium">{{ $report->volunteers_goal_2025 ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Chatters Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_chatters_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Chatters Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_chatters_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Mentors Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_mentors_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Mentors Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_mentors_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Content Creators Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_creators_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Volunteers: Content Creators Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'volunteers_creators_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Section II: Organic Reach -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Organic Reach (Per Language & Platform)</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Facebook Reach Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'facebook_reach_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Facebook Reach Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'facebook_reach_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Instagram Reach Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'instagram_reach_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Instagram Reach Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'instagram_reach_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">YouTube Reach Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'youtube_reach_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">YouTube Reach Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'youtube_reach_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Website Reach Goal ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'website_reach_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Website Reach Achieved ({{ $report->quarter }})</label>
                <p class="text-gray-900 font-medium">{{ $report->{'website_reach_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Section III: Bible Course Students -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Bible Course Students ({{ $report->quarter }})</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Evangelistic Students Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'evangelistic_students_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Evangelistic Students Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'evangelistic_students_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Discipleship Students Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'discipleship_students_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Discipleship Students Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'discipleship_students_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Leadership Students Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'leadership_students_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Leadership Students Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'leadership_students_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Section IV: Chat Conversations -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Chat Conversations ({{ $report->quarter }})</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Evangelistic Conversations Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'evangelistic_conversations_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Evangelistic Conversations Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'evangelistic_conversations_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Pastoral Connections Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'pastoral_connections_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Pastoral Connections Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'pastoral_connections_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Section V: Organization -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Organization ({{ $report->quarter }})</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Income (€) Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'income_euros_goal_' . $quarterNum} ? '€' . number_format($report->{'income_euros_goal_' . $quarterNum}, 2) : '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Income (€) Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'income_euros_achieved_' . $quarterNum} ? '€' . number_format($report->{'income_euros_achieved_' . $quarterNum}, 2) : '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Expenditure (€) Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'expenditure_euros_goal_' . $quarterNum} ? '€' . number_format($report->{'expenditure_euros_goal_' . $quarterNum}, 2) : '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Expenditure (€) Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'expenditure_euros_achieved_' . $quarterNum} ? '€' . number_format($report->{'expenditure_euros_achieved_' . $quarterNum}, 2) : '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">PR Total Organic Reach Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'pr_total_organic_reach_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">PR Total Organic Reach Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'pr_total_organic_reach_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Personnel FTE Goal</label>
                <p class="text-gray-900 font-medium">{{ $report->{'personal_fte_goal_' . $quarterNum} ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Personnel FTE Achieved</label>
                <p class="text-gray-900 font-medium">{{ $report->{'personal_fte_achieved_' . $quarterNum} ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Section VI: Descriptive Text Fields -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h4>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">New Activity</label>
                <p class="text-gray-900">{{ $report->new_activity ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Language Team Highlight</label>
                <p class="text-gray-900">{{ $report->organizational_highlight ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Language Team Concern</label>
                <p class="text-gray-900">{{ $report->organizational_concern ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Language Team Issues</label>
                <p class="text-gray-900">{{ $report->organizational_issues ?? '—' }}</p>
            </div>
        </div>
    </div>
    
    <div class="flex items-center justify-end space-x-3">
        <a href="{{ route('user.reports') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Reports
        </a>
        @if($report->status !== 'submitted')
            <a href="{{ route('user.reports.edit', $report->id) }}" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>Edit Report
            </a>
        @endif
    </div>
</div>
@endsection
