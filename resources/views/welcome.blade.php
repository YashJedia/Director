<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Welcome to Our Application</h1>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Super Admin Panel -->
                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-purple-500">
                            <div class="flex items-center justify-center w-full">
                                <div class="text-center">
                                    <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Super Admin Panel</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">Access the super admin dashboard to manage admins, languages, and system-wide settings.</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('admin.login') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                            Super Admin Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Panel -->
                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div class="flex items-center justify-center w-full">
                                <div class="text-center">
                                    <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Admin Panel</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">Access the admin dashboard to manage users and system settings.</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('admin.login') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Admin Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Panel -->
                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500">
                            <div class="flex items-center justify-center w-full">
                                <div class="text-center">
                                    <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">User Panel</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">Access your personal dashboard to view reports and manage your account.</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('user.login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            User Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
