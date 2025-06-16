<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic Link - {{ $appName }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #1F2937;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .magic-link-btn {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .magic-link-btn:hover {
            background-color: #4338CA;
        }
        .expiry-info {
            background-color: #FEF3C7;
            border: 1px solid #F59E0B;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .security-note {
            background-color: #EFF6FF;
            border: 1px solid #3B82F6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 14px;
            color: #6B7280;
        }
        .alternative-link {
            word-break: break-all;
            background-color: #F9FAFB;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $appName }}</div>
            <h1 class="title">🔐 Your Magic Link</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name ?? 'there' }},</p>
            
            <p>You requested a magic link to sign in to your {{ $appName }} account. Click the button below to sign in instantly:</p>
            
            <div style="text-align: center;">
                <a href="{{ $magicLink }}" class="magic-link-btn">
                    ✨ Sign In with Magic Link
                </a>
            </div>

            <div class="expiry-info">
                <strong>⏰ Important:</strong> This magic link will expire on <strong>{{ $expiresAt->format('M j, Y \a\t g:i A') }}</strong> 
                ({{ $expiresAt->diffForHumans() }}).
            </div>

            <div class="security-note">
                <strong>🔒 Security Note:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>This link can only be used once</li>
                    <li>It will expire in 15 minutes for your security</li>
                    <li>Don't share this link with anyone</li>
                    <li>If you didn't request this, you can safely ignore this email</li>
                </ul>
            </div>

            <p><strong>Can't click the button?</strong> Copy and paste this link into your browser:</p>
            <div class="alternative-link">{{ $magicLink }}</div>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $user->email ?? 'your email address' }}</p>
            <p>If you didn't request this magic link, please ignore this email or contact support.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
