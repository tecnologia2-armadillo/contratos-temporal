<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #f59e0b;
            margin: 0;
            text-transform: uppercase;
            font-size: 16pt;
        }

        .header p {
            margin: 5px 0 0;
            color: #555;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
        }

        .section {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: justify;
            white-space: pre-wrap;
            font-size: 11pt;
        }

        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            border-bottom: 1px solid #333;
            width: 300px;
            height: 90px;
            margin-bottom: 8px;
            text-align: center;
        }

        .signature-img {
            max-width: 250px;
            max-height: 75px;
        }

        .footer {
            margin-top: 40px;
            font-size: 9pt;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $contrato->nombre }}</h1>
        <p>ARMADILLO &ndash; Operaciones Temporales &nbsp;|&nbsp;
        </p>
    </div>

    <div>
        <div class="info-item"><span class="info-label">Contratista:</span> {{ $nombre }}</div>
        <div class="info-item"><span class="info-label">Identificaci&oacute;n:</span> {{ $tipo_doc }} {{ $num_doc }}
        </div>
    </div>

    <div class="section">{{ $contrato->terminos }}</div>

    <div class="signature-section">
        <p>En constancia de aceptaci&oacute;n, se firma digitalmente:</p>
        <div class="signature-box">
            <img src="{{ $signature }}" class="signature-img">
        </div>
        <p><strong>{{ $nombre }}</strong><br>
            {{ $tipo_doc }}. {{ $num_doc }}<br>
            <span style="font-size: 8pt; color: #555;">Firmado el {{ $date }} desde la IP {{ $ip }}</span>
        </p>
    </div>

    <div class="footer">
        Documento generado electr&oacute;nicamente. ID: {{ uniqid() }}
    </div>
</body>

</html>