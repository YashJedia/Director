@extends('admin.layouts.app')

@section('title', 'GlobalRize Reporting - Admin Reports')

@section('content')
    <!-- First Section: Back Button and Title -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
        <h2 class="text-3xl font-bold text-gray-900 text-center flex-grow" id="report-heading">Quarter Report – Q3 2025</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.create') }}" id="create-report-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>Create Report
            </a>
        </div>
    </div>

    <!-- Report Details Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Report Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Year Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                <select id="year-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2024" {{ $year == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2023" {{ $year == '2023' ? 'selected' : '' }}>2023</option>
                    <option value="2022" {{ $year == '2022' ? 'selected' : '' }}>2022</option>
                </select>
            </div>
            <!-- Quarter Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                <select id="quarter-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Q1" {{ $quarter == 'Q1' ? 'selected' : '' }}>Q1</option>
                    <option value="Q2" {{ $quarter == 'Q2' ? 'selected' : '' }}>Q2</option>
                    <option value="Q3" {{ $quarter == 'Q3' ? 'selected' : '' }}>Q3</option>
                    <option value="Q4" {{ $quarter == 'Q4' ? 'selected' : '' }}>Q4</option>
                </select>
            </div>
            <!-- Language Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                <select id="language-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Languages</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ $languageId == $language->id ? 'selected' : '' }}>{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Report Statistics Tiles -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Reports Received -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Total Reports Received</p>
                    <p class="text-2xl font-bold text-blue-900" id="total-reports">0</p>
                    <p class="text-xs text-blue-500" id="total-languages">0 languages</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Reports Due -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Reports Due</p>
                    <p class="text-2xl font-bold text-orange-900" id="reports-due">0</p>
                    <p class="text-xs text-orange-500">Pending submission</p>
                </div>
                <div class="bg-orange-500 p-3 rounded-full">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Reports Reviewed -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Reports Reviewed</p>
                    <p class="text-2xl font-bold text-purple-900" id="reports-reviewed">0</p>
                    <p class="text-xs text-purple-500">Under review</p>
                </div>
                <div class="bg-purple-500 p-3 rounded-full">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Reports Approved -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Reports Approved</p>
                    <p class="text-2xl font-bold text-green-900" id="reports-approved">0</p>
                    <p class="text-xs text-green-500">Completed</p>
                </div>
                <div class="bg-green-500 p-3 rounded-full">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Year Progress Tiles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">Submitted</p>
                    <p class="text-2xl font-bold text-indigo-900" id="year-submitted">0</p>
                    <p class="text-xs text-indigo-500">This year</p>
                </div>
                <div class="bg-indigo-500 p-3 rounded-full">
                    <i class="fas fa-upload text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-50 to-teal-100 border border-teal-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-teal-600">Reviewed</p>
                    <p class="text-2xl font-bold text-teal-900" id="year-reviewed">0</p>
                    <p class="text-xs text-teal-500">This year</p>
                </div>
                <div class="bg-teal-500 p-3 rounded-full">
                    <i class="fas fa-search text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-600">Approved</p>
                    <p class="text-2xl font-bold text-emerald-900" id="year-approved">0</p>
                    <p class="text-xs text-emerald-500">This year</p>
                </div>
                <div class="bg-emerald-500 p-3 rounded-full">
                    <i class="fas fa-thumbs-up text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section I: Goal Progress -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-lg border border-blue-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-bullseye text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section I: Goal Progress</h3>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto p-6">
            <table class="w-full border border-gray-200 bg-white rounded-lg shadow-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Metric</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Year End 2024<br><span class="text-xs font-normal">(Previous Year)</span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Year End Goal 2025<br><span class="text-xs font-normal">(Full Year Target)</span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Goal Q1<br><span class="text-xs font-normal">(Quarterly Target)</span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Achieved Q1</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">% Achieved Q1<br><span class="text-xs font-normal">(Auto-calculated)</span></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Achieved 2025 so far<br><span class="text-xs font-normal">(Cumulative)</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">Number of Languages</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="languages_previous_year">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="languages_goal_2025">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="languages_goal_q">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="languages_achieved_q">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium border border-gray-200" id="languages_percent">0%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="languages_cumulative">0</td>
                    </tr>
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">Number of Volunteers</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="volunteers_previous_year">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="volunteers_goal_2025">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="volunteers_goal_q">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="volunteers_achieved_q">0</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium border border-gray-200" id="volunteers_percent">0%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200" id="volunteers_cumulative">0</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section II: Organic Reach (Per Language & Platform) -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-lg border border-green-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-chart-line text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section II: Organic Reach (Per Language & Platform)</h3>
            </div>
        </div>
        
        <!-- Important Note -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 mx-6 mt-4 rounded-r-lg">
            <div class="flex items-start">
                <i class="fa-solid fa-info-circle text-green-600 mr-3 mt-1"></i>
                <p class="text-green-800 text-sm">
                    <strong>Important:</strong> Enter organic reach only (exclude paid advertisements). These metrics should reflect natural engagement for each assigned language during the reporting period.
                </p>
            </div>
        </div>
        
        <!-- Language Sections -->
        @foreach($languages as $language)
        <div class="p-6 language-section" data-language-id="{{ $language->id }}" style="{{ $languageId && $languageId != $language->id ? 'display: none;' : '' }} {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-3 rounded-full mr-3 shadow-lg">
                    <i class="fa-solid fa-globe text-white"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900">{{ $language->name }}</h4>
                    <p class="text-sm text-gray-500">Enter student counts for each course type</p>
                </div>
            </div>
            
            <!-- Platform Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Facebook -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-facebook text-blue-600 text-xl mr-2"></i>
                            <span class="font-medium text-gray-900">Facebook</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organic reach count</label>
                    <input type="number" name="facebook_{{ $language->id }}" id="facebook_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- Instagram -->
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 border border-pink-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-instagram text-pink-600 text-xl mr-2"></i>
                            <span class="font-medium text-gray-900">Instagram</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organic reach count</label>
                    <input type="number" name="instagram_{{ $language->id }}" id="instagram_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- YouTube -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fab fa-youtube text-red-600 text-xl mr-2"></i>
                            <span class="font-medium text-gray-900">YouTube</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organic reach count</label>
                    <input type="number" name="youtube_{{ $language->id }}" id="youtube_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- Website -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fa-solid fa-globe text-gray-600 text-xl mr-2"></i>
                            <span class="font-medium text-gray-900">Website</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organic reach count</label>
                    <input type="number" name="website_{{ $language->id }}" id="website_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section III: Bible Course Students -->
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg shadow-lg border border-purple-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-graduation-cap text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section III: Bible Course Students</h3>
            </div>
        </div>
        
        <!-- Language Sections -->
        @foreach($languages as $language)
        <div class="p-6 language-section" data-language-id="{{ $language->id }}" style="{{ $languageId && $languageId != $language->id ? 'display: none;' : '' }} {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3 rounded-full mr-3 shadow-lg">
                    <i class="fa-solid fa-globe text-white"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900">{{ $language->name }}</h4>
                    <p class="text-sm text-gray-500">Enter student counts for each course type</p>
                </div>
            </div>
            
            <!-- Course Type Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Evangelistic Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fa-solid fa-file-lines text-green-600 text-xl mr-2"></i>
                            <span class="font-bold text-green-700">Evangelistic</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Basic introduction courses</p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of students</label>
                    <input type="number" name="evangelistic_{{ $language->id }}" id="evangelistic_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- Discipleship Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fa-solid fa-users text-blue-600 text-xl mr-2"></i>
                            <span class="font-bold text-blue-700">Discipleship</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Growth and development courses</p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of students</label>
                    <input type="number" name="discipleship_{{ $language->id }}" id="discipleship_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- Leadership Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border-l-4 border-purple-500 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fa-solid fa-graduation-cap text-purple-600 text-xl mr-2"></i>
                            <span class="font-bold text-purple-700">Leadership</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Leadership training courses</p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of students</label>
                    <input type="number" name="leadership_{{ $language->id }}" id="leadership_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section IV: Chat Conversations -->
    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg shadow-lg border border-orange-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-comments text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section IV: Chat Conversations</h3>
            </div>
        </div>
        
        <!-- Language Sections -->
        @foreach($languages as $language)
        <div class="p-6 language-section" data-language-id="{{ $language->id }}" style="{{ $languageId && $languageId != $language->id ? 'display: none;' : '' }} {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white p-3 rounded-full mr-3 shadow-lg">
                    <i class="fa-solid fa-globe text-white"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900">{{ $language->name }}</h4>
                    <p class="text-sm text-gray-500">Enter conversation counts for each type</p>
                </div>
            </div>
            
            <!-- Conversation Type Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Evangelistic Conversations Card -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-2 rounded-full mr-3 shadow-md">
                                <i class="fa-solid fa-users text-white"></i>
                            </div>
                            <span class="font-bold text-orange-600">Evangelistic Conversations</span>
                        </div>
                        <div class="flex items-center text-sm text-blue-600">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Outreach and evangelistic discussions</p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of conversations</label>
                    <input type="number" name="evangelistic_conversations_{{ $language->id }}" id="evangelistic_conversations_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
                
                <!-- Pastoral Connections Card -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-2 rounded-full mr-3 shadow-md">
                                <i class="fa-solid fa-heart text-white"></i>
                            </div>
                            <span class="font-bold text-red-600">Pastoral Connections</span>
                        </div>
                        <div class="flex items-center text-sm text-blue-600">
                            <i class="fa-solid fa-globe mr-1"></i>
                            <span>{{ $language->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Pastoral care and support connections made</p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of conversations</label>
                    <input type="number" name="pastoral_connections_{{ $language->id }}" id="pastoral_connections_{{ $language->id }}" data-language-id="{{ $language->id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" placeholder="0" value="0" readonly>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section V: Organization -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg shadow-lg border border-emerald-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-euro-sign text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section V: Organization</h3>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Income Field -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Income (Euros)</label>
                    <input type="number" step="0.01" id="income_euros" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" value="0" placeholder="0.00" readonly>
                </div>
                
                <!-- Expenditure Field -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Expenditure (Euros)</label>
                    <input type="number" step="0.01" id="expenditure_euros" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" value="0" placeholder="0.00" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Section VI: Public Relations & Staffing -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg shadow-lg border border-indigo-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-people-group text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section VI: Public Relations & Staffing</h3>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- PR Field -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <label class="block text-sm font-bold text-gray-900 mb-2">PR (Total Organic Reach)</label>
                    <input type="number" id="pr_total_organic_reach" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" value="0" placeholder="0" readonly>
                </div>
                
                <!-- Personal FTE Field -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Personal FTE (Full-Time Equivalent)</label>
                    <input type="number" step="0.1" id="personal_fte" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed" value="0.0" placeholder="0.0" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Section VII: Descriptive Text Fields -->
    <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg shadow-lg border border-pink-200 mb-8">
        <!-- Blue Header -->
        <div class="bg-gradient-to-r from-pink-600 to-rose-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-feather text-white mr-3"></i>
                <h3 class="text-xl font-bold">Section VII: Descriptive Text Fields</h3>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- New Activity -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <label class="block text-sm font-bold text-gray-900 mb-2">New Activity (max 100 words)</label>
                <textarea id="new_activity" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed resize-none" rows="4" placeholder="Describe new activities for this quarter..." readonly></textarea>
                <div class="text-xs text-gray-500 mt-2"><span id="new_activity_count">0</span>/500 characters</div>
            </div>
            
            <!-- Organizational Highlight -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <label class="block text-sm font-bold text-gray-900 mb-2">Organizational Highlight (max 50 words)</label>
                <textarea id="organizational_highlight" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed resize-none" rows="4" placeholder="Share an organizational highlight..." readonly></textarea>
                <div class="text-xs text-gray-500 mt-2"><span id="organizational_highlight_count">0</span>/250 characters</div>
            </div>
            
            <!-- Organizational Concern -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <label class="block text-sm font-bold text-gray-900 mb-2">Organizational Concern (max 50 words)</label>
                <textarea id="organizational_concern" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed resize-none" rows="4" placeholder="Share any organizational concerns..." readonly></textarea>
                <div class="text-xs text-gray-500 mt-2"><span id="organizational_concern_count">0</span>/250 characters</div>
            </div>
            
            <!-- Organizational Issues -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-300 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <label class="block text-sm font-bold text-gray-900 mb-2">Organizational Issues (max 50 words)</label>
                <textarea id="organizational_issues" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-gray-50 cursor-not-allowed resize-none" rows="4" placeholder="Describe any organizational issues (board, office, or personal)..." readonly></textarea>
                <div class="text-xs text-gray-500 mt-2"><span id="organizational_issues_count">0</span>/250 characters</div>
            </div>
        </div>
    </div>

    <!-- Recent Reports Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-clock text-blue-600 mr-2"></i>Recent Reports
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 bg-white rounded-lg shadow-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Report Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Language</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Quarter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white" id="reports-table-body">
                    @foreach($reports as $report)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $report->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $report->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $report->language->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $report->quarter }}</td>
                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($report->status === 'submitted') bg-blue-100 text-blue-800
                                @elseif($report->status === 'under_review') bg-yellow-100 text-yellow-800
                                @elseif($report->status === 'pending_super_admin_review') bg-purple-100 text-purple-800
                                @elseif($report->status === 'approved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $report->updated_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <div class="flex space-x-2">
                                <button onclick="openReviewModal({{ $report->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Review
                                </button>
                                <a href="{{ route('admin.reports.edit', $report->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="deleteReport({{ $report->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="review-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Review Report</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Remarks/Comments</label>
                    <textarea id="admin-remarks" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add your remarks or comments here..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeReviewModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button onclick="submitReview()" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit Review
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentReportId = null;

        // Update heading when year/quarter changes
        function updateHeading() {
            const year = document.getElementById('year-select').value;
            const quarter = document.getElementById('quarter-select').value;
            document.getElementById('report-heading').textContent = `Quarter Report – ${quarter} ${year}`;
        }

        // Update statistics and populate fields based on dropdown selections
        function updateStatistics() {
            const year = document.getElementById('year-select').value;
            const quarter = document.getElementById('quarter-select').value;
            const languageId = document.getElementById('language-select').value;
            
            // Update Create Report button URL with selected values
            const createBtn = document.getElementById('create-report-btn');
            if (createBtn) {
                let url = '{{ route("admin.reports.create") }}';
                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (quarter) params.append('quarter', quarter);
                if (languageId) params.append('language_id', languageId);
                if (params.toString()) {
                    url += '?' + params.toString();
                }
                createBtn.href = url;
            }
            
            // Fetch report data via AJAX
            fetch(`{{ route('admin.reports.data') }}?year=${year}&quarter=${quarter}${languageId ? '&language_id=' + languageId : ''}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update statistics
                    document.getElementById('total-reports').textContent = data.stats.total_reports || '0';
                    document.getElementById('total-languages').textContent = (data.stats.total_languages || '0') + ' languages';
                    document.getElementById('reports-due').textContent = data.stats.reports_due || '0';
                    document.getElementById('reports-reviewed').textContent = data.stats.reports_reviewed || '0';
                    document.getElementById('reports-approved').textContent = data.stats.reports_approved || '0';
                    document.getElementById('year-submitted').textContent = data.stats.year_submitted || '0';
                    document.getElementById('year-reviewed').textContent = data.stats.year_reviewed || '0';
                    document.getElementById('year-approved').textContent = data.stats.year_approved || '0';
                    
                    // Populate fields if report data exists
                    if (data.report_data && languageId) {
                        populateFields(data.report_data);
                    } else {
                        // Clear fields if no report data
                        clearFields();
                    }
                    
                    // Update reports table
                    updateReportsTable(data.reports || []);
                }
            })
            .catch(error => {
                console.error('Error fetching report data:', error);
            });
        }
        
        // Populate form fields with report data
        function populateFields(reportData) {
            // Goal Progress Section
            if (reportData.languages_previous_year !== null) {
                document.getElementById('languages_previous_year').textContent = reportData.languages_previous_year || '0';
            }
            if (reportData.languages_goal_2025 !== null) {
                document.getElementById('languages_goal_2025').textContent = reportData.languages_goal_2025 || '0';
            }
            if (reportData.languages_goal_q1 !== null) {
                document.getElementById('languages_goal_q').textContent = reportData.languages_goal_q1 || '0';
            }
            if (reportData.languages_achieved_q1 !== null) {
                document.getElementById('languages_achieved_q').textContent = reportData.languages_achieved_q1 || '0';
                // Calculate percentage
                const goal = reportData.languages_goal_q1 || 0;
                const achieved = reportData.languages_achieved_q1 || 0;
                const percent = goal > 0 ? Math.round((achieved / goal) * 100) : 0;
                document.getElementById('languages_percent').textContent = percent + '%';
            }
            
            if (reportData.volunteers_previous_year !== null) {
                document.getElementById('volunteers_previous_year').textContent = reportData.volunteers_previous_year || '0';
            }
            if (reportData.volunteers_goal_2025 !== null) {
                document.getElementById('volunteers_goal_2025').textContent = reportData.volunteers_goal_2025 || '0';
            }
            if (reportData.volunteers_goal_q1 !== null) {
                document.getElementById('volunteers_goal_q').textContent = reportData.volunteers_goal_q1 || '0';
            }
            if (reportData.volunteers_achieved_q1 !== null) {
                document.getElementById('volunteers_achieved_q').textContent = reportData.volunteers_achieved_q1 || '0';
                // Calculate percentage
                const goal = reportData.volunteers_goal_q1 || 0;
                const achieved = reportData.volunteers_achieved_q1 || 0;
                const percent = goal > 0 ? Math.round((achieved / goal) * 100) : 0;
                document.getElementById('volunteers_percent').textContent = percent + '%';
            }
            
            // Social Media Reach - populate for the selected language
            const languageId = reportData.language_id;
            if (languageId) {
                const facebookInput = document.getElementById('facebook_' + languageId);
                if (facebookInput && reportData.facebook_reach !== null) {
                    facebookInput.value = reportData.facebook_reach || '0';
                }
                
                const instagramInput = document.getElementById('instagram_' + languageId);
                if (instagramInput && reportData.instagram_reach !== null) {
                    instagramInput.value = reportData.instagram_reach || '0';
                }
                
                const youtubeInput = document.getElementById('youtube_' + languageId);
                if (youtubeInput && reportData.youtube_reach !== null) {
                    youtubeInput.value = reportData.youtube_reach || '0';
                }
                
                const websiteInput = document.getElementById('website_' + languageId);
                if (websiteInput && reportData.website_reach !== null) {
                    websiteInput.value = reportData.website_reach || '0';
                }
                
                // Bible Course Students
                const evangelisticInput = document.getElementById('evangelistic_' + languageId);
                if (evangelisticInput && reportData.evangelistic_students !== null) {
                    evangelisticInput.value = reportData.evangelistic_students || '0';
                }
                
                const discipleshipInput = document.getElementById('discipleship_' + languageId);
                if (discipleshipInput && reportData.discipleship_students !== null) {
                    discipleshipInput.value = reportData.discipleship_students || '0';
                }
                
                const leadershipInput = document.getElementById('leadership_' + languageId);
                if (leadershipInput && reportData.leadership_students !== null) {
                    leadershipInput.value = reportData.leadership_students || '0';
                }
                
                // Chat Conversations
                const evangelisticConvInput = document.getElementById('evangelistic_conversations_' + languageId);
                if (evangelisticConvInput && reportData.evangelistic_conversations !== null) {
                    evangelisticConvInput.value = reportData.evangelistic_conversations || '0';
                }
                
                const pastoralInput = document.getElementById('pastoral_connections_' + languageId);
                if (pastoralInput && reportData.pastoral_connections !== null) {
                    pastoralInput.value = reportData.pastoral_connections || '0';
                }
            }
            
            // Organization Section
            if (reportData.income_euros !== null) {
                document.getElementById('income_euros').value = reportData.income_euros || '0';
            }
            if (reportData.expenditure_euros !== null) {
                document.getElementById('expenditure_euros').value = reportData.expenditure_euros || '0';
            }
            if (reportData.pr_total_organic_reach !== null) {
                document.getElementById('pr_total_organic_reach').value = reportData.pr_total_organic_reach || '0';
            }
            if (reportData.personal_fte !== null) {
                document.getElementById('personal_fte').value = reportData.personal_fte || '0.0';
            }
            
            // Descriptive Text Fields
            if (reportData.new_activity !== null) {
                const newActivityField = document.getElementById('new_activity');
                if (newActivityField) {
                    newActivityField.value = reportData.new_activity || '';
                    document.getElementById('new_activity_count').textContent = (reportData.new_activity || '').length;
                }
            }
            if (reportData.organizational_highlight !== null) {
                const highlightField = document.getElementById('organizational_highlight');
                if (highlightField) {
                    highlightField.value = reportData.organizational_highlight || '';
                    document.getElementById('organizational_highlight_count').textContent = (reportData.organizational_highlight || '').length;
                }
            }
            if (reportData.organizational_concern !== null) {
                const concernField = document.getElementById('organizational_concern');
                if (concernField) {
                    concernField.value = reportData.organizational_concern || '';
                    document.getElementById('organizational_concern_count').textContent = (reportData.organizational_concern || '').length;
                }
            }
            if (reportData.organizational_issues !== null) {
                const issuesField = document.getElementById('organizational_issues');
                if (issuesField) {
                    issuesField.value = reportData.organizational_issues || '';
                    document.getElementById('organizational_issues_count').textContent = (reportData.organizational_issues || '').length;
                }
            }
        }
        
        // Clear all fields
        function clearFields() {
            // Goal Progress - reset to 0
            document.getElementById('languages_previous_year').textContent = '0';
            document.getElementById('languages_goal_2025').textContent = '0';
            document.getElementById('languages_goal_q').textContent = '0';
            document.getElementById('languages_achieved_q').textContent = '0';
            document.getElementById('languages_percent').textContent = '0%';
            
            document.getElementById('volunteers_previous_year').textContent = '0';
            document.getElementById('volunteers_goal_2025').textContent = '0';
            document.getElementById('volunteers_goal_q').textContent = '0';
            document.getElementById('volunteers_achieved_q').textContent = '0';
            document.getElementById('volunteers_percent').textContent = '0%';
            
            // Clear all input fields
            document.querySelectorAll('input[type="number"]').forEach(input => {
                if (input.id && !input.id.includes('_')) {
                    input.value = '0';
                }
            });
            
            document.querySelectorAll('textarea').forEach(textarea => {
                if (textarea.id) {
                    textarea.value = '';
                    const countId = textarea.id + '_count';
                    const countEl = document.getElementById(countId);
                    if (countEl) countEl.textContent = '0';
                }
            });
        }
        
        // Update reports table
        function updateReportsTable(reports) {
            const tbody = document.getElementById('reports-table-body');
            if (!tbody) return;
            
            if (reports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No reports found for the selected filters.</td></tr>';
                return;
            }
            
            tbody.innerHTML = reports.map(report => {
                const statusClass = report.status === 'submitted' ? 'bg-blue-100 text-blue-800' :
                                  report.status === 'under_review' ? 'bg-yellow-100 text-yellow-800' :
                                  report.status === 'pending_super_admin_review' ? 'bg-purple-100 text-purple-800' :
                                  report.status === 'approved' ? 'bg-green-100 text-green-800' :
                                  'bg-gray-100 text-gray-800';
                
                return `
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">${report.title}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">${report.user}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">${report.language}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">${report.quarter}</td>
                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                ${report.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">${report.updated_at}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                            <div class="flex space-x-2">
                                <button onclick="openReviewModal(${report.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Review
                                </button>
                                <a href="/admin/reports/${report.id}/edit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="deleteReport(${report.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Open review modal
        function openReviewModal(reportId) {
            currentReportId = reportId;
            document.getElementById('review-modal').classList.remove('hidden');
        }

        // Close review modal
        function closeReviewModal() {
            document.getElementById('review-modal').classList.add('hidden');
            document.getElementById('admin-remarks').value = '';
            currentReportId = null;
        }

        // Submit review
        function submitReview() {
            const remarks = document.getElementById('admin-remarks').value;
            
            if (!remarks.trim()) {
                alert('Please add remarks or comments before submitting the review.');
                return;
            }
            
            // In a real implementation, this would be an AJAX call to submit the review
            alert('Review submitted successfully! Email notification will be sent to the user.');
            closeReviewModal();
        }

        // Delete report
        function deleteReport(reportId) {
            if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
                fetch(`/admin/reports/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting report: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting report');
                });
            }
        }

        // Event listeners
        document.getElementById('year-select').addEventListener('change', function() {
            updateHeading();
            updateStatistics();
        });

        document.getElementById('quarter-select').addEventListener('change', function() {
            updateHeading();
            updateStatistics();
        });

        // Function to filter language sections
        function filterLanguageSections() {
            const selectedLanguageId = document.getElementById('language-select').value;
            const languageSections = document.querySelectorAll('.language-section');
            
            // Show/hide language sections based on selection
            languageSections.forEach(section => {
                const sectionLanguageId = section.getAttribute('data-language-id');
                if (selectedLanguageId === '' || selectedLanguageId === sectionLanguageId) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        document.getElementById('language-select').addEventListener('change', function() {
            filterLanguageSections();
            updateStatistics();
        });
        
        // Character counters for textareas
        document.getElementById('new_activity')?.addEventListener('input', function() {
            document.getElementById('new_activity_count').textContent = this.value.length;
        });
        document.getElementById('organizational_highlight')?.addEventListener('input', function() {
            document.getElementById('organizational_highlight_count').textContent = this.value.length;
        });
        document.getElementById('organizational_concern')?.addEventListener('input', function() {
            document.getElementById('organizational_concern_count').textContent = this.value.length;
        });
        document.getElementById('organizational_issues')?.addEventListener('input', function() {
            document.getElementById('organizational_issues_count').textContent = this.value.length;
        });

        // Initialize
        updateHeading();
        filterLanguageSections();
        updateStatistics();
    </script>
@endsection 