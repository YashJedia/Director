<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobalRize Reporting - Quarterly Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <button class="toggle-btn mr-4 text-gray-600 hover:text-gray-800" id="sidebarToggle">
                    <i class="fa fa-bars text-lg"></i>
                </button>
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white p-2 rounded-lg mr-3">
                        <i class="fa-solid fa-chart-column text-lg"></i>
                    </div>
                    <div>
                        <h5 class="text-xl font-bold text-gray-900 mb-0">GlobalRize Reporting</h5>
                        <div class="text-sm text-gray-500">International Director</div>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-gray-500 text-sm border border-gray-300 rounded-full px-4 py-2">
                    Current Period: <strong>Q3 2025</strong>
                </div>
                <div class="relative">
                    <button class="flex items-center space-x-3 cursor-pointer hover:opacity-80 transition-opacity" id="profileToggle">
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->email }}</div>
                        </div>
                        <div class="w-10 h-10 bg-gray-800 text-white rounded-full flex items-center justify-center font-semibold">
                            {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400"></i>
                    </button>
                    
                    <!-- Profile Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50" id="profileDropdown">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-800 text-white rounded-full flex items-center justify-center font-semibold text-lg">
                                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ Auth::guard('admin')->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-user-gear text-gray-400"></i>
                                <span>Profile Settings</span>
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="inline w-full">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar fixed left-0 top-16 h-full w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out z-40" id="sidebar">
        <div class="p-6">
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-house text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 active">
                    <i class="fa-solid fa-file-lines text-lg"></i>
                    <span class="font-medium">Reports</span>
                </a>
                <a href="{{ route('admin.user-management') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span class="font-medium">User Management</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-language text-lg"></i>
                    <span class="font-medium">Language Assignment</span>
                </a>
                <a href="{{ route('admin.analytics') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-chart-column text-lg"></i>
                    <span class="font-medium">Analytics</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-gear text-lg"></i>
                    <span class="font-medium">Settings</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content ml-64 pt-20 p-8">
        <!-- Top Bar with Back Button -->
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-600 hover:text-gray-800 mr-6">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                <span class="font-medium">Back to Dashboard</span>
            </a>
        </div>

        <!-- Page Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Quarterly Report</h1>
        </div>

        <!-- Report Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-4xl mx-auto">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Report Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Year Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                        <option>2022</option>
                    </select>
                </div>

                <!-- Quarter Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Q3</option>
                        <option>Q1</option>
                        <option>Q2</option>
                        <option>Q4</option>
                    </select>
                </div>

                <!-- Language Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    content.classList.toggle('ml-0');
                    content.classList.toggle('ml-64');
                });
            }

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.add('-translate-x-full');
                        content.classList.add('ml-0');
                        content.classList.remove('ml-64');
                    }
                }
            });

            // Profile dropdown toggle
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html> 