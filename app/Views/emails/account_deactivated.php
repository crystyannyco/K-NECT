<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivation Notice - K-NECT</title>
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
            background-color: #f59e0b;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .warning-icon {
            width: 60px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #f59e0b;
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
        .deactivation-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .deactivation-box h3 {
            font-size: 15px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .deactivation-box h3:before {
            content: "ℹ";
            display: inline-flex;
            width: 20px;
            height: 20px;
            background-color: #f59e0b;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 14px;
            line-height: 1;
        }
        .reason-text {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
            background-color: #ffffff;
            padding: 12px;
            border-radius: 4px;
            border-left: 3px solid #f59e0b;
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
        .user-info-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .user-info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-info-box td {
            padding: 8px 0;
            font-size: 13px;
        }
        .user-info-box td:first-child {
            font-weight: bold;
            color: #6b7280;
            width: 35%;
        }
        .user-info-box td:last-child {
            color: #1f2937;
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
            <h1>Account Deactivation Notice</h1>
            <p>K-NECT Youth Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <?= esc($userName) ?>,
            </div>

            <div class="message">
                <p>We are writing to inform you that your <strong>K-NECT account</strong> has been <strong>deactivated</strong> by the SK Officials.</p>
            </div>

            <?php if (!empty($userId)): ?>
            <div class="user-info-box">
                <table>
                    <tr>
                        <td>User ID</td>
                        <td><strong><?= esc($userId) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Account Type</td>
                        <td><strong>Katipunan ng Kabataan (KK) Member</strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><strong style="color: #f59e0b;">Deactivated</strong></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>

            <?php if (!empty($reason)): ?>
            <div class="deactivation-box">
                <h3>Reason for Deactivation</h3>
                <div class="reason-text">
                    <?= nl2br(esc($reason)) ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>What This Means:</strong></p>
                <ul>
                    <li>You will no longer be able to log in to your K-NECT account</li>
                    <li>Your profile and data have been preserved in our system</li>
                    <li>You will not receive notifications about events and activities</li>
                    <li>You cannot participate in KK assemblies or programs through the platform</li>
                </ul>
            </div>

            <div class="message">
                <p><strong>Need to Reactivate Your Account?</strong></p>
                <p>
                    If you believe this deactivation was made in error or if you would like to discuss the possibility of reactivating your account, 
                    please contact your barangay SK office or reach out to us at 
                    <a href="mailto:knect.system@gmail.com" style="color: #2563eb;">knect.system@gmail.com</a>
                </p>
            </div>

            <div class="message">
                <p>
                    The SK Officials will review your case and determine if your account can be reactivated based on the circumstances 
                    and compliance with the youth program requirements.
                </p>
            </div>

            <div class="button-container">
                <a href="<?= base_url() ?>" class="cta-button" style="color: #ffffff;">
                    Visit K-NECT Website
                </a>
            </div>

            <div class="message">
                <p>We appreciate your understanding and past participation in our youth programs.</p>
                <p style="margin-top: 15px;">Thank you,<br><strong>K-NECT System Team</strong></p>
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
