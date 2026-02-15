<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #7c3aed;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #7c3aed;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #6d28d9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .link {
            word-break: break-all;
            color: #7c3aed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Invitation</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>You have been invited to become an administrator for our platform.</p>
            
            <p>To accept this invitation and create your admin account, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $invitationLink }}" class="button">Accept Invitation</a>
            </div>
            
            <p>Or copy and paste this link into your browser:</p>
            <p class="link">{{ $invitationLink }}</p>
            
            <p><strong>Important:</strong> This invitation link will expire on {{ $invitation->expires_at->format('F d, Y \a\t g:i A') }}.</p>
            
            <p>If you did not expect this invitation, you can safely ignore this email.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

