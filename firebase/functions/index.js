const functions = require('firebase-functions');
const admin = require('firebase-admin');
const axios = require('axios');
const moment = require('moment-timezone');

// Initialize Firebase Admin
admin.initializeApp();

// Database configuration for your K-NECT application
const DB_CONFIG = {
  host: functions.config().database.host || 'localhost',
  user: functions.config().database.user || 'root',
  password: functions.config().database.password || '',
  database: functions.config().database.name || 'k-nect',
  port: functions.config().database.port || 3306
};

// Google Calendar API configuration
const GOOGLE_CALENDAR_API_KEY = functions.config().google.api_key;
const GOOGLE_CALENDAR_CLIENT_ID = functions.config().google.client_id;
const GOOGLE_CALENDAR_CLIENT_SECRET = functions.config().google.client_secret;

/**
 * Scheduled function that runs every minute to check for events that need to be published
 */
exports.checkScheduledEvents = functions.pubsub
  .schedule('every 1 minutes')
  .timeZone('Asia/Manila')
  .onRun(async (context) => {
    try {
      console.log('Checking for scheduled events to publish...');
      
      // Get current time in Philippine timezone
      const currentTime = moment().tz('Asia/Manila').format('YYYY-MM-DD HH:mm:ss');
      console.log('Current time (Philippines):', currentTime);
      
      // Find events that are scheduled and due for publishing
      const scheduledEvents = await getScheduledEvents(currentTime);
      
      if (scheduledEvents.length === 0) {
        console.log('No scheduled events to publish at this time.');
        return null;
      }
      
      console.log(`Found ${scheduledEvents.length} events to publish.`);
      
      // Process each scheduled event
      for (const event of scheduledEvents) {
        try {
          await publishScheduledEvent(event);
          console.log(`Successfully published event: ${event.title} (ID: ${event.event_id})`);
        } catch (error) {
          console.error(`Failed to publish event ${event.event_id}:`, error.message);
          // Update event status to failed
          await updateEventStatus(event.event_id, 'Failed');
        }
      }
      
      return null;
    } catch (error) {
      console.error('Error in checkScheduledEvents:', error);
      throw error;
    }
  });

/**
 * HTTP function to manually trigger event publishing (for testing)
 */
exports.manualPublishEvent = functions.https.onRequest(async (req, res) => {
  try {
    const { eventId } = req.body;
    
    if (!eventId) {
      return res.status(400).json({ error: 'Event ID is required' });
    }
    
    const event = await getEventById(eventId);
    if (!event) {
      return res.status(404).json({ error: 'Event not found' });
    }
    
    await publishScheduledEvent(event);
    
    res.json({ 
      success: true, 
      message: `Event ${event.title} published successfully` 
    });
  } catch (error) {
    console.error('Error in manualPublishEvent:', error);
    res.status(500).json({ error: error.message });
  }
});

/**
 * Get scheduled events that are due for publishing
 */
async function getScheduledEvents(currentTime) {
  const mysql = require('mysql2/promise');
  const connection = await mysql.createConnection(DB_CONFIG);
  
  try {
    const [rows] = await connection.execute(`
      SELECT * FROM event 
      WHERE status = 'Scheduled' 
      AND scheduling_enabled = 1 
      AND scheduled_publish_datetime <= ?
      ORDER BY scheduled_publish_datetime ASC
    `, [currentTime]);
    
    return rows;
  } finally {
    await connection.end();
  }
}

/**
 * Get event by ID
 */
async function getEventById(eventId) {
  const mysql = require('mysql2/promise');
  const connection = await mysql.createConnection(DB_CONFIG);
  
  try {
    const [rows] = await connection.execute(`
      SELECT * FROM event WHERE event_id = ?
    `, [eventId]);
    
    return rows[0] || null;
  } finally {
    await connection.end();
  }
}

/**
 * Publish a scheduled event
 */
