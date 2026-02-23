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
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quarterly Data Summary</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-300">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border border-gray-200">Section</th>
                        @foreach($quarters as $quarter)
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900 border border-gray-200">{{ $quarter }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900 border border-gray-200">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aggregatedData as $sectionName => $sectionData)
                        <!-- Section Header Row -->
                        <tr class="bg-blue-50 border-b border-gray-200">
                            <td colspan="{{ count($quarters) + 2 }}" class="px-4 py-3 text-sm font-bold text-blue-900 border border-gray-200">
                                {{ $sectionName }}
                            </td>
                        </tr>

                        @if(is_array(reset($sectionData)) && isset(reset($sectionData)['Q1']))
                            <!-- Simple section with languages -->
                            @foreach($sectionData as $languageName => $quarterData)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700 border border-gray-200">
                                        <span class="ml-4">{{ $languageName }}</span>
                                    </td>
                                    @php $total = 0; @endphp
                                    @foreach($quarters as $quarter)
                                        @php 
                                            $value = $quarterData[$quarter] ?? 0;
                                            $total += (is_numeric($value) ? $value : 0);
                                        @endphp
                                        <td class="px-4 py-3 text-sm text-gray-900 border border-gray-200 text-center">
                                            {{ is_numeric($value) && strpos((string)$value, '.') !== false ? number_format($value, 2) : $value }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 border border-gray-200 text-center bg-gray-50">
                                        {{ is_numeric($total) && strpos((string)$total, '.') !== false ? number_format($total, 2) : $total }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <!-- Complex section with subsections and languages -->
                            @foreach($sectionData as $subsectionName => $languagesData)
                                <!-- Subsection Header -->
                                <tr class="bg-gray-100 border-b border-gray-200">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800 border border-gray-200">
                                        <span class="ml-4">{{ $subsectionName }}</span>
                                    </td>
                                    @foreach($quarters as $quarter)
                                        @php
                                            $quarterTotal = 0;
                                            foreach($languagesData as $langData) {
                                                $quarterTotal += (is_numeric($langData[$quarter] ?? 0) ? $langData[$quarter] : 0);
                                            }
                                        @endphp
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-700 border border-gray-200 text-center">
                                            {{ is_numeric($quarterTotal) && strpos((string)$quarterTotal, '.') !== false ? number_format($quarterTotal, 2) : $quarterTotal }}
                                        </td>
                                    @endforeach
                                    @php
                                        $subTotal = 0;
                                        foreach($languagesData as $langData) {
                                            foreach($langData as $val) {
                                                $subTotal += (is_numeric($val) ? $val : 0);
                                            }
                                        }
                                    @endphp
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 border border-gray-200 text-center bg-blue-50">
                                        {{ is_numeric($subTotal) && strpos((string)$subTotal, '.') !== false ? number_format($subTotal, 2) : $subTotal }}
                                    </td>
                                </tr>

                                <!-- Language rows for this subsection -->
                                @foreach($languagesData as $languageName => $quarterData)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-600 border border-gray-200">
                                            <span class="ml-8">• {{ $languageName }}</span>
                                        </td>
                                        @php $total = 0; @endphp
                                        @foreach($quarters as $quarter)
                                            @php 
                                                $value = $quarterData[$quarter] ?? 0;
                                                $total += (is_numeric($value) ? $value : 0);
                                            @endphp
                                            <td class="px-4 py-3 text-sm text-gray-700 border border-gray-200 text-center">
                                                {{ is_numeric($value) && strpos((string)$value, '.') !== false ? number_format($value, 2) : $value }}
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-800 border border-gray-200 text-center bg-gray-50">
                                            {{ is_numeric($total) && strpos((string)$total, '.') !== false ? number_format($total, 2) : $total }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
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
