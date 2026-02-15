<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GlobalRize Reporting - Admin')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <!-- Main Content -->
    <div class="content ml-64 pt-20 p-8">
        @yield('content')
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
        });
    </script>
</body>
</html> 