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
        <p>ARMADILLO - Personal de Apoyo</p>
    </div>

    <div class="info-grid">
        <div class="info-item"><span class="info-label">Contratista:</span> {{ $person['nombre_completo'] }}</div>
        <div class="info-item"><span class="info-label">Identificación:</span> {{ $person['tipo_identificacion'] }}
            {{ $person['identificacion'] }}</div>
    </div>

    <div class="section">
        <p style="text-align: justify; margin-bottom: 15px;">Por medio del presente acuerdo, la persona que suscribe, en
            adelante EL CONTRATISTA, declara bajo la gravedad de juramento que actuará como proveedor independiente de
            servicios de apoyo logístico para eventos de cualquier naturaleza organizados por OPERADORES ARMADILLO
            S.A.S., NIT 901.346.590-8, con domicilio en Bogotá D.C., en adelante EL CONTRATANTE, desempeñando funciones
            como control de accesos, lectura de boletería, manejo de tránsito, apoyo en alimentación u otras tareas
            logísticas definidas para cada evento, las cuales ejecutará de manera autónoma, bajo su cuenta y riesgo, con
            sus conocimientos, habilidades y buena disposición, sin que exista vínculo laboral ni relación de
            subordinación; las indicaciones de coordinadores o directores corresponden únicamente a lineamientos
            operativos para la adecuada prestación del servicio contratado. EL CONTRATANTE facilitará prendas
            logísticas, herramientas y materiales necesarios para la buena prestación de los servicios, los cuales se
            entregan a título de préstamo, no constituyen dotación laboral y deberán ser devueltos al finalizar la
            labor. EL CONTRATISTA manifiesta que su participación no implica exclusividad ni continuidad y que podrá
            prestar servicios a otras personas naturales o jurídicas sin restricción alguna, siendo convocado únicamente
            cuando EL CONTRATANTE requiera sus servicios, pudiendo aceptarlos o rechazarlos libremente.</p>

        <p style="text-align: justify; margin-bottom: 15px;">Declara asimismo que conoce y acepta que la prestación del
            servicio se realizará en la franja horaria requerida para el evento, conforme a las necesidades operativas
            informadas previamente y aceptadas por el proveedor independiente al confirmar su disponibilidad, sin que
            ello implique dirección o subordinación laboral, asumiendo los riesgos inherentes a su actividad y la plena
            responsabilidad por la calidad y cuidado en el desarrollo de sus actividades, comprometiéndose a cumplir las
            normas de seguridad, convivencia y protocolos exigidos en cada sitio de trabajo, ética y buena conducta en
            sus relaciones con EL CONTRATANTE, otros contratistas, clientes y terceros, así como a asistir a las
            inducciones informativas o de orientación general que se realicen, sin que estas constituyan subordinación.
        </p>

        <p style="text-align: justify; margin-bottom: 15px;">Manifiesta que prestará sus servicios como persona natural
            independiente, declara conocer y cumplir con las normas vigentes en materia de seguridad social como
            independiente, y que no tendrá derecho a prestaciones sociales, indemnizaciones, recargos o beneficios
            laborales de ninguna naturaleza en su calidad de independencia, considerando que los pagos recibidos
            corresponden únicamente a honorarios por servicios prestados y no constituyen pagos salariales en ningún
            caso. Cualquier pago de honorarios como apoyo logístico adicional se otorga de manera voluntaria por EL
            CONTRATANTE y no constituye salario ni genera derechos laborales. El pago se realizará conforme a los
            valores previamente informados y aceptados, constituyendo honorarios como proveedor independiente e
            incluyendo todos sus costos, gastos y obligaciones fiscales o parafiscales.</p>

        <p style="text-align: justify; margin-bottom: 15px;">Declara que responderá frente a cualquier situación que se
            derive exclusivamente de su actuación profesional durante la ejecución de sus servicios, manteniendo indemne
            a EL CONTRATANTE frente a situaciones imputables a su actuación directa. EL CONTRATANTE podrá dar por
            terminado este acuerdo de manera anticipada y unilateral en caso de incumplimiento de las obligaciones aquí
            pactadas, de la prestación defectuosa de sus servicios o del incumplimiento en su asistencia conforme a la
            programación establecida. EL CONTRATISTA se obliga a guardar confidencialidad sobre toda información
            relativa al CONTRATANTE, sus clientes, pagos y eventos durante el término de ejecución del acuerdo y hasta
            seis (6) meses posteriores a su terminación.</p>

        <p style="text-align: justify; margin-bottom: 15px;">EL CONTRATISTA autoriza el tratamiento de sus datos
            personales por parte de EL CONTRATANTE para fines administrativos, operativos y de contratación, conforme a
            la política de protección de datos del CONTRATANTE, la cual declara conocer, y para ejercer sus derechos
            podrá comunicarse al correo descrito en este documento. EL CONTRATANTE declara que este acuerdo constituye
            prueba suficiente de su carácter no subordinado para cualquier proceso administrativo, civil, laboral o
            judicial, y tendrá efectos como acuerdo marco para futuras participaciones en eventos logísticos organizados
            por EL CONTRATANTE, sin necesidad de nueva suscripción, salvo que las partes convengan otra cosa por
            escrito, declarando haber leído y comprendido su integridad y comprometiéndose a prestar sus servicios en
            los términos aquí establecidos. Este acuerdo tendrá una vigencia inicial de dos (2) años, prorrogable
            automáticamente por iguales periodos, salvo aviso en contrario por cualquiera de las partes.</p>

        <p style="text-align: justify; margin-bottom: 15px; font-weight: bold;">Declaro que he leído, comprendido y
            acepto los términos y condiciones establecidos en este acuerdo, comprometiéndome a prestar mis servicios
            conforme a lo aquí dispuesto.</p>

        <p style="text-align: center; margin-top: 20px; font-size: 0.9em; color: #555;">
            <strong>OPERADORES ARMADILLO SAS NIT. 901346590-8</strong><br>
            Cra. 62 No 97-78, Bogotá, Colombia<br>
            atencion@operadoresarmadillo.com
        </p>
    </div>

    <div class="signature-section">
        <p>En constancia de aceptación, se firma digitalmente:</p>
        <div class="signature-box">
            <img src="{{ $signature }}" class="signature-img">
        </div>
        <p><strong>{{ $person['nombre_completo'] }}</strong><br>
            {{ $person['tipo_identificacion'] }} {{ $person['identificacion'] }}<br>
            <span style="font-size: 8pt; color: #555;">Firmado el {{ $date }} desde la IP {{ $ip }}</span>
        </p>
    </div>

    <div class="footer">
        Este documento ha sido generado electrónicamente y tiene plena validez legal bajo las normativas vigentes de
        comercio electrónico y firma digital. ID de Verificación: {{ uniqid() }}
    </div>
</body>

</html>