@extends('admin.layouts.app')

@section('title', 'Aggregated Reports - Admin Panel')

@section('content')
<div class="w-full flex flex-col items-start px-2 md:px-0">
    <!-- Session Messages -->
    @if($message = Session::get('success'))
        <div class="w-full max-w-7xl mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex justify-between items-center">
            <div>
                <i class="fa-solid fa-check mr-2"></i>{{ $message }}
            </div>
            <button onclick="this.parentElement.style.display='none';" class="text-green-700 hover:text-green-900 font-bold">×</button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="w-full max-w-7xl mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center">
            <div>
                <i class="fa-solid fa-exclamation-circle mr-2"></i>{{ $message }}
            </div>
            <button onclick="this.parentElement.style.display='none';" class="text-red-700 hover:text-red-900 font-bold">×</button>
        </div>
    @endif

    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8 w-full max-w-7xl">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Aggregated Reports by Quarter</h1>
            <p class="text-gray-500">Cumulative data for your assigned languages - goals, achievements, and performance metrics</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Quarter Selection Buttons -->
    <div class="flex gap-4 mb-8 w-full max-w-7xl flex-wrap">
        @foreach($quarters as $index => $quarter)
            <div class="relative flex flex-col items-center">
                <button 
                    onclick="switchQuarter('{{ $quarter }}')"
                    class="quarter-btn px-6 py-2 rounded-lg font-semibold transition-all duration-200 {{ $index === 0 ? 'bg-blue-600 text-white shadow-lg active' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}"
                    data-quarter="{{ $quarter }}"
                >
                    {{ $quarter }}
                </button>
                @if(isset($submissions[$quarter]))
                    <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center" title="Submitted on {{ $submissions[$quarter]->submitted_at->format('M d, Y') }}">
                        <i class="fa-solid fa-check"></i>
                    </span>
                @else
                    <form action="{{ route('admin.submit-aggregated-report') }}" method="POST" class="inline mt-2">
                        @csrf
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200" onclick="return confirm('Submit aggregated report for {{ $quarter }}?')">
                            <i class="fa-solid fa-paper-plane mr-1"></i>Submit
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Quarter Tables Container -->
    <div class="w-full max-w-7xl">
        @foreach($aggregatedData as $quarter => $sections)
            <div id="quarter-{{ str_replace(' ', '-', $quarter) }}" class="quarter-content bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full mb-8 {{ $loop->first ? '' : 'hidden' }}">
                <div class="mb-6 pb-4 border-b-2 border-blue-400">
                    <h2 class="text-2xl font-bold text-blue-900">📈 {{ $quarter }}</h2>
                    <p class="text-gray-600 text-sm mt-1">Ministry metrics, outreach, engagement, and financial data for your assigned languages</p>
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
                                        <th class="px-4 py-2 text-left font-semibold text-gray-800 border-r border-gray-300 w-24">Language</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 border-r border-gray-300 w-20">End Year</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-800 border-r border-gray-300 w-20">Goal {{ date('Y') }}</th>
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

                                                    <!-- Language Name Column -->
                                                    <td class="px-4 py-2 border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold text-blue-800 bg-blue-50' : 'text-gray-600 ml-4' }}">
                                                        {{ $languageName }}
                                                    </td>

                                                    <!-- Data Columns -->
                                                    <td class="px-4 py-2 text-center border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }}">
                                                        {{ is_numeric($values['end_2024']) ? number_format($values['end_2024'], 0) : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center border-r border-gray-300 {{ $languageName === 'Total' ? 'font-bold bg-blue-50' : '' }}">
                                                        {{ is_numeric($values['goal_year']) ? number_format($values['goal_year'], 0) : '-' }}
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
                                                <td class="px-4 py-2 border-r border-gray-300 text-gray-600">
                                                    -
                                                </td>
                                                <td class="px-4 py-2 text-center border-r border-gray-300">
                                                    {{ is_numeric($fieldData['data']['end_2024']) ? number_format($fieldData['data']['end_2024'], 0) : '-' }}
                                                </td>
                                                <td class="px-4 py-2 text-center border-r border-gray-300">
                                                    {{ is_numeric($fieldData['data']['goal_year']) ? number_format($fieldData['data']['goal_year'], 0) : '-' }}
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

<script>
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
