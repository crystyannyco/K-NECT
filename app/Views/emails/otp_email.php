<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP - K-NECT</title>
    <style>
        body, .email-container, .header, .content, .greeting, .message, .otp-container, .otp-label, .otp-code, .otp-expiry, .warning-box, .info-box, .footer {
            font-family: Arial, 'Helvetica Neue', Helvetica, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4, h5, h6, p, a, span, div, strong, em, li {
            font-family: inherit;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 15px;
            line-height: 1.7;
            color: #4b5563;
            margin-bottom: 30px;
        }
        .otp-container {
            background-color: #eff6ff;
            border: 2px dashed #2563eb;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            color: #1e3a8a;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .otp-expiry {
            font-size: 13px;
            color: #6b7280;
            margin-top: 15px;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
            line-height: 1.6;
        }
        .warning-box strong {
            color: #78350f;
        }
        .info-box {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-box p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
        }
        .info-box p:last-child {
            margin-bottom: 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #6b7280;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 30px 20px;
            }
            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container" style="font-family: Arial, 'Helvetica Neue', Helvetica, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;">
        <!-- Header -->
    <div class="header" style="font-family: inherit;">
            <h1>Password Reset OTP</h1>
            <p>Verification Code for Your Account</p>
        </div>

        <!-- Content -->
    <div class="content" style="font-family: inherit;">
            <div class="greeting" style="font-family: inherit;">
                Hello, <?= esc($userName) ?>!
            </div>

            <div class="message">
                <p>We received a request to reset the password for your <strong><?= esc($accountTypeLabel) ?></strong> account. To proceed with the password reset, please use the One-Time Password (OTP) below:</p>
            </div>

            <!-- OTP Box -->
            <div class="otp-container" style="font-family: inherit;">
                <div class="otp-label">Your OTP Code</div>
                <div class="otp-code" style="font-family: inherit;"><?= esc($otp) ?></div>
                <div class="otp-expiry">⏱️ This code will expire in <strong>5 minutes</strong></div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p><strong>⚠️ Security Warning:</strong> Do NOT share this code with anyone. K-NECT staff will never ask for your OTP code. If you didn't request this password reset, please ignore this email and your password will remain unchanged.</p>
            </div>

            <!-- Information Box -->
            <div class="info-box">
                <p><strong>📋 What to do next:</strong></p>
                <p>1. Return to the password reset page</p>
                <p>2. Enter the 6-digit OTP code shown above</p>
                <p>3. Create your new password</p>
                <p>4. Log in with your new credentials</p>
            </div>

            <div class="message">
                <p>If you encounter any issues or did not request this password reset, please contact our support team immediately.</p>
            </div>
        </div>

        <!-- Footer -->
    <div class="footer" style="font-family: inherit;">
            <p><strong>K-NECT: A Youth Governance System</strong></p>
            <p>Kabataan Connect - Connecting Youth, Building Communities</p>
            <p style="margin-top: 15px;">
                <a href="<?= base_url() ?>">Visit K-NECT</a> | 
                <a href="<?= base_url('contact') ?>">Contact Support</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                © <?= date('Y') ?> K-NECT. All rights reserved.<br>
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
