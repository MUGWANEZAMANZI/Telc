<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Abo bestätigt - GERMAN SUCCESS</title>
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
            border-left: 6px solid #22c55e;
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
        .details-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .details-box h3 {
            font-size: 14px;
            font-weight: 700;
            color: #166534;
            margin: 0 0 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px solid #dcfce7;
        }
        .details-row:last-child {
            border-bottom: none;
        }
        .details-label {
            color: #6b7280;
            font-weight: 500;
        }
        .details-value {
            color: #111827;
            font-weight: 700;
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
                <h1>Zahlung bestätigt, {{ $name }}!</h1>
                <p>Vielen Dank für Ihren Kauf! Ihr Abonnement wurde erfolgreich aktiviert. Hier sind Ihre Bestelldaten:</p>

                <div class="details-box">
                    <h3>Bestelldetails</h3>
                    <div class="details-row">
                        <span class="details-label">Tarif:</span>
                        <span class="details-value">{{ $planName }}</span>
                    </div>
                    <div class="details-row">
                        <span class="details-label">Dauer:</span>
                        <span class="details-value">{{ $durationMonths }} {{ $durationMonths === 1 ? 'Monat' : 'Monate' }}</span>
                    </div>
                    <div class="details-row">
                        <span class="details-label">Betrag:</span>
                        <span class="details-value">{{ $pricePaid }} €</span>
                    </div>
                    <div class="details-row">
                        <span class="details-label">Zahlungsart:</span>
                        <span class="details-value">{{ $provider }}</span>
                    </div>
                    <div class="details-row">
                        <span class="details-label">Gültig bis:</span>
                        <span class="details-value">{{ $endsAt }}</span>
                    </div>
                </div>

                <p>Mit Ihrem Abonnement haben Sie vollen Zugriff auf alle Premium-Prüfungssimulationen, KI-Sprechübungen und Zertifikatsbewertungen. Nutzen Sie diese Zeit, um sich optimal auf Ihre Prüfung vorzubereiten!</p>

                <div class="button-wrapper">
                    <a href="{{ url('/dashboard') }}" class="button">Zum Dashboard</a>
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
