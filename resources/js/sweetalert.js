import Swal from 'sweetalert2';

// Make Swal available globally
window.Swal = Swal;

// Default configuration for SweetAlert Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Make Toast available globally
window.Toast = Toast;

// Global toast functions for session flash messages
window.toast = {
    success: (message) => Toast.fire({
        icon: 'success',
        title: message
    }),
    error: (message) => Toast.fire({
        icon: 'error',
        title: message
    }),
    warning: (message) => Toast.fire({
        icon: 'warning',
        title: message
    }),
    info: (message) => Toast.fire({
        icon: 'info',
        title: message
    })
};

// Console log to verify SweetAlert is loaded
console.log('SweetAlert2 loaded successfully');

// Ensure SweetAlert is available immediately
if (typeof window !== 'undefined') {
    window.Swal = Swal;
    window.Toast = Toast;
}

