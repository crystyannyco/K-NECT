# Firebase Functions for K-NECT Event Publishing

This directory contains Firebase Cloud Functions that automatically publish scheduled events in the K-NECT system.

## Features

- **Automatic Event Publishing**: Scheduled events are automatically published at their scheduled date and time
- **Google Calendar Integration**: Events are synced to Google Calendar upon publishing
- **SMS Notifications**: Optional SMS notifications to recipients when events are published
- **Philippine Timezone Support**: All operations use Asia/Manila timezone
- **Error Handling**: Comprehensive error handling and logging

## Setup Instructions

### 1. Prerequisites

- Node.js 18 or higher
- Firebase CLI (`npm install -g firebase-tools`)
- Google Cloud Project with billing enabled
- MySQL database access

### 2. Firebase Project Setup

1. **Create a new Firebase project** (or use existing):
   ```bash
   firebase login
   firebase projects:create your-project-id
   ```

2. **Initialize Firebase Functions**:
   ```bash
   cd firebase
   firebase init functions
   ```

3. **Install dependencies**:
   ```bash
   cd functions
   npm install
   ```

### 3. Database Configuration

Set your database configuration using Firebase Functions config:

```bash
firebase functions:config:set database.host="your-database-host"
firebase functions:config:set database.user="your-database-user"
firebase functions:config:set database.password="your-database-password"
firebase functions:config:set database.name="k-nect"
firebase functions:config:set database.port="3306"
```

### 4. Google Calendar API Configuration

1. **Enable Google Calendar API** in your Google Cloud Console
2. **Create OAuth 2.0 credentials** for Google Calendar API
3. **Set the credentials**:

```bash
firebase functions:config:set google.api_key="your-google-api-key"
firebase functions:config:set google.client_id="your-google-client-id"
firebase functions:config:set google.client_secret="your-google-client-secret"
```

### 5. Deploy Functions

```bash
cd firebase
firebase deploy --only functions
```

### 6. Update K-NECT Application

1. **Update EventController.php** with your Firebase Functions URL
2. **Add the route** for manual publishing (already done)
3. **Test the integration**

## Functions Overview

### 1. `checkScheduledEvents` (Scheduled Function)
- **Trigger**: Runs every minute
- **Purpose**: Checks for events that need to be published
- **Actions**:
  - Finds events with status 'Scheduled' and due datetime
  - Updates status to 'Publishing'
  - Syncs to Google Calendar
  - Sends SMS notifications (if enabled)
  - Updates status to 'Published'

### 2. `manualPublishEvent` (HTTP Function)
- **Trigger**: HTTP POST request
- **Purpose**: Manually trigger event publishing (for testing)
- **Usage**: `POST /manualPublishEvent` with `eventId` in body

## Database Schema Requirements

The system expects the following fields in the `event` table:

```sql
- event_id (Primary Key)
- barangay_id (Foreign Key to barangay table)
- title (Event title)
- description (Event description)
- status (ENUM: 'Draft', 'Scheduled', 'Published', 'cancelled', 'postponed')
- publish_date (DateTime)
- start_datetime (DateTime)
- end_datetime (DateTime)
- location (String)
- event_banner (String - file path)
- category (ENUM)
- created_by (Foreign Key to user table)
- scheduling_enabled (Boolean)
- scheduled_publish_datetime (DateTime)
- sms_notification_enabled (Boolean)
- sms_recipient_scope (ENUM)
- sms_recipient_barangays (JSON)
- sms_recipient_roles (JSON)
```

## Testing

### 1. Test Manual Publishing

```bash
curl -X POST https://your-project-id.cloudfunctions.net/manualPublishEvent \
  -H "Content-Type: application/json" \
  -d '{"eventId": 32}'
```

### 2. Test Scheduled Publishing

1. Create a scheduled event with a future datetime
2. Wait for the scheduled time or manually trigger the function
3. Check the event status in the database

### 3. Monitor Logs

```bash
firebase functions:log
```

## Configuration

### Environment Variables

The following environment variables are set via Firebase Functions config:

- `database.host`: MySQL database host
- `database.user`: MySQL database user
- `database.password`: MySQL database password
- `database.name`: MySQL database name
- `database.port`: MySQL database port
- `google.api_key`: Google Calendar API key
- `google.client_id`: Google Calendar OAuth client ID
- `google.client_secret`: Google Calendar OAuth client secret

### Timezone Configuration

All datetime operations use the Asia/Manila timezone to ensure accurate scheduling for Philippine events.

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check database credentials in Firebase config
   - Ensure database is accessible from Firebase Functions

2. **Google Calendar Sync Failed**
   - Verify Google Calendar API credentials
   - Check calendar permissions

3. **SMS Notifications Not Sent**
   - Verify SMS service configuration
   - Check recipient phone numbers

### Logs

View function logs:
```bash
firebase functions:log --only checkScheduledEvents
firebase functions:log --only manualPublishEvent
```

## Security Considerations

1. **Database Access**: Use dedicated database user with minimal permissions
2. **API Keys**: Store sensitive credentials in Firebase Functions config
3. **HTTPS**: All HTTP functions require HTTPS in production
4. **Rate Limiting**: Consider implementing rate limiting for manual publish function

## Cost Optimization

1. **Function Timeout**: Set appropriate timeout values
2. **Memory Allocation**: Use minimal memory allocation for functions
3. **Scheduling**: Consider reducing check frequency during low-usage hours

## Support

For issues or questions:
1. Check Firebase Functions logs
2. Review database connectivity
3. Verify Google Calendar API setup
4. Test with manual publish function first 