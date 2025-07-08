import './bootstrap';
import './sweetalert'; // Import SweetAlert configuration

// Enhanced error handling for asset loading
window.addEventListener('error', (event) => {
    // Handle CSS loading errors
    if (event.target && event.target.tagName === 'LINK' && event.target.rel === 'stylesheet') {
        console.warn('CSS file failed to load:', event.target.href);
        // Attempt to reload the CSS file
        const newLink = document.createElement('link');
        newLink.rel = 'stylesheet';
        newLink.href = event.target.href + '?retry=' + Date.now();
        document.head.appendChild(newLink);
        return;
    }

    // Handle script loading errors
    if (event.target && event.target.tagName === 'SCRIPT') {
        console.warn('Script file failed to load:', event.target.src);
        return;
    }
});

// Enhanced fetch wrapper with better error handling and global exposure
const safeFetch = async (url, options = {}) => {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                ...options.headers
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return response;
    } catch (error) {
        console.warn('Fetch request failed:', error.message);
        throw error;
    }
};

// Ensure safeFetch is globally available
window.safeFetch = safeFetch;

// Export for module usage
export { safeFetch };

