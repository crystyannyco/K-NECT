<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Chairperson Appointment - K-NECT</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .success-icon {
            width: 70px;
            height: 70px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #3b82f6;
            font-weight: bold;
        }
        .header h1 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.95;
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
        .credentials-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }
        .credentials-box h3:before {
            content: "🔑";
            display: inline-flex;
            width: 24px;
            height: 24px;
            background-color: #3b82f6;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 14px;
            line-height: 1;
        }
        .credentials-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
        }
        .credentials-table tr {
            border-bottom: 1px solid #e0e7ff;
        }
        .credentials-table tr:last-child {
            border-bottom: none;
        }
        .credentials-table td {
            padding: 14px 12px;
            font-size: 14px;
        }
        .credentials-table td:first-child {
            font-weight: bold;
            color: #1e40af;
            width: 35%;
        }
        .credentials-table td:last-child {
            color: #1f2937;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            font-size: 13px;
            color: #92400e;
            line-height: 1.6;
            margin: 0;
        }
        .info-box strong {
            display: block;
            margin-bottom: 8px;
            color: #78350f;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
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
            color: #3b82f6;
            text-decoration: none;
        }
        .highlight {
            color: #2563eb;
            font-weight: bold;
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
            .credentials-box {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>SK Chairperson Appointment</h1>
            <p>K-NECT Youth Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Congratulations, <?= esc($userName) ?>!
            </div>

            <div class="message">
                <p>We are excited to inform you that you have been <strong class="highlight">appointed as an SK Chairperson</strong><?php if (!empty($barangayName)): ?> for <strong class="highlight">Barangay <?= esc($barangayName) ?></strong><?php endif; ?>!</p>
            </div>

            <div class="message">
                <p>Your K-NECT account has been upgraded with SK Chairperson privileges. You now have access to enhanced features including event management, member oversight, and administrative tools.</p>
            </div>

            <?php if (!empty($skUsername) && !empty($skPassword)): ?>
            <div class="credentials-box">
                <h3>Your SK Login Credentials</h3>
                <table class="credentials-table">
                    <?php if (!empty($userId)): ?>
                    <tr>
                        <td>User ID</td>
                        <td><?= esc($userId) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>SK Username</td>
                        <td><?= esc($skUsername) ?></td>
                    </tr>
                    <tr>
                        <td>SK Password</td>
                        <td><?= esc($skPassword) ?></td>
                    </tr>
                    <?php if (!empty($barangayName)): ?>
                    <tr>
                        <td>Barangay</td>
                        <td><?= esc($barangayName) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>⚠️ Important Security Reminders:</strong></p>
                <p>
                    • Keep your SK credentials confidential and secure<br>
                    • Change your password after your first login<br>
                    • Never share your credentials with anyone<br>
                    • Log out from shared devices after use
                </p>
            </div>

            <div class="message">
                <p><strong>As an SK Chairperson, you can now:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li style="margin-bottom: 8px;">Create and manage SK events and activities</li>
                    <li style="margin-bottom: 8px;">Approve and verify KK member registrations</li>
                    <li style="margin-bottom: 8px;">Access administrative dashboard and reports</li>
                    <li style="margin-bottom: 8px;">Coordinate with Pederasyon officers</li>
                    <li style="margin-bottom: 8px;">Manage your barangay's youth programs</li>
                </ul>
            </div>

            <div class="button-container">
                <a href="<?= base_url('login') ?>" class="cta-button" style="color: #ffffff;">
                    Login to K-NECT
                </a>
            </div>

            <div class="message">
                <p>If you have any questions or need assistance with your new role, please contact the Pederasyon office or reach out to our support team.</p>
            </div>

            <div class="message">
                <p><strong>Welcome to SK leadership! We look forward to working with you.</strong></p>
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
