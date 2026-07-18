<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Abo läuft ab - GERMAN SUCCESS</title>
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
            border-left: 6px solid #f97316;
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
        .alert-box {
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-box .days {
            font-size: 48px;
            font-weight: 800;
            color: #ea580c;
            line-height: 1;
            margin: 0;
        }
        .alert-box .label {
            font-size: 14px;
            color: #9a3412;
            font-weight: 600;
            margin-top: 8px;
            display: block;
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
                <h1>Abo-Laufzeit, {{ $name }}</h1>
                <p>Ihr <strong>{{ $planName }}</strong>-Abonnement bei GERMAN SUCCESS läuft bald ab.</p>

                <div class="alert-box">
                    <p class="days">{{ $daysRemaining }}</p>
                    <span class="label">{{ $daysRemaining === 1 ? 'Tag' : 'Tage' }} verbleibend</span>
                </div>

                <p>Am <strong>{{ $endsAt }}</strong> endet Ihr Zugang zu allen Premium-Prüfungssimulationen, KI-Sprechübungen und Zertifikatsbewertungen. Verlängern Sie jetzt, um Ihren Lernfortschritt nicht zu verlieren!</p>

                <div class="button-wrapper">
                    <a href="{{ url('/billing') }}" class="button">Abo verlängern</a>
                </div>

                <p style="font-size: 13px; color: #9ca3af;">Sollten Sie Fragen haben, antworten Sie einfach auf diese E-Mail oder kontaktieren Sie uns unter support@germansuccess.com.</p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} GERMAN SUCCESS. Alle Rechte vorbehalten.
            </div>
        </div>
    </div>
</body>
</html>
