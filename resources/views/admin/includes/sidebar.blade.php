<!-- Sidebar -->
<div class="sidebar fixed left-0 top-16 h-full w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out z-40" id="sidebar">
    <div class="p-6">
        @php
            $admin = Auth::guard('admin')->user();
            $isSuperAdmin = $admin->isSuperAdmin();
        @endphp
        
        <nav class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-blue-700 bg-blue-50' : '' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            @if($isSuperAdmin)
                <!-- Super Admin Menu -->
                <a href="{{ route('admin.admins.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.admins.*') ? 'text-purple-700 bg-purple-50' : '' }}">
                    <i class="fa-solid fa-users-gear text-lg"></i>
                    <span class="font-medium">Admin Management</span>
                </a>
                <a href="{{ route('admin.language-assignment') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.language-assignment') ? 'text-blue-700 bg-blue-50' : '' }}">
                    <i class="fa-solid fa-language text-lg"></i>
                    <span class="font-medium">Language Assignment</span>
                </a>
            @else
                <!-- Regular Admin Menu -->
                <a href="{{ route('admin.reports') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reports') ? 'text-blue-700 bg-blue-50' : '' }}">
                    <i class="fa-solid fa-file-lines text-lg"></i>
                    <span class="font-medium">Reports</span>
                </a>
                <a href="{{ route('admin.user-management') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.user-management') ? 'text-blue-700 bg-blue-50' : '' }}">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span class="font-medium">User Management</span>
                </a>
                <a href="{{ route('admin.language-assignment') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.language-assignment') ? 'text-blue-700 bg-blue-50' : '' }}">
                    <i class="fa-solid fa-language text-lg"></i>
                    <span class="font-medium">Language Assignment</span>
                </a>
                <a href="{{ route('admin.analytics') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics') ? 'text-blue-700 bg-blue-50' : '' }}">
                    <i class="fa-solid fa-chart-column text-lg"></i>
                    <span class="font-medium">Analytics</span>
                </a>
            @endif
            
            <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.settings') ? 'text-blue-700 bg-blue-50' : '' }}">
                <i class="fa-solid fa-gear text-lg"></i>
                <span class="font-medium">Settings</span>
            </a>
        </nav>
    </div>
</div> 