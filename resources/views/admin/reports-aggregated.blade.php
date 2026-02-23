@extends('admin.layouts.app')

@section('title', 'Aggregated Reports - Admin Dashboard')

@section('content')
<div class="w-full flex flex-col items-start px-2 md:px-0">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8 w-full max-w-6xl">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Aggregated Reports</h1>
            <p class="text-gray-500">Reports submitted by language directors</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Aggregated Reports Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quarterly Data Summary - All Fields</h3>
        <p class="text-gray-600 text-sm mb-6">All submitted reports data aggregated by section, field, language, and quarter</p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-blue-600 text-white sticky top-0">
                        <th class="px-4 py-3 text-left font-semibold border border-blue-700 w-48">Field</th>
                        <th class="px-4 py-3 text-left font-semibold border border-blue-700 w-32">Language</th>
                        @foreach($quarters as $quarter)
                            <th class="px-4 py-3 text-center font-semibold border border-blue-700 w-24">{{ $quarter }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-center font-semibold border border-blue-700 w-24">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aggregatedData as $sectionName => $fieldsData)
                        <!-- Section Header -->
                        <tr class="bg-blue-100 border-b-2 border-blue-400">
                            <td colspan="{{ count($quarters) + 3 }}" class="px-4 py-3 text-sm font-bold text-blue-900 border border-blue-300">
                                📊 {{ $sectionName }}
                            </td>
                        </tr>

                        @foreach($fieldsData as $fieldLabel => $languagesData)
                            @php $isFirstField = $loop->first; @endphp
                            <!-- Field rows -->
                            @foreach($languagesData as $languageName => $quarterData)
                                <tr class="border-b border-gray-200 hover:bg-blue-50">
                                    <!-- Field Name (only show on first language row) -->
                                    @if($loop->first)
                                        <td rowspan="{{ count($languagesData) }}" class="px-4 py-2 font-medium text-gray-800 border border-gray-300 bg-gray-50 align-top">
                                            {{ $fieldLabel }}
                                        </td>
                                    @endif
                                    
                                    <!-- Language Name -->
                                    <td class="px-4 py-2 text-gray-700 border border-gray-300">
                                        <span class="inline-block px-2 py-1 bg-gray-200 rounded text-xs font-semibold">
                                            {{ $languageName }}
                                        </span>
                                    </td>
                                    
                                    <!-- Quarter Data -->
                                    @php $sectionTotal = 0; @endphp
                                    @foreach($quarters as $quarter)
                                        @php 
                                            $value = $quarterData[$quarter] ?? 0;
                                            $sectionTotal += (is_numeric($value) ? $value : 0);
                                        @endphp
                                        <td class="px-4 py-2 text-center border border-gray-300">
                                            <span class="font-semibold {{ $value > 0 ? 'text-green-700' : 'text-gray-500' }}">
                                                @if(is_numeric($value))
                                                    @if(strpos((string)$value, '.') !== false)
                                                        {{ number_format($value, 2) }}
                                                    @else
                                                        {{ number_format($value) }}
                                                    @endif
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </td>
                                    @endforeach
                                    
                                    <!-- Language Total -->
                                    <td class="px-4 py-2 text-center font-bold border border-gray-300 bg-yellow-50">
                                        @if(is_numeric($sectionTotal))
                                            @if(strpos((string)$sectionTotal, '.') !== false)
                                                {{ number_format($sectionTotal, 2) }}
                                            @else
                                                {{ number_format($sectionTotal) }}
                                            @endif
                                        @else
                                            {{ $sectionTotal }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Report Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Submitted Reports Details</h3>
        
        <div class="space-y-4">
            @forelse($submittedReports as $report)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $report->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">Leader:</span> {{ $report->user->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Language:</span> {{ $report->language->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Quarter:</span> {{ $report->quarter }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                <i class="fa-solid fa-check mr-1"></i>Submitted
                            </span>
                            <a href="{{ route('admin.reports.edit', $report->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fa-solid fa-eye mr-1"></i>View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No reports submitted yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
