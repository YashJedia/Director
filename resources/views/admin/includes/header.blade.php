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

<script>
document.addEventListener('DOMContentLoaded', function() {
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