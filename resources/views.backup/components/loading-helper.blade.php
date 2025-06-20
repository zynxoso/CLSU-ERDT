{{--
    Universal Loading Helper Component - Simplified Circle Spinner

    The loading system now shows a clean spinning circle without any text.

    Usage Examples:

    1. Show/Hide loading programmatically:
       <script>
           // Show loading (circle spinner only)
           window.universalLoading.show();

           // Hide loading
           window.universalLoading.hide();
       </script>

    2. Button with manual loading control:
       <button onclick="handleAction()">Click Me</button>
       <script>
           function handleAction() {
               window.universalLoading.show();

               // Simulate API call
               setTimeout(() => {
                   window.universalLoading.hide();
                   alert('Done!');
               }, 2000);
           }
       </script>

    3. Automatic loading for:
       - Livewire navigation (wire:navigate)
       - Livewire component interactions (wire:click, wire:submit)
       - Form submissions
       - AJAX/Fetch requests
       - Page navigation

    The system automatically shows a clean spinning circle for all these actions.
--}}

{{-- This component is just documentation. The actual loading system is integrated into the main app layout. --}}

<div class="hidden">
    <!-- Universal Loading System is automatically integrated -->
    <!-- See resources/views/layouts/app.blade.php for implementation -->
</div>
