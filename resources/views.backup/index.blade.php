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
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: {{ asset('storage/bg/bgloginscholar.png') }}
        }
        h1, h2, h3, .logo-text {
            font-family: 'Montserrat', sans-serif;
        }
        .engineering-gradient {
            background: linear-gradient(135deg, #0369a1, #0c4a6e);
        }
        .blueprint-pattern {
            background-color: #0c4a6e;
            background-image: radial-gradient(#164e63 1px, transparent 1px),
                              radial-gradient(#164e63 1px, transparent 1px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.8;
        }
    </style>
</head>
<body style="background-image: url('{{ asset('storage/bg/bgloginscholar.png') }}'); background-repeat: no-repeat; background-size: cover;">
    @livewire('home-page')

    <form id="scholar-logout-form" action="{{ route('scholar-logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
