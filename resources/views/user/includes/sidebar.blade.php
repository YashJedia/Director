<!-- Sidebar -->
<div class="sidebar fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out z-40 overflow-y-auto" id="sidebar">
    <div class="p-6">
        <nav class="space-y-2">
            <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('user.dashboard') ? 'text-green-700 bg-green-50' : '' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('user.reports') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 {{ request()->routeIs('user.reports') ? 'text-green-700 bg-green-50' : '' }}">
                <i class="fa-solid fa-file-lines text-lg"></i>
                <span class="font-medium">My Reports</span>
            </a>
        </nav>
    </div>
</div>
