@extends('admin.layouts.app')

@section('title', 'Aggregated Reports - Admin Dashboard')

@section('content')
<div class="w-full flex flex-col items-start px-2 md:px-0">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8 w-full max-w-7xl">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Aggregated Reports by Quarter</h1>
            <p class="text-gray-500">Reports data by languages, sections, and quarterly goals</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Quarter Selection Buttons -->
    <div class="flex gap-4 mb-8 w-full max-w-7xl flex-wrap">
        @foreach($quarters as $index => $quarter)
            <button 
                onclick="switchQuarter('{{ $quarter }}')"
                class="quarter-btn px-6 py-2 rounded-lg font-semibold transition-all duration-200 {{ $index === 0 ? 'bg-blue-600 text-white shadow-lg active' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}"
                data-quarter="{{ $quarter }}"
            >
                {{ $quarter }}
            </button>
        @endforeach
    </div>

    <!-- Quarter Tables Container -->
    <div class="w-full max-w-7xl">
        @foreach($aggregatedData as $quarter => $sections)
            <div id="quarter-{{ str_replace(' ', '-', $quarter) }}" class="quarter-content bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full mb-8 {{ $loop->first ? '' : 'hidden' }}">
                <div class="mb-6 pb-4 border-b-2 border-blue-400">
                    <h2 class="text-2xl font-bold text-blue-900">📈 {{ $quarter }}</h2>
                    <p class="text-gray-600 text-sm mt-1">Ministry metrics, outreach, engagement, and financial data</p>
                </div>

                @foreach($sections as $sectionName => $fields)
                    <!-- Section Header -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-blue-800 bg-blue-50 px-4 py-2 rounded-t-lg border-b-2 border-blue-300">
                            📊 {{ $sectionName }}
                        </h3>

                        <!-- Section Table -->
                        <div class="overflow-x-auto border border-t-0 border-blue-300 rounded-b-lg">
                            <table class="min-w-full border-collapse text-xs md:text-sm">
                                <thead>
                                    <tr class="bg-blue-100 border-b border-gray-300">
                                        <th class="px-4 py-2 text-left font-semibold text-gray-800 border-r border-gray-300 w-32 md:w-40">{{ $sectionName }}</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 border-r border-gray-300 w-20">End 2024</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 border-r border-gray-300 w-20">Goal</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 border-r border-gray-300 w-20">Achieved</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 w-20">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fields as $fieldLabel => $fieldData)
                                        @if($fieldData['by_language'])
                                            <!-- Language-specific fields -->
                                            @php $isFirstInField = true; @endphp
                                            @foreach($fieldData['data'] as $languageName => $values)
                                                <tr class="border-b border-gray-200 hover:bg-blue-50">
                                                    <!-- Field/Language Name -->
                                                    @if($isFirstInField)
                                                        <td rowspan="{{ count($fieldData['data']) }}" class="px-4 py-2 font-semibold text-gray-800 border-r border-gray-300 bg-gray-50 align-top">
                                                            {{ $fieldLabel }}
                                                        </td>
                                                        @php $isFirstInField = false; @endphp
                                                    @endif

                                                    <!-- Language Name (or Total) -->
                                                    <td class="px-4 py-2 text-gray-700 border-r border-gray-300">
                                                        @if($languageName === 'Total')
                                                            <span class="font-bold text-blue-800">{{ $languageName }}</span>
                                                        @else
                                                            <span class="text-gray-600 ml-4">{{ $languageName }}</span>
                                                        @endif
                                                    </td>

                                                    <!-- Data Columns -->
                                                    <td class="px-4 py-2 text-center border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }}">
                                                        {{ is_numeric($values['end_2024']) ? number_format($values['end_2024'], 0) : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }}">
                                                        {{ is_numeric($values['goal']) ? number_format($values['goal'], 0) : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }} {{ $values['achieved'] > 0 ? 'text-green-700' : 'text-gray-500' }}">
                                                        {{ is_numeric($values['achieved']) ? number_format($values['achieved'], 0) : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }} {{ $values['percentage'] >= 100 ? 'text-green-700' : ($values['percentage'] >= 75 ? 'text-yellow-700' : 'text-red-700') }}">
                                                        @if($values['goal'] > 0)
                                                            {{ $values['percentage'] }}%
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <!-- Non-language-specific fields (aggregated across all languages) -->
                                            <tr class="border-b border-gray-200 hover:bg-blue-50">
                                                <td class="px-4 py-2 font-semibold text-gray-800 border-r border-gray-300 bg-gray-50">
                                                    {{ $fieldLabel }}
                                                </td>
                                                <td class="px-4 py-2 text-center border-r border-gray-300">
                                                    {{ is_numeric($fieldData['data']['end_2024']) ? number_format($fieldData['data']['end_2024'], 0) : '-' }}
                                                </td>
                                                <td class="px-4 py-2 text-center border-r border-gray-300">
                                                    {{ is_numeric($fieldData['data']['goal']) ? number_format($fieldData['data']['goal'], 0) : '-' }}
                                                </td>
                                                <td class="px-4 py-2 text-center border-r border-gray-300 {{ $fieldData['data']['achieved'] > 0 ? 'text-green-700' : 'text-gray-500' }}">
                                                    {{ is_numeric($fieldData['data']['achieved']) ? number_format($fieldData['data']['achieved'], 0) : '-' }}
                                                </td>
                                                <td class="px-4 py-2 text-center {{ $fieldData['data']['percentage'] >= 100 ? 'text-green-700' : ($fieldData['data']['percentage'] >= 75 ? 'text-yellow-700' : 'text-red-700') }}">
                                                    @if($fieldData['data']['goal'] > 0)
                                                        {{ $fieldData['data']['percentage'] }}%
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Report Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full max-w-7xl">
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
                            <button onclick="deleteReport({{ $report->id }})" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fa-solid fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No reports submitted yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
function deleteReport(reportId) {
    if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
        fetch(`/admin/reports/${reportId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting report: ' + error);
        });
    }
}

function switchQuarter(quarter) {
    // Hide all quarter contents
    const allContents = document.querySelectorAll('.quarter-content');
    allContents.forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active state from all buttons
    const allButtons = document.querySelectorAll('.quarter-btn');
    allButtons.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'active');
        btn.classList.add('bg-gray-200', 'text-gray-800');
    });

    // Show selected quarter
    const quarterId = 'quarter-' + quarter.replace(/\s+/g, '-');
    const selectedContent = document.getElementById(quarterId);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }

    // Activate clicked button
    const selectedButton = document.querySelector(`[data-quarter="${quarter}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('bg-gray-200', 'text-gray-800');
        selectedButton.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'active');
    }
}
</script>
@endsection