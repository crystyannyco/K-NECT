<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration Update - K-NECT</title>
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
            background-color: #ef4444;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .info-icon {
            width: 60px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #ef4444;
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
        .reason-box {
            background-color: #fef2f2;
            border: 1px solid #ef4444;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .reason-box h3 {
            font-size: 15px;
            font-weight: bold;
            color: #991b1b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .reason-box h3:before {
            content: "!";
            display: inline-flex;
            width: 20px;
            height: 20px;
            background-color: #ef4444;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 12px;
            line-height: 1;
        }
        .reason-text {
            font-size: 14px;
            color: #7f1d1d;
            line-height: 1.6;
            background-color: #ffffff;
            padding: 12px;
            border-radius: 4px;
            border-left: 3px solid #ef4444;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 3px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            font-size: 13px;
            color: #1e40af;
            line-height: 1.6;
            margin: 0;
        }
        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }
        .info-box ul {
            margin: 8px 0 0 20px;
            padding: 0;
            color: #1e40af;
        }
        .info-box li {
            margin: 4px 0;
            font-size: 13px;
        }
        .success-box {
            background-color: #f0fdf4;
            border-left: 3px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success-box p {
            font-size: 13px;
            color: #065f46;
            line-height: 1.6;
            margin: 0;
        }
        .success-box strong {
            display: block;
            margin-bottom: 8px;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
        }
        .button-container {
            text-align: center;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .header {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Account Registration Update</h1>
            <p>K-NECT Youth Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <?= esc($userName) ?>,
            </div>

            <div class="message">
                <p>Thank you for your interest in joining the K-NECT Youth Management System.</p>
            </div>

            <div class="message">
                <p>After careful review of your application, we regret to inform you that your registration for <strong>Katipunan ng Kabataan (KK) Member</strong> could not be approved at this time.</p>
            </div>

            <?php if (!empty($reason)): ?>
            <div class="reason-box">
                <h3>Reason for Non-Approval</h3>
                <div class="reason-text">
                    <?= nl2br(esc($reason)) ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>Common Reasons for Non-Approval:</strong></p>
                <ul>
                    <li>Incomplete or unclear supporting documents</li>
                    <li>Information mismatch between profile and submitted documents</li>
                    <li>Age eligibility requirements not met (15-30 years old)</li>
                    <li>Duplicate registration or existing active account</li>
                    <li>Invalid or expired identification documents</li>
                    <li>Insufficient proof of barangay residency</li>
                </ul>
            </div>

            <div class="success-box">
                <p><strong>How to Re-Apply:</strong></p>
                <p>
                    You can resubmit your application with corrected information and proper documentation. Please follow these steps:
                </p>
                <p style="margin-top: 8px;">
                    <strong>Option 1:</strong> Login to your account with your existing credentials, then click the profile or re-upload button to update your information.
                </p>
                <p style="margin-top: 8px;">
                    <strong>Option 2:</strong> Click <a href="<?= base_url('profiling/reupload/' . $id) ?>" style="color: #065f46; text-decoration: underline; font-weight: bold;">here to re-upload your documents</a> directly.
                </p>
                <p style="margin-top: 8px;">
                    Ensure all required documents are clear, valid, and match your profile information.
                </p>
            </div>

            <div class="button-container">
                <a href="<?= base_url('login') ?>" class="cta-button" style="color: #ffffff;">
                    Login to Re-Apply
                </a>
            </div>

            <div class="message">
                <p><strong>Need Help?</strong></p>
                <p>
                    If you have questions about this decision or need clarification on the requirements, 
                    please visit your barangay SK office or contact us at 
                    <a href="mailto:knect.system@gmail.com" style="color: #2563eb;">knect.system@gmail.com</a>
                </p>
            </div>

            <div class="message">
                <p>We appreciate your understanding and look forward to your participation in our youth programs.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>K-NECT Youth Management System</strong></p>
            <p>Kabataang-Networking and Events Coordination Tool</p>
            <p>Iriga City, Camarines Sur, Philippines</p>
            <p style="margin-top: 10px;">
                <a href="<?= base_url() ?>">Visit Website</a> | 
                <a href="mailto:knect.system@gmail.com">Contact Support</a>
            </p>
            <p style="margin-top: 10px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
