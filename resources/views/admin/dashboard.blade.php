<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-red-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-2 px-4">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="text-2xl font-extrabold">Admin Panel</span>
            </div>
            
            <nav>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 hover:text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
                @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->isSuperAdmin())
                <a href="{{ route('admin.reports.aggregated') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 hover:text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Reports</span>
                    </div>
                </a>
                @endif
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 hover:text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span>Users</span>
                    </div>
                </a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 hover:text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Analytics</span>
                    </div>
                </a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 hover:text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Settings</span>
                    </div>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, {{ Auth::guard('admin')->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                                                @php
                                                    $revisionCount = $reportsForRevision ?? 0;
                                                    $isRevisionNeeded = $revisionCount > 0;
                                                @endphp
                                                <!-- Reports for Revision Tile -->
                                                <div id="revision-tile" class="@if($isRevisionNeeded) bg-gradient-to-br from-red-50 to-red-100 border border-red-200 @else bg-white border border-gray-200 @endif rounded-lg p-5 shadow-md">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <p id="revision-label" class="@if($isRevisionNeeded) text-red-600 @else text-gray-600 @endif text-sm font-medium">Reports for Revision</p>
                                                            <p id="revision-count" class="@if($isRevisionNeeded) text-red-900 @else text-gray-900 @endif text-2xl font-bold">{{ $revisionCount }}</p>
                                                            <p id="revision-subtext" class="@if($isRevisionNeeded) text-red-500 @else text-gray-500 @endif text-xs">Needs user revision</p>
                                                        </div>
                                                        <div id="revision-icon-bg" class="@if($isRevisionNeeded) bg-red-500 @else bg-gray-500 @endif p-3 rounded-full">
                                                            <svg id="revision-icon" class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h7V3m0 0l11 11-4 4-7-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                        <!-- Stats Cards -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                            <dd class="text-lg font-medium text-gray-900">1,234</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                                            <dd class="text-lg font-medium text-gray-900">987</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Revenue</dt>
                                            <dd class="text-lg font-medium text-gray-900">$45,678</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Avg. Session</dt>
                                            <dd class="text-lg font-medium text-gray-900">24m</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
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
                        const iconBg = document.getElementById('revision-icon-bg');
                        
                        if (countElement) {
                            countElement.textContent = count;
                        }
                        
                        if (tile && label && subtext && iconBg) {
                            if (count > 0) {
                                // Apply red styling
                                tile.className = 'bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-5 shadow-md';
                                label.className = 'text-red-600 text-sm font-medium';
                                countElement.className = 'text-red-900 text-2xl font-bold';
                                subtext.className = 'text-red-500 text-xs';
                                iconBg.className = 'bg-red-500 p-3 rounded-full';
                            } else {
                                // Apply gray styling
                                tile.className = 'bg-white border border-gray-200 rounded-lg p-5 shadow-md';
                                label.className = 'text-gray-600 text-sm font-medium';
                                countElement.className = 'text-gray-900 text-2xl font-bold';
                                subtext.className = 'text-gray-500 text-xs';
                                iconBg.className = 'bg-gray-500 p-3 rounded-full';
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
        });
    </script>
</body>
</html> 