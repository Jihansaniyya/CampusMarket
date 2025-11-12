<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - CampusMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gray-900 text-white transition-all duration-300 shrink-0">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-800 flex items-center justify-between">
                <div x-show="sidebarOpen" class="flex items-center gap-2">
                    <span class="text-2xl">🛒</span>
                    <h1 class="font-bold text-xl">CampusMarket</h1>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 border-l-4 border-blue-400' : 'hover:bg-gray-800' }} transition-all">
                    <i class="fas fa-home text-lg w-6"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 border-l-4 border-blue-400' : 'hover:bg-gray-800' }} transition-all">
                    <i class="fas fa-users text-lg w-6"></i>
                    <span x-show="sidebarOpen">Users</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-box text-lg w-6"></i>
                    <span x-show="sidebarOpen">Products</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-shopping-cart text-lg w-6"></i>
                    <span x-show="sidebarOpen">Orders</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-tags text-lg w-6"></i>
                    <span x-show="sidebarOpen">Categories</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-chart-line text-lg w-6"></i>
                    <span x-show="sidebarOpen">Reports</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-cog text-lg w-6"></i>
                    <span x-show="sidebarOpen">Settings</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 w-full p-4" :class="sidebarOpen ? 'w-64' : 'w-20'">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-600 transition-all w-full">
                        <i class="fas fa-sign-out-alt text-lg w-6"></i>
                        <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-md z-40 px-6 py-4 flex items-center justify-between">
                <div>
                    <nav class="text-sm text-gray-600">
                        @yield('breadcrumb', 'Home > Dashboard')
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..."
                            class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">5</span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-100 p-2 rounded-lg">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"
                        x-data="{ show: true }" x-show="show">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"
                        x-data="{ show: true }" x-show="show">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
