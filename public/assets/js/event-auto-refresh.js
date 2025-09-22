/**
 * Auto-refresh functionality for K-NECT event pages
 * Automatically refreshes the page when scheduled events are published
 */

class EventAutoRefresh {
    constructor(options = {}) {
        this.pollInterval = options.pollInterval || 60000; // Check every minute
        this.lastEventCount = null;
        this.lastPublishedCount = null;
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
               currentPath.includes('/calendar');
    }
    
    init() {
        console.log('[Auto-refresh] Initializing event auto-refresh...');
        this.startPolling();
        
        // Add visual indicator
        this.addRefreshIndicator();
        
        // Stop polling when user leaves the page
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
            }
        });
    }
    
    startPolling() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        console.log('[Auto-refresh] Started polling for event updates...');
        
        // Initial check
        this.checkForUpdates();
        
        // Set up recurring check
        this.pollTimer = setInterval(() => {
            this.checkForUpdates();
        }, this.pollInterval);
    }
    
    stopPolling() {
        if (!this.isPolling) return;
        
        this.isPolling = false;
        console.log('[Auto-refresh] Stopped polling for event updates.');
        
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
    }
    
    async checkForUpdates() {
        try {
            const response = await fetch('/cron/health');
            const data = await response.json();
            
            // Check if we have baseline counts
            if (this.lastEventCount === null) {
                this.lastEventCount = data.upcoming_scheduled || 0;
                this.lastPublishedCount = this.getPublishedEventCount();
                return;
            }
            
            // Check if scheduled events decreased (meaning they were published)
            const currentScheduled = data.upcoming_scheduled || 0;
            const currentPublished = this.getPublishedEventCount();
            
            if (currentScheduled < this.lastEventCount || 
                currentPublished > this.lastPublishedCount) {
                
                console.log('[Auto-refresh] New event published! Refreshing page...');
                this.showRefreshNotification();
                
                // Smooth refresh with a slight delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
            
            // Update counts
            this.lastEventCount = currentScheduled;
            this.lastPublishedCount = currentPublished;
            
            // Update indicator
            this.updateRefreshIndicator(data);
            
        } catch (error) {
            console.warn('[Auto-refresh] Failed to check for updates:', error);
        }
    }
    
    getPublishedEventCount() {
        // Count published events on the current page
        const publishedBadges = document.querySelectorAll('.badge:contains("Published"), .status-published, [data-status="Published"]');
        return publishedBadges.length;
    }
    
    addRefreshIndicator() {
        // Create a small indicator in the corner
        const indicator = document.createElement('div');
        indicator.id = 'event-refresh-indicator';
        indicator.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #10B981;
                color: white;
                padding: 8px 12px;
                border-radius: 20px;
                font-size: 12px;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            " title="Auto-refresh enabled - page will update when events are published">
                <i class="fas fa-sync" style="margin-right: 5px;"></i>
                <span>Live Updates: ON</span>
            </div>
        `;
        document.body.appendChild(indicator);
    }
    
    updateRefreshIndicator(healthData) {
        const indicator = document.getElementById('event-refresh-indicator');
        if (!indicator) return;
        
        const statusDiv = indicator.querySelector('div');
        const span = indicator.querySelector('span');
        
        if (healthData.status === 'healthy') {
            statusDiv.style.background = '#10B981';
            span.textContent = `Live Updates: ON (${healthData.upcoming_scheduled || 0} scheduled)`;
        } else {
            statusDiv.style.background = '#F59E0B';
            span.textContent = 'Live Updates: Warning';
        }
    }
    
    showRefreshNotification() {
        // Show a brief notification before refresh
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #1F2937;
                color: white;
                padding: 20px 30px;
                border-radius: 10px;
                font-size: 16px;
                z-index: 9999;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                animation: fadeInOut 1.5s ease-in-out;
            ">
                <i class="fas fa-check-circle" style="color: #10B981; margin-right: 10px;"></i>
                Event published! Refreshing page...
            </div>
            <style>
                @keyframes fadeInOut {
                    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                    50% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                    100% { opacity: 0; transform: translate(-50%, -50%) scale(1.1); }
                }
            </style>
        `;
        document.body.appendChild(notification);
        
        // Remove notification after animation
        setTimeout(() => {
            notification.remove();
        }, 1500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on event pages
    const eventPages = ['/events', '/dashboard', '/calendar'];
    const currentPath = window.location.pathname;
    
    if (eventPages.some(page => currentPath.includes(page))) {
        window.eventAutoRefresh = new EventAutoRefresh({
            pollInterval: 30000 // Check every 30 seconds
        });
    }
});

// Expose for manual control
window.EventAutoRefresh = EventAutoRefresh;