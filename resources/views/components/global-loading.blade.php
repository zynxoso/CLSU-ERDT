<!-- Global Loading Overlay Component - DISABLED -->
<div x-data="{
     show: false,
     message: 'Loading...',
     
     init() {
         // Listen for custom loading events (for login, navigation, static pages)
         window.addEventListener('show-loading', (event) => {
             this.showLoading(event.detail?.message || 'Loading...');
         });
         
         window.addEventListener('hide-loading', () => {
             this.hideLoading();
         });
         
         // Listen for navigation events
         window.addEventListener('beforeunload', () => {
             this.showLoading('Loading...');
         });
     },
    
    setupLivewireListeners() {
        // Global loading disabled - no listeners setup
        return;
    },
    
    showLoading(message = 'Loading...') {
         this.message = message;
         this.show = true;
         document.body.style.overflow = 'hidden';
         
         // Auto-hide after 30 seconds as fallback
         setTimeout(() => {
             if (this.show) {
                 this.hideLoading();
             }
         }, 30000);
     },
     
     hideLoading() {
         this.show = false;
         document.body.style.overflow = '';
     },
    
    getContextualMessage() {
        const userRole = '{{ Auth::check() ? Auth::user()->role : "guest" }}';
        const currentPath = window.location.pathname;
        
        if (currentPath.includes('dashboard')) {
            switch(userRole) {
                case 'scholar':
                    return 'Loading Scholar Dashboard...';
                case 'admin':
                    return 'Loading Admin Dashboard...';
                case 'super_admin':
                    return 'Loading Super Admin Dashboard...';
                default:
                    return 'Loading Dashboard...';
            }
        }
        
        if (currentPath.includes('manuscripts')) return 'Loading Manuscripts...';
        if (currentPath.includes('fund-requests')) return 'Loading Fund Requests...';
        if (currentPath.includes('scholars')) return 'Loading Scholars...';
        if (currentPath.includes('audit-logs')) return 'Loading Audit Logs...';
        if (currentPath.includes('notifications')) return 'Loading Notifications...';
        
        return 'Loading...';
    }
}"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[99999] flex items-center justify-center min-h-screen w-full loading-overlay"
     style="display: none; top: 0; left: 0; right: 0; bottom: 0; position: fixed;"
     x-cloak>
    
    <!-- Backdrop with modern gradient effect -->
    <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-gray-50/90 to-green-50/85 backdrop-blur-sm w-full h-full"></div>
    
    <!-- Loading Content -->
    <div class="relative z-10 flex flex-col items-center justify-center space-y-6">
        <!-- Main Loading Container -->
        <div class="relative flex items-center justify-center">
            <!-- Outer Ring -->
            <div class="absolute w-24 h-24 border-4 border-gray-200 rounded-full animate-pulse"></div>
            
            <!-- Spinning Ring -->
            <div class="absolute w-24 h-24 border-4 border-transparent border-t-green-600 border-r-green-500 rounded-full animate-spin"></div>
            
            <!-- CLSU Logo -->
            <div class="relative z-10 bg-white rounded-full p-2 shadow-lg">
                <img src="{{ asset('images/CLSU-new-logo.png') }}" alt="CLSU Logo" class="w-12 h-12 object-contain">
            </div>
        </div>
        
        <!-- Loading Message -->
        <div class="text-center">
            <div class="loading-message text-lg font-semibold text-gray-800 mb-4" x-text="message">Loading...</div>
        </div>
        
        <!-- Loading Dots -->
        <div class="flex space-x-1">
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced animations -->
<style>
    [x-cloak] { display: none !important; }
    
    @keyframes smooth-spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    @keyframes gentle-pulse {
        0%, 100% {
            opacity: 0.4;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }
    
    @keyframes bounce-dots {
        0%, 80%, 100% {
            transform: scale(0);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .loading-overlay .animate-spin {
        animation: smooth-spin 2s linear infinite;
    }
    
    .loading-overlay .animate-pulse {
        animation: gentle-pulse 2s ease-in-out infinite;
    }
    
    .loading-overlay .animate-bounce {
        animation: bounce-dots 1.4s ease-in-out infinite;
    }
    
    /* Ensure loading overlay is above everything */
    .loading-overlay {
        z-index: 99999 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        min-height: 100vh !important;
        min-width: 100vw !important;
    }
    
    /* Prevent interaction with background elements */
    body.loading-active {
        overflow: hidden !important;
        pointer-events: none !important;
        position: relative;
    }
    
    html.loading-active {
        overflow: hidden !important;
    }
    
    body.loading-active .loading-overlay {
        pointer-events: auto !important;
    }
    
    /* Ensure backdrop covers everything */
    .loading-overlay .absolute.inset-0 {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
    }
</style>