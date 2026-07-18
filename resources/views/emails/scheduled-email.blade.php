<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GERMAN SUCCESS</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f5;
            color: #18181b;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f5;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-left: 6px solid #eab308;
            overflow: hidden;
        }
        .header {
            background-color: #18181b;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        h1 {
            font-size: 24px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 20px;
            color: #111827;
            text-transform: uppercase;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 20px;
            color: #4b5563;
        }
        .button-wrapper {
            margin: 30px 0;
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #eab308;
            color: #09090b !important;
            text-decoration: none;
            padding: 12px 30px;
            font-weight: 700;
            font-size: 14px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <span style="color: #ffffff; font-size: 20px; font-weight: 800; letter-spacing: 0.1em;">GERMAN SUCCESS</span>
            </div>
            <div class="content">
                <p>Hallo {{ $name }},</p>
                {!! $body !!}
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} GERMAN SUCCESS. Alle Rechte vorbehalten.</p>
                <p style="margin-top: 8px;">
                    <a href="{{ url('/pricing') }}" style="color: #9ca3af; text-decoration: underline;">Preise ansehen</a>
                    &nbsp;&middot;&nbsp;
                    <a href="{{ url('/blog') }}" style="color: #9ca3af; text-decoration: underline;">Blog</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
