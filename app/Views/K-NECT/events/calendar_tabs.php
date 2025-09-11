<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Calendars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, .font-sans, * {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji' !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-8">
        <h1 class="text-3xl font-bold text-blue-700 text-center mb-6">Event Calendars</h1>
        
        <div class="flex justify-center mb-6">
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                <?php foreach ($tabs as $i => $tab): ?>
                    <button 
                        class="<?= $i === 0 ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-gray-900' ?> px-4 py-2 rounded-md font-medium transition duration-200"
                        onclick="showCalendar('cal<?= $i ?>', this)"
                    >
                        <?= esc($tab['label']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php foreach ($tabs as $i => $tab): ?>
            <div id="cal<?= $i ?>" class="calendar-embed<?= $i === 0 ? ' active' : '' ?> mb-6">
                <div class="flex justify-center">
                    <iframe 
                        src="<?= $i === 0 ? 'https://calendar.google.com/calendar/embed?src=' . urlencode($tab['calendar_id']) . '&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF' : 'about:blank' ?>"
                        data-src="https://calendar.google.com/calendar/embed?src=<?= urlencode($tab['calendar_id']) ?>&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
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
                <?php if ($role === 'super_admin' || ($role === 'admin' && $i === 0)): ?>
                    <div class="text-center mt-4">
                        <button 
                            onclick="alert('Add Event: Use Google Calendar UI or integrate API')"
                            class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 mr-2"
                        >
                            <i class="fas fa-plus mr-2"></i>Add Event
                        </button>
                        <button 
                            onclick="alert('Delete Event: Use Google Calendar UI or integrate API')"
                            class="bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200"
                        >
                            <i class="fas fa-trash mr-2"></i>Delete Event
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <style>
        .calendar-embed { display: none; }
        .calendar-embed.active { display: block; }
    </style>

    <script>
        function showCalendar(id, btn) {
            // Hide all calendar embeds
            document.querySelectorAll('.calendar-embed').forEach(el => el.classList.remove('active'));
            
            // Show selected calendar
            const selectedCalendar = document.getElementById(id);
            selectedCalendar.classList.add('active');
            
            // Load iframe if not already loaded
            const iframe = selectedCalendar.querySelector('iframe');
            if (iframe.src === 'about:blank' || iframe.src === window.location.href) {
                iframe.style.opacity = '0';
                iframe.src = iframe.dataset.src;
            }
            
            // Update tab buttons
            const tabContainer = btn.parentElement;
            tabContainer.querySelectorAll('button').forEach(el => {
                el.classList.remove('bg-blue-600', 'text-white');
                el.classList.add('text-gray-600');
            });
            btn.classList.remove('text-gray-600');
            btn.classList.add('bg-blue-600', 'text-white');
        }

        // Initialize page - load first calendar immediately, others on demand
        document.addEventListener('DOMContentLoaded', function() {
            // First calendar should already be loaded, just ensure it's visible
            const firstIframe = document.querySelector('.calendar-embed.active iframe');
            if (firstIframe) {
                firstIframe.style.opacity = '1';
            }
        });
    </script>
</body>
</html> 