async function publishScheduledEvent(event) {
  console.log(`Publishing event: ${event.title} (ID: ${event.event_id})`);
  
  try {
    // 1. Update event status to Publishing
    await updateEventStatus(event.event_id, 'Publishing');
    
    // 2. Sync to Google Calendar
    const googleEventId = await syncToGoogleCalendar(event);
    
    // 3. Send SMS notifications if enabled
    if (event.sms_notification_enabled) {
      await sendSmsNotifications(event);
    }
    
    // 4. Update event status to Published
    await updateEventStatus(event.event_id, 'Published', googleEventId);
    
    console.log(`Successfully published event: ${event.title}`);
  } catch (error) {
    console.error(`Failed to publish event ${event.event_id}:`, error);
    await updateEventStatus(event.event_id, 'Failed');
    throw error;
  }
}

/**
 * Update event status in database
 */
async function updateEventStatus(eventId, status, googleEventId = null) {
  const mysql = require('mysql2/promise');
  const connection = await mysql.createConnection(DB_CONFIG);
  
  try {
    const updateData = {
      status: status,
      updated_at: new Date()
    };
    
    if (status === 'Published') {
      updateData.publish_date = new Date();
    }
    
    if (googleEventId) {
      updateData.google_event_id = googleEventId;
    }
    
    const [result] = await connection.execute(`
      UPDATE event 
      SET status = ?, 
          publish_date = ?, 
          google_event_id = ?, 
          updated_at = ? 
      WHERE event_id = ?
    `, [
      updateData.status,
      updateData.publish_date,
      updateData.google_event_id,
      updateData.updated_at,
      eventId
    ]);
    
    console.log(`Updated event ${eventId} status to: ${status}`);
    return result;
  } finally {
    await connection.end();
  }
}

/**
 * Sync event to Google Calendar
 */
async function syncToGoogleCalendar(event) {
  try {
    // Get calendar ID based on barangay
    const calendarId = event.barangay_id == 0 
      ? 'knect.system@gmail.com' 
      : await getBarangayCalendarId(event.barangay_id);
    
    if (!calendarId) {
      throw new Error('No Google Calendar ID found for this barangay');
    }
    
    // Prepare event data for Google Calendar
    const eventData = {
      summary: event.title,
      description: event.description,
      location: event.location,
      start: {
        dateTime: moment(event.start_datetime).tz('Asia/Manila').format(),
        timeZone: 'Asia/Manila'
      },
      end: {
        dateTime: moment(event.end_datetime).tz('Asia/Manila').format(),
        timeZone: 'Asia/Manila'
      }
    };
    
    // If event already has a Google Event ID, update it
    if (event.google_event_id) {
      await updateGoogleCalendarEvent(calendarId, event.google_event_id, eventData);
      return event.google_event_id;
    } else {
      // Create new Google Calendar event
      const googleEventId = await createGoogleCalendarEvent(calendarId, eventData);
      return googleEventId;
    }
  } catch (error) {
    console.error('Error syncing to Google Calendar:', error);
    throw error;
  }
}

/**
 * Get barangay calendar ID
 */
async function getBarangayCalendarId(barangayId) {
  const mysql = require('mysql2/promise');
  const connection = await mysql.createConnection(DB_CONFIG);
  
  try {
    const [rows] = await connection.execute(`
      SELECT google_calendar_id FROM barangay WHERE barangay_id = ?
    `, [barangayId]);
    
    return rows[0]?.google_calendar_id || null;
  } finally {
    await connection.end();
  }
}

/**
 * Create Google Calendar event
 */
async function createGoogleCalendarEvent(calendarId, eventData) {
  // This would require Google Calendar API integration
  // For now, we'll return a mock ID
  console.log('Creating Google Calendar event:', eventData);
  return `mock_google_event_${Date.now()}`;
}

/**
 * Update Google Calendar event
 */
async function updateGoogleCalendarEvent(calendarId, eventId, eventData) {
  // This would require Google Calendar API integration
  console.log('Updating Google Calendar event:', eventId, eventData);
}

/**
 * Send SMS notifications
 */
