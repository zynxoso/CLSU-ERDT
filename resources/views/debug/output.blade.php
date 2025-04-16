<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Debug Output' }}</title>
    <style>
        body {
            font-family: monospace;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            margin-top: 0;
        }
        pre {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        .toolbar {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #0069d9;
        }
        .back-btn {
            background: #6c757d;
        }
        .back-btn:hover {
            background: #5a6268;
        }
        .copy-btn {
            background: #28a745;
        }
        .copy-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="toolbar">
            <h1>{{ $title ?? 'Debug Output' }}</h1>
            <div>
                <a href="/" class="btn back-btn">Back to Home</a>
                <button class="btn copy-btn" onclick="copyOutput()">Copy Output</button>
            </div>
        </div>

        <div id="output">{!! $output !!}</div>

        <textarea id="raw-output" style="display: none;">{{ $raw }}</textarea>
    </div>

    <script>
        function copyOutput() {
            const rawOutput = document.getElementById('raw-output');
            rawOutput.select();
            document.execCommand('copy');
            alert('Output copied to clipboard!');
        }
    </script>
</body>
</html>
