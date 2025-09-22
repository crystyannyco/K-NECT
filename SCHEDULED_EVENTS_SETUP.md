# K-NECT Scheduled Events Setup for Hostinger

This guide explains how to set up automated event publishing for K-NECT on Hostinger web hosting.

## Overview

The K-NECT application already includes:
- ✅ Event scheduling functionality in the UI
- ✅ `PublishScheduledEventsCommand.php` for processing scheduled events
- ✅ Database fields for scheduled publishing (`scheduled_publish_datetime`, `scheduling_enabled`)
- ✅ Status management (Draft → Scheduled → Published)
- ✅ Google Calendar integration
- ✅ SMS notifications

## New Components Added

1. **Cron Job Script** (`cron_publish_events.php`)
2. **Shell Script** (`cron_publish_events.sh`)  
3. **API Controller** (`CronController.php`)
4. **Secure HTTP Endpoints** for external cron services

## Hostinger Setup Instructions

### Method 1: Using Hostinger's Cron Jobs (Recommended)

1. **Login to your Hostinger cPanel**
2. **Navigate to "Cron Jobs"** section
3. **Create a new cron job** with these settings:
   - **Command**: `/usr/local/bin/php /home/yourdomain/public_html/cron_publish_events.php`
   - **Schedule**: `* * * * *` (every minute)
   - **Email notifications**: Optional (recommended for debugging)

   Replace `/home/yourdomain/public_html/` with your actual domain path.

4. **Alternative Shell Script Method**:
   ```bash
   Command: /bin/bash /home/yourdomain/public_html/cron_publish_events.sh
   Schedule: * * * * *
   ```

### Method 2: Using External Cron Service (Backup Option)

If Hostinger's cron jobs don't work, use external services like:
- cron-job.org
- EasyCron
- Crontab.guru

**Setup**:
1. **Add a secret token to your environment**:
   - Edit `.env` file and add: `CRON_SECRET_TOKEN=your-very-secure-random-token-here`
   
2. **Create external cron job** pointing to:
   ```
   https://yourdomain.com/cron/publish-events?token=your-very-secure-random-token-here
   ```
   
3. **Set schedule**: Every minute (`* * * * *`)

### Method 3: Using Webhooks or Server Monitoring

You can also trigger the endpoint using:
- Uptime monitoring services (UptimeRobot, Pingdom)
- Webhook services (Zapier, IFTTT)
- Server monitoring tools

## Environment Configuration

### 1. Update your `.env` file:
```env
# Add this line for cron job security
CRON_SECRET_TOKEN=your-very-secure-random-token-here

# Ensure these are set for proper timezone handling
app.timezone = 'Asia/Manila'

# Database configuration should already be set
database.default.hostname = your-db-host
database.default.database = your-db-name
database.default.username = your-db-user
database.default.password = your-db-password
```

### 2. File Permissions
Make sure the cron script is executable:
```bash
chmod +x cron_publish_events.sh
```

## Monitoring and Debugging

### 1. Check Logs
Monitor these files for debugging:
- `writable/logs/log-YYYY-MM-DD.log` (Application logs)
- `logs/cron_publish_events.log` (Cron-specific logs)
- Hostinger cPanel → Error Logs

### 2. Health Check Endpoint
Visit: `https://yourdomain.com/cron/health`

This will show:
- Number of overdue events (should be 0)
- Number of upcoming scheduled events
- System health status

### 3. Test the Setup
1. **Create a test event** with scheduling enabled
2. **Set publish time** to 2-3 minutes in the future
3. **Save as "Scheduled"**
4. **Wait and check** if it gets published automatically

## How It Works

### 1. Event Creation Flow
```
User creates event → 
Sets "Schedule for later" → 
Selects date/time → 
Clicks "Schedule Event" → 
Status: "Scheduled"
```

### 2. Automated Publishing Flow
```
Cron runs every minute →
Finds events with status="Scheduled" AND scheduled_publish_datetime <= NOW →
Updates status to "Publishing" →
Syncs to Google Calendar →
Sends SMS notifications (if enabled) →
Updates status to "Published" →
Sets publish_date
```

### 3. Database Status Flow
```
Draft → Scheduled → Publishing → Published
     ↘ (immediate) → Published
```

## Testing Commands

### Manual Testing (via SSH if available)
```bash
# Test the cron script manually
php cron_publish_events.php

# Test the CodeIgniter command directly
php spark events:publish-scheduled

# Check for scheduled events in database
mysql -u username -p -e "SELECT event_id, title, status, scheduled_publish_datetime FROM event WHERE status='Scheduled';"
```

### API Testing
```bash
# Test the API endpoint (replace with your token)
curl "https://yourdomain.com/cron/publish-events?token=your-secret-token"

# Check health status
curl "https://yourdomain.com/cron/health"
```

## Troubleshooting

### Common Issues:

1. **Cron job not running**:
   - Check Hostinger cPanel → Cron Jobs status
   - Verify file paths are correct
   - Check file permissions

2. **PHP path issues**:
   - Try `/usr/bin/php` instead of `/usr/local/bin/php`
   - Contact Hostinger support for the correct PHP path

3. **Events not publishing**:
   - Check application logs in `writable/logs/`
   - Verify database connectivity
   - Test Google Calendar API credentials

4. **Time zone issues**:
   - Ensure `.env` has correct timezone: `app.timezone = 'Asia/Manila'`
   - Check server time vs. your local time

5. **Permission errors**:
   ```bash
   chmod 755 cron_publish_events.php
   chmod +x cron_publish_events.sh
   ```

### Contact Support
If issues persist:
1. Check Hostinger's documentation on cron jobs
2. Contact Hostinger support for PHP path and cron job setup
3. Check the health endpoint for detailed error messages

## Security Notes

- The cron endpoints are secured with a secret token
- Logs contain IP addresses of unauthorized access attempts  
- The system validates all scheduled times are in the future
- Failed events are marked with "Failed" status for review

## Benefits of This Setup

- ✅ **Fully automated** - No manual intervention required
- ✅ **Reliable** - Runs every minute, catches events immediately when due
- ✅ **Secure** - Token-based authentication for external access
- ✅ **Monitored** - Comprehensive logging and health checks
- ✅ **Flexible** - Multiple deployment methods available
- ✅ **Integrated** - Works with existing Google Calendar and SMS systems