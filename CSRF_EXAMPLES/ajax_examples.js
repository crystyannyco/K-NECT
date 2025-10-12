/**
 * EXAMPLE: AJAX Requests with CSRF Protection
 * Add this to your JavaScript files
 */

// ============================================
// Method 1: Using Fetch API with CSRF Token
// ============================================

function approveUser(userId) {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');
    
    fetch(`${baseUrl}/approved/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User approved successfully');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// ============================================
// Method 2: Using jQuery AJAX with CSRF Token
// ============================================

// Set up CSRF token globally for all jQuery AJAX requests
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_test_name"]').attr('content')
        }
    });
});

// Example: Delete event
function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) {
        return;
    }
    
    $.ajax({
        url: `${baseUrl}/events/delete/${eventId}`,
        type: 'POST',
        data: {
            event_id: eventId
        },
        success: function(response) {
            if (response.success) {
                alert('Event deleted successfully');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            alert('Error deleting event: ' + error);
        }
    });
}

// Example: Bulk delete events
function bulkDeleteEvents() {
    const selectedIds = [];
    $('input[name="event_ids[]"]:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) {
        alert('Please select at least one event');
        return;
    }
    
    if (!confirm(`Delete ${selectedIds.length} event(s)?`)) {
        return;
    }
    
    $.ajax({
        url: `${baseUrl}/events/bulk_delete`,
        type: 'POST',
        data: {
            event_ids: selectedIds
        },
        success: function(response) {
            if (response.success) {
                alert('Events deleted successfully');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
}

// ============================================
// Method 3: Using FormData with File Upload
// ============================================

function uploadDocument() {
    const form = document.getElementById('uploadForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');
    
    fetch(`${baseUrl}/admin/documents/upload`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
            // Don't set Content-Type for FormData, browser will set it with boundary
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Document uploaded successfully');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Upload failed. Please try again.');
    });
}

// ============================================
// Method 4: Dynamic Form Submission with CSRF
// ============================================

function createDynamicForm(action, data, method = 'POST') {
    const form = document.createElement('form');
    form.method = method;
    form.action = action;
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');
    const csrfName = document.querySelector('meta[name="csrf_test_name"]').getAttribute('name');
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = csrfName;
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Add other form data
    for (const key in data) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}

// Usage
function shareDocument(docId, username) {
    createDynamicForm(`${baseUrl}/admin/documents/share/${docId}`, {
        shared_with: username,
        permission: 'view',
        expires_at: '2025-12-31'
    });
}

// ============================================
// Method 5: Axios with CSRF Token
// ============================================

// Configure Axios defaults
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');

// Example usage
async function updateUserStatus(userId, status) {
    try {
        const response = await axios.post(`${baseUrl}/user-status/update`, {
            user_id: userId,
            status: status
        });
        
        if (response.data.success) {
            alert('Status updated successfully');
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update status');
    }
}

// ============================================
// Method 6: Handling Token Regeneration
// ============================================

// If token regeneration is enabled, update token after each request
function makeAjaxRequestWithTokenUpdate(url, data) {
    const csrfToken = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        // Update CSRF token if server sends new one
        if (data.csrf_token) {
            document.querySelector('meta[name="csrf_test_name"]').setAttribute('content', data.csrf_token);
        }
        
        // Handle response
        if (data.success) {
            console.log('Request successful');
        }
    });
}

// ============================================
// Method 7: Attendance RFID Processing
// ============================================

function processAttendance(eventId, rfidCode, session) {
    const csrfToken = document.querySelector('meta[name="csrf_test_name"]').getAttribute('content');
    
    $.ajax({
        url: `${baseUrl}/sk/processAttendance`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            event_id: eventId,
            rfid_code: rfidCode,
            session: session,
            action: 'time-in'
        },
        success: function(response) {
            if (response.success) {
                console.log('Attendance recorded:', response.user_name);
                // Update UI
                updateAttendanceDisplay(response);
            } else {
                console.error('Error:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}

// ============================================
// Method 8: Error Handling for CSRF Failures
// ============================================

// Global AJAX error handler
$(document).ajaxError(function(event, xhr, settings, error) {
    if (xhr.status === 403) {
        // CSRF token likely invalid or expired
        alert('Your session has expired. Please refresh the page and try again.');
        // Optional: Redirect to login or refresh page
        // location.reload();
    }
});

// With Fetch API
function handleFetchError(response) {
    if (!response.ok) {
        if (response.status === 403) {
            alert('Security token expired. Please refresh the page.');
            return Promise.reject('CSRF token expired');
        }
        return Promise.reject('Request failed');
    }
    return response.json();
}

// Usage
fetch(`${baseUrl}/your-endpoint`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf_test_name"]').content
    },
    body: JSON.stringify(data)
})
.then(handleFetchError)
.then(data => {
    console.log('Success:', data);
})
.catch(error => {
    console.error('Error:', error);
});

// ============================================
// Helper Functions
// ============================================

// Get current CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf_test_name"]')?.getAttribute('content') || '';
}

// Get CSRF token name
function getCsrfTokenName() {
    return document.querySelector('meta[name="csrf_test_name"]')?.getAttribute('name') || 'csrf_test_name';
}

// Refresh CSRF token (call after page updates)
function refreshCsrfToken() {
    fetch(`${baseUrl}/get-csrf-token`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            document.querySelector('meta[name="csrf_test_name"]').setAttribute('content', data.token);
        }
    });
}
