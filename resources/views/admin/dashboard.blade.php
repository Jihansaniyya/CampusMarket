@extends('admin.layout')

@section('title', 'Dashboard')

@section('breadcrumb')
    Home > Dashboard
@endsection

@section('content')
    <div class="space-y-6">

        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Users Card -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Users</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $stats['total_users'] }}</h3>
                        <p class="text-blue-100 text-sm mt-2">
                            <i class="fas fa-arrow-up"></i> {{ $stats['new_users_today'] }} new today
                        </p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Sellers Card -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Sellers</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $stats['total_sellers'] }}</h3>
                        <p class="text-purple-100 text-sm mt-2">
                            Active merchants
                        </p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>

            <!-- Buyers Card -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Buyers</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $stats['total_buyers'] }}</h3>
                        <p class="text-green-100 text-sm mt-2">
                            Active customers
                        </p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div
                class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow-lg hover:scale-105 transform transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Products</p>
                        <h3 class="text-4xl font-bold mt-2">0</h3>
                        <p class="text-yellow-100 text-sm mt-2">
                            Coming soon
                        </p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recent_users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'seller') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif
                            ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($user->is_active) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif
                            ">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2"></i>
                                    <p>No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-md hover:shadow-xl transform hover:scale-105 transition-all flex flex-col items-center gap-2">
                <i class="fas fa-user-plus text-3xl"></i>
                <span class="font-semibold">Add New User</span>
            </a>

            <a href="#"
                class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-md hover:shadow-xl transform hover:scale-105 transition-all flex flex-col items-center gap-2">
                <i class="fas fa-box-open text-3xl"></i>
                <span class="font-semibold">Add Product</span>
            </a>

            <a href="#"
                class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-md hover:shadow-xl transform hover:scale-105 transition-all flex flex-col items-center gap-2">
                <i class="fas fa-file-alt text-3xl"></i>
                <span class="font-semibold">Generate Report</span>
            </a>

            <a href="#"
                class="bg-gray-600 hover:bg-gray-700 text-white p-6 rounded-lg shadow-md hover:shadow-xl transform hover:scale-105 transition-all flex flex-col items-center gap-2">
                <i class="fas fa-database text-3xl"></i>
                <span class="font-semibold">Backup Data</span>
            </a>
        </div>

    </div>
@endsection
