<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirect Loop Detected</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px 30px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        h1 {
            color: #e53e3e;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .alert {
            background-color: #fff5f5;
            border-left: 4px solid #e53e3e;
            padding: 15px;
            margin-bottom: 20px;
            color: #c53030;
        }
        pre {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 14px;
        }
        .actions {
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #4299e1;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #3182ce;
        }
        .btn-secondary {
            background-color: #718096;
        }
        .btn-secondary:hover {
            background-color: #4a5568;
        }
        .details {
            margin-top: 30px;
            background-color: #f7fafc;
            padding: 20px;
            border-radius: 5px;
        }
        ul {
            margin: 0;
            padding: 0 0 0 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Redirect Loop Detected</h1>

        <div class="alert">
            <strong>Warning:</strong> The application detected a redirect loop and has stopped it to prevent your browser from crashing.
        </div>

        <p>The system has detected {{ $count }} redirects within {{ $timeWindow }} seconds at the following URL:</p>

        <pre>{{ $url }}</pre>

        <div class="details">
            <h3>Possible Causes:</h3>
            <ul>
                <li>Session issues or authentication problems</li>
                <li>Middleware conflicts</li>
                <li>Browser cookies problems</li>
                <li>Route configuration issues</li>
            </ul>

            <h3>Suggested Solutions:</h3>
            <ul>
                <li>Clear your browser cookies and cache</li>
                <li>Try logging out and logging back in</li>
                <li>Contact the site administrator if the problem persists</li>
            </ul>
        </div>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn">Go to Home Page</a>
            <a href="{{ url('/logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="btn btn-secondary">Logout</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</body>
</html>
