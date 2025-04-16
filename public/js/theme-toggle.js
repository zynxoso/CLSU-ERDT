document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference or use OS preference
    const savedTheme = localStorage.getItem('erdtThemePreference');
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Set initial theme
    if (savedTheme) {
        htmlElement.setAttribute('data-bs-theme', savedTheme);
        updateToggleIcon(savedTheme);
    } else if (prefersDarkMode) {
        htmlElement.setAttribute('data-bs-theme', 'dark');
        updateToggleIcon('dark');
    } else {
        htmlElement.setAttribute('data-bs-theme', 'light');
        updateToggleIcon('light');
    }
    
    // Toggle theme on click
    themeToggle.addEventListener('click', function() {
        const currentTheme = htmlElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        htmlElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('erdtThemePreference', newTheme);
        updateToggleIcon(newTheme);
    });
    
    // Update toggle icon based on current theme
    function updateToggleIcon(theme) {
        const iconElement = themeToggle.querySelector('i');
        if (theme === 'dark') {
            iconElement.className = 'bi bi-moon-fill';
        } else {
            iconElement.className = 'bi bi-sun-fill';
        }
    }
});