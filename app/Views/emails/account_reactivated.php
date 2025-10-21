<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Reactivated - K-NECT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #10b981;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .success-icon {
            width: 60px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #10b981;
            font-weight: bold;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #10b981;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-box h3 {
            font-size: 15px;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .info-box h3:before {
            content: "✓";
            display: inline-flex;
            width: 20px;
            height: 20px;
            background-color: #10b981;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 12px;
            line-height: 1;
        }
        .info-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #d1fae5;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #065f46;
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #047857;
            flex: 1;
        }
        .login-button {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #059669;
        }
        .center {
            text-align: center;
        }
        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box p {
            font-size: 13px;
            color: #92400e;
            margin: 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .footer a {
            color: #10b981;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Account Reactivated!</h1>
            <p>Your K-NECT account has been successfully reactivated</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello <?= esc($first_name) ?> <?= esc($last_name) ?>,
            </div>

            <div class="message">
                Great news! Your K-NECT account has been <strong>reactivated</strong> and you can now access the platform again.
            </div>

            <div class="info-box">
                <h3>Account Information</h3>
                <div class="info-item">
                    <div class="info-label">User ID:</div>
                    <div class="info-value"><?= esc($user_id) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= esc($email) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value"><strong>Active</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Reactivated On:</div>
                    <div class="info-value"><?= date('F j, Y g:i A') ?></div>
                </div>
            </div>

            <div class="message">
                You can now log in to your account using your existing credentials. All your previous data and information have been preserved.
            </div>

            <div class="center">
                <a href="<?= base_url('login') ?>" class="login-button">Login to K-NECT</a>
            </div>

            <div class="divider"></div>

            <div class="message">
                <strong>What's Next?</strong>
            </div>
            <div class="message">
                • You can access all K-NECT features and services<br>
                • Update your profile information if needed<br>
                • Participate in events and community activities<br>
                • Stay connected with your barangay youth community
            </div>

            <div class="warning-box">
                <p><strong>Note:</strong> If you did not request this reactivation or believe this was done in error, please contact your SK administrator immediately.</p>
            </div>

            <div class="divider"></div>

            <div class="message">
                If you need any assistance or have questions, please don't hesitate to contact your barangay SK office.
            </div>

            <div class="message">
                <strong>Welcome back to K-NECT!</strong><br>
                <em>Together, we build stronger communities.</em>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>K-NECT - Kabataan Connect</strong></p>
            <p>Youth Governance System for Iriga City</p>
            <p style="margin-top: 15px;">
                This is an automated message. Please do not reply to this email.
            </p>
            <p>
                Need help? Contact your SK office or visit <a href="<?= base_url() ?>">K-NECT Platform</a>
            </p>
        </div>
    </div>
</body>
</html>
