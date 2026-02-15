@extends('user.layouts.app')

@section('title', 'Create Report - GlobalRize User Portal')

@section('page_title', 'Create New Quarterly Report')

@section('content')
<div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
    @if($errors->any())
        <div class="p-4 mb-6 bg-red-50 rounded-lg border border-red-200">
            <div class="flex items-start">
                <i class="mt-0.5 mr-2 text-red-600 fa-solid fa-exclamation-triangle"></i>
                <div>
                    <span class="text-sm text-red-800 font-bold">Please fix the following errors:</span>
                    <ul class="mt-2 text-sm text-red-800 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Input Guidelines -->
    <div class="p-4 mb-6 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex items-start">
            <i class="mt-0.5 mr-2 text-blue-600 fa-solid fa-info-circle"></i>
            <div class="text-sm text-blue-800">
                <p class="mb-2 font-medium">Input Guidelines:</p>
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

    <form action="{{ route('user.reports.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="quarterly">
        
        <!-- Basic Information -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Quarter *</label>
                    <select name="quarter" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Quarter</option>
                        <option value="Q1 2025">Q1 2025</option>
                        <option value="Q2 2025">Q2 2025</option>
                        <option value="Q3 2025">Q3 2025</option>
                        <option value="Q4 2025">Q4 2025</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language *</label>
                    <select name="language" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Language</option>
                        @foreach($assignedLanguages as $language)
                            <option value="{{ $language->name }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Report Title *</label>
                    <input type="text" name="title" required placeholder="Enter report title" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section I: Goal Progress -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Goal Progress</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Previous Year</label>
                    <input type="number" name="volunteers_previous_year" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Goal 2025</label>
                    <input type="number" name="volunteers_goal_2025" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Goal Q1</label>
                    <input type="number" name="volunteers_goal_q1" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Achieved Q1</label>
                    <input type="number" name="volunteers_achieved_q1" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Chatters</label>
                    <input type="number" name="volunteers_chatters" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Mentors</label>
                    <input type="number" name="volunteers_mentors" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Content Creators</label>
                    <input type="number" name="volunteers_content_creators" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Others</label>
                    <input type="number" name="volunteers_others" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section II: Organic Reach -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Organic Reach (Per Language & Platform)</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Facebook Reach</label>
                    <input type="number" name="facebook_reach" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Instagram Reach</label>
                    <input type="number" name="instagram_reach" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">YouTube Reach</label>
                    <input type="number" name="youtube_reach" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Website Reach</label>
                    <input type="number" name="website_reach" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section III: Bible Course Students -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Bible Course Students</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Students</label>
                    <input type="number" name="evangelistic_students" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Discipleship Students</label>
                    <input type="number" name="discipleship_students" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Leadership Students</label>
                    <input type="number" name="leadership_students" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section IV: Chat Conversations -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Chat Conversations</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Conversations</label>
                    <input type="number" name="evangelistic_conversations" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pastoral Connections</label>
                    <input type="number" name="pastoral_connections" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section V: Organization -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Organization</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Income (Euros)</label>
                    <input type="number" name="income_euros" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Expenditure (Euros)</label>
                    <input type="number" name="expenditure_euros" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section VI: Public Relations & Staffing -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Public Relations & Staffing</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">PR Total Organic Reach</label>
                    <input type="number" name="pr_total_organic_reach" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Personal FTE</label>
                    <input type="number" name="personal_fte" min="0" max="999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>
        
        <!-- Section VII: Descriptive Text Fields -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Additional Information</h4>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">New Activity (max 100 words)</label>
                    <textarea name="new_activity" rows="3" placeholder="Describe any new activities or initiatives" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Highlight (max 50 words)</label>
                    <textarea name="organizational_highlight" rows="2" placeholder="Key Language Team highlights" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Concern (max 50 words)</label>
                    <textarea name="organizational_concern" rows="2" placeholder="Any Language Team concerns" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Issues (max 50 words)</label>
                    <textarea name="organizational_issues" rows="2" placeholder="Any Language Team issues" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end items-center space-x-3">
            <a href="{{ route('user.reports') }}" class="px-4 py-2 font-medium text-gray-700 bg-gray-100 rounded-lg transition-colors duration-200 hover:bg-gray-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 font-medium text-white bg-green-600 rounded-lg transition-colors duration-200 hover:bg-green-700">
                <i class="mr-2 fa-solid fa-plus"></i>Submit Report
            </button>
        </div>
    </form>
</div>
@endsection
