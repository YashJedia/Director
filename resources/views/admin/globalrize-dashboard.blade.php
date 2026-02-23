@extends('admin.layouts.app')

@section('title', 'GlobalRize Reporting - Admin Dashboard')

@section('content')
<div class="w-full flex flex-col items-start px-2 md:px-0">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8 w-full max-w-5xl ml-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
            <p class="text-gray-500">International Director Dashboard</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="bg-white border border-gray-300 text-gray-700 rounded-full px-4 py-2 text-sm font-medium">Q3 2025</span>
            <button onclick="openAdminNewReportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>New Report
            </button>
            <!-- Admin New Report Modal -->
            <div id="adminNewReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                    <button onclick="closeAdminNewReportModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                    <h3 class="text-xl font-bold mb-4 text-blue-700">Create Report on Behalf of User</h3>
                    <form id="adminNewReportForm" method="GET" action="{{ route('admin.reports.create') }}">
                        <div class="mb-4">
                            <label for="modal_user_id" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                            <select name="user_id" id="modal_user_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label for="modal_language_id" class="block text-sm font-medium text-gray-700 mb-2">Select Language</label>
                            <select name="language_id" id="modal_language_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select Language</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Continue</button>
                    </form>
                </div>
            </div>
            <script>
            function openAdminNewReportModal() {
                document.getElementById('adminNewReportModal').classList.remove('hidden');
            }
            function closeAdminNewReportModal() {
                document.getElementById('adminNewReportModal').classList.add('hidden');
            }
            </script>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 w-full max-w-5xl ml-0">
                @php
                    $revisionCount = $reportsForRevision ?? 0;
                    $isRevisionNeeded = $revisionCount > 0;
                @endphp
                <div id="revision-tile" class="bg-white rounded-lg shadow-sm @if($isRevisionNeeded) border-red-200 @else border-gray-200 @endif p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 id="revision-label" class="@if($isRevisionNeeded) text-red-600 @else text-gray-600 @endif text-sm font-medium mb-2">Reports for Revision</h6>
                            <h4 id="revision-count" class="@if($isRevisionNeeded) text-red-900 @else text-gray-900 @endif text-3xl font-bold">{{ $revisionCount }}</h4>
                            <p id="revision-subtext" class="@if($isRevisionNeeded) text-red-500 @else text-gray-500 @endif text-sm mt-1">Needs user revision</p>
                        </div>
                        <div id="revision-icon" class="@if($isRevisionNeeded) text-red-600 @else text-gray-600 @endif text-2xl">
                            <i class="fa-solid fa-undo"></i>
                        </div>
                    </div>
                </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Total Reports</h6>
                    <h4 class="text-3xl font-bold text-gray-900">2</h4>
                    <p class="text-gray-500 text-sm mt-1">All time reports created</p>
                </div>
                <div class="text-blue-600 text-2xl">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Draft Reports</h6>
                    <h4 class="text-3xl font-bold text-gray-900">0</h4>
                    <p class="text-gray-500 text-sm mt-1">Pending completion</p>
                </div>
                <div class="text-yellow-600 text-2xl">
                    <i class="fa-solid fa-edit"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 text-sm font-medium mb-2">Submitted</h6>
                    <h4 class="text-3xl font-bold text-gray-900">2</h4>
                    <p class="text-gray-500 text-sm mt-1">Completed reports</p>
                </div>
                <div class="text-green-600 text-2xl">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-6 cursor-pointer hover:shadow-lg transition" onclick="openQuarterlyReportsModal()">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-purple-600 text-sm font-medium mb-2">This Quarter</h6>
                    <h4 class="text-3xl font-bold text-gray-900">{{ $quarterlyReportsCount ?? 0 }}</h4>
                    <p class="text-purple-500 text-sm mt-1">Q3 2025 reports</p>
                </div>
                <div class="text-purple-600 text-2xl">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quarterly Reports Modal -->
    <div id="quarterlyReportsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-lg relative">
            <button onclick="closeQuarterlyReportsModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
            <h3 class="text-xl font-bold mb-4 text-purple-700">Quarterly Reports Received</h3>
            <ul class="divide-y divide-gray-200">
                @foreach($quarterlyReports as $report)
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-gray-900">{{ $report->language->name ?? 'N/A' }}</span>
                        <span class="text-sm text-gray-500">Leader: {{ $report->user->name ?? 'N/A' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
    function openQuarterlyReportsModal() {
        document.getElementById('quarterlyReportsModal').classList.remove('hidden');
    }
    function closeQuarterlyReportsModal() {
        document.getElementById('quarterlyReportsModal').classList.add('hidden');
    }
    </script>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 w-full ml-0">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Recent Reports</h4>
        <p class="text-gray-500 text-sm mb-6">Your latest quarterly reporting activities</p>
        <div class="space-y-4">
            @foreach($recentReports as $report)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="text-blue-600 text-xl">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900">{{ $report->title }}</h5>
                        <p class="text-sm text-gray-500">Updated {{ $report->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($report->status === 'submitted') bg-blue-100 text-blue-800
                        @elseif($report->status === 'under_review') bg-yellow-100 text-yellow-800
                        @elseif($report->status === 'approved') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="border border-gray-300 text-gray-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-eye mr-1"></i>View
                    </a>
                    @if($report->status === 'submitted')
                    <button onclick="approveReportAction({{ $report->id }})" class="border border-green-300 text-green-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-green-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-check mr-1"></i>Approve
                    </button>
                    <button onclick="openRevisionModal({{ $report->id }})" class="border border-orange-300 text-orange-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-orange-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-redo mr-1"></i>Revision
                    </button>
                    @endif
                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="border border-gray-300 text-gray-700 text-xs font-medium px-3 py-1 rounded-full hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Submit Reports to Super Admin -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 w-full ml-0">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Submit Quarterly Reports to Super Admin</h4>
        <p class="text-gray-500 text-sm mb-6">Submit all approved reports for a selected quarter cumulatively to the super admin</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
            <button onclick="openSubmitModal('{{ $quarter }}')" class="bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Submit {{ $quarter }}</span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Approved Reports Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 w-full ml-0">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-xl font-bold text-gray-900 mb-1">Approved Reports Ready for Submission</h4>
                <p class="text-gray-500 text-sm">Reports that have been approved and are ready to submit to super admin</p>
            </div>
            @if($approvedReports && $approvedReports->count() > 0)
            <form action="{{ route('admin.reports.submit-approved') }}" method="POST" id="submit-approved-form">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit All ({{ $approvedReports->count() }})</span>
                </button>
            </form>
            @endif
        </div>

        @if($approvedReports && $approvedReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-gray-700">Title</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-700">Language</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-700">Quarter</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-700">User</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-700">Modified</th>
                            <th class="text-center px-4 py-3 font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($approvedReports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $report->title }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $report->language->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $report->quarter }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $report->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600 text-sm">{{ $report->updated_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No approved reports ready for submission.</p>
            </div>
        @endif
    </div>


    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 w-full ml-0">
        <h4 class="text-xl font-bold text-gray-900 mb-2">Quick Actions</h4>
        <p class="text-gray-500 text-sm mb-6">Administrative tools and user management</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full ml-0">
            <a href="{{ route('admin.user-management') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-users text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Manage Users</span>
            </a>
            <a href="#" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-globe text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Language Settings</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 text-left hover:bg-gray-100 transition-colors duration-200 flex items-center">
                <i class="fa-solid fa-chart-line text-gray-700 text-xl mr-4"></i>
                <span class="font-medium text-gray-700">Analytics Overview</span>
            </a>
        </div>
    </div>

    <!-- Form Management Center -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full ml-0">
        <div class="flex items-center mb-4">
            <i class="fa-solid fa-search text-gray-600 mr-2"></i>
            <h4 class="text-xl font-bold text-gray-900">Form Management Center</h4>
        </div>
        <p class="text-gray-500 text-sm mb-6">Search and view specific forms by admin, year, and quarter.</p>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All years</option>
                <option>2025</option>
                <option>2024</option>
            </select>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All quarters</option>
                <option>Q1</option>
                <option>Q2</option>
                <option>Q3</option>
                <option>Q4</option>
            </select>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All users</option>
                <option>Maria Rodriguez</option>
                <option>John Smith</option>
            </select>
        </div>
        
        <!-- Search Results Summary -->
        <div class="flex items-center mb-6">
            <i class="fa-solid fa-folder text-gray-600 mr-2"></i>
            <span class="text-gray-700">2 forms found</span>
        </div>
        
        <!-- Form Entries -->
        <div class="space-y-4">
            </div>
            @foreach($languages->take(2) as $language)
            <div class="border border-gray-200 rounded-lg p-4 relative">
                <div class="absolute top-4 right-4">
                    <span class="bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-full">1 form</span>
                </div>
                <div class="mb-3">
                    <h5 class="font-semibold text-gray-900">Maria Rodriguez</h5>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fa-solid fa-globe mr-1"></i>
                        <span>{{ $language->name }}</span>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fa-solid fa-calendar mr-1"></i>
                            <span>2025 Q3</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-full">submitted</span>
                            <button class="border border-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded hover:bg-gray-50 transition-colors duration-200 flex items-center">
                                <i class="fa-solid fa-eye mr-1"></i>View
                            </button>
                            <button class="border border-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded hover:bg-gray-50 transition-colors duration-200 flex items-center">
                                <i class="fa-solid fa-edit mr-1"></i>Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Send for Revision Modal -->
    <div id="revision-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-redo text-orange-600 mr-2"></i>Send Report for Revision
                    </h3>
                    <button onclick="closeRevisionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Report Information Summary -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-3">Report Information</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><strong>Title:</strong> <span id="revision-report-title"></span></li>
                        <li><strong>Quarter:</strong> <span id="revision-report-quarter"></span></li>
                        <li><strong>Submitter:</strong> <span id="revision-report-submitter"></span></li>
                    </ul>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Revision Feedback <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-600 mb-2">Provide specific, constructive feedback about what needs to be revised. This will be sent to the user in the notification email.</p>
                    <textarea id="revision-reason" rows="6" maxlength="1000" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Example: Please revise the following sections:&#10;1. Section I - Languages data needs verification&#10;2. Section III - Bible course numbers incomplete&#10;3. Add details to Section VII - Organizational concerns"></textarea>
                    <div class="flex justify-between items-center mt-2">
                        <small class="text-gray-500">Max 1000 characters</small>
                        <small id="char-count" class="text-gray-500">0 / 1000</small>
                    </div>
                </div>

                <!-- Email Preview -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm">
                        <i class="fas fa-envelope text-gray-600 mr-2"></i>Email Preview
                    </h4>
                    <p class="text-xs text-gray-600">The user will receive an email notification with your feedback and a link to edit their report. The report status will be automatically reset to Draft.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeRevisionModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Cancel
                    </button>
                    <button onclick="submitRevision()" class="px-4 py-2 bg-orange-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Send for Revision
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit to Super Admin Modal -->
    <div id="submit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-paper-plane text-purple-600 mr-2"></i>Submit <span id="submit-quarter"></span> Reports to Super Admin
                    </h3>
                    <button onclick="closeSubmitModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Submission Information -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-purple-900 mb-3">Submission Details</h4>
                    <ul class="text-sm text-purple-800 space-y-2">
                        <li><strong>Quarter:</strong> <span id="submit-quarter-display"></span></li>
                        <li><strong>Action:</strong> Submit all approved reports for this quarter to the Super Admin</li>
                        <li><strong>Note:</strong> Reports will be aggregated and submitted cumulatively</li>
                    </ul>
                </div>

                <!-- Warning Alert -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-900 mb-1">Important</h4>
                            <p class="text-sm text-yellow-800">
                                Once submitted, reports cannot be un-submitted. The Super Admin will receive all approved and reviewed reports for this quarter in a cumulative submission.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeSubmitModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button onclick="submitQuarterlyReports()" class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 flex items-center">
                        <i class="fa-solid fa-check mr-2"></i>Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentReportId = null;

        // Open revision modal
        function openRevisionModal(reportId) {
            // Get report data from the page
            const reportRow = document.querySelector(`[data-report-id="${reportId}"]`);
            
            if (reportRow) {
                const reportTitle = reportRow.querySelector('h5')?.textContent || 'Report';
                const reportQuarter = reportRow.querySelector('.text-sm')?.textContent || 'Q3 2025';
                const reportSubmitter = reportRow.querySelector('[data-submitter]')?.textContent || 'User';
                
                document.getElementById('revision-report-title').textContent = reportTitle;
                document.getElementById('revision-report-quarter').textContent = reportQuarter;
                document.getElementById('revision-report-submitter').textContent = reportSubmitter;
            }
            
            currentReportId = reportId;
            document.getElementById('revision-modal').classList.remove('hidden');
        }

        // Close revision modal
        function closeRevisionModal() {
            document.getElementById('revision-modal').classList.add('hidden');
            document.getElementById('revision-reason').value = '';
            document.getElementById('char-count').textContent = '0 / 1000';
            currentReportId = null;
        }

        // Character counter for revision reason
        document.getElementById('revision-reason')?.addEventListener('input', function() {
            document.getElementById('char-count').textContent = this.value.length + ' / 1000';
        });

        // Submit revision request
        function submitRevision() {
            const reason = document.getElementById('revision-reason').value;
            
            if (!reason.trim()) {
                alert('Please provide revision feedback before sending.');
                return;
            }
            
            // Submit the revision request via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reports/${currentReportId}/send-for-revision`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="revision_reason" value="${reason}">
            `;
            
            document.body.appendChild(form);
            form.submit();
        }

        // Approve report action
        function approveReportAction(reportId) {
            if (confirm('Are you sure you want to approve this report? It will be ready for submission to the super admin.')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/reports/${reportId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Server error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    setTimeout(() => location.reload(), 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Failed to approve report');
                });
            }
        }

        // Submit to Super Admin functions
        let selectedQuarter = null;

        function openSubmitModal(quarter) {
            selectedQuarter = quarter;
            document.getElementById('submit-modal').classList.remove('hidden');
            document.getElementById('submit-quarter').textContent = quarter;
        }

        function closeSubmitModal() {
            document.getElementById('submit-modal').classList.add('hidden');
            selectedQuarter = null;
        }

        function submitQuarterlyReports() {
            if (!selectedQuarter) {
                alert('Please select a quarter');
                return;
            }

            const button = event.target;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Submitting...';

            fetch('{{ route("admin.reports.submit-quarterly-to-super-admin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quarter: selectedQuarter
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeSubmitModal();
                    // Reload page to show updated status
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert(data.message || 'Failed to submit reports');
                    button.disabled = false;
                    button.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Submit';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'An error occurred while submitting reports');
                button.disabled = false;
                button.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Submit';
            });
        }

        // Fetch and update revision count dynamically
        function fetchRevisionCount() {
            fetch('{{ route("admin.reports.revision-count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const count = data.revision_count;
                        const countElement = document.getElementById('revision-count');
                        const tile = document.getElementById('revision-tile');
                        const label = document.getElementById('revision-label');
                        const subtext = document.getElementById('revision-subtext');
                        const icon = document.getElementById('revision-icon');
                        
                        if (countElement) {
                            countElement.textContent = count;
                        }
                        
                        if (tile && label && subtext && icon) {
                            if (count > 0) {
                                // Apply red styling
                                tile.className = 'bg-white rounded-lg shadow-sm border-red-200 p-6';
                                label.className = 'text-red-600 text-sm font-medium mb-2';
                                countElement.className = 'text-red-900 text-3xl font-bold';
                                subtext.className = 'text-red-500 text-sm mt-1';
                                icon.className = 'text-red-600 text-2xl';
                            } else {
                                // Apply gray styling
                                tile.className = 'bg-white rounded-lg shadow-sm border-gray-200 p-6';
                                label.className = 'text-gray-600 text-sm font-medium mb-2';
                                countElement.className = 'text-gray-900 text-3xl font-bold';
                                subtext.className = 'text-gray-500 text-sm mt-1';
                                icon.className = 'text-gray-600 text-2xl';
                            }
                        }
                    }
                })
                .catch(error => console.error('Error fetching revision count:', error));
        }

        // Fetch revision count on page load and every 10 seconds
        document.addEventListener('DOMContentLoaded', function() {
            fetchRevisionCount();
            setInterval(fetchRevisionCount, 10000);

            // Handle submit approved reports form
            const submitForm = document.getElementById('submit-approved-form');
            if (submitForm) {
                submitForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const count = document.querySelectorAll('table tbody tr').length;
                    if (count === 0) {
                        alert('No approved reports to submit.');
                        return;
                    }
                    if (confirm(`Are you sure you want to submit ${count} approved report(s) to the super admin? This action cannot be undone.`)) {
                        this.submit();
                    }
                });
            }
        });
    </script>
@endsection