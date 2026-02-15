@extends('admin.layouts.app')

@section('title', 'Edit Report - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Edit Report</h1>
                <p class="text-gray-500">Update report information and data.</p>
            </div>
            <a href="{{ route('admin.reports') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Edit Report Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form id="report-form" action="{{ route('admin.reports.update', $report->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Basic Information -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Report Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $report->title) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Spanish Q3 2025 Report">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="type" id="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="Quarterly Progress" {{ old('type', $report->type) == 'Quarterly Progress' ? 'selected' : '' }}>Quarterly Progress</option>
                        <option value="Quarterly Summary" {{ old('type', $report->type) == 'Quarterly Summary' ? 'selected' : '' }}>Quarterly Summary</option>
                        <option value="Quarterly Review" {{ old('type', $report->type) == 'Quarterly Review' ? 'selected' : '' }}>Quarterly Review</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                    <input type="text" name="quarter" id="quarter" value="{{ old('quarter', $report->quarter) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Q3 2025">
                    @error('quarter')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id" id="user_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $report->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="language_id" class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                    <select name="language_id" id="language_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" {{ old('language_id', $report->language_id) == $language->id ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                    @error('language_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Goal Progress Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Goal Progress</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="languages_previous_year" class="block text-sm font-medium text-gray-700 mb-2">Languages Previous Year</label>
                        <input type="number" name="languages_previous_year" id="languages_previous_year" value="{{ old('languages_previous_year', $report->languages_previous_year) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <!-- Comments Section -->
                        <div class="mt-2 comment-section" data-field="languages_previous_year" data-section="goal_progress">
                            @if(isset($commentsByField['languages_previous_year']))
                                @foreach($commentsByField['languages_previous_year'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'languages_previous_year')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="languages_goal_2025" class="block text-sm font-medium text-gray-700 mb-2">Languages Goal 2025</label>
                        <input type="number" name="languages_goal_2025" id="languages_goal_2025" value="{{ old('languages_goal_2025', $report->languages_goal_2025) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="languages_goal_2025" data-section="goal_progress">
                            @if(isset($commentsByField['languages_goal_2025']))
                                @foreach($commentsByField['languages_goal_2025'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'languages_goal_2025')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="languages_goal_q1" class="block text-sm font-medium text-gray-700 mb-2">Languages Goal Q1</label>
                        <input type="number" name="languages_goal_q1" id="languages_goal_q1" value="{{ old('languages_goal_q1', $report->languages_goal_q1) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="languages_goal_q1" data-section="goal_progress">
                            @if(isset($commentsByField['languages_goal_q1']))
                                @foreach($commentsByField['languages_goal_q1'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'languages_goal_q1')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="languages_achieved_q1" class="block text-sm font-medium text-gray-700 mb-2">Languages Achieved Q1</label>
                        <input type="number" name="languages_achieved_q1" id="languages_achieved_q1" value="{{ old('languages_achieved_q1', $report->languages_achieved_q1) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="languages_achieved_q1" data-section="goal_progress">
                            @if(isset($commentsByField['languages_achieved_q1']))
                                @foreach($commentsByField['languages_achieved_q1'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'languages_achieved_q1')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="volunteers_previous_year" class="block text-sm font-medium text-gray-700 mb-2">Volunteers Previous Year</label>
                        <input type="number" name="volunteers_previous_year" id="volunteers_previous_year" value="{{ old('volunteers_previous_year', $report->volunteers_previous_year) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="volunteers_previous_year" data-section="goal_progress">
                            @if(isset($commentsByField['volunteers_previous_year']))
                                @foreach($commentsByField['volunteers_previous_year'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'volunteers_previous_year')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="volunteers_goal_2025" class="block text-sm font-medium text-gray-700 mb-2">Volunteers Goal 2025</label>
                        <input type="number" name="volunteers_goal_2025" id="volunteers_goal_2025" value="{{ old('volunteers_goal_2025', $report->volunteers_goal_2025) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="volunteers_goal_2025" data-section="goal_progress">
                            @if(isset($commentsByField['volunteers_goal_2025']))
                                @foreach($commentsByField['volunteers_goal_2025'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'volunteers_goal_2025')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="volunteers_goal_q1" class="block text-sm font-medium text-gray-700 mb-2">Volunteers Goal Q1</label>
                        <input type="number" name="volunteers_goal_q1" id="volunteers_goal_q1" value="{{ old('volunteers_goal_q1', $report->volunteers_goal_q1) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="volunteers_goal_q1" data-section="goal_progress">
                            @if(isset($commentsByField['volunteers_goal_q1']))
                                @foreach($commentsByField['volunteers_goal_q1'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'volunteers_goal_q1')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="volunteers_achieved_q1" class="block text-sm font-medium text-gray-700 mb-2">Volunteers Achieved Q1</label>
                        <input type="number" name="volunteers_achieved_q1" id="volunteers_achieved_q1" value="{{ old('volunteers_achieved_q1', $report->volunteers_achieved_q1) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="volunteers_achieved_q1" data-section="goal_progress">
                            @if(isset($commentsByField['volunteers_achieved_q1']))
                                @foreach($commentsByField['volunteers_achieved_q1'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('goal_progress', 'volunteers_achieved_q1')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Reach Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Social Media Reach</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="facebook_reach" class="block text-sm font-medium text-gray-700 mb-2">Facebook Reach</label>
                        <input type="number" name="facebook_reach" id="facebook_reach" value="{{ old('facebook_reach', $report->facebook_reach) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="facebook_reach" data-section="organic_reach">
                            @if(isset($commentsByField['facebook_reach']))
                                @foreach($commentsByField['facebook_reach'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organic_reach', 'facebook_reach')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="instagram_reach" class="block text-sm font-medium text-gray-700 mb-2">Instagram Reach</label>
                        <input type="number" name="instagram_reach" id="instagram_reach" value="{{ old('instagram_reach', $report->instagram_reach) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="instagram_reach" data-section="organic_reach">
                            @if(isset($commentsByField['instagram_reach']))
                                @foreach($commentsByField['instagram_reach'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organic_reach', 'instagram_reach')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="youtube_reach" class="block text-sm font-medium text-gray-700 mb-2">YouTube Reach</label>
                        <input type="number" name="youtube_reach" id="youtube_reach" value="{{ old('youtube_reach', $report->youtube_reach) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="youtube_reach" data-section="organic_reach">
                            @if(isset($commentsByField['youtube_reach']))
                                @foreach($commentsByField['youtube_reach'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organic_reach', 'youtube_reach')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="website_reach" class="block text-sm font-medium text-gray-700 mb-2">Website Reach</label>
                        <input type="number" name="website_reach" id="website_reach" value="{{ old('website_reach', $report->website_reach) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="website_reach" data-section="organic_reach">
                            @if(isset($commentsByField['website_reach']))
                                @foreach($commentsByField['website_reach'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organic_reach', 'website_reach')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="income_euros" class="block text-sm font-medium text-gray-700 mb-2">Income (Euros)</label>
                        <input type="number" step="0.01" name="income_euros" id="income_euros" value="{{ old('income_euros', $report->income_euros) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="income_euros" data-section="organization">
                            @if(isset($commentsByField['income_euros']))
                                @foreach($commentsByField['income_euros'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organization', 'income_euros')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="expenditure_euros" class="block text-sm font-medium text-gray-700 mb-2">Expenditure (Euros)</label>
                        <input type="number" step="0.01" name="expenditure_euros" id="expenditure_euros" value="{{ old('expenditure_euros', $report->expenditure_euros) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="expenditure_euros" data-section="organization">
                            @if(isset($commentsByField['expenditure_euros']))
                                @foreach($commentsByField['expenditure_euros'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('organization', 'expenditure_euros')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bible Course Students Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bible Course Students</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="evangelistic_students" class="block text-sm font-medium text-gray-700 mb-2">Evangelistic Students</label>
                        <input type="number" name="evangelistic_students" id="evangelistic_students" value="{{ old('evangelistic_students', $report->evangelistic_students) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="evangelistic_students" data-section="bible_course">
                            @if(isset($commentsByField['evangelistic_students']))
                                @foreach($commentsByField['evangelistic_students'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('bible_course', 'evangelistic_students')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="discipleship_students" class="block text-sm font-medium text-gray-700 mb-2">Discipleship Students</label>
                        <input type="number" name="discipleship_students" id="discipleship_students" value="{{ old('discipleship_students', $report->discipleship_students) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="discipleship_students" data-section="bible_course">
                            @if(isset($commentsByField['discipleship_students']))
                                @foreach($commentsByField['discipleship_students'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('bible_course', 'discipleship_students')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="leadership_students" class="block text-sm font-medium text-gray-700 mb-2">Leadership Students</label>
                        <input type="number" name="leadership_students" id="leadership_students" value="{{ old('leadership_students', $report->leadership_students) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="leadership_students" data-section="bible_course">
                            @if(isset($commentsByField['leadership_students']))
                                @foreach($commentsByField['leadership_students'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('bible_course', 'leadership_students')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Conversations Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Chat Conversations</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="evangelistic_conversations" class="block text-sm font-medium text-gray-700 mb-2">Evangelistic Conversations</label>
                        <input type="number" name="evangelistic_conversations" id="evangelistic_conversations" value="{{ old('evangelistic_conversations', $report->evangelistic_conversations) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="evangelistic_conversations" data-section="chat_conversations">
                            @if(isset($commentsByField['evangelistic_conversations']))
                                @foreach($commentsByField['evangelistic_conversations'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('chat_conversations', 'evangelistic_conversations')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="pastoral_connections" class="block text-sm font-medium text-gray-700 mb-2">Pastoral Connections</label>
                        <input type="number" name="pastoral_connections" id="pastoral_connections" value="{{ old('pastoral_connections', $report->pastoral_connections) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="pastoral_connections" data-section="chat_conversations">
                            @if(isset($commentsByField['pastoral_connections']))
                                @foreach($commentsByField['pastoral_connections'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('chat_conversations', 'pastoral_connections')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PR & Staffing Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Public Relations & Staffing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pr_total_organic_reach" class="block text-sm font-medium text-gray-700 mb-2">PR Total Organic Reach</label>
                        <input type="number" name="pr_total_organic_reach" id="pr_total_organic_reach" value="{{ old('pr_total_organic_reach', $report->pr_total_organic_reach) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="pr_total_organic_reach" data-section="pr_staffing">
                            @if(isset($commentsByField['pr_total_organic_reach']))
                                @foreach($commentsByField['pr_total_organic_reach'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('pr_staffing', 'pr_total_organic_reach')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="personal_fte" class="block text-sm font-medium text-gray-700 mb-2">Personal FTE</label>
                        <input type="number" step="0.01" name="personal_fte" id="personal_fte" value="{{ old('personal_fte', $report->personal_fte) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 comment-section" data-field="personal_fte" data-section="pr_staffing">
                            @if(isset($commentsByField['personal_fte']))
                                @foreach($commentsByField['personal_fte'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('pr_staffing', 'personal_fte')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="new_activity" class="block text-sm font-medium text-gray-700 mb-2">New Activity</label>
                        <textarea name="new_activity" id="new_activity" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('new_activity', $report->new_activity) }}</textarea>
                        <div class="mt-2 comment-section" data-field="new_activity" data-section="additional_info">
                            @if(isset($commentsByField['new_activity']))
                                @foreach($commentsByField['new_activity'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('additional_info', 'new_activity')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="organizational_highlight" class="block text-sm font-medium text-gray-700 mb-2">Organizational Highlight</label>
                        <textarea name="organizational_highlight" id="organizational_highlight" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_highlight', $report->organizational_highlight) }}</textarea>
                        <div class="mt-2 comment-section" data-field="organizational_highlight" data-section="additional_info">
                            @if(isset($commentsByField['organizational_highlight']))
                                @foreach($commentsByField['organizational_highlight'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('additional_info', 'organizational_highlight')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="organizational_concern" class="block text-sm font-medium text-gray-700 mb-2">Organizational Concern</label>
                        <textarea name="organizational_concern" id="organizational_concern" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_concern', $report->organizational_concern) }}</textarea>
                        <div class="mt-2 comment-section" data-field="organizational_concern" data-section="additional_info">
                            @if(isset($commentsByField['organizational_concern']))
                                @foreach($commentsByField['organizational_concern'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('additional_info', 'organizational_concern')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="organizational_issues" class="block text-sm font-medium text-gray-700 mb-2">Organizational Issues</label>
                        <textarea name="organizational_issues" id="organizational_issues" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('organizational_issues', $report->organizational_issues) }}</textarea>
                        <div class="mt-2 comment-section" data-field="organizational_issues" data-section="additional_info">
                            @if(isset($commentsByField['organizational_issues']))
                                @foreach($commentsByField['organizational_issues'] as $comment)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mb-2 text-xs">
                                        <div class="font-semibold text-yellow-800">{{ $comment->admin->name }}</div>
                                        <div class="text-yellow-700">{{ $comment->comment }}</div>
                                        <div class="text-yellow-600 text-xs mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" onclick="openCommentModal('additional_info', 'organizational_issues')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                <i class="fa-solid fa-comment mr-1"></i>Add Comment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-between">
                <form action="{{ route('admin.reports.submit-to-super-admin', $report->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Submit to Super Admin
                    </button>
                </form>
                <button type="submit" form="report-form"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-save mr-2"></i>Update Report
                </button>
            </div>
        </form>
    </div>

    <!-- Comment Modal -->
    <div id="comment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Add Comment</h3>
                    <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form id="comment-form">
                    @csrf
                    <input type="hidden" id="comment-section" name="section">
                    <input type="hidden" id="comment-field" name="field">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                        <textarea id="comment-text" name="comment" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Enter your comment..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCommentModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-comment mr-2"></i>Add Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCommentModal(section, field) {
            document.getElementById('comment-section').value = section;
            document.getElementById('comment-field').value = field;
            document.getElementById('comment-modal').classList.remove('hidden');
        }

        function closeCommentModal() {
            document.getElementById('comment-modal').classList.add('hidden');
            document.getElementById('comment-text').value = '';
        }

        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const reportId = {{ $report->id }};
            
            fetch(`/admin/reports/${reportId}/comment`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the comment.');
            });
        });
    </script>
@endsection
