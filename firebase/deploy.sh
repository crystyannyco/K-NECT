#!/bin/bash

# Deploy Firebase Functions
echo "Deploying Firebase Functions..."

# Set Firebase configuration
firebase functions:config:set database.host="your-database-host"
firebase functions:config:set database.user="your-database-user"
firebase functions:config:set database.password="your-database-password"
firebase functions:config:set database.name="k-nect"
firebase functions:config:set database.port="3306"

# Set Google Calendar API configuration
firebase functions:config:set google.api_key="your-google-api-key"
firebase functions:config:set google.client_id="your-google-client-id"
firebase functions:config:set google.client_secret="your-google-client-secret"

# Deploy functions
firebase deploy --only functions

echo "Firebase Functions deployed successfully!" 