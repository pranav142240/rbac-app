<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
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
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #007bff;
            background-color: #e7f3ff;
            padding: 15px 30px;
            border-radius: 8px;
            margin: 20px 0;
            display: inline-block;
        }
        .warning {
            color: #dc3545;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your OTP Code</h2>
        <p>Use the following code to complete your authentication:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>This code will expire in 10 minutes.</p>
        
        <div class="warning">
            <strong>Security Notice:</strong> Do not share this code with anyone. Our team will never ask for your OTP code.
        </div>
    </div>
</body>
</html>
