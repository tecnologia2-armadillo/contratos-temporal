<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #f59e0b;
            margin: 0;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            color: #1e293b;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-grid {
            margin-bottom: 30px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            border-bottom: 1px solid #333;
            width: 300px;
            height: 100px;
            margin-bottom: 10px;
            text-align: center;
        }
        .signature-img {
            max-width: 250px;
            max-height: 80px;
        }
        .footer {
            margin-top: 50px;
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
        <h1>Contrato de Prestación de Servicios</h1>
        <p>ARMADILLO - Operaciones Temporales</p>
    </div>

    <div class="info-grid">
        <div class="info-item"><span class="info-label">Contratista:</span> {{ $person->nombre_completo }}</div>
        <div class="info-item"><span class="info-label">Identificación:</span> {{ $person->per_tipo_doc }} {{ $person->per_num_doc }}</div>
        <div class="info-item"><span class="info-label">Ciudad:</span> {{ $person->ciudad->ciu_nombre ?? 'N/A' }}</div>
        <div class="info-item"><span class="info-label">Fecha de Firma:</span> {{ $date }}</div>
    </div>

    <div class="section">
        <h3>CLÁUSULA PRIMERA: OBJETO DEL CONTRATO</h3>
        <p>El presente contrato tiene como objeto la prestación de servicios temporales por parte de el/la contratista para ARMADILLO, bajo las condiciones de tiempo, modo y lugar estipuladas en la orden de servicio correspondiente.</p>
    </div>

    <div class="section">
        <h3>CLÁUSULA SEGUNDA: DURACIÓN</h3>
        <p>La vigencia del presente contrato será determinada por la duración de la operación asignada, iniciando en la fecha de aceptación del presente documento.</p>
    </div>

    <div class="section">
        <h3>CLÁUSULA TERCERA: COMPROMISO DE CONFIDENCIALIDAD</h3>
        <p>El/la contratista se compromete a mantener absoluta reserva sobre toda la información corporativa, procesos, bases de datos y estrategias de ARMADILLO a las que tenga acceso durante el ejercicio de sus funciones. El incumplimiento de esta cláusula dará lugar a las acciones legales correspondientes.</p>
    </div>

    <div class="section">
        <h3>CLÁUSULA CUARTA: PAGOS Y HONORARIOS</h3>
        <p>Los pagos se realizarán de acuerdo a la tabla de honorarios vigente para el perfil del contratista, previa presentación de los requisitos de ley y reporte de horas laboradas por parte del supervisor de la operación.</p>
    </div>

    <div class="signature-section">
        <p>En constancia de aceptación, se firma digitalmente:</p>
        <div class="signature-box">
            <img src="{{ $signature }}" class="signature-img">
        </div>
        <p><strong>{{ $person->nombre_completo }}</strong><br>
        CC. {{ $person->per_num_doc }}</p>
    </div>

    <div class="footer">
        Este documento ha sido generado electrónicamente y tiene plena validez legal bajo las normativas vigentes de comercio electrónico y firma digital. ID de Verificación: {{ uniqid() }}
    </div>
</body>
</html>
