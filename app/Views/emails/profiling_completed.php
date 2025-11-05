<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiling Completed - K-NECT</title>
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
            background-color: #3b82f6;
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
            color: #3b82f6;
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
        .credentials-box {
            background-color: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            font-size: 15px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .credentials-box h3:before {
            content: "ℹ";
            display: inline-flex;
            width: 20px;
            height: 20px;
            background-color: #3b82f6;
            color: #ffffff;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 14px;
            line-height: 1;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 15px;
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
        .pending-status {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .pending-status p {
            font-size: 14px;
            color: #92400e;
            font-weight: bold;
            margin: 0;
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
            <h1>Profiling Completed!</h1>
            <p>K-NECT Youth Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Thank you, <?= esc($userName) ?>!
            </div>

            <div class="message">
                <p>We are pleased to inform you that your <strong>K-NECT profiling</strong> has been <strong>successfully completed</strong>.</p>
            </div>

            <div class="message">
                <p>Your account registration information has been submitted and is currently being reviewed by the SK Officials for approval.</p>
            </div>

            <?php if (!empty($username)): ?>
            <div class="credentials-box">
                <h3>Your Submitted Information</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #dbeafe;">
                        <td style="padding: 12px 0; font-weight: bold; color: #1e40af; font-size: 13px; width: 35%;">Username</td>
                        <td style="padding: 12px 0; color: #1e3a8a; font-size: 14px; font-weight: bold;"><?= esc($username) ?></td>
                    </tr>
                    <?php if (!empty($barangayName)): ?>
                    <tr>
                        <td style="padding: 12px 0; font-weight: bold; color: #1e40af; font-size: 13px;">Barangay</td>
                        <td style="padding: 12px 0; color: #1e3a8a; font-size: 14px; font-weight: bold;"><?= esc($barangayName) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <?php endif; ?>

            <div class="pending-status">
                <p>⏳ Your account is currently PENDING APPROVAL</p>
            </div>

            <div class="info-box">
                <p><strong>What happens next?</strong></p>
                <p>
                    1. Your application will be reviewed by SK Officials<br>
                    2. This process typically takes 1-3 business days<br>
                    3. You will receive an email and SMS notification once your account is approved<br>
                    4. After approval, you can log in and access all K-NECT features
                </p>
            </div>

            <div class="message">
                <p><strong>Important Reminders:</strong></p>
                <ul style="margin-left: 20px; line-height: 1.8;">
                    <li>Keep your login credentials safe and secure</li>
                    <li>Check your email regularly for approval updates</li>
                    <li>Contact your barangay SK office if you have questions</li>
                </ul>
            </div>

            <div class="message">
                <p>If you have any questions or concerns about your registration, please feel free to reach out to us.</p>
            </div>

            <div class="message">
                <p><strong>We look forward to welcoming you to the K-NECT community!</strong></p>
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
