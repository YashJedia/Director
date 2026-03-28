@extends('admin.layouts.app')

@section('title', 'Create Report - GlobalRize Reporting')

@section('content')
<style>
    /* Hide all quarterly fields by default */
    .quarter-field {
        display: none;
    }
    
    /* Show Q1 fields by default */
    .quarter-field[data-quarter="Q1"] {
        display: block;
    }
</style>

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

    <form action="{{ route('admin.reports.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="quarterly">
        
        <!-- Basic Information -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- User Selection (Admin-only field) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Select User *</label>
                    <select id="user_id" name="user_id" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('user_id', $prefill['user_id'] ?? '') == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Quarter *</label>
                    <select id="quarter" name="quarter" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="overflow-y: auto; max-height: 300px;">
                        <option value="">Select Quarter</option>
                        <option value="Q1 2026" {{ old('quarter') == 'Q1 2026' ? 'selected' : '' }}>Q1 2026</option>
                        <option value="Q2 2026" {{ old('quarter') == 'Q2 2026' ? 'selected' : '' }}>Q2 2026</option>
                        <option value="Q3 2026" {{ old('quarter') == 'Q3 2026' ? 'selected' : '' }}>Q3 2026</option>
                        <option value="Q4 2026" {{ old('quarter') == 'Q4 2026' ? 'selected' : '' }}>Q4 2026</option>
                    </select>
                    @error('quarter')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language *</label>
                    <select id="language" name="language" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->name }}" {{ old('language') == $language->name ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                    @error('language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Report Title *</label>
                    <input type="text" id="title" name="title" required placeholder="Title will auto-generate based on quarter and language" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Section I: Goal Progress -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Goal Progress</h4>
                <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-lg" id="quarter-label">Select Quarter to Update</span>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Languages Previous Year</label>
                    <input type="number" name="languages_previous_year" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('languages_previous_year') }}">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Languages Goal 2025</label>
                    <input type="number" name="languages_goal_2025" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('languages_goal_2025') }}">
                </div>
                
                <!-- Languages Goal/Achieved - Quarterly -->
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Languages Goal {{ $qLabel }}</label>
                    <input type="number" name="languages_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('languages_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Languages Achieved {{ $qLabel }}</label>
                    <input type="number" name="languages_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('languages_achieved_q' . $qNum) }}">
                </div>
                @endforeach
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Previous Year</label>
                    <input type="number" name="volunteers_previous_year" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_previous_year') }}">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Goal 2025</label>
                    <input type="number" name="volunteers_goal_2025" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_goal_2025') }}">
                </div>
                
                <!-- Volunteers Goal/Achieved - Quarterly -->
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Goal {{ $qLabel }}</label>
                    <input type="number" name="volunteers_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers Achieved {{ $qLabel }}</label>
                    <input type="number" name="volunteers_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Chatters (Goal {{ $qLabel }})</label>
                    <input type="number" name="volunteers_chatters_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_chatters_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Chatters (Achieved {{ $qLabel }})</label>
                    <input type="number" name="volunteers_chatters_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_chatters_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Mentors (Goal {{ $qLabel }})</label>
                    <input type="number" name="volunteers_mentors_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_mentors_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Mentors (Achieved {{ $qLabel }})</label>
                    <input type="number" name="volunteers_mentors_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_mentors_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Content Creators (Goal {{ $qLabel }})</label>
                    <input type="number" name="volunteers_creators_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_creators_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Volunteers: Content Creators (Achieved {{ $qLabel }})</label>
                    <input type="number" name="volunteers_creators_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('volunteers_creators_achieved_q' . $qNum) }}">
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Section II: Organic Reach -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Organic Reach (Per Language & Platform)</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Facebook Reach Goal {{ $qLabel }}</label>
                    <input type="number" name="facebook_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('facebook_reach_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Facebook Reach Achieved {{ $qLabel }}</label>
                    <input type="number" name="facebook_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('facebook_reach_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Instagram Reach Goal {{ $qLabel }}</label>
                    <input type="number" name="instagram_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('instagram_reach_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Instagram Reach Achieved {{ $qLabel }}</label>
                    <input type="number" name="instagram_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('instagram_reach_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">YouTube Reach Goal {{ $qLabel }}</label>
                    <input type="number" name="youtube_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('youtube_reach_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">YouTube Reach Achieved {{ $qLabel }}</label>
                    <input type="number" name="youtube_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('youtube_reach_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Website Reach Goal {{ $qLabel }}</label>
                    <input type="number" name="website_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('website_reach_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Website Reach Achieved {{ $qLabel }}</label>
                    <input type="number" name="website_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('website_reach_achieved_q' . $qNum) }}">
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Section III: Bible Course Students -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Bible Course Students</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Students Goal {{ $qLabel }}</label>
                    <input type="number" name="evangelistic_students_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('evangelistic_students_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Students Achieved {{ $qLabel }}</label>
                    <input type="number" name="evangelistic_students_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('evangelistic_students_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Discipleship Students Goal {{ $qLabel }}</label>
                    <input type="number" name="discipleship_students_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('discipleship_students_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Discipleship Students Achieved {{ $qLabel }}</label>
                    <input type="number" name="discipleship_students_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('discipleship_students_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Leadership Students Goal {{ $qLabel }}</label>
                    <input type="number" name="leadership_students_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('leadership_students_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Leadership Students Achieved {{ $qLabel }}</label>
                    <input type="number" name="leadership_students_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('leadership_students_achieved_q' . $qNum) }}">
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Section IV: Chat Conversations -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Chat Conversations</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Conversations Goal {{ $qLabel }}</label>
                    <input type="number" name="evangelistic_conversations_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('evangelistic_conversations_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Evangelistic Conversations Achieved {{ $qLabel }}</label>
                    <input type="number" name="evangelistic_conversations_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('evangelistic_conversations_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pastoral Connections Goal {{ $qLabel }}</label>
                    <input type="number" name="pastoral_connections_goal_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('pastoral_connections_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pastoral Connections Achieved {{ $qLabel }}</label>
                    <input type="number" name="pastoral_connections_achieved_q{{ $qNum }}" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('pastoral_connections_achieved_q' . $qNum) }}">
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Section V: Organization -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Organization</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Income (Euros) Goal {{ $qLabel }}</label>
                    <input type="number" name="income_euros_goal_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('income_euros_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Income (Euros) Achieved {{ $qLabel }}</label>
                    <input type="number" name="income_euros_achieved_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('income_euros_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Expenditure (Euros) Goal {{ $qLabel }}</label>
                    <input type="number" name="expenditure_euros_goal_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('expenditure_euros_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Expenditure (Euros) Achieved {{ $qLabel }}</label>
                    <input type="number" name="expenditure_euros_achieved_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('expenditure_euros_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">PR Total Organic Reach Goal {{ $qLabel }}</label>
                    <input type="number" name="pr_total_organic_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('pr_total_organic_reach_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">PR Total Organic Reach Achieved {{ $qLabel }}</label>
                    <input type="number" name="pr_total_organic_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('pr_total_organic_reach_achieved_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Personal FTE Goal {{ $qLabel }}</label>
                    <input type="number" name="personal_fte_goal_q{{ $qNum }}" min="0" max="999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('personal_fte_goal_q' . $qNum) }}">
                </div>
                <div class="quarter-field" data-quarter="{{ $qLabel }}">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Personal FTE Achieved {{ $qLabel }}</label>
                    <input type="number" name="personal_fte_achieved_q{{ $qNum }}" min="0" max="999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('personal_fte_achieved_q' . $qNum) }}">
                </div>
                @endforeach
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Income from Fundraising (Euros)</label>
                    <input type="number" name="income_from_fundraising_euros" min="0" max="999999999999.99" step="0.01" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('income_from_fundraising_euros') }}">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Number of Supporters</label>
                    <input type="number" name="number_of_supporters" min="0" max="999999" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('number_of_supporters') }}">
                </div>
            </div>
        </div>
        
        <!-- Section VI: Descriptive Text Fields -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Additional Information</h4>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">New Activity (max 1000 chars)</label>
                    <textarea name="new_activity" rows="3" placeholder="Describe any new activities or initiatives" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('new_activity') }}</textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Highlight (max 500 chars)</label>
                    <textarea name="organizational_highlight" rows="2" placeholder="Key Language Team highlights" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_highlight') }}</textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Concern (max 500 chars)</label>
                    <textarea name="organizational_concern" rows="2" placeholder="Any Language Team concerns" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_concern') }}</textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language Team Issues (max 500 chars)</label>
                    <textarea name="organizational_issues" rows="2" placeholder="Any Language Team issues" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_issues') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end items-center space-x-3">
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 font-medium text-gray-700 bg-gray-100 rounded-lg transition-colors duration-200 hover:bg-gray-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 font-medium text-white bg-blue-600 rounded-lg transition-colors duration-200 hover:bg-blue-700">
                <i class="mr-2 fa-solid fa-plus"></i>Submit Report
            </button>
        </div>
    </form>
