<?php $isConnected = session('google_access_token') ? true : false; ?>
<?php $googleEmail = session('google_email'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Google Calendar Integration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-blue-700">Admin Google Calendar Integration</h1>
            <a href="/logout" class="bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>

    <?php if (!$isConnected): ?>
            <div class="text-center mb-8">
                <button 
                    onclick="window.location.href='/google-calendar/connect'" 
                    class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 text-lg"
                >
                    <i class="fab fa-google mr-2"></i>Sign in to Google Calendar
                </button>
                <p class="text-red-600 mt-4 text-lg">You must sign in to Google Calendar before adding events.</p>
            </div>
            
            <div class="flex justify-center">
                <iframe 
                    id="gcalFrame" 
                    src="https://calendar.google.com/calendar/embed?src=en.philippines%23holiday%40group.v.calendar.google.com&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
                    style="border: 0; opacity: 0; transition: opacity 0.3s ease;" 
                    width="800" 
                    height="600" 
                    frameborder="0" 
                    scrolling="no"
                    class="rounded-lg shadow-lg calendar-iframe"
                    loading="lazy"
                    onload="this.style.opacity=1"
                ></iframe>
        </div>
    <?php else: ?>
            <div class="text-center mb-8">
                <button 
                    onclick="openModal()" 
                    class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 text-lg"
                >
                    <i class="fas fa-plus mr-2"></i>Add Event
                </button>
            </div>
            
            <div class="flex justify-center">
                <iframe 
                    id="gcalFrame" 
                    src="https://calendar.google.com/calendar/embed?src=<?= urlencode($googleEmail) ?>&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
                    style="border: 0; opacity: 0; transition: opacity 0.3s ease;" 
                    width="800" 
                    height="600" 
                    frameborder="0" 
                    scrolling="no"
                    class="rounded-lg shadow-lg calendar-iframe"
                    loading="lazy"
                    onload="this.style.opacity=1"
                ></iframe>
        </div>
    <?php endif; ?>
    </div>

    <!-- Modal for adding event -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Add Event to Google Calendar</h3>
            <form id="addEventForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="title" 
                        required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Enter event title"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        name="description"
                        rows="3"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-vertical"
                        placeholder="Enter event description"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input 
                        type="text" 
                        name="location"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Enter event location"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                    <input 
                        type="datetime-local" 
                        name="start_datetime" 
                        required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                    <input 
                        type="datetime-local" 
                        name="end_datetime" 
                        required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    >
                </div>
                
                <div class="flex justify-end space-x-4 pt-4">
                    <button 
                        type="button" 
                        onclick="closeModal()"
                        class="bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button 
                        type="submit"
                        class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200"
                    >
                        <i class="fas fa-plus mr-2"></i>Add Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal logic
        function openModal() {
            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('addEventForm').reset();
            // Set minimum datetime for inputs when modal opens
            setMinimumDateTime();
        }
        
        function closeModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        // ===== DATE/TIME PICKER RESTRICTIONS =====
        // Function to get current date and time in local timezone
        function getCurrentDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Function to set minimum datetime for inputs
        function setMinimumDateTime() {
            const currentDateTime = getCurrentDateTime();
            
            // Set minimum for event start datetime
            const startDatetimeInput = document.querySelector('input[name="start_datetime"]');
            if (startDatetimeInput) {
                startDatetimeInput.min = currentDateTime;
                
                // Update end datetime minimum when start changes (without showing notifications)
                startDatetimeInput.addEventListener('change', function() {
                    const endDatetimeInput = document.querySelector('input[name="end_datetime"]');
                    if (endDatetimeInput && this.value) {
                        endDatetimeInput.min = this.value;
                    }
                });
            }
            
            // Set minimum for event end datetime
            const endDatetimeInput = document.querySelector('input[name="end_datetime"]');
            if (endDatetimeInput) {
                endDatetimeInput.min = currentDateTime;
            }
        }

        // Initialize restrictions when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setMinimumDateTime();
        });

        // RFC3339 conversion for Google Calendar
        function toRFC3339(localDateTime) {
            if (!localDateTime) return '';
            return localDateTime + ':00+08:00';
        }

        // Modal form submit
        document.getElementById('addEventForm').onsubmit = function(e) {
            e.preventDefault();
            var form = e.target;
            var data = {
                title: form.title.value,
                description: form.description.value,
                location: form.location.value,
                start_datetime: toRFC3339(form.start_datetime.value),
                end_datetime: toRFC3339(form.end_datetime.value)
            };
            
            fetch('/google-calendar/add-event', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(data)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showNotification('Event added to Google Calendar!', 'success');
                    closeModal();
                    document.getElementById('gcalFrame').src += '';
                } else {
                    showNotification('Failed to add event: ' + (typeof res.error === 'string' ? res.error : JSON.stringify(res.error)), 'error');
                }
            });
        };

        // Utility function to show notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            switch(type) {
                case 'success':
                    notification.className += ' bg-green-500 text-white';
                    break;
                case 'error':
                    notification.className += ' bg-red-500 text-white';
                    break;
                default:
                    notification.className += ' bg-blue-500 text-white';
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html> 