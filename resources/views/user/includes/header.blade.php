<!-- Header -->
<div class="sticky top-0 z-50 bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <button class="toggle-btn mr-4 text-gray-600 hover:text-gray-800" id="sidebarToggle">
                <i class="fa fa-bars text-lg"></i>
            </button>
            <div class="flex items-center">
                <div class="text-green-600 text-2xl mr-3">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h5 class="text-xl font-bold text-gray-900 mb-0">Language Team Leader Panel</h5>
                    <div class="text-sm text-gray-500">@yield('page_title', 'User Dashboard')</div>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-gray-500 text-sm border border-gray-300 rounded-full px-4 py-2 flex items-center space-x-3">
                <span>Current Period: <strong>Q3 2025</strong></span>
                <span class="text-gray-300">|</span>
                <span id="currentTime"></span>
            </div>
            <div class="flex items-center space-x-3">
                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-2 py-1 transition-colors duration-200" id="userProfileToggle">
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold overflow-hidden">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-500 text-sm"></i>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden" id="userProfileDropdown">
                        <div class="py-1">
                            <a href="{{ route('user.profile') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fa-solid fa-user text-gray-500"></i>
                                <span class="text-sm font-medium">Profile</span>
                            </a>
                            <a href="{{ route('user.help') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fa-solid fa-circle-question text-gray-500"></i>
                                <span class="text-sm font-medium">Help</span>
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <form method="POST" action="{{ route('user.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200 text-left">
                                    <i class="fa-solid fa-sign-out-alt text-gray-500"></i>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User profile dropdown toggle
    const userProfileToggle = document.getElementById('userProfileToggle');
    const userProfileDropdown = document.getElementById('userProfileDropdown');
    
    if (userProfileToggle && userProfileDropdown) {
        userProfileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userProfileDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userProfileToggle.contains(e.target) && !userProfileDropdown.contains(e.target)) {
                userProfileDropdown.classList.add('hidden');
            }
        });
    }
    
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: true, 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        });
        const dateString = now.toLocaleDateString('en-US', { 
            weekday: 'short', 
            month: 'short', 
            day: 'numeric' 
        });
        
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
            timeElement.innerHTML = `<i class="fa-solid fa-clock mr-1"></i>${timeString} <span class="text-xs">${dateString}</span>`;
        }
    }
    
    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
});
</script>
