#!/bin/bash

# K-NECT Scheduled Events Setup Script for Hostinger
# Run this script after uploading your files to set up automated event publishing

echo "========================================="
echo "K-NECT Scheduled Events Setup"
echo "========================================="

# Check if running on the correct server
if [ ! -f "spark" ]; then
    echo "❌ Error: This script must be run from the K-NECT root directory"
    echo "   Make sure you're in the directory containing the 'spark' file"
    exit 1
fi

echo "✅ Found K-NECT installation"

# Set file permissions
echo "🔧 Setting file permissions..."
chmod 755 cron_publish_events.php 2>/dev/null || echo "⚠️  Could not set permissions for cron_publish_events.php"
chmod +x cron_publish_events.sh 2>/dev/null || echo "⚠️  Could not set permissions for cron_publish_events.sh"

# Create logs directory if it doesn't exist
if [ ! -d "logs" ]; then
    echo "📁 Creating logs directory..."
    mkdir -p logs
    chmod 755 logs
fi

# Create a sample .env entry for cron token
echo "🔐 Generating secure token for cron jobs..."
RANDOM_TOKEN=$(openssl rand -hex 32 2>/dev/null || head /dev/urandom | tr -dc A-Za-z0-9 | head -c 32)

if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp env .env 2>/dev/null || touch .env
fi

# Check if CRON_SECRET_TOKEN already exists
if ! grep -q "CRON_SECRET_TOKEN" .env; then
    echo "🔑 Adding CRON_SECRET_TOKEN to .env file..."
    echo "" >> .env
    echo "# Cron job security token" >> .env
    echo "CRON_SECRET_TOKEN=$RANDOM_TOKEN" >> .env
    echo "✅ Token added to .env file"
else
    echo "✅ CRON_SECRET_TOKEN already exists in .env file"
fi

# Test the setup
echo ""
echo "🧪 Testing the setup..."

# Test PHP command
echo "Testing PHP access..."
if php -v > /dev/null 2>&1; then
    echo "✅ PHP is accessible"
    PHP_VERSION=$(php -v | head -n 1)
    echo "   Version: $PHP_VERSION"
else
    echo "⚠️  PHP command not found or not accessible"
    echo "   You may need to use full path like /usr/local/bin/php or /usr/bin/php"
fi

# Test the cron script
echo ""
echo "Testing cron script..."
if php cron_publish_events.php > /dev/null 2>&1; then
    echo "✅ Cron script executed successfully"
else
    echo "⚠️  Cron script test failed"
    echo "   Check your database configuration and file permissions"
fi

# Display setup information
echo ""
echo "========================================="
echo "✅ Setup Complete!"
echo "========================================="
echo ""
echo "Next Steps:"
echo ""
echo "1. 🏠 LOGIN TO HOSTINGER CPANEL"
echo "   Go to your Hostinger control panel"
echo ""
echo "2. 🕐 SET UP CRON JOB"
echo "   Navigate to 'Cron Jobs' section and create a new job:"
echo ""
echo "   Command: php $(pwd)/cron_publish_events.php"
echo "   Schedule: * * * * * (every minute)"
echo ""
echo "   Alternative command if needed:"
echo "   /usr/local/bin/php $(pwd)/cron_publish_events.php"
echo ""
echo "3. 🔒 SECURE TOKEN (if using external cron service):"
echo "   CRON_SECRET_TOKEN=$RANDOM_TOKEN"
echo ""
echo "   External URL:"
echo "   https://yourdomain.com/cron/publish-events?token=$RANDOM_TOKEN"
echo ""
echo "4. 📊 MONITOR HEALTH"
echo "   Visit: https://yourdomain.com/cron/health"
echo ""
echo "5. 📖 READ DOCUMENTATION"
echo "   See: SCHEDULED_EVENTS_SETUP.md for detailed instructions"
echo ""
echo "========================================="
echo "🎉 Your scheduled events system is ready!"
echo "========================================="

# Create a simple test script
cat > test_scheduled_events.php << 'EOF'
<?php
// Quick test script to verify scheduled events functionality
echo "Testing K-NECT Scheduled Events System\n";
echo "=====================================\n";

// Basic connectivity test
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer autoload found\n";
} else {
    echo "❌ Composer autoload not found\n";
    exit(1);
}

// Test environment
if (file_exists('.env')) {
    echo "✅ Environment file found\n";
} else {
    echo "⚠️  Environment file not found\n";
}

// Check for required directories
$dirs = ['writable', 'writable/logs', 'app/Commands'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ Directory exists: $dir\n";
    } else {
        echo "❌ Directory missing: $dir\n";
    }
}

// Check for required files
$files = [
    'app/Commands/PublishScheduledEventsCommand.php',
    'app/Controllers/CronController.php',
    'cron_publish_events.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ File exists: $file\n";
    } else {
        echo "❌ File missing: $file\n";
    }
}

echo "\n🧪 Run this test with: php test_scheduled_events.php\n";
EOF

echo "📝 Created test_scheduled_events.php for troubleshooting"
echo "   Run: php test_scheduled_events.php"
echo ""