<!-- Modern Professional Navigation -->
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo Section -->
            <a href="{{ route('home') }}" class="flex items-center group">
                <div class="relative">
                    <img src="{{ asset('images/CLSU-new-logo.png') }}" alt="CLSU-ERDT Logo" class="h-14 w-14 rounded-lg shadow-sm transition-transform duration-200 group-hover:scale-105">
                </div>
                <div class="ml-4">
                    <span class="font-bold text-2xl tracking-tight text-gray-900">Central Luzon State University</span>
                    <div class="text-sm font-medium text-gray-600">Engineering Research & Development For Technology</div>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}">
                    Home
                </a>
                <a href="{{ route('how-to-apply') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('how-to-apply') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}">
                    How to Apply
                </a>
                <a href="{{ route('about') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('about') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}">
                    About
                </a>
                <a href="{{ route('history') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('history') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}">
                    History
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" 
                    class="md:hidden p-3 text-gray-600 hover:text-gray-900 active:text-gray-950 hover:bg-gray-50 active:bg-gray-100 focus:bg-gray-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200 min-h-[48px] min-w-[48px] flex items-center justify-center touch-manipulation"
                    aria-label="Toggle mobile menu - Open or close navigation menu"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    role="button"
                    tabindex="0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-6 pt-6 border-t border-gray-200" role="menu" aria-labelledby="mobile-menu-button">
            <div class="space-y-2">
                <a href="{{ route('home') }}" 
                   class="block px-4 py-4 min-h-[48px] text-gray-700 hover:text-gray-900 active:text-gray-950 hover:bg-gray-50 active:bg-gray-100 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-lg transition-all duration-200 font-medium text-base touch-manipulation {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}"
                   role="menuitem"
                   aria-label="Navigate to Home page"
                   tabindex="-1">
                    Home
                </a>
                <a href="{{ route('how-to-apply') }}" 
                   class="block px-4 py-4 min-h-[48px] text-gray-700 hover:text-gray-900 active:text-gray-950 hover:bg-gray-50 active:bg-gray-100 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-lg transition-all duration-200 font-medium text-base touch-manipulation {{ request()->routeIs('how-to-apply') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}"
                   role="menuitem"
                   aria-label="Navigate to How to Apply page"
                   tabindex="-1">
                    How to Apply
                </a>
                <a href="{{ route('about') }}" 
                   class="block px-4 py-4 min-h-[48px] text-gray-700 hover:text-gray-900 active:text-gray-950 hover:bg-gray-50 active:bg-gray-100 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-lg transition-all duration-200 font-medium text-base touch-manipulation {{ request()->routeIs('about') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}"
                   role="menuitem"
                   aria-label="Navigate to About page"
                   tabindex="-1">
                    About
                </a>
                <a href="{{ route('history') }}" 
                   class="block px-4 py-4 min-h-[48px] text-gray-700 hover:text-gray-900 active:text-gray-950 hover:bg-gray-50 active:bg-gray-100 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-lg transition-all duration-200 font-medium text-base touch-manipulation {{ request()->routeIs('history') ? 'text-gray-900 bg-gray-100 font-semibold' : '' }}"
                   role="menuitem"
                   aria-label="Navigate to History page"
                   tabindex="-1">
                    History
                </a>
            </div>
        </div>
    </div>

    <!-- Reading Progress Bar -->
    <div class="h-1 bg-gray-100">
        <div id="reading-progress" class="h-full bg-green-600 transition-all duration-300" style="width: 0%;"></div>
    </div>
</nav>

