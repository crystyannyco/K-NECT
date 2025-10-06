<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .email-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .account-type-badge {
            display: inline-block;
            background-color: #fbbf24;
            color: #92400e;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 15px 0;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #2563eb;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
            font-size: 16px;
        }
        .reset-button {
            display: inline-block;
            margin: 30px 0;
            padding: 14px 40px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }
        .reset-button:hover {
            background-color: #1d4ed8;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .security-notice {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-notice p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
        }
        .link-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            word-break: break-all;
        }
        .link-box a {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="<?= base_url('assets/images/K-Nect-Logo.png') ?>" alt="K-NECT Logo">
            <h1>K-NECT</h1>
            <p>Kabataan Connect - Youth Governance System</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Password Reset Request</h2>
            <p>Hello <?= esc($userName ?? 'User') ?>,</p>
            
            <?php
            // Define account type labels and colors
            $accountTypeLabel = 'KK (Katipunan ng Kabataan)';
            $accountTypeBadgeColor = '#fbbf24'; // yellow
            $accountTypeTextColor = '#92400e'; // dark yellow
            
            if (isset($accountType)) {
                switch ($accountType) {
                    case 'sk':
                        $accountTypeLabel = 'SK (Sangguniang Kabataan)';
                        $accountTypeBadgeColor = '#60a5fa'; // blue
                        $accountTypeTextColor = '#1e3a8a'; // dark blue
                        break;
                    case 'pederasyon':
                        $accountTypeLabel = 'Pederasyon';
                        $accountTypeBadgeColor = '#a78bfa'; // purple
                        $accountTypeTextColor = '#5b21b6'; // dark purple
                        break;
                    case 'kk':
                    default:
                        $accountTypeLabel = 'KK (Katipunan ng Kabataan)';
                        $accountTypeBadgeColor = '#fbbf24'; // yellow
                        $accountTypeTextColor = '#92400e'; // dark yellow
                        break;
                }
            }
            ?>
            
            <div style="text-align: center; margin: 20px 0;">
                <span style="display: inline-block; background-color: <?= $accountTypeBadgeColor ?>; color: <?= $accountTypeTextColor ?>; padding: 8px 20px; border-radius: 20px; font-weight: bold; font-size: 14px;">
                    <?= esc($accountTypeLabel) ?> Account
                </span>
            </div>
            
            <p>We received a request to reset your password for your <strong><?= esc($accountTypeLabel) ?></strong> account. If you made this request, click the button below to reset your password:</p>
            
            <div style="text-align: center;">
                <a href="<?= esc($resetLink) ?>" class="reset-button">Reset Your Password</a>
            </div>

            <div class="security-notice">
                <p><strong>⚠️ Security Notice:</strong> This link will expire in 30 minutes for your security.</p>
            </div>

            <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
            
            <div class="link-box">
                <a href="<?= esc($resetLink) ?>"><?= esc($resetLink) ?></a>
            </div>

            <p><strong>If you didn't request a password reset,</strong> please ignore this email. Your password will remain unchanged, and your account is secure.</p>

            <p>For security reasons:</p>
            <ul>
                <li>Never share this link with anyone</li>
                <li>K-NECT staff will never ask for your password</li>
                <li>This link can only be used once</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>K-NECT: A Youth Governance System</strong></p>
            <p>Iriga City, Philippines</p>
            <p>© 2025 K-NECT. All rights reserved.</p>
            <p style="margin-top: 15px;">
                If you have any questions, please contact your system administrator.
            </p>
        </div>
    </div>
</body>
</html>
