@extends('user.layouts.app')

@section('title', 'Help & Support - GlobalRize User Portal')

@section('page_title', 'Help & Support')

@section('content')
<!-- Top Bar -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-1">Help & Support</h1>
    <p class="text-gray-500">Get help with using the GlobalRize reporting system</p>
</div>

<!-- Quick Help -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow duration-200">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-book text-2xl"></i>
        </div>
        <h4 class="text-lg font-bold text-gray-900 mb-2">Documentation</h4>
        <p class="text-gray-600 text-sm mb-4">Comprehensive guides and tutorials</p>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
            View Docs
        </button>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow duration-200">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-video text-2xl"></i>
        </div>
        <h4 class="text-lg font-bold text-gray-900 mb-2">Video Tutorials</h4>
        <p class="text-gray-600 text-sm mb-4">Step-by-step video guides</p>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
            Watch Videos
        </button>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow duration-200">
        <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-headset text-2xl"></i>
        </div>
        <h4 class="text-lg font-bold text-gray-900 mb-2">Contact Support</h4>
        <p class="text-gray-600 text-sm mb-4">Get help from our team</p>
        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
            Contact Us
        </button>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-question-circle mr-2 text-gray-600"></i>Frequently Asked Questions
    </h4>
    
    <div class="space-y-4">
        <div class="border border-gray-200 rounded-lg">
            <button class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">How do I create a new report?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                </div>
            </button>
            <div class="px-4 pb-3 hidden">
                <p class="text-gray-600 text-sm">To create a new quarterly report, go to the Reports page and click "Create New Report". Choose from available quarterly templates and fill in the required information. You can save as draft and submit when ready. Note: Reports cannot be deleted once created, but they can be edited and updated.</p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg">
            <button class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">How are reports scored?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                </div>
            </button>
            <div class="px-4 pb-3 hidden">
                <p class="text-gray-600 text-sm">Reports are scored by administrators based on completeness, accuracy, and quality. Scores range from 1-10, with detailed feedback provided for improvement.</p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg">
            <button class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">Can I edit a submitted report?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                </div>
            </button>
            <div class="px-4 pb-3 hidden">
                <p class="text-gray-600 text-sm">Yes, you can edit submitted reports if they haven't been reviewed yet. Once reviewed, you'll need to create a new version or contact support for changes.</p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg">
            <button class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">How do I track my progress?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                </div>
            </button>
            <div class="px-4 pb-3 hidden">
                <p class="text-gray-600 text-sm">You can track your progress by viewing your reports in the My Reports section. Check the status of your reports to see which ones are submitted, in draft, or need revision.</p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg">
            <button class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-900">What if I forget my password?</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                </div>
            </button>
            <div class="px-4 pb-3 hidden">
                <p class="text-gray-600 text-sm">If you forget your password, use the "Forgot Password" link on the login page. You'll receive an email with instructions to reset your password.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Support -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h4 class="text-xl font-bold text-gray-900 mb-4">
        <i class="fa fa-envelope mr-2 text-gray-600"></i>Contact Support Team
    </h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h6 class="font-medium text-gray-900 mb-3">Support Channels</h6>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-envelope text-blue-600"></i>
                    <span class="text-gray-700">support@globalrize.com</span>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-phone text-green-600"></i>
                    <span class="text-gray-700">+1 (555) 123-4567</span>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-clock text-purple-600"></i>
                    <span class="text-gray-700">Mon-Fri 9AM-6PM EST</span>
                </div>
            </div>
        </div>
        
        <div>
            <h6 class="font-medium text-gray-900 mb-3">Response Time</h6>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Email Support:</span>
                    <span class="font-medium">Within 24 hours</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phone Support:</span>
                    <span class="font-medium">Immediate</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Urgent Issues:</span>
                    <span class="font-medium">Within 4 hours</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
            <i class="fa-solid fa-envelope mr-2"></i>Send Support Request
        </button>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection
