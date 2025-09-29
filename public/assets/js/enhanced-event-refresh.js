/**
 * Enhanced Event Auto-Refresh with Manual Controls
 * Shows a live status indicator and allows manual refresh
 */

class EnhancedEventRefresh {
    constructor() {
        this.pollInterval = 30000; // Check every 30 seconds
        this.lastCheck = null;
        this.isPolling = false;
        this.pollTimer = null;
        this.statusIndicator = null;
        
        if (this.isEventPage()) {
            this.init();
        }
    }
    
    isEventPage() {
        const path = window.location.pathname;
        return path.includes('/events') || 
               path.includes('/dashboard') || 
               path.includes('/calendar');
    }
    
    init() {
        this.createStatusIndicator();
        this.startPolling();
        this.bindEvents();
    }
    
    createStatusIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'live-event-status';
        indicator.innerHTML = `
            <div class="live-indicator" style="
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                border: 1px solid #E5E7EB;
                border-radius: 12px;
                padding: 12px 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                font-size: 14px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
                z-index: 9999;
                min-width: 220px;
                transition: all 0.3s ease;
            ">
                <div class="status-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-weight: 600; color: #374151;">Live Event Status</span>
                    <button id="refresh-now" style="
                        background: #3B82F6;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 4px 8px;
                        font-size: 12px;
                        cursor: pointer;
                    " title="Check for updates now">
                        <i class="fas fa-sync" style="margin-right: 4px;"></i>Refresh
                    </button>
                </div>
                
                <div class="status-content">
                    <div class="status-item" style="margin-bottom: 4px;">
                        <i class="fas fa-clock" style="color: #F59E0B; margin-right: 6px;"></i>
                        <span id="scheduled-count">0</span> scheduled events
                    </div>
                    
                    <div class="status-item" style="margin-bottom: 4px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-right: 6px;"></i>
                        <span id="system-status">Checking...</span>
                    </div>
                    
                    <div class="status-item" style="font-size: 12px; color: #6B7280;">
                        Last check: <span id="last-check">Never</span>
                    </div>
                </div>
                
                <div id="refresh-notification" style="
                    display: none;
                    background: #10B981;
                    color: white;
                    padding: 8px;
                    border-radius: 6px;
                    margin-top: 8px;
                    text-align: center;
                    animation: pulse 0.5s ease-in-out;
                ">
                    <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
                    New events published! Page refreshing...
                </div>
            </div>
            
            <style>
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
                
                #refresh-now:hover {
                    background: #2563EB !important;
                }
                
                .live-indicator.checking #refresh-now i {
                    animation: spin 1s linear infinite;
                }
                
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            </style>
        `;
        
        document.body.appendChild(indicator);
        this.statusIndicator = indicator;
    }
    
    bindEvents() {
        // Manual refresh button
        document.getElementById('refresh-now').addEventListener('click', () => {
            this.checkForUpdates(true);
        });
        
        // Pause when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
            }
        });
        
        // Close indicator (optional)
        const indicator = this.statusIndicator;
        indicator.addEventListener('dblclick', () => {
            indicator.style.display = 'none';
            this.stopPolling();
        });
    }
    
    startPolling() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        this.checkForUpdates();
        
        this.pollTimer = setInterval(() => {
            this.checkForUpdates();
        }, this.pollInterval);
    }
    
    stopPolling() {
        this.isPolling = false;
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
    }
    
    async checkForUpdates(manual = false) {
        if (manual) {
            document.querySelector('.live-indicator').classList.add('checking');
        }
        
        try {
            const response = await fetch('/cron/health');
            const data = await response.json();
            
            // Update UI
            this.updateStatus(data);
            
            // Check for changes (only if not first check)
            if (this.lastCheck !== null && !manual) {
                const scheduledBefore = this.lastCheck.upcoming_scheduled || 0;
                const scheduledNow = data.upcoming_scheduled || 0;
                
                // If scheduled events decreased, something was published
                if (scheduledNow < scheduledBefore) {
                    this.showRefreshNotification();
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            }
            
            this.lastCheck = data;
            
        } catch (error) {
            console.warn('Failed to check event status:', error);
            this.updateStatus({
                status: 'error',
                upcoming_scheduled: 0,
                message: 'Connection error'
            });
        }
        
        if (manual) {
            setTimeout(() => {
                document.querySelector('.live-indicator')?.classList.remove('checking');
            }, 500);
        }
    }
    
    updateStatus(data) {
        const scheduledCount = document.getElementById('scheduled-count');
        const systemStatus = document.getElementById('system-status');
        const lastCheck = document.getElementById('last-check');
        
        if (scheduledCount) scheduledCount.textContent = data.upcoming_scheduled || 0;
        
        if (systemStatus) {
            const statusText = data.status === 'healthy' ? 'System healthy' : 'System warning';
            const statusColor = data.status === 'healthy' ? '#10B981' : '#F59E0B';
            systemStatus.textContent = statusText;
            systemStatus.parentElement.querySelector('i').style.color = statusColor;
        }
        
        if (lastCheck) {
            lastCheck.textContent = new Date().toLocaleTimeString();
        }
    }
    
    showRefreshNotification() {
        const notification = document.getElementById('refresh-notification');
        if (notification) {
            notification.style.display = 'block';
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    window.enhancedEventRefresh = new EnhancedEventRefresh();
});

// Make available globally
window.EnhancedEventRefresh = EnhancedEventRefresh;