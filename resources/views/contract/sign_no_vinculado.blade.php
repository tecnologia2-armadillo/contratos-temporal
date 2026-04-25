<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Firma Contrato | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f59e0b;
            --primary-hover: #d97706;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            justify-content: center;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        .card {
            background-color: var(--bg-card);
            border-radius: 1.5rem;
            padding: 3rem;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            margin-bottom: 2rem;
        }

        h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        input, select {
            padding: 0.75rem 1rem;
            background-color: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: white;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .contract-text {
            background-color: rgba(15, 23, 42, 0.3);
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            max-height: 300px;
            overflow-y: auto;
            line-height: 1.6;
            font-size: 0.90rem;
            color: var(--text-main);
        }

        .signature-area {
            margin-top: 2rem;
        }

        canvas {
            background-color: white;
            border-radius: 0.75rem;
            width: 100%;
            height: 200px;
            cursor: crosshair;
            border: 2px solid var(--primary);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--bg-dark);
            width: 100%;
            margin-top: 1.5rem;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .btn-outline:hover {
            color: var(--text-main);
            border-color: var(--text-main);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: rgba(148, 163, 184, 0.5); /* muted */
            color: var(--text-main);
            border: none;
            transform: none !important;
        }

        .footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* Loading Overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.9);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(245, 158, 11, 0.2);
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media(max-width: 600px) {
            body {
                padding: 1rem;
            }
            .card {
                padding: 1.5rem;
            }
            .contract-text {
                padding: 1rem;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Registro y Contrato</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Por favor, completa tus datos para registrarte como personal de apoyo (no vinculado) y firma el acuerdo de prestación de servicios.</p>
            
            @if(session('error'))
                <div class="error-alert">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-alert">
                    <ul style="margin-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="signature-form" action="{{ route('contract.no_vinculado.post') }}" method="POST">
                @csrf

                <h3 style="margin-bottom: 1rem; color: var(--primary); font-size: 1.2rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Datos Personales</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Identificación</label>
                        <select name="tipo_identificacion" required>
                            <option value="CC" {{ old('tipo_identificacion') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="CE" {{ old('tipo_identificacion') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PEP" {{ old('tipo_identificacion') == 'PEP' ? 'selected' : '' }}>PEP</option>
                            <option value="PPT" {{ old('tipo_identificacion') == 'PPT' ? 'selected' : '' }}>PPT</option>
                            <option value="TI" {{ old('tipo_identificacion') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número de Identificación</label>
                        <input type="text" name="identificacion" value="{{ old('identificacion') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono (WhatsApp)</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" required>
                    </div>
                    <div class="form-group full">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem; color: var(--primary); font-size: 1.2rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; margin-top: 1rem;">Datos Bancarios</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Banco / Entidad</label>
                        <input type="text" name="banco" value="{{ old('banco') }}" placeholder="Ej: Bancolombia, Nequi, Daviplata" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Cuenta</label>
                        <select name="tipo_cuenta" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="Ahorros" {{ old('tipo_cuenta') == 'Ahorros' ? 'selected' : '' }}>Ahorros</option>
                            <option value="Corriente" {{ old('tipo_cuenta') == 'Corriente' ? 'selected' : '' }}>Corriente</option>
                            <option value="Digital" {{ old('tipo_cuenta') == 'Digital' ? 'selected' : '' }}>Billetera Digital (Nequi, Daviplata)</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Número de Cuenta</label>
                        <input type="text" name="numero_cuenta" value="{{ old('numero_cuenta') }}" required>
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem; color: var(--primary); font-size: 1.2rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; margin-top: 1rem;">Acuerdo de Prestación de Servicios</h3>
                <p style="margin-bottom: 1rem; font-size: 0.85rem; color: var(--text-muted);">Por favor, lee atentamente el siguiente texto antes de firmar.</p>
                
                <div class="contract-text" id="contract-box">
                    <p style="text-align: justify; margin-bottom: 15px;">Por medio del presente acuerdo, la persona que suscribe, en adelante EL CONTRATISTA, declara bajo la gravedad de juramento que actuará como proveedor independiente de servicios de apoyo logístico para eventos de cualquier naturaleza organizados por OPERADORES ARMADILLO S.A.S., NIT 901.346.590-8, con domicilio en Bogotá D.C., en adelante EL CONTRATANTE, desempeñando funciones como control de accesos, lectura de boletería, manejo de tránsito, apoyo en alimentación u otras tareas logísticas definidas para cada evento, las cuales ejecutará de manera autónoma, bajo su cuenta y riesgo, con sus conocimientos, habilidades y buena disposición, sin que exista vínculo laboral ni relación de subordinación; las indicaciones de coordinadores o directores corresponden únicamente a lineamientos operativos para la adecuada prestación del servicio contratado. EL CONTRATANTE facilitará prendas logísticas, herramientas y materiales necesarios para la buena prestación de los servicios, los cuales se entregan a título de préstamo, no constituyen dotación laboral y deberán ser devueltos al finalizar la labor. EL CONTRATISTA manifiesta que su participación no implica exclusividad ni continuidad y que podrá prestar servicios a otras personas naturales o jurídicas sin restricción alguna, siendo convocado únicamente cuando EL CONTRATANTE requiera sus servicios, pudiendo aceptarlos o rechazarlos libremente.</p>
            
                    <p style="text-align: justify; margin-bottom: 15px;">Declara asimismo que conoce y acepta que la prestación del servicio se realizará en la franja horaria requerida para el evento, conforme a las necesidades operativas informadas previamente y aceptadas por el proveedor independiente al confirmar su disponibilidad, sin que ello implique dirección o subordinación laboral, asumiendo los riesgos inherentes a su actividad y la plena responsabilidad por la calidad y cuidado en el desarrollo de sus actividades, comprometiéndose a cumplir las normas de seguridad, convivencia y protocolos exigidos en cada sitio de trabajo, ética y buena conducta en sus relaciones con EL CONTRATANTE, otros contratistas, clientes y terceros, así como a asistir a las inducciones informativas o de orientación general que se realicen, sin que estas constituyan subordinación.</p>

                    <p style="text-align: justify; margin-bottom: 15px;">Manifiesta que prestará sus servicios como persona natural independiente, declara conocer y cumplir con las normas vigentes en materia de seguridad social como independiente, y que no tendrá derecho a prestaciones sociales, indemnizaciones, recargos o beneficios laborales de ninguna naturaleza en su calidad de independencia, considerando que los pagos recibidos corresponden únicamente a honorarios por servicios prestados y no constituyen pagos salariales en ningún caso. Cualquier pago de honorarios como apoyo logístico adicional se otorga de manera voluntaria por EL CONTRATANTE y no constituye salario ni genera derechos laborales. El pago se realizará conforme a los valores previamente informados y aceptados, constituyendo honorarios como proveedor independiente e incluyendo todos sus costos, gastos y obligaciones fiscales o parafiscales.</p>

                    <p style="text-align: justify; margin-bottom: 15px;">Declara que responderá frente a cualquier situación que se derive exclusivamente de su actuación profesional durante la ejecución de sus servicios, manteniendo indemne a EL CONTRATANTE frente a situaciones imputables a su actuación directa. EL CONTRATANTE podrá dar por terminado este acuerdo de manera anticipada y unilateral en caso de incumplimiento de las obligaciones aquí pactadas, de la prestación defectuosa de sus servicios o del incumplimiento en su asistencia conforme a la programación establecida. EL CONTRATISTA se obliga a guardar confidencialidad sobre toda información relativa al CONTRATANTE, sus clientes, pagos y eventos durante el término de ejecución del acuerdo y hasta seis (6) meses posteriores a su terminación.</p>

                    <p style="text-align: justify; margin-bottom: 15px;">EL CONTRATISTA autoriza el tratamiento de sus datos personales por parte de EL CONTRATANTE para fines administrativos, operativos y de contratación, conforme a la política de protección de datos del CONTRATANTE, la cual declara conocer, y para ejercer sus derechos podrá comunicarse al correo descrito en este documento. EL CONTRATANTE declara que este acuerdo constituye prueba suficiente de su carácter no subordinado para cualquier proceso administrativo, civil, laboral o judicial, y tendrá efectos como acuerdo marco para futuras participaciones en eventos logísticos organizados por EL CONTRATANTE, sin necesidad de nueva suscripción, salvo que las partes convengan otra cosa por escrito, declarando haber leído y comprendido su integridad y comprometiéndose a prestar sus servicios en los términos aquí establecidos. Este acuerdo tendrá una vigencia inicial de dos (2) años, prorrogable automáticamente por iguales periodos, salvo aviso en contrario por cualquiera de las partes.</p>

                    <p style="text-align: justify; margin-bottom: 15px; font-weight: bold; color: var(--primary);">Declaro que he leído, comprendido y acepto los términos y condiciones establecidos en este acuerdo, comprometiéndome a prestar mis servicios conforme a lo aquí dispuesto.</p>
                </div>

                <div class="signature-area">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="font-weight: 600; color: white;">Firma Digital Integrada:</label>
                        <div style="font-size: 0.8rem;">
                            <button type="button" class="btn-outline" id="toggle-upload" style="padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem; cursor: pointer;">¿O subir foto?</button>
                        </div>
                    </div>

                    <div id="canvas-container">
                        <canvas id="signature-pad"></canvas>
                        <div class="controls">
                            <button type="button" class="btn btn-outline" id="clear" style="font-size:0.8rem; padding:0.5rem 1rem;">Limpiar Firma</button>
                        </div>
                    </div>

                    <div id="upload-container" style="display: none;">
                        <div style="background-color: rgba(255, 255, 255, 0.05); padding: 2rem; border-radius: 0.75rem; border: 2px dashed var(--primary); text-align: center;">
                            <input type="file" id="signature-photo" accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-outline" onclick="document.getElementById('signature-photo').click()">Seleccionar Imagen de Firma</button>
                            <p id="photo-name" style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--success);"></p>
                        </div>
                    </div>

                    <input type="hidden" name="signature" id="signature-input">
                </div>

                <button type="submit" class="btn btn-primary" id="save" disabled>Lee el acuerdo hasta el final</button>
            </form>
        </div>
        <div class="footer">
            © 2026 Armadillo - Sistema de Contratación Segura
        </div>
    </div>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <h2 style="color: var(--primary); font-size:1.5rem; text-align:center;">Guardando Datos y Firma...</h2>
        <p style="color: var(--text-muted); margin-top: 0.5rem; text-align:center;">Por favor espera, no cierres esta ventana.</p>
    </div>

    <!-- Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        document.getElementById('clear').addEventListener('click', function() {
            signaturePad.clear();
            document.getElementById('signature-photo').value = "";
            document.getElementById('photo-name').innerText = "";
            uploadedFileBase64 = null;
        });

        const toggleBtn = document.getElementById('toggle-upload');
        const canvasContainer = document.getElementById('canvas-container');
        const uploadContainer = document.getElementById('upload-container');
        let mode = 'canvas'; 
        let uploadedFileBase64 = null;

        toggleBtn.addEventListener('click', function() {
            if (mode === 'canvas') {
                mode = 'upload';
                canvasContainer.style.display = 'none';
                uploadContainer.style.display = 'block';
                this.innerText = '¿O dibujar firma?';
            } else {
                mode = 'canvas';
                canvasContainer.style.display = 'block';
                uploadContainer.style.display = 'none';
                this.innerText = '¿O subir foto?';
            }
        });

        document.getElementById('signature-photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('photo-name').innerText = "Seleccionado: " + file.name;
                const reader = new FileReader();
                reader.onload = function(event) {
                    uploadedFileBase64 = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        const form = document.getElementById('signature-form');
        form.addEventListener('submit', function(e) {
            if (mode === 'canvas') {
                if (signaturePad.isEmpty()) {
                    alert('Por favor, ingresa tu firma antes de continuar.');
                    e.preventDefault();
                    return;
                } else {
                    document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
                }
            } else {
                if (!uploadedFileBase64) {
                    alert('Por favor, selecciona una imagen para tu firma.');
                    e.preventDefault();
                    return;
                } else {
                    document.getElementById('signature-input').value = uploadedFileBase64;
                }
            }

            document.getElementById('loading-overlay').style.display = 'flex';
            document.getElementById('save').disabled = true;
            document.getElementById('save').innerText = 'Procesando...';
        });

        // Reader lock logic
        const contractBox = document.getElementById('contract-box');
        const saveBtn = document.getElementById('save');
        let hasScrolledToBottom = false;

        function checkScroll() {
            if (hasScrolledToBottom) return;
            // Add a buffer of 20px to account for any rounding issues in different browsers
            if (contractBox.scrollHeight - contractBox.scrollTop <= contractBox.clientHeight + 20) {
                hasScrolledToBottom = true;
                saveBtn.disabled = false;
                saveBtn.innerText = 'Aceptar y Enviar Registro';
            }
        }

        // Check initially in case screen is large enough that scroll isn't needed
        setTimeout(checkScroll, 100);
        contractBox.addEventListener('scroll', checkScroll);
    </script>
</body>
</html>
