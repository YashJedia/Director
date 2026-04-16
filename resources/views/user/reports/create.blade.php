@extends('user.layouts.app')

@section('title', 'Create Report - GlobalRize User Portal')

@section('page_title', 'Create New Quarterly Report')

@section('content')
<style>
    /* Show quarter fields by default - Q1 will be visible initially */
    .quarter-field {
        display: table-row;
    }
    
    /* Hide all other quarters by default */
    .quarter-field[data-quarter="Q2"],
    .quarter-field[data-quarter="Q3"],
    .quarter-field[data-quarter="Q4"] {
        display: none;
    }
    
    /* Hide language fields in user form */
    .hide-in-user-form {
        display: none !important;
    }

    /* Table input styling */
    .table-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .table-input:focus {
        outline: none;
        ring: 2px;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .table-input:read-only {
        background-color: #f3f4f6;
        cursor: default;
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

    <form action="{{ route('user.reports.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="quarterly">
        
        <!-- Basic Information -->
        <div class="pb-6 mb-6 border-b border-gray-200">
            <h4 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h4>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Quarter *</label>
                    <select id="quarter" name="quarter" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500" style="overflow-y: auto; max-height: 300px;">
                        <option value="">Select Quarter</option>
                        <option value="Q1 2026">Q1 2026</option>
                        <option value="Q2 2026">Q2 2026</option>
                        <option value="Q3 2026">Q3 2026</option>
                        <option value="Q4 2026">Q4 2026</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Language *</label>
                    <select id="language" name="language" required class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Language</option>
                        @foreach($assignedLanguages as $language)
                            <option value="{{ $language->name }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Report Title *</label>
                    <input type="text" id="title" name="title" required placeholder="Title will auto-generate based on quarter and language" class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500" readonly>
                </div>
            </div>
        </div>
        
        <!-- Quarterly Report Table -->
        <div class="overflow-x-auto mb-6 border border-green-300 rounded-lg">
            <table class="min-w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-green-100 border-b border-gray-300">
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 border-r border-gray-300 w-40">Metric</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 border-r border-gray-300 w-24">End <span id="end-year">2025</span></th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 border-r border-gray-300 w-24">Goal <span id="goal-year">2026</span></th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 border-r border-gray-300 w-32">Goal <span id="goal-quarter">Q1</span></th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 border-r border-gray-300 w-32">Achieved <span id="achieved-quarter">Q1</span></th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 w-24">%</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Goal Progress Rows -->
                    @foreach(['1' => 'Q1', '2' => 'Q2', '3' => 'Q3', '4' => 'Q4'] as $qNum => $qLabel)
                        <!-- SECTION 1: Ministry Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-300 quarter-field" data-quarter="{{ $qLabel }}">
                            <td colspan="6" class="px-4 py-2 text-lg font-bold text-blue-800">
                                📊 Ministry
                            </td>
                        </tr>
                        <!-- Volunteers -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Number of Volunteers</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_end_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_goal_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('volunteers_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('volunteers_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="volunteers_goal_year" data-achieved-field="volunteers_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Volunteers: Mentors -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300">&nbsp;&nbsp; Bible Mentors</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_mentors_end_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_mentors_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_mentors_goal_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_mentors_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_mentors_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('volunteers_mentors_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_mentors_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('volunteers_mentors_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="volunteers_mentors_goal_q{{ $qNum }}" data-achieved-field="volunteers_mentors_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Volunteers: Chatters -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300">&nbsp;&nbsp; Chat Volunteers</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_chatters_end_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_chatters_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_chatters_goal_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_chatters_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_chatters_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('volunteers_chatters_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_chatters_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('volunteers_chatters_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="volunteers_chatters_goal_q{{ $qNum }}" data-achieved-field="volunteers_chatters_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Volunteers: Content Creators -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 text-gray-700 border-r border-gray-300">&nbsp;&nbsp; Content Creators</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_creators_end_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_creators_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_creators_goal_year" min="0" max="999999" class="table-input" value="{{ old('volunteers_creators_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_creators_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('volunteers_creators_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="volunteers_creators_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('volunteers_creators_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="volunteers_creators_goal_q{{ $qNum }}" data-achieved-field="volunteers_creators_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>

                        <!-- SECTION 2: Organic Reach (Social Media) Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-300 quarter-field" data-quarter="{{ $qLabel }}">
                            <td colspan="6" class="px-4 py-2 text-lg font-bold text-blue-800">
                                📊 Organic Reach
                            </td>
                        </tr>
                        <!-- Organic Reach: Facebook -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Facebook Reach</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="facebook_reach_end_year" min="0" max="999999999" class="table-input" value="{{ old('facebook_reach_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="facebook_reach_goal_year" min="0" max="999999999" class="table-input" value="{{ old('facebook_reach_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="facebook_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="table-input goal-input" value="{{ old('facebook_reach_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="facebook_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="table-input achieved-input" value="{{ old('facebook_reach_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="facebook_reach_goal_q{{ $qNum }}" data-achieved-field="facebook_reach_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organic Reach: Instagram -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Instagram Reach</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="instagram_reach_end_year" min="0" max="999999999" class="table-input" value="{{ old('instagram_reach_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="instagram_reach_goal_year" min="0" max="999999999" class="table-input" value="{{ old('instagram_reach_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="instagram_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="table-input goal-input" value="{{ old('instagram_reach_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="instagram_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="table-input achieved-input" value="{{ old('instagram_reach_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="instagram_reach_goal_q{{ $qNum }}" data-achieved-field="instagram_reach_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organic Reach: YouTube -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">YouTube Reach</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="youtube_reach_end_year" min="0" max="999999999" class="table-input" value="{{ old('youtube_reach_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="youtube_reach_goal_year" min="0" max="999999999" class="table-input" value="{{ old('youtube_reach_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="youtube_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="table-input goal-input" value="{{ old('youtube_reach_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="youtube_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="table-input achieved-input" value="{{ old('youtube_reach_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="youtube_reach_goal_q{{ $qNum }}" data-achieved-field="youtube_reach_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organic Reach: Website -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Website Reach</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="website_reach_end_year" min="0" max="999999999" class="table-input" value="{{ old('website_reach_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="website_reach_goal_year" min="0" max="999999999" class="table-input" value="{{ old('website_reach_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="website_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="table-input goal-input" value="{{ old('website_reach_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="website_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="table-input achieved-input" value="{{ old('website_reach_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="website_reach_goal_q{{ $qNum }}" data-achieved-field="website_reach_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>

                        <!-- SECTION 3: Bible Course Students Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-300 quarter-field" data-quarter="{{ $qLabel }}">
                            <td colspan="6" class="px-4 py-2 text-lg font-bold text-blue-800">
                                📊 Bible Course Students
                            </td>
                        </tr>
                        <!-- Bible Course: Evangelistic -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Evangelistic Students</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_students_end_year" min="0" max="999999" class="table-input" value="{{ old('evangelistic_students_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_students_goal_year" min="0" max="999999" class="table-input" value="{{ old('evangelistic_students_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_students_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('evangelistic_students_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_students_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('evangelistic_students_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="evangelistic_students_goal_q{{ $qNum }}" data-achieved-field="evangelistic_students_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Bible Course: Discipleship -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Discipleship Students</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="discipleship_students_end_year" min="0" max="999999" class="table-input" value="{{ old('discipleship_students_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="discipleship_students_goal_year" min="0" max="999999" class="table-input" value="{{ old('discipleship_students_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="discipleship_students_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('discipleship_students_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="discipleship_students_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('discipleship_students_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="discipleship_students_goal_q{{ $qNum }}" data-achieved-field="discipleship_students_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Bible Course: Leadership -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Leadership Students</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="leadership_students_end_year" min="0" max="999999" class="table-input" value="{{ old('leadership_students_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="leadership_students_goal_year" min="0" max="999999" class="table-input" value="{{ old('leadership_students_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="leadership_students_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('leadership_students_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="leadership_students_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('leadership_students_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="leadership_students_goal_q{{ $qNum }}" data-achieved-field="leadership_students_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>

                        <!-- SECTION 4: Chat Conversations Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-300 quarter-field" data-quarter="{{ $qLabel }}">
                            <td colspan="6" class="px-4 py-2 text-lg font-bold text-blue-800">
                                📊 Chat Conversations
                            </td>
                        </tr>
                        <!-- Chat: Evangelistic -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Evangelistic Conversations</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_conversations_end_year" min="0" max="999999" class="table-input" value="{{ old('evangelistic_conversations_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_conversations_goal_year" min="0" max="999999" class="table-input" value="{{ old('evangelistic_conversations_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_conversations_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('evangelistic_conversations_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="evangelistic_conversations_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('evangelistic_conversations_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="evangelistic_conversations_goal_q{{ $qNum }}" data-achieved-field="evangelistic_conversations_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Chat: Pastoral Connections -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Pastoral Connections</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pastoral_connections_end_year" min="0" max="999999" class="table-input" value="{{ old('pastoral_connections_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pastoral_connections_goal_year" min="0" max="999999" class="table-input" value="{{ old('pastoral_connections_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pastoral_connections_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('pastoral_connections_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pastoral_connections_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('pastoral_connections_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="pastoral_connections_goal_q{{ $qNum }}" data-achieved-field="pastoral_connections_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Chat: Number of Connections -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Number of Connections</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="connections_end_year" min="0" max="999999" class="table-input" value="{{ old('connections_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="connections_goal_year" min="0" max="999999" class="table-input" value="{{ old('connections_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="connections_goal_q{{ $qNum }}" min="0" max="999999" class="table-input goal-input" value="{{ old('connections_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="connections_achieved_q{{ $qNum }}" min="0" max="999999" class="table-input achieved-input" value="{{ old('connections_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="connections_goal_q{{ $qNum }}" data-achieved-field="connections_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>

                        <!-- SECTION 5: Financial & Operations Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-300 quarter-field" data-quarter="{{ $qLabel }}">
                            <td colspan="6" class="px-4 py-2 text-lg font-bold text-blue-800">
                                📊 Financial & Operations
                            </td>
                        </tr>
                        <!-- Organization: Income -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Income (€)</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_euros_end_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('income_euros_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_euros_goal_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('income_euros_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_euros_goal_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="table-input goal-input" value="{{ old('income_euros_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_euros_achieved_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="table-input achieved-input" value="{{ old('income_euros_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="income_euros_goal_q{{ $qNum }}" data-achieved-field="income_euros_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organization: Expenditure -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Expenditure (€)</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="expenditure_euros_end_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('expenditure_euros_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="expenditure_euros_goal_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('expenditure_euros_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="expenditure_euros_goal_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="table-input goal-input" value="{{ old('expenditure_euros_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="expenditure_euros_achieved_q{{ $qNum }}" min="0" max="999999999999.99" step="0.01" class="table-input achieved-input" value="{{ old('expenditure_euros_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="expenditure_euros_goal_q{{ $qNum }}" data-achieved-field="expenditure_euros_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organization: PR Total Organic Reach -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">PR Total Organic Reach</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pr_total_organic_reach_end_year" min="0" max="999999999" class="table-input" value="{{ old('pr_total_organic_reach_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pr_total_organic_reach_goal_year" min="0" max="999999999" class="table-input" value="{{ old('pr_total_organic_reach_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pr_total_organic_reach_goal_q{{ $qNum }}" min="0" max="999999999" class="table-input goal-input" value="{{ old('pr_total_organic_reach_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="pr_total_organic_reach_achieved_q{{ $qNum }}" min="0" max="999999999" class="table-input achieved-input" value="{{ old('pr_total_organic_reach_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="pr_total_organic_reach_goal_q{{ $qNum }}" data-achieved-field="pr_total_organic_reach_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                        <!-- Organization: Personal FTE -->
                        <tr class="border-b border-gray-200 hover:bg-green-50 quarter-field" data-quarter="{{ $qLabel }}">
                            <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Personnel FTE</td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="personal_fte_end_year" min="0" max="999999.99" step="0.01" class="table-input" value="{{ old('personal_fte_end_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="personal_fte_goal_year" min="0" max="999999.99" step="0.01" class="table-input" value="{{ old('personal_fte_goal_year') }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="personal_fte_goal_q{{ $qNum }}" min="0" max="999999.99" step="0.01" class="table-input goal-input" value="{{ old('personal_fte_goal_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="personal_fte_achieved_q{{ $qNum }}" min="0" max="999999.99" step="0.01" class="table-input achieved-input" value="{{ old('personal_fte_achieved_q' . $qNum) }}"></td>
                            <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="personal_fte_goal_q{{ $qNum }}" data-achieved-field="personal_fte_achieved_q{{ $qNum }}" value="N/A"></td>
                        </tr>
                    @endforeach
                    
                    <!-- Non-Quarterly Metrics -->
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Organization - Income from Fundraising</td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_fundraising_end_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('income_fundraising_end_year') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_fundraising_goal_year" min="0" max="999999999999.99" step="0.01" class="table-input" value="{{ old('income_fundraising_goal_year') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_fundraising_goal" min="0" max="999999999999.99" step="0.01" class="table-input goal-input" value="{{ old('income_fundraising_goal') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="income_fundraising_achieved" min="0" max="999999999999.99" step="0.01" class="table-input achieved-input" value="{{ old('income_fundraising_achieved') }}"></td>
                        <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="income_fundraising_goal" data-achieved-field="income_fundraising_achieved" value="N/A"></td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-green-50">
                        <td class="px-4 py-3 font-semibold text-gray-800 bg-gray-50 border-r border-gray-300">Organization - Number of Supporters</td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="number_of_supporters_end_year" min="0" max="999999" class="table-input" value="{{ old('number_of_supporters_end_year') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="number_of_supporters_goal_year" min="0" max="999999" class="table-input" value="{{ old('number_of_supporters_goal_year') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="number_of_supporters_goal" min="0" max="999999" class="table-input goal-input" value="{{ old('number_of_supporters_goal') }}"></td>
                        <td class="px-4 py-3 border-r border-gray-300"><input type="number" name="number_of_supporters_achieved" min="0" max="999999" class="table-input achieved-input" value="{{ old('number_of_supporters_achieved') }}"></td>
                        <td class="px-4 py-3 text-center"><input type="text" readonly class="table-input percentage-display" data-goal-field="number_of_supporters_goal" data-achieved-field="number_of_supporters_achieved" value="N/A"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Section VI: Text Fields -->
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
        
        <!-- Form Actions -->
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

<script>
    // Auto-generate report title and update table based on quarter
    const quarterSelect = document.getElementById('quarter');
    const languageSelect = document.getElementById('language');
    const titleInput = document.getElementById('title');

    function updateTitle() {
        const quarter = quarterSelect.value;
        const language = languageSelect.value;

        if (quarter && language) {
            titleInput.value = `${quarter} ${language}`;
            
            // Extract year and quarter number from selection (e.g., "Q2 2026")
            const quarterMatch = quarter.match(/(Q\d)\s(\d{4})/);
            if (quarterMatch) {
                const quarterLabel = quarterMatch[1];
                const currentYear = parseInt(quarterMatch[2]);
                const endYear = currentYear - 1;
                
                // Update column headers
                document.getElementById('end-year').textContent = endYear;
                document.getElementById('goal-year').textContent = currentYear;
                document.getElementById('goal-quarter').textContent = quarterLabel;
                document.getElementById('achieved-quarter').textContent = quarterLabel;
                
                // Update table row visibility
                document.querySelectorAll('.quarter-field').forEach(row => {
                    row.style.display = 'none';
                });
                document.querySelectorAll(`.quarter-field[data-quarter="${quarterLabel}"]`).forEach(row => {
                    row.style.display = 'table-row';
                });
                
                // Update percentage calculations
                updatePercentageFieldsInline();
            }
        } else {
            titleInput.value = '';
        }
    }

    quarterSelect.addEventListener('change', updateTitle);
    languageSelect.addEventListener('change', updateTitle);

    // Initialize on page load
    window.addEventListener('load', () => {
        // Set first quarter as default if available
        if (!quarterSelect.value) {
            const options = quarterSelect.querySelectorAll('option');
            if (options.length > 1) {
                quarterSelect.value = options[1].value;
            }
        }
        updateTitle();
    });

    // Inline percentage calculation
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('goal-input') || e.target.classList.contains('achieved-input')) {
            updatePercentageFieldsInline();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('goal-input') || e.target.classList.contains('achieved-input')) {
            updatePercentageFieldsInline();
        }
    });

    function updatePercentageFieldsInline() {
        const percentageFields = document.querySelectorAll('.percentage-display');
        
        percentageFields.forEach(field => {
            const goalFieldName = field.dataset.goalField;
            const achievedFieldName = field.dataset.achievedField;
            
            const goalInput = document.querySelector(`input[name="${goalFieldName}"]`);
            const achievedInput = document.querySelector(`input[name="${achievedFieldName}"]`);
            
            if (goalInput && achievedInput) {
                const goal = parseFloat(goalInput.value) || 0;
                const achieved = parseFloat(achievedInput.value) || 0;
                
                let displayText = 'N/A';
                let bgColor = 'bg-gray-50';
                let textColor = 'text-gray-600';
                
                if (goal > 0) {
                    const percentage = (achieved / goal) * 100;
                    displayText = percentage.toFixed(1) + '%';
                    
                    if (percentage < 50) {
                        bgColor = 'bg-red-100';
                        textColor = 'text-red-700';
                    } else if (percentage < 100) {
                        bgColor = 'bg-yellow-100';
                        textColor = 'text-yellow-700';
                    } else {
                        bgColor = 'bg-green-100';
                        textColor = 'text-green-700';
                    }
                }
                
                field.value = displayText;
                field.className = 'table-input percentage-display ' + bgColor + ' ' + textColor;
            }
        });
    }
</script>

@endsection

