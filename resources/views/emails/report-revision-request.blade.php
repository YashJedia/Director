<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Revision Requested</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #2c3e50;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Report Revision Requested</h1>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>⚠️ Action Required:</strong> Your report has been sent back for revision. Please review the feedback below and update your report accordingly.
            </div>
            
            <div class="section">
                <p>Dear {{ $report->user->name }},</p>
                
                <p>{{ $adminName }} has reviewed your report "{{ $report->title }}" ({{ $report->quarter }}) and has requested revisions.</p>
                
                <div class="label">Report Details:</div>
                <ul>
                    <li><strong>Report Title:</strong> {{ $report->title }}</li>
                    <li><strong>Quarter:</strong> {{ $report->quarter }}</li>
                    <li><strong>Language:</strong> {{ $report->language->name ?? 'N/A' }}</li>
                    <li><strong>Current Status:</strong> {{ ucwords(str_replace('_', ' ', $report->status)) }}</li>
                </ul>
                
                <div class="label">△ Revision Feedback:</div>
                <div style="background-color: #f0f0f0; padding: 15px; border-left: 4px solid #e74c3c; border-radius: 4px;">
                    {{ $revisionReason }}
                </div>
                
                <div class="label">Next Steps:</div>
                <p>Please log in to your account and navigate to your reports section to:</p>
                <ol>
                    <li>Review the feedback provided above</li>
                    <li>Make the necessary revisions to your report</li>
                    <li>Resubmit your updated report for review</li>
                </ol>
                
                <a href="{{ route('user.reports') }}" class="button">View Your Reports</a>
            </div>
            
            <div class="section">
                <p>If you have any questions about the revision feedback, please contact {{ $adminName }} directly or reach out to the support team.</p>
                
                <p>Best regards,<br>
                <strong>Report Management System</strong></p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