</div>

<script>
    // Show/hide quarterly fields based on selected quarter
    function showQuarterFields(quarterValue) {
        if (!quarterValue) return;

        // Determine quarter label from value (e.g., "Q1 2025" → "Q1")
        const quarterNum = quarterValue.match(/Q\d/)?.[0];
        if (!quarterNum) return;

        // Hide all quarter fields first
        document.querySelectorAll('.quarter-field').forEach(el => {
            el.style.display = 'none';
        });

        // Show only the selected quarter fields
        document.querySelectorAll(`.quarter-field[data-quarter="${quarterNum}"]`).forEach(el => {
            el.style.display = 'block';
        });

        // Update header label
        const headerLabel = document.getElementById('quarter-label');
        if (headerLabel) {
            headerLabel.textContent = `Goals for ${quarterValue}`;
        }
    }

    // Auto-generate report title and show/hide fields
    const quarterSelect = document.getElementById('quarter');
    const languageSelect = document.getElementById('language');
    const titleInput = document.getElementById('title');

    function updateTitle() {
        const quarter = quarterSelect.value;
        const language = languageSelect.value;

        if (quarter && language) {
            titleInput.value = `${quarter} ${language}`;
            showQuarterFields(quarter);
        } else {
            titleInput.value = '';
        }
    }

    quarterSelect.addEventListener('change', updateTitle);
    languageSelect.addEventListener('change', updateTitle);

    // Update on page load if values are already selected
    window.addEventListener('load', updateTitle);
</script>

@endsection
