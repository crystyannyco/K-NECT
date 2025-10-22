<?php
/**
 * K-NECT Scheduled Events Setup Script (PHP Version)
 * Run this script after uploading your files to set up automated event publishing
 */

echo "=========================================\n";
echo "K-NECT Scheduled Events Setup (PHP)\n";
echo "=========================================\n";

// Check if running from correct directory
if (!file_exists('spark')) {
    echo "❌ Error: This script must be run from the K-NECT root directory\n";
    echo "   Make sure you're in the directory containing the 'spark' file\n";
    exit(1);
}

echo "✅ Found K-NECT installation\n";

// Set file permissions
echo "🔧 Setting file permissions...\n";
chmod('cron_publish_events.php', 0755);
chmod('cron_publish_events.sh', 0755);

// Create logs directory if it doesn't exist
if (!is_dir('logs')) {
    echo "📁 Creating logs directory...\n";
    mkdir('logs', 0755, true);
}

// Generate secure token
echo "🔐 Generating secure token for cron jobs...\n";
$randomToken = bin2hex(random_bytes(32));

// Handle .env file
if (!file_exists('.env')) {
    echo "📝 Creating .env file...\n";
    if (file_exists('env')) {
        copy('env', '.env');
    } else {
        touch('.env');
    }
}

// Check if CRON_SECRET_TOKEN already exists
$envContent = file_get_contents('.env');
if (strpos($envContent, 'CRON_SECRET_TOKEN') === false) {
    echo "🔑 Adding CRON_SECRET_TOKEN to .env file...\n";
    file_put_contents('.env', "\n# Cron job security token\nCRON_SECRET_TOKEN=$randomToken\n", FILE_APPEND);
    echo "✅ Token added to .env file\n";
} else {
    echo "✅ CRON_SECRET_TOKEN already exists in .env file\n";
}

// Test the setup
echo "\n🧪 Testing the setup...\n";

// Test PHP version
echo "Testing PHP version...\n";
echo "✅ PHP Version: " . PHP_VERSION . "\n";

// Test required directories
echo "\nChecking directories...\n";
$dirs = ['writable', 'writable/logs', 'app/Commands', 'app/Controllers'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ Directory exists: $dir\n";
    } else {
        echo "❌ Directory missing: $dir\n";
    }
}

// Test required files
echo "\nChecking files...\n";
$files = [
    'app/Commands/PublishScheduledEventsCommand.php',
    'app/Controllers/CronController.php', 
    'cron_publish_events.php',
    'vendor/autoload.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ File exists: $file\n";
    } else {
        echo "❌ File missing: $file\n";
    }
}

// Test basic script execution
echo "\nTesting cron script execution...\n";
ob_start();
$output = '';
try {
    // Try to include and test basic functionality
    if (file_exists('cron_publish_events.php')) {
        // Just check syntax without executing
        $syntax = exec('php -l cron_publish_events.php 2>&1', $output);
        if (strpos(implode(' ', $output), 'No syntax errors') !== false) {
            echo "✅ Cron script syntax is valid\n";
        } else {
            echo "⚠️  Cron script syntax check failed\n";
            echo "   Output: " . implode("\n", $output) . "\n";
        }
    }
} catch (Exception $e) {
    echo "⚠️  Could not test cron script: " . $e->getMessage() . "\n";
}
ob_end_clean();

// Get current directory for instructions
$currentDir = getcwd();

// Display setup information
echo "\n=========================================\n";
echo "✅ Setup Complete!\n";
echo "=========================================\n\n";

echo "Next Steps:\n\n";

echo "1. 🏠 LOGIN TO HOSTINGER CPANEL\n";
echo "   Go to your Hostinger control panel\n\n";

echo "2. 🕐 SET UP CRON JOB\n";
echo "   Navigate to 'Cron Jobs' section and create a new job:\n\n";
echo "   Command: php $currentDir/cron_publish_events.php\n";
echo "   Schedule: * * * * * (every minute)\n\n";
echo "   Alternative commands if needed:\n";
echo "   /usr/local/bin/php $currentDir/cron_publish_events.php\n";
echo "   /usr/bin/php $currentDir/cron_publish_events.php\n\n";

echo "3. 🔒 SECURE TOKEN (if using external cron service):\n";
echo "   CRON_SECRET_TOKEN=$randomToken\n\n";
echo "   External URL:\n";
$domain = $_SERVER['HTTP_HOST'] ?? 'yourdomain.com';
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
echo "   $protocol://$domain/cron/publish-events?token=$randomToken\n\n";

echo "4. 📊 MONITOR HEALTH\n";
echo "   Visit: $protocol://$domain/cron/health\n\n";

echo "5. 📖 READ DOCUMENTATION\n";
echo "   See: SCHEDULED_EVENTS_SETUP.md for detailed instructions\n\n";

echo "=========================================\n";
echo "🎉 Your scheduled events system is ready!\n";
echo "=========================================\n\n";

// Create a test file
$testScript = '<?php
// Quick test script to verify scheduled events functionality
echo "Testing K-NECT Scheduled Events System\n";
echo "=====================================\n";

// Check current time and timezone
date_default_timezone_set("Asia/Manila");
echo "Current server time: " . date("Y-m-d H:i:s") . " (Asia/Manila)\n\n";

// Basic file checks
$files = [
    "vendor/autoload.php" => "Composer autoload",
    ".env" => "Environment file",
    "app/Commands/PublishScheduledEventsCommand.php" => "Publish command",
    "app/Controllers/CronController.php" => "Cron controller",
    "cron_publish_events.php" => "Cron script"
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $description: Found\n";
    } else {
        echo "❌ $description: Missing ($file)\n";
    }
}

// Check writable directories
$dirs = ["writable", "writable/logs", "logs"];
echo "\nDirectory permissions:\n";
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf("%o", fileperms($dir)), -4);
        $writable = is_writable($dir) ? "✅ Writable" : "❌ Not writable";
        echo "$writable: $dir (permissions: $perms)\n";
    } else {
        echo "❌ Missing: $dir\n";
    }
}

echo "\n🧪 Test completed. Run with: php test_scheduled_events.php\n";
';

file_put_contents('test_scheduled_events.php', $testScript);
echo "📝 Created test_scheduled_events.php for troubleshooting\n";
echo "   Run: php test_scheduled_events.php\n\n";

echo "🚀 Setup completed successfully!\n";
?>