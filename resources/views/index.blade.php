<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSU-ERDT</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/CLSU-FAVICON.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('fonts/google-fonts.css') }}" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body { font-family: theme('fontFamily.sans'); }
        h1, h2, h3, .logo-text { font-family: theme('fontFamily.display'); }
        .engineering-gradient { background: linear-gradient(135deg, rgb(3 105 161), rgb(12 74 110)); }
        .blueprint-pattern { background-color: rgb(12 74 110); background-image: radial-gradient(rgb(22 78 99) 1px, transparent 1px), radial-gradient(rgb(22 78 99) 1px, transparent 1px); background-size: 20px 20px; background-position: 0 0, 10px 10px; opacity: 0.8; }
          @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up {animation: fadeInUp 0.6s ease forwards;}
    </style>
</head>
<body>
    @livewire('home-page')

    <!-- Global Loading Overlay -->
    @include('components.global-loading')

    <form id="scholar-logout-form" action="{{ route('scholar-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Loading Service -->
    <script src="{{ asset('js/selective-loading-service.js') }}" defer></script>
    <script>
        window.addEventListener('scroll', () => { const winScroll = document.body.scrollTop || document.documentElement.scrollTop; const height = document.documentElement.scrollHeight - document.documentElement.clientHeight; const scrolled = (winScroll / height) * 100; const progressBar = document.getElementById('reading-progress'); if (progressBar) progressBar.style.width = scrolled + '%'; }); 
        document.querySelectorAll('a[href^="#"]').forEach(anchor => anchor.addEventListener('click', e => { e.preventDefault(); const target = document.querySelector(anchor.getAttribute('href')); if (target) target.scrollIntoView({behavior: 'smooth', block: 'start'}); }));
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => { entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('fade-in-up'); } }); }, observerOptions);
        document.querySelectorAll('.transform').forEach(el => { observer.observe(el); });
    </script>
</body>
</html>