<!-- Mobile Menu Toggle Script -->
<script>
    (function() {
        'use strict';
        
        // Global state management for mobile menu
        let mobileMenuState = {
            isOpen: false,
            button: null,
            menu: null,
            initialized: false
        };
        
        // Enhanced mobile menu initialization with better error handling
        function initializeMobileMenu() {
            try {
                // Prevent multiple initializations
                if (mobileMenuState.initialized) {
                    return;
                }
                
                // Find DOM elements with retry mechanism
                const findElements = () => {
                    const button = document.getElementById('mobile-menu-button');
                    const menu = document.getElementById('mobile-menu');
                    return { button, menu };
                };
                
                let { button, menu } = findElements();
                
                // Retry finding elements if not found initially
                if (!button || !menu) {
                    setTimeout(() => {
                        const retry = findElements();
                        if (retry.button && retry.menu) {
                            setupMobileMenu(retry.button, retry.menu);
                        } else {
                            console.warn('Mobile menu elements not found after retry');
                        }
                    }, 100);
                    return;
                }
                
                setupMobileMenu(button, menu);
                
            } catch (error) {
                console.error('Error initializing mobile menu:', error);
            }
        }
        
        // Setup mobile menu functionality
        function setupMobileMenu(button, menu) {
            try {
                // Store references
                mobileMenuState.button = button;
                mobileMenuState.menu = menu;
                mobileMenuState.initialized = true;
                
                // Remove any existing event listeners to prevent duplicates
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                mobileMenuState.button = newButton;
                
                // Enhanced click handler with better state management
                newButton.addEventListener('click', handleMenuToggle);
                
                // Global click handler for closing menu
                document.addEventListener('click', handleOutsideClick);
                
                // Keyboard navigation support
                document.addEventListener('keydown', handleKeyboardNavigation);
                
                // Handle window resize to close menu on desktop
                window.addEventListener('resize', handleWindowResize);
                
                // Initialize ARIA attributes and accessibility
                initializeAccessibility(newButton, menu);
                
                console.log('Mobile menu initialized successfully');
                
            } catch (error) {
                console.error('Error setting up mobile menu:', error);
            }
        }
        
        // Enhanced menu toggle handler
        function handleMenuToggle(e) {
            e.preventDefault();
            e.stopPropagation();
            
            try {
                const { button, menu } = mobileMenuState;
                if (!button || !menu) return;
                
                // Toggle menu state
                mobileMenuState.isOpen = !mobileMenuState.isOpen;
                
                // Update DOM classes with smooth transitions
                if (mobileMenuState.isOpen) {
                    menu.classList.remove('hidden');
                    button.classList.add('bg-gray-100');
                    // Add focus trap for accessibility
                    trapFocus(menu);
                } else {
                    menu.classList.add('hidden');
                    button.classList.remove('bg-gray-100');
                    // Return focus to button
                    button.focus();
                }
                
                // Update ARIA attributes
                button.setAttribute('aria-expanded', mobileMenuState.isOpen);
                
                // Update button icon if needed
                updateButtonIcon(button, mobileMenuState.isOpen);
                
            } catch (error) {
                console.error('Error toggling mobile menu:', error);
            }
        }
        
        // Handle clicks outside the menu
        function handleOutsideClick(e) {
            try {
                const { button, menu } = mobileMenuState;
                if (!button || !menu || !mobileMenuState.isOpen) return;
                
                // Check if click is outside both button and menu
                if (!button.contains(e.target) && !menu.contains(e.target)) {
                    closeMobileMenu();
                }
            } catch (error) {
                console.error('Error handling outside click:', error);
            }
        }
        
        // Enhanced keyboard navigation
        function handleKeyboardNavigation(e) {
            try {
                const { button, menu } = mobileMenuState;
                if (!button || !menu) return;
                
                switch (e.key) {
                    case 'Escape':
                        if (mobileMenuState.isOpen) {
                            closeMobileMenu();
                            button.focus();
                        }
                        break;
                    case 'Tab':
                        if (mobileMenuState.isOpen) {
                            handleTabNavigation(e, menu);
                        }
                        break;
                    case 'ArrowDown':
                    case 'ArrowUp':
                        if (mobileMenuState.isOpen) {
                            handleArrowNavigation(e, menu);
                        }
                        break;
                }
            } catch (error) {
                console.error('Error handling keyboard navigation:', error);
            }
        }
        
        // Handle window resize
        function handleWindowResize() {
            try {
                // Close mobile menu on desktop breakpoint
                if (window.innerWidth >= 768 && mobileMenuState.isOpen) {
                    closeMobileMenu();
                }
            } catch (error) {
                console.error('Error handling window resize:', error);
            }
        }
        
        // Close mobile menu helper
        function closeMobileMenu() {
            try {
                const { button, menu } = mobileMenuState;
                if (!button || !menu) return;
                
                mobileMenuState.isOpen = false;
                menu.classList.add('hidden');
                button.classList.remove('bg-gray-100');
                button.setAttribute('aria-expanded', 'false');
                updateButtonIcon(button, false);
            } catch (error) {
                console.error('Error closing mobile menu:', error);
            }
        }
        
        // Initialize accessibility features
        function initializeAccessibility(button, menu) {
            try {
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-controls', 'mobile-menu');
                button.setAttribute('aria-haspopup', 'true');
                menu.setAttribute('role', 'menu');
                
                // Add role and tabindex to menu items
                const menuItems = menu.querySelectorAll('a');
                menuItems.forEach((item, index) => {
                    item.setAttribute('role', 'menuitem');
                    item.setAttribute('tabindex', '-1');
                });
                
                // Make first menu item focusable
                if (menuItems.length > 0) {
                    menuItems[0].setAttribute('tabindex', '0');
                }
            } catch (error) {
                console.error('Error initializing accessibility:', error);
            }
        }
        
        // Focus trap for accessibility
        function trapFocus(menu) {
            try {
                const focusableElements = menu.querySelectorAll('a[href], button:not([disabled])');
                if (focusableElements.length > 0) {
                    focusableElements[0].focus();
                }
            } catch (error) {
                console.error('Error trapping focus:', error);
            }
        }
        
        // Handle tab navigation within menu
        function handleTabNavigation(e, menu) {
            try {
                const focusableElements = menu.querySelectorAll('a[href], button:not([disabled])');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            } catch (error) {
                console.error('Error handling tab navigation:', error);
            }
        }
        
        // Handle arrow key navigation
        function handleArrowNavigation(e, menu) {
            try {
                e.preventDefault();
                const menuItems = menu.querySelectorAll('a[href]');
                const currentIndex = Array.from(menuItems).indexOf(document.activeElement);
                
                let nextIndex;
                if (e.key === 'ArrowDown') {
                    nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
                } else {
                    nextIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
                }
                
                menuItems[nextIndex].focus();
            } catch (error) {
                console.error('Error handling arrow navigation:', error);
            }
        }
        
        // Update button icon based on state
        function updateButtonIcon(button, isOpen) {
            try {
                const svg = button.querySelector('svg path');
                if (svg) {
                    if (isOpen) {
                        svg.setAttribute('d', 'M6 18L18 6M6 6l12 12'); // X icon
                    } else {
                        svg.setAttribute('d', 'M4 6h16M4 12h16M4 18h16'); // Hamburger icon
                    }
                }
            } catch (error) {
                console.error('Error updating button icon:', error);
            }
        }
        
        // Enhanced DOM ready detection with multiple fallbacks
        function initializeWhenReady() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeMobileMenu);
            } else {
                // DOM is already ready
                initializeMobileMenu();
            }
            
            // Additional fallback for dynamic content
            setTimeout(initializeMobileMenu, 100);
        }
        
        // Public API for re-initialization
        window.mobileMenuAPI = {
            reinitialize: function() {
                mobileMenuState.initialized = false;
                initializeMobileMenu();
            },
            close: closeMobileMenu,
            isOpen: function() {
                return mobileMenuState.isOpen;
            }
        };
        
        // Initialize the mobile menu
        initializeWhenReady();
        
    })();
</script>
