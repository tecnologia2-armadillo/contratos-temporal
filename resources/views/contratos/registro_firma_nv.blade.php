<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Firma | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#f59e0b; --primary-hover:#d97706; --bg-dark:#0f172a; --bg-card:#1e293b; --text-main:#f8fafc; --text-muted:#94a3b8; --border:rgba(255,255,255,0.1); --input-bg: rgba(15,23,42,0.6); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; background:radial-gradient(circle at top right,#1e293b,#0f172a); color:var(--text-main); min-height:100vh; padding:2rem 1rem; display:flex; justify-content:center; }
        .container { max-width:800px; width:100%; }
        .card { background:var(--bg-card); border-radius:1.5rem; padding:2.5rem; border:1px solid var(--border); box-shadow:0 25px 50px -12px rgba(0,0,0,.5); margin-bottom:2rem; }
        
        h1 { font-size:1.75rem; font-weight:700; color:var(--primary); margin-bottom:1rem; text-align:center; }
        .info-alert { background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.2); color:var(--text-main); padding:1rem; border-radius:.75rem; margin-bottom:2rem; font-size:.9rem; text-align:center; }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:2rem; }
        .form-group { display:flex; flex-direction:column; gap:.5rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size:.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:.05em; }
        input, select { background:var(--input-bg); border:1px solid rgba(255,255,255,.1); border-radius:.6rem; color:var(--text-main); padding:.75rem 1rem; font-family:'Outfit',sans-serif; font-size:.95rem; outline:none; transition:all .2s; }
        input:focus, select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(245,158,11,.1); }
        
        .contract-section { margin-top:2rem; padding-top:2rem; border-top:1px solid var(--border); }
        .contract-text { background:rgba(15,23,42,.4); padding:1.5rem; border-radius:1rem; border:1px solid var(--border); margin-bottom:1.5rem; max-height:300px; overflow-y:auto; line-height:1.7; font-size:.9rem; white-space:pre-wrap; }
        
        .signature-area { margin-top:1.5rem; }
        .signature-label { font-weight:700; font-size:.95rem; margin-bottom:.75rem; display:block; }
        canvas { background:white; border-radius:.75rem; width:100%; height:180px; cursor:crosshair; border:2px solid var(--primary); display:block; }
        
        .toggle-upload { background:transparent; border:1px solid rgba(255,255,255,.1); color:var(--text-muted); padding:.25rem .65rem; border-radius:.35rem; cursor:pointer; font-size:.75rem; font-family:'Outfit',sans-serif; }
        .upload-zone { background:rgba(255,255,255,.03); border:2px dashed var(--primary); border-radius:.75rem; padding:2rem; text-align:center; }

        .controls { display:flex; justify-content:space-between; align-items:center; margin-top:1rem; flex-wrap:wrap; gap:.75rem; }
        .btn { padding:.8rem 1.5rem; border-radius:.75rem; font-weight:700; cursor:pointer; transition:all .3s; border:none; font-family:'Outfit',sans-serif; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-hover)); color:#0f172a; box-shadow:0 4px 14px rgba(245,158,11,.3); width:100%; margin-top:1rem; }
        .btn-primary:disabled { opacity:.5; cursor:not-allowed; }
        .btn-outline { background:transparent; border:1px solid var(--border); color:var(--text-muted); }
        
        @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .card { padding:1.5rem; } }
        
        #loading-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.92); z-index:9999; flex-direction:column; align-items:center; justify-content:center; backdrop-filter:blur(6px); }
        .spinner { width:50px; height:50px; border:5px solid rgba(245,158,11,.2); border-top:5px solid var(--primary); border-radius:50%; animation:spin 1s linear infinite; margin-bottom:1.5rem; }
        @keyframes spin { to { transform:rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Registro y Firma de Contrato</h1>
            <div class="info-alert">
                No encontramos tus datos en nuestro sistema. Por favor regístrate para proceder con la firma del contrato: <strong>{{ $contrato->nombre }}</strong>
            </div>

            <form id="registro-firma-form" action="{{ route('firmar.registro_nv.post', $contrato->id) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo_identificacion" value="{{ $tipo }}">
                <input type="hidden" name="identificacion" value="{{ $numero }}">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Tipo Doc</label>
                        <input type="text" value="{{ $tipo }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Número Doc</label>
                        <input type="text" value="{{ $numero }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombres</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Tus nombres">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellidos</label>
                        <input type="text" id="apellido" name="apellido" required placeholder="Tus apellidos">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono / WhatsApp</label>
                        <input type="text" id="telefono" name="telefono" required placeholder="Ej: 3001234567">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" required placeholder="tu@correo.com">
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="banco">Banco</label>
                        <input type="text" id="banco" name="banco" required placeholder="Ej: Bancolombia, Nequi...">
                    </div>
                    <div class="form-group">
                        <label for="tipo_cuenta">Tipo de Cuenta</label>
                        <select id="tipo_cuenta" name="tipo_cuenta" required>
                            <option value="Ahorros">Ahorros</option>
                            <option value="Corriente">Corriente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numero_cuenta">Número de Cuenta</label>
                        <input type="text" id="numero_cuenta" name="numero_cuenta" required placeholder="Número de cuenta">
                    </div>
                </div>

                <div class="contract-section">
                    <h3 style="margin-bottom:1rem; font-size:1.1rem;">Términos del Contrato</h3>
                    <div class="contract-text" id="contract-box">{{ $contrato->terminos }}</div>
                    
                    <div class="signature-area">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;">
                            <span class="signature-label">Tu Firma Digital:</span>
                            <button type="button" class="toggle-upload" id="toggle-upload">¿O subir foto?</button>
                        </div>
                        
                        <div id="canvas-container">
                            <canvas id="signature-pad"></canvas>
                        </div>
                        <div id="upload-container" style="display:none;">
                            <div class="upload-zone">
                                <input type="file" id="signature-photo" accept="image/*" style="display:none;">
                                <button type="button" class="btn btn-outline" onclick="document.getElementById('signature-photo').click()">📷 Seleccionar imagen de firma</button>
                                <p id="photo-name" style="margin-top:.5rem;font-size:.8rem;color:var(--primary);"></p>
                            </div>
                        </div>
                        <input type="hidden" name="signature" id="signature-input">
                    </div>

                    <div class="controls">
                        <button type="button" class="btn btn-outline" id="clear">Limpiar Firma</button>
                    </div>

                    <button type="submit" class="btn btn-primary" id="save" disabled>Lee el contrato hasta el final para habilitar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <h2 style="color:var(--primary)">Procesando Registro y Firma...</h2>
        <p style="color:var(--text-muted);margin-top:.5rem">Cargando documento en Google Drive.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, { backgroundColor:'rgba(255,255,255,0)', penColor:'rgb(0,0,0)' });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width  = canvas.offsetWidth  * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        window.onresize = resizeCanvas; resizeCanvas();

        let mode = 'canvas', uploadedBase64 = null;
        document.getElementById('toggle-upload').addEventListener('click', function() {
            mode = mode === 'canvas' ? 'upload' : 'canvas';
            document.getElementById('canvas-container').style.display = mode === 'canvas' ? 'block' : 'none';
            document.getElementById('upload-container').style.display = mode === 'upload' ? 'block' : 'none';
            this.innerText = mode === 'canvas' ? '¿O subir foto?' : '¿O dibujar firma?';
        });

        document.getElementById('clear').addEventListener('click', () => {
            signaturePad.clear();
            uploadedBase64 = null;
            document.getElementById('photo-name').innerText = '';
        });

        document.getElementById('signature-photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('photo-name').innerText = 'Seleccionado: ' + file.name;
                const reader = new FileReader();
                reader.onload = ev => { uploadedBase64 = ev.target.result; };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('registro-firma-form').addEventListener('submit', function(e) {
            if (mode === 'canvas') {
                if (signaturePad.isEmpty()) { alert('Por favor ingresa tu firma.'); e.preventDefault(); return; }
                document.getElementById('signature-input').value = signaturePad.toDataURL('image/png');
            } else {
                if (!uploadedBase64) { alert('Por favor selecciona una imagen de firma.'); e.preventDefault(); return; }
                document.getElementById('signature-input').value = uploadedBase64;
            }
            document.getElementById('loading-overlay').style.display = 'flex';
        });

        const contractBox = document.getElementById('contract-box');
        const saveBtn = document.getElementById('save');
        let scrolled = false;
        function checkScroll() {
            if (scrolled) return;
            if (contractBox.scrollHeight - contractBox.scrollTop <= contractBox.clientHeight + 20) {
                scrolled = true;
                saveBtn.disabled = false;
                saveBtn.innerText = 'Registrarse y Firmar Contrato';
            }
        }
        setTimeout(checkScroll, 100);
        contractBox.addEventListener('scroll', checkScroll);
    </script>
</body>
</html>
