@extends('admin.layouts.app')

@section('title', 'Create Report - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-1">Create New Report</h1>
                <p class="text-gray-500">Add a new quarterly report to the system.</p>
            </div>
            <a href="{{ route('admin.reports') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Create Report Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.reports.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Basic Information -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Report Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Spanish Q3 2025 Report">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @if(isset($prefill['quarter']) && isset($prefill['language_id']))
                        <p class="text-xs text-gray-500 mt-1">Tip: Title will be auto-generated based on selected language and quarter</p>
                    @endif
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="type" id="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="Quarterly Progress" {{ old('type') == 'Quarterly Progress' ? 'selected' : '' }}>Quarterly Progress</option>
                        <option value="Quarterly Summary" {{ old('type') == 'Quarterly Summary' ? 'selected' : '' }}>Quarterly Summary</option>
                        <option value="Quarterly Review" {{ old('type') == 'Quarterly Review' ? 'selected' : '' }}>Quarterly Review</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                    <input type="text" name="quarter" id="quarter" value="{{ old('quarter', $prefill['quarter'] ?? '') }}" required
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
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                            <option value="{{ $language->id }}" {{ (old('language_id', $prefill['language_id'] ?? '') == $language->id) ? 'selected' : '' }}>{{ $language->name }}</option>
                        @endforeach
                    </select>
                    @error('language_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Goal Progress Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Goal Progress</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="languages_previous_year" class="block text-sm font-medium text-gray-700 mb-2">Languages Previous Year</label>
                        <input type="number" name="languages_previous_year" id="languages_previous_year" value="{{ old('languages_previous_year', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="languages_goal_2025" class="block text-sm font-medium text-gray-700 mb-2">Languages Goal 2025</label>
                        <input type="number" name="languages_goal_2025" id="languages_goal_2025" value="{{ old('languages_goal_2025', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="languages_goal_q1" class="block text-sm font-medium text-gray-700 mb-2">Languages Goal Q1</label>
                        <input type="number" name="languages_goal_q1" id="languages_goal_q1" value="{{ old('languages_goal_q1', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="languages_achieved_q1" class="block text-sm font-medium text-gray-700 mb-2">Languages Achieved Q1</label>
                        <input type="number" name="languages_achieved_q1" id="languages_achieved_q1" value="{{ old('languages_achieved_q1', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Social Media Reach Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Social Media Reach</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="facebook_reach" class="block text-sm font-medium text-gray-700 mb-2">Facebook Reach</label>
                        <input type="number" name="facebook_reach" id="facebook_reach" value="{{ old('facebook_reach', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="instagram_reach" class="block text-sm font-medium text-gray-700 mb-2">Instagram Reach</label>
                        <input type="number" name="instagram_reach" id="instagram_reach" value="{{ old('instagram_reach', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="youtube_reach" class="block text-sm font-medium text-gray-700 mb-2">YouTube Reach</label>
                        <input type="number" name="youtube_reach" id="youtube_reach" value="{{ old('youtube_reach', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="website_reach" class="block text-sm font-medium text-gray-700 mb-2">Website Reach</label>
                        <input type="number" name="website_reach" id="website_reach" value="{{ old('website_reach', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="income_euros" class="block text-sm font-medium text-gray-700 mb-2">Income (Euros)</label>
                        <input type="number" step="0.01" name="income_euros" id="income_euros" value="{{ old('income_euros', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="expenditure_euros" class="block text-sm font-medium text-gray-700 mb-2">Expenditure (Euros)</label>
                        <input type="number" step="0.01" name="expenditure_euros" id="expenditure_euros" value="{{ old('expenditure_euros', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Create Report
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Auto-generate title when language and quarter are selected
        document.addEventListener('DOMContentLoaded', function() {
            const languageSelect = document.getElementById('language_id');
            const quarterInput = document.getElementById('quarter');
            const titleInput = document.getElementById('title');
            
            function updateTitle() {
                const languageId = languageSelect.value;
                const quarter = quarterInput.value;
                
                if (languageId && quarter && !titleInput.value) {
                    const languageName = languageSelect.options[languageSelect.selectedIndex].text;
                    titleInput.value = languageName + ' ' + quarter + ' Report';
                }
            }
            
            languageSelect.addEventListener('change', updateTitle);
            quarterInput.addEventListener('input', updateTitle);
            
            // Initialize if pre-filled
            if (languageSelect.value && quarterInput.value) {
                updateTitle();
            }
        });
    </script>
@endsection
