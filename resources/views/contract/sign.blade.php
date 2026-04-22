<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmar Contrato | Armadillo</title>
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
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .contract-text {
            background-color: rgba(15, 23, 42, 0.3);
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            max-height: 400px;
            overflow-y: auto;
            line-height: 1.6;
            font-size: 0.95rem;
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

        .footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
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

        .error-alert {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Contrato de Prestación de Servicios</h1>
            
            @if(session('error'))
                <div class="error-alert">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <p style="margin-bottom: 1rem; color: var(--text-muted);">Hola, <strong>{{ $person->nombre_completo }}</strong>. Por favor revisa el siguiente contrato y firma al final.</p>
            
            <div class="contract-text">
                <h3>CLÁUSULA PRIMERA: OBJETO DEL CONTRATO</h3>
                <p>El presente contrato tiene como objeto la prestación de servicios temporales por parte de el/la contratista para ARMADILLO, bajo las condiciones de tiempo, modo y lugar estipuladas en la orden de servicio correspondiente.</p>
                
                <br>
                <h3>CLÁUSULA SEGUNDA: DURACIÓN</h3>
                <p>La vigencia del presente contrato será determinada por la duración de la operación asignada, iniciando en la fecha de aceptación del presente documento.</p>
                
                <br>
                <h3>CLÁUSULA TERCERA: COMPROMISO DE CONFIDENCIALIDAD</h3>
                <p>El/la contratista se compromete a mantener absoluta reserva sobre toda la información corporativa, procesos, bases de datos y estrategias de ARMADILLO a las que tenga acceso durante el ejercicio de sus funciones.</p>
                
                <br>
                <h3>CLÁUSULA CUARTA: PAGOS Y HONORARIOS</h3>
                <p>Los pagos se realizarán de acuerdo a la tabla de honorarios vigente para el perfil del contratista, previa presentación de los requisitos de ley y reporte de horas laboradas.</p>
                
                <br>
                <p>Al firmar este documento, el/la contratista declara que ha leído y aceptado todos los términos y condiciones aquí presentados.</p>
            </div>

            <form id="signature-form" action="{{ route('contract.sign.post', $person->signature_token) }}" method="POST">
                @csrf
                <div class="signature-area">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="font-weight: 600;">Tu Firma:</label>
                        <div style="font-size: 0.8rem;">
                            <button type="button" class="btn-outline" id="toggle-upload" style="padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem; cursor: pointer;">¿O subir foto?</button>
                        </div>
                    </div>

                    <div id="canvas-container">
                        <canvas id="signature-pad"></canvas>
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

                <div class="controls">
                    <button type="button" class="btn btn-outline" id="clear">Limpiar Firma</button>
                    <button type="submit" class="btn btn-primary" id="save">Firmar y Descargar PDF</button>
                </div>
            </form>
        </div>
        <div class="footer">
            © 2026 Armadillo - Sistema de Contratación Segura
        </div>
    </div>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <h2 style="color: var(--primary);">Guardando Contrato...</h2>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Cargando documento en Google Drive.</p>
    </div>

    <!-- Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Resize canvas correctly
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

        // Toggle logic
        const toggleBtn = document.getElementById('toggle-upload');
        const canvasContainer = document.getElementById('canvas-container');
        const uploadContainer = document.getElementById('upload-container');
        let mode = 'canvas'; // 'canvas' or 'upload'
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

        // Photo upload logic
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
                } else {
                    document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
                }
            } else {
                if (!uploadedFileBase64) {
                    alert('Por favor, selecciona una imagen para tu firma.');
                    e.preventDefault();
                } else {
                    document.getElementById('signature-input').value = uploadedFileBase64;
                }
            }

            // Show loading and disable button
            document.getElementById('loading-overlay').style.display = 'flex';
            document.getElementById('save').disabled = true;
            document.getElementById('save').innerText = 'Procesando...';
        });
    </script>
</body>
</html>
