import Swal from 'sweetalert2';

// Make Swal available globally
window.Swal = Swal;

// Default configuration for SweetAlert
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

// Global functions for common alert types
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

// Global confirmation function
window.confirmAction = (title, text, callback, cancelCallback = null) => {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        } else if (cancelCallback && typeof cancelCallback === 'function') {
            cancelCallback();
        }
    });
};

export { Swal, Toast };

