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
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }
        .otp-container {
            background-color: #ecf0f1;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #e74c3c;
            letter-spacing: 5px;
        }
        p {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your OTP Code</h1>
        <div class="otp-container">
            <p>Your One-Time Password (OTP) is:</p>
            <div class="otp-code">{{ $otp }}</div>
        </div>
        <p>This OTP will expire in <strong>10 minutes</strong>.</p>
        <p>If you didn't request this code, please ignore this email.</p>
        <div class="footer">
            <p>This is an automated message, please do not reply.</p>
            <p>&copy; 2024 Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>