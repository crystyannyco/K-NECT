/**
 * Invisible Event Auto-Refresh System with Toast Notifications
 * Automatically refreshes pages when scheduled events are published
 * Shows toast notification like manual publishing
 */

class InvisibleEventAutoRefresh {
    constructor(options = {}) {
        this.pollInterval = options.pollInterval || 30000; // Check every 30 seconds
        this.lastEventCounts = {
            scheduled: null,
            published: null
        };
        this.isPolling = false;
        this.pollTimer = null;
        
        // Only run on event-related pages
        if (this.isEventPage()) {
            this.init();
        }
    }
    
    isEventPage() {
        const currentPath = window.location.pathname;
        return currentPath.includes('/events') || 
               currentPath.includes('/dashboard') ||
               currentPath.includes('/calendar') ||
               currentPath.includes('/city-events');
    }
    
    init() {
        console.log('[Auto-refresh] Starting invisible event monitoring...');
        this.startPolling();
        
        // Pause when user leaves the page to save resources
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
            }
        });
        
        // Stop polling when navigating away
        window.addEventListener('beforeunload', () => {
            this.stopPolling();
        });
    }
    
    startPolling() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        
        // Initial check to establish baseline
        this.checkForUpdates();
        
        // Set up recurring check
        this.pollTimer = setInterval(() => {
            this.checkForUpdates();
        }, this.pollInterval);
    }
    
    stopPolling() {
        if (!this.isPolling) return;
        
        this.isPolling = false;
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
    }
    
    async checkForUpdates() {
        try {
            const response = await fetch('/cron/health');
            if (!response.ok) throw new Error('Health check failed');
            
            const data = await response.json();
            
            // On first check, just establish baseline
            if (this.lastEventCounts.scheduled === null) {
                this.lastEventCounts.scheduled = data.upcoming_scheduled || 0;
                this.lastEventCounts.published = await this.getPublishedEventCount();
                return;
            }
            
            // Check if scheduled events decreased (meaning they were published)
            const currentScheduled = data.upcoming_scheduled || 0;
            const currentPublished = await this.getPublishedEventCount();
            
            const scheduledDecreased = currentScheduled < this.lastEventCounts.scheduled;
            const publishedIncreased = currentPublished > this.lastEventCounts.published;
            
            if (scheduledDecreased || publishedIncreased) {
                const eventsPublished = Math.max(
                    this.lastEventCounts.scheduled - currentScheduled,
                    currentPublished - this.lastEventCounts.published,
                    1
                );
                
                console.log(`[Auto-refresh] ${eventsPublished} event(s) published! Showing notification and refreshing...`);
                
                // Show toast notification like manual publishing
                this.showEventPublishedToast(eventsPublished);
                
                // Refresh page after showing notification
                setTimeout(() => {
                    window.location.reload();
                }, 2500);
            }
            
            // Update counts
            this.lastEventCounts.scheduled = currentScheduled;
            this.lastEventCounts.published = currentPublished;
            
        } catch (error) {
            console.warn('[Auto-refresh] Failed to check for updates:', error);
        }
    }
    
    async getPublishedEventCount() {
        try {
            // Try to count published events on the current page
            const publishedElements = document.querySelectorAll('[data-status="Published"], .status-published');
            if (publishedElements.length > 0) {
                return publishedElements.length;
            }
            
            // Fallback: make a quick API call to get recent published events count
            const recentResponse = await fetch('/cron/debug-events');
            if (recentResponse.ok) {
                const debugData = await recentResponse.json();
                // This is a rough estimation - in a real scenario you'd want a proper API endpoint
                return 0; // Will rely on scheduled count decrease for detection
            }
            
            return 0;
        } catch (error) {
            return 0;
        }
    }
    
    showEventPublishedToast(eventCount = 1) {
        const message = eventCount === 1 
            ? 'Event published successfully!'
            : `${eventCount} events published successfully!`;
            
        // Use the existing toast notification system that matches your UI
        this.showToast(message);
    }
    
    // Show toast notification using existing showNotification if available
    showToast(message) {
        // Check if global showNotification function exists (preferred method)
        if (typeof showNotification === 'function') {
            showNotification(message, 'success');
            return;
        }

        // Fallback toast implementation that matches existing styling exactly
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
            document.body.appendChild(toastContainer);
        } else {
            toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
        }

        const notification = document.createElement('div');
        notification.className = 'pointer-events-auto p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[280px] max-w-md break-words bg-green-500 text-white';
        
        const icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
        
        notification.innerHTML = `
            <div class="flex items-center">
                ${icon}
                <span class="mr-2">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        toastContainer.appendChild(notification);
        
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on event pages
    const eventPages = ['/events', '/dashboard', '/calendar', '/city-events'];
    const currentPath = window.location.pathname;
    
    if (eventPages.some(page => currentPath.includes(page))) {
        window.invisibleEventAutoRefresh = new InvisibleEventAutoRefresh({
            pollInterval: 30000 // Check every 30 seconds
        });
    }
});

// Expose for debugging purposes
window.InvisibleEventAutoRefresh = InvisibleEventAutoRefresh;