async function sendSmsNotifications(event) {
  try {
    console.log(`Sending SMS notifications for event: ${event.title}`);
    
    // Get recipients based on event configuration
    const recipients = await getSmsRecipients(event);
    
    // Send SMS to each recipient
    for (const recipient of recipients) {
      await sendSms(recipient.phone_number, formatSmsMessage(event));
    }
    
    console.log(`Sent SMS notifications to ${recipients.length} recipients`);
  } catch (error) {
    console.error('Error sending SMS notifications:', error);
    // Don't throw error to prevent blocking the publishing process
  }
}

/**
 * Get SMS recipients
 */
async function getSmsRecipients(event) {
  const mysql = require('mysql2/promise');
  const connection = await mysql.createConnection(DB_CONFIG);
  
  try {
    let query = `
      SELECT DISTINCT u.phone_number, u.first_name, u.last_name
      FROM user u
      WHERE u.is_active = 1
    `;
    
    const params = [];
    
    // Handle recipient scope
    if (event.sms_recipient_scope === 'specific_barangays') {
      const barangayIds = JSON.parse(event.sms_recipient_barangays || '[]');
      if (barangayIds.length > 0) {
        query += ` AND u.barangay_id IN (${barangayIds.map(() => '?').join(',')})`;
        params.push(...barangayIds);
      }
    }
    
    // Handle recipient roles
    if (event.sms_recipient_roles) {
      const roles = JSON.parse(event.sms_recipient_roles);
      if (roles.length > 0) {
        const roleConditions = [];
        roles.forEach(role => {
          switch (role) {
            case 'all_pederasyon_officials':
              roleConditions.push("u.position LIKE '%Pederasyon%'");
              break;
            case 'pederasyon_officers':
              roleConditions.push("(u.position LIKE '%Pederasyon President%' OR u.position LIKE '%Pederasyon Vice President%' OR u.position LIKE '%Pederasyon Secretary%' OR u.position LIKE '%Pederasyon Treasurer%' OR u.position LIKE '%Pederasyon Auditor%' OR u.position LIKE '%Pederasyon Public Information Officer%' OR u.position LIKE '%Pederasyon Sergeant at Arms%')");
              break;
            case 'pederasyon_members':
              roleConditions.push("(u.position LIKE '%Pederasyon%' AND u.position NOT LIKE '%Pederasyon President%' AND u.position NOT LIKE '%Pederasyon Vice President%' AND u.position NOT LIKE '%Pederasyon Secretary%' AND u.position NOT LIKE '%Pederasyon Treasurer%' AND u.position NOT LIKE '%Pederasyon Auditor%' AND u.position NOT LIKE '%Pederasyon Public Information Officer%' AND u.position NOT LIKE '%Pederasyon Sergeant at Arms%')");
              break;
            case 'all_officials':
              roleConditions.push("u.position LIKE '%SK%'");
              break;
            case 'chairman':
              roleConditions.push("u.position LIKE '%Chairman%'");
              break;
            case 'secretary':
              roleConditions.push("u.position LIKE '%Secretary%'");
              break;
            case 'treasurer':
              roleConditions.push("u.position LIKE '%Treasurer%'");
              break;
            case 'kk_members':
              roleConditions.push("(u.position LIKE '%KK%' OR u.position LIKE '%Katipunan%')");
              break;
          }
        });
        
        if (roleConditions.length > 0) {
          query += ` AND (${roleConditions.join(' OR ')})`;
        }
      }
    }
    
    const [rows] = await connection.execute(query, params);
    return rows;
  } finally {
    await connection.end();
  }
}

/**
 * Format SMS message
 */
function formatSmsMessage(event) {
  return `K-NECT Event Update: "${event.title}" is now published! 
Date: ${moment(event.start_datetime).format('MMM DD, YYYY')}
Time: ${moment(event.start_datetime).format('hh:mm A')} - ${moment(event.end_datetime).format('hh:mm A')}
Location: ${event.location}`;
}

/**
 * Send SMS (mock implementation)
 */
async function sendSms(phoneNumber, message) {
  // This would integrate with your SMS service provider
  console.log(`Sending SMS to ${phoneNumber}: ${message}`);
  return true;
} 