@extends('admin.layouts.app')

@section('title', 'Submitted Reports - Super Admin Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Submitted Reports</h1>
            <p class="text-gray-500">View all reports submitted by admins for your review</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Total Submitted</h6>
                        <h4 class="text-3xl font-bold text-gray-900">{{ $stats['total_submitted'] }}</h4>
                        <p class="text-gray-500 text-sm mt-1">All reports</p>
                    </div>
                    <div class="text-purple-600 text-2xl">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Languages</h6>
                        <h4 class="text-3xl font-bold text-gray-900">{{ $stats['total_languages'] }}</h4>
                        <p class="text-gray-500 text-sm mt-1">Represented</p>
                    </div>
                    <div class="text-blue-600 text-2xl">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Quarters</h6>
                        <h4 class="text-3xl font-bold text-gray-900">{{ $stats['by_quarter'] }}</h4>
                        <p class="text-gray-500 text-sm mt-1">Covered</p>
                    </div>
                    <div class="text-green-600 text-2xl">
                        <i class="fa-solid fa-calendar"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Admins</h6>
                        <h4 class="text-3xl font-bold text-gray-900">{{ $stats['by_admin'] }}</h4>
                        <p class="text-gray-500 text-sm mt-1">Submitted</p>
                    </div>
                    <div class="text-orange-600 text-2xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form action="{{ route('admin.reports') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                    <select name="quarter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Quarters</option>
                        <option value="Q1" {{ $quarter === 'Q1' ? 'selected' : '' }}>Q1</option>
                        <option value="Q2" {{ $quarter === 'Q2' ? 'selected' : '' }}>Q2</option>
                        <option value="Q3" {{ $quarter === 'Q3' ? 'selected' : '' }}>Q3</option>
                        <option value="Q4" {{ $quarter === 'Q4' ? 'selected' : '' }}>Q4</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fa-solid fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($formattedReports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quarter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($formattedReports as $report)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $report['title'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $report['user'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">
                                        <i class="fa-solid fa-globe text-blue-600 mr-1"></i>{{ $report['language'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        <i class="fa-solid fa-user-shield text-purple-600 mr-1"></i>{{ $report['admin'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                        {{ $report['quarter'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $report['submitted_at'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.reports.edit', $report['id']) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <i class="fa-solid fa-inbox text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600 font-medium mb-1">No submitted reports found</p>
                    <p class="text-gray-400 text-sm">Reports submitted by admins will appear here</p>
                </div>
            @endif
        </div>
    </div>
@endsection
