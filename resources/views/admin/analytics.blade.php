@extends('admin.layouts.app')

@section('title', 'Analytics Overview - GlobalRize Reporting')

@section('content')
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Analytics Overview</h1>
            <p class="text-gray-500">Comprehensive reporting insights and organizational metrics</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Filters -->
            <select id="year-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>
            <select id="quarter-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Quarters</option>
                <option value="Q1">Q1</option>
                <option value="Q2">Q2</option>
                <option value="Q3" selected>Q3</option>
                <option value="Q4">Q4</option>
            </select>
            <select id="form-plan-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Form Plans</option>
                <option value="Quarterly Progress">Quarterly Progress</option>
                <option value="Quarterly Summary">Quarterly Summary</option>
                <option value="Quarterly Review">Quarterly Review</option>
            </select>
            
            <!-- Export Options -->
            <div class="flex space-x-2">
                <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
                <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Overall Totals Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Overall Totals Across Organization
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Reports Card - Blue Gradient -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-blue-100 text-sm font-medium mb-2">Total Reports</h6>
                        <h4 class="text-3xl font-bold text-white" id="total-reports">{{ $systemStats['total_reports'] }}</h4>
                        <p class="text-blue-100 text-sm mt-1">Across all languages</p>
                    </div>
                    <div class="text-blue-200 text-2xl">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>
            </div>

            <!-- Completion Rate Card - Green Gradient -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-green-100 text-sm font-medium mb-2">Completion Rate</h6>
                        <h4 class="text-3xl font-bold text-white" id="completion-rate">{{ $systemStats['completion_rate'] }}%</h4>
                        <p class="text-green-100 text-sm mt-1" id="completion-detail">{{ $systemStats['completed_reports'] }} of {{ $systemStats['total_reports'] }} submitted</p>
                    </div>
                    <div class="text-green-200 text-2xl">
                        <i class="fa-solid fa-bar-chart"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users Card - Purple Gradient -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-purple-100 text-sm font-medium mb-2">Active Users</h6>
                        <h4 class="text-3xl font-bold text-white" id="active-users">{{ $totalUsers }}</h4>
                        <p class="text-purple-100 text-sm mt-1">Users in system</p>
                    </div>
                    <div class="text-purple-200 text-2xl">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
            </div>

            <!-- Languages Card - Orange Gradient -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-orange-100 text-sm font-medium mb-2">Languages</h6>
                        <h4 class="text-3xl font-bold text-white" id="total-languages">{{ $systemStats['total_languages'] }}</h4>
                        <p class="text-orange-100 text-sm mt-1">Available languages</p>
                    </div>
                    <div class="text-orange-200 text-2xl">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Quarterly Reports Bar Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-2">Quarterly Reports (2025)</h4>
            <p class="text-gray-500 text-sm mb-4">Report submissions by quarter</p>
            <div class="h-64">
                <canvas id="quarterlyChart"></canvas>
            </div>
        </div>

        <!-- Report Status Distribution Donut Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-2">Report Status Distribution</h4>
            <p class="text-gray-500 text-sm mb-4">Overall completion status</p>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Reports by Language Bar Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-2">Reports by Language (2025)</h4>
            <p class="text-gray-500 text-sm mb-4">Most active languages</p>
            <div class="h-64">
                <canvas id="languageChart"></canvas>
            </div>
        </div>

        <!-- Top Active Users Bar Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-2">Top Active Users (2025)</h4>
            <p class="text-gray-500 text-sm mb-4">Admin reporting activity</p>
            <div class="h-64">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Language Summary Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h4 class="text-lg font-bold text-gray-900 mb-2">Language Summary (2025)</h4>
        <p class="text-gray-500 text-sm mb-6">Detailed breakdown by language</p>
        
        <div class="space-y-4">
            @forelse($languages as $language)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-{{ $language->status == 'active' ? 'green' : ($language->status == 'inactive' ? 'red' : 'yellow') }}-500 rounded-full"></div>
                        <div>
                            <h5 class="font-bold text-gray-900">{{ $language->name }}</h5>
                            <p class="text-sm text-gray-600">{{ $language->assignedUser ? '1 submitted, 0 draft' : '0 submitted, 0 draft' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $language->assignedUser ? '1' : '0' }}</div>
                        <div class="text-sm text-gray-500">total reports</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fa-solid fa-globe text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600 font-medium mb-1">No languages found</p>
                    <p class="text-gray-400 text-sm">Add languages to see them here</p>
                </div>
            @endforelse
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Charts
            const quarterlyCtx = document.getElementById('quarterlyChart').getContext('2d');
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const languageCtx = document.getElementById('languageChart').getContext('2d');
            const usersCtx = document.getElementById('usersChart').getContext('2d');

            // Quarterly Reports Bar Chart
            new Chart(quarterlyCtx, {
                type: 'bar',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                    datasets: [
                        {
                            label: 'Submitted',
                            data: [0, 0, 2, 0],
                            backgroundColor: '#10B981',
                            borderColor: '#10B981',
                            borderWidth: 1
                        },
                        {
                            label: 'Draft',
                            data: [0, 0, 0, 0],
                            backgroundColor: '#D1D5DB',
                            borderColor: '#D1D5DB',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 2.0,
                            ticks: {
                                stepSize: 0.2,
                                callback: function(value) {
                                    return value.toFixed(1);
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Report Status Distribution Donut Chart
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Submitted', 'Draft'],
                    datasets: [{
                        data: [99.5, 0.5],
                        backgroundColor: [
                            '#10B981',
                            '#F97316'
                        ],
                        borderWidth: 0,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });

            // Reports by Language Bar Chart
            new Chart(languageCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($languages->pluck('name')) !!},
                    datasets: [{
                        label: 'Reports',
                        data: {!! json_encode($languages->map(function($lang) { return $lang->assignedUser ? 1.0 : 0.0; })) !!},
                        backgroundColor: '#8B5CF6',
                        borderColor: '#8B5CF6',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1.0,
                            ticks: {
                                stepSize: 0.1,
                                callback: function(value) {
                                    return value.toFixed(1);
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Top Active Users Bar Chart
            new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: ['Maria Rodriguez', 'John Smith', 'Sophie Chen', 'Test Leader'],
                    datasets: [{
                        label: 'Reports',
                        data: [2.0, 0, 0, 0],
                        backgroundColor: '#8B5CF6',
                        borderColor: '#8B5CF6',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 2.0,
                            ticks: {
                                stepSize: 0.2,
                                callback: function(value) {
                                    return value.toFixed(1);
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });

        // Filter functionality
        function updateFilters() {
            const year = document.getElementById('year-filter').value;
            const quarter = document.getElementById('quarter-filter').value;
            const formPlan = document.getElementById('form-plan-filter').value;
            
            // In a real implementation, these would be AJAX calls to update the data
            console.log('Filters updated:', { year, quarter, formPlan });
            
            // Update statistics based on filters
            // This would be replaced with actual API calls
            updateStatistics(year, quarter, formPlan);
        }

        function updateStatistics(year, quarter, formPlan) {
            // In a real implementation, these would be AJAX calls to get filtered data
            // For now, we'll just show some example updates
            document.getElementById('total-reports').textContent = '15';
            document.getElementById('completion-rate').textContent = '87%';
            document.getElementById('completion-detail').textContent = '13 of 15 submitted';
            document.getElementById('active-users').textContent = '8';
            document.getElementById('total-languages').textContent = '12';
        }

        // Export functionality
        function exportToPDF() {
            // In a real implementation, this would generate and download a PDF
            alert('PDF export functionality would be implemented here. This would generate a comprehensive analytics report in PDF format.');
        }

        function exportToExcel() {
            // In a real implementation, this would generate and download an Excel file
            alert('Excel export functionality would be implemented here. This would generate a spreadsheet with all analytics data.');
        }

        // Event listeners for filters
        document.getElementById('year-filter').addEventListener('change', updateFilters);
        document.getElementById('quarter-filter').addEventListener('change', updateFilters);
        document.getElementById('form-plan-filter').addEventListener('change', updateFilters);
    </script>
@endsection 