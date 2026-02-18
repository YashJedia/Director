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
                <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="text-red-600 text-sm font-medium mb-2">Reports for Revision</h6>
                            <h4 class="text-3xl font-bold text-red-900">{{ $reportsForRevision ?? 0 }}</h4>
                            <p class="text-red-500 text-sm mt-1">Needs user revision</p>
                        </div>
                        <div class="text-red-600 text-2xl">
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
    </script>
@endsection