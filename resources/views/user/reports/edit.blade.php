@extends('user.layouts.app')

@section('title', 'Edit Report - GlobalRize User Portal')

@section('page_title', 'Edit Report')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    @if($errors->has('duplicate'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600 mr-2"></i>
                <span class="text-red-800 text-sm">{{ $errors->first('duplicate') }}</span>
            </div>
        </div>
    @endif

    <!-- Input Guidelines -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start">
            <i class="fa-solid fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
            <div class="text-blue-800 text-sm">
                <p class="font-medium mb-2">Input Guidelines:</p>
                <ul class="space-y-1 text-xs">
                    <li>• <strong>Personal FTE:</strong> Maximum 999,999.99 (Full-Time Equivalent)</li>
                    <li>• <strong>Financial amounts:</strong> Maximum 999,999,999,999.99 euros</li>
                    <li>• <strong>Reach metrics:</strong> Maximum 999,999,999</li>
                    <li>• <strong>Count fields:</strong> Maximum 999,999</li>
                    <li>• <strong>Text fields:</strong> New Activity (max 1000 chars), others (max 500 chars)</li>
                </ul>
            </div>
        </div>
    </div>

    <form action="{{ route('user.reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quarter *</label>
                    <select name="quarter" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Quarter</option>
                        <option value="Q1 2025" {{ $report->quarter == 'Q1 2025' ? 'selected' : '' }}>Q1 2025</option>
                        <option value="Q2 2025" {{ $report->quarter == 'Q2 2025' ? 'selected' : '' }}>Q2 2025</option>
                        <option value="Q3 2025" {{ $report->quarter == 'Q3 2025' ? 'selected' : '' }}>Q3 2025</option>
                        <option value="Q4 2025" {{ $report->quarter == 'Q4 2025' ? 'selected' : '' }}>Q4 2025</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language *</label>
                    <select name="language" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Language</option>
                        @foreach($assignedLanguages as $language)
                            <option value="{{ $language->name }}" {{ $report->language->name == $language->name ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Title *</label>
                    <input type="text" name="title" value="{{ $report->title }}" required placeholder="Enter report title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section I: Goal Progress -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Goal Progress</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers Previous Year</label>
                    <input type="number" name="volunteers_previous_year" value="{{ $report->volunteers_previous_year }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers Goal 2025</label>
                    <input type="number" name="volunteers_goal_2025" value="{{ $report->volunteers_goal_2025 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers Goal Q1</label>
                    <input type="number" name="volunteers_goal_q1" value="{{ $report->volunteers_goal_q1 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers Achieved Q1</label>
                    <input type="number" name="volunteers_achieved_q1" value="{{ $report->volunteers_achieved_q1 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers: Chatters</label>
                    <input type="number" name="volunteers_chatters" value="{{ $report->volunteers_chatters ?? 0 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers: Mentors</label>
                    <input type="number" name="volunteers_mentors" value="{{ $report->volunteers_mentors ?? 0 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers: Content Creators</label>
                    <input type="number" name="volunteers_content_creators" value="{{ $report->volunteers_content_creators ?? 0 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volunteers: Others</label>
                    <input type="number" name="volunteers_others" value="{{ $report->volunteers_others ?? 0 }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section II: Organic Reach -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Organic Reach (Per Language & Platform)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Reach</label>
                    <input type="number" name="facebook_reach" value="{{ $report->facebook_reach }}" min="0" max="999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instagram Reach</label>
                    <input type="number" name="instagram_reach" value="{{ $report->instagram_reach }}" min="0" max="999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Reach</label>
                    <input type="number" name="youtube_reach" value="{{ $report->youtube_reach }}" min="0" max="999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website Reach</label>
                    <input type="number" name="website_reach" value="{{ $report->website_reach }}" min="0" max="999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section III: Bible Course Students -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Bible Course Students</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Evangelistic Students</label>
                    <input type="number" name="evangelistic_students" value="{{ $report->evangelistic_students }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discipleship Students</label>
                    <input type="number" name="discipleship_students" value="{{ $report->discipleship_students }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leadership Students</label>
                    <input type="number" name="leadership_students" value="{{ $report->leadership_students }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section IV: Chat Conversations -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Chat Conversations</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Evangelistic Conversations</label>
                    <input type="number" name="evangelistic_conversations" value="{{ $report->evangelistic_conversations }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pastoral Connections</label>
                    <input type="number" name="pastoral_connections" value="{{ $report->pastoral_connections }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section V: Organization -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Organization</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Income (Euros)</label>
                    <input type="number" name="income_euros" value="{{ $report->income_euros }}" min="0" max="999999999999.99" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expenditure (Euros)</label>
                    <input type="number" name="expenditure_euros" value="{{ $report->expenditure_euros }}" min="0" max="999999999999.99" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section VI: Public Relations & Staffing -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Public Relations & Staffing</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PR Total Organic Reach</label>
                    <input type="number" name="pr_total_organic_reach" value="{{ $report->pr_total_organic_reach }}" min="0" max="999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal FTE</label>
                    <input type="number" name="personal_fte" value="{{ $report->personal_fte }}" min="0" max="999999.99" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section VII: Descriptive Text Fields -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Activity (max 100 words)</label>
                    <textarea name="new_activity" rows="3" placeholder="Describe any new activities or initiatives" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ $report->new_activity }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language Team Highlight (max 50 words)</label>
                    <textarea name="organizational_highlight" rows="2" placeholder="Key Language Team highlights" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ $report->organizational_highlight }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language Team Concern (max 50 words)</label>
                    <textarea name="organizational_concern" rows="2" placeholder="Any Language Team concerns" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ $report->organizational_concern }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language Team Issues (max 50 words)</label>
                    <textarea name="organizational_issues" rows="2" placeholder="Any Language Team issues" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ $report->organizational_issues }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('user.reports') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-save mr-2"></i>Update Report
            </button>
        </div>
    </form>
</div>
@endsection
