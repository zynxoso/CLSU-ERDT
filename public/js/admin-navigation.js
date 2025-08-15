// Admin Navigation Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Prevent double-click requirement by removing any delay in click handling
    const navigationLinks = document.querySelectorAll('nav a');
    navigationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove any existing click delays
            if (this.getAttribute('data-clicked')) {
                return;
            }
            this.setAttribute('data-clicked', 'true');

            // Clean up the data attribute after navigation
            setTimeout(() => {
                this.removeAttribute('data-clicked');
            }, 100);
        });
    });

    // Enhance mobile navigation responsiveness
    const mobileMenuButton = document.querySelector('[x-data]');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function(e) {
            // Ensure immediate response to click
            if (window.innerWidth < 1024) {
                setTimeout(() => {
                    // Access the Alpine component data directly
                    const alpineData = Alpine.$data(mobileMenuButton);
                    if (alpineData && typeof alpineData.sidebarOpen !== 'undefined') {
                        alpineData.sidebarOpen = false;
                    }
                }, 50);
            }
        });
    }

    // Add active state highlight
    const currentPath = window.location.pathname;
    navigationLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});
