#!/bin/bash

# Alternative cron script for systems that prefer shell scripts
# Usage: Add to crontab with: * * * * * /home/yourdomain/public_html/cron_publish_events.sh

# Change to the application directory
cd "$(dirname "$0")"

# Run the PHP script
/usr/local/bin/php cron_publish_events.php >> logs/cron_publish_events.log 2>&1