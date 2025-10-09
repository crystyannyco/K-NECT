<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved - K-NECT</title>
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
        .credentials-box {
            background-color: #f0fdf4;
            border: 1px solid #10b981;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            font-size: 15px;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .credentials-box h3:before {
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
            color: white;
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
            <h1>Account Approved!</h1>
            <p>K-NECT Youth Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Congratulations, <?= esc($userName) ?>!
            </div>

            <div class="message">
                <p>We are pleased to inform you that your <strong>K-NECT account</strong> has been successfully <strong>approved and verified</strong> by the SK Officials.</p>
            </div>

            <div class="message">
                <p>You are now an official member of <strong>Katipunan ng Kabataan (KK)</strong> and can access all the features and services available on the K-NECT platform.</p>
            </div>

            <?php if (!empty($userId)): ?>
            <div class="credentials-box">
                <h3>Your Account Information</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #d1fae5;">
                        <td style="padding: 12px 0; font-weight: bold; color: #047857; font-size: 13px; width: 35%;">User ID</td>
                        <td style="padding: 12px 0; color: #065f46; font-size: 14px; font-weight: bold;"><?= esc($userId) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #d1fae5;">
                        <td style="padding: 12px 0; font-weight: bold; color: #047857; font-size: 13px;">Username</td>
                        <td style="padding: 12px 0; color: #065f46; font-size: 14px; font-weight: bold;"><?= esc($username) ?></td>
                    </tr>
                    <?php if (!empty($barangayName)): ?>
                    <tr>
                        <td style="padding: 12px 0; font-weight: bold; color: #047857; font-size: 13px;">Barangay</td>
                        <td style="padding: 12px 0; color: #065f46; font-size: 14px; font-weight: bold;"><?= esc($barangayName) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>Next Steps:</strong></p>
                <p>
                    1. Log in to your K-NECT account using your credentials<br>
                    2. Complete your profile if you haven't already<br>
                    3. Explore upcoming events and activities<br>
                    4. Participate in youth programs and assemblies
                </p>
            </div>

            <div class="button-container">
                <a href="<?= base_url('login') ?>" class="cta-button" style="color: #ffffff;">
                    Login to K-NECT
                </a>
            </div>

            <div class="message">
                <p>If you have any questions or need assistance, please contact your barangay SK office or reach out to us.</p>
            </div>

            <div class="message">
                <p><strong>Welcome to the K-NECT community!</strong></p>
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
