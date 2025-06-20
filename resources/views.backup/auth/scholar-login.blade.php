<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLSU-ERDT Scholarship Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/erdt_logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @livewireStyles
</head>
<body style="background-image: url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover;">
    @livewire('auth.scholar-login')

    <form id="scholar-logout-form" action="{{ route('scholar-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @livewireScripts
</body>
</html>
