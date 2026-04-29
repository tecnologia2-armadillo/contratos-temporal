<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmar | {{ $contrato->nombre }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#f59e0b; --primary-hover:#d97706; --bg-dark:#0f172a; --bg-card:#1e293b; --text-main:#f8fafc; --text-muted:#94a3b8; --danger:#ef4444; --border:rgba(255,255,255,0.1); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; background:radial-gradient(circle at top right,#1e293b,#0f172a); color:var(--text-main); min-height:100vh; padding:2rem 1rem; display:flex; justify-content:center; }
        .container { max-width:800px; width:100%; }
        .card { background:var(--bg-card); border-radius:1.5rem; padding:2.5rem; border:1px solid var(--border); box-shadow:0 25px 50px -12px rgba(0,0,0,.5); margin-bottom:2rem; }
        .contrato-badge { display:inline-flex; align-items:center; gap:.5rem; background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.2); color:var(--primary); padding:.35rem .85rem; border-radius:9999px; font-size:.75rem; font-weight:700; margin-bottom:1rem; }
        h1 { font-size:1.5rem; font-weight:700; margin-bottom:.35rem; }
        .person-meta { color:var(--text-muted); font-size:.9rem; margin-bottom:1.5rem; }
        .person-meta strong { color:var(--text-main); }
        .contract-text { background:rgba(15,23,42,.4); padding:1.5rem; border-radius:1rem; border:1px solid var(--border); margin-bottom:1.5rem; max-height:380px; overflow-y:auto; line-height:1.7; font-size:.9rem; white-space:pre-wrap; }
        .signature-label { font-weight:700; font-size:.95rem; margin-bottom:.75rem; display:block; }
        canvas { background:white; border-radius:.75rem; width:100%; height:180px; cursor:crosshair; border:2px solid var(--primary); display:block; }
        .controls { display:flex; justify-content:space-between; align-items:center; margin-top:1rem; flex-wrap:wrap; gap:.75rem; }
        .btn { padding:.7rem 1.4rem; border-radius:.6rem; font-weight:700; cursor:pointer; transition:all .3s; border:none; font-family:'Outfit',sans-serif; font-size:.875rem; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-hover)); color:#0f172a; box-shadow:0 4px 14px rgba(245,158,11,.3); }
        .btn-primary:hover { transform:translateY(-1px); }
        .btn-primary:disabled { opacity:.5; cursor:not-allowed; transform:none !important; }
        .btn-outline { background:transparent; border:1px solid var(--border); color:var(--text-muted); }
        .btn-outline:hover { color:var(--text-main); }
        .toggle-upload { background:transparent; border:1px solid rgba(255,255,255,.1); color:var(--text-muted); padding:.25rem .65rem; border-radius:.35rem; cursor:pointer; font-size:.75rem; font-family:'Outfit',sans-serif; }
        .upload-zone { background:rgba(255,255,255,.03); border:2px dashed var(--primary); border-radius:.75rem; padding:2rem; text-align:center; }
        #loading-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.92); z-index:9999; flex-direction:column; align-items:center; justify-content:center; backdrop-filter:blur(6px); }
        .spinner { width:50px; height:50px; border:5px solid rgba(245,158,11,.2); border-top:5px solid var(--primary); border-radius:50%; animation:spin 1s linear infinite; margin-bottom:1.5rem; }
        @keyframes spin { to { transform:rotate(360deg); } }
        .footer { text-align:center; color:var(--text-muted); font-size:.8rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="contrato-badge">📋 {{ $contrato->nombre }}</div>
            <h1>Firma tu Contrato</h1>
            <p class="person-meta">
                Hola, <strong>{{ $person->nombre }} {{ $person->apellido }}</strong>
                &nbsp;·&nbsp; {{ $person->tipo_identificacion }}: {{ $person->identificacion }}
                <br>Lee el contrato completo y firma al final.
            </p>

            @if(session('error'))
                <div style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);padding:.85rem 1rem;border-radius:.75rem;margin-bottom:1.25rem;font-size:.875rem;">⚠️ {{ session('error') }}</div>
            @endif

            <div class="contract-text" id="contract-box">{{ $contrato->terminos }}</div>

            <form id="signature-form" action="{{ route('firmar.sign_nv.post', [$contrato->id, $person->id]) }}" method="POST">
                @csrf
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;">
                    <span class="signature-label">Tu Firma:</span>
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

                <div class="controls">
                    <button type="button" class="btn btn-outline" id="clear">Limpiar</button>
                    <button type="submit" class="btn btn-primary" id="save" disabled>Lee el contrato hasta el final</button>
                </div>
            </form>
        </div>
        <p class="footer">© {{ date('Y') }} Operadores Armadillo SAS &nbsp;·&nbsp; Firma Digital Segura</p>
    </div>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <h2 style="color:var(--primary)">Guardando Contrato...</h2>
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
        document.getElementById('clear').addEventListener('click', () => { signaturePad.clear(); uploadedBase64 = null; document.getElementById('photo-name').innerText = ''; });
        document.getElementById('signature-photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) { document.getElementById('photo-name').innerText = 'Seleccionado: ' + file.name; const reader = new FileReader(); reader.onload = ev => { uploadedBase64 = ev.target.result; }; reader.readAsDataURL(file); }
        });
        document.getElementById('signature-form').addEventListener('submit', function(e) {
            if (mode === 'canvas') { if (signaturePad.isEmpty()) { alert('Por favor ingresa tu firma.'); e.preventDefault(); return; } document.getElementById('signature-input').value = signaturePad.toDataURL('image/png'); }
            else { if (!uploadedBase64) { alert('Por favor selecciona una imagen.'); e.preventDefault(); return; } document.getElementById('signature-input').value = uploadedBase64; }
            document.getElementById('loading-overlay').style.display = 'flex';
            document.getElementById('save').disabled = true;
        });
        const contractBox = document.getElementById('contract-box');
        const saveBtn = document.getElementById('save');
        let scrolled = false;
        function checkScroll() {
            if (scrolled) return;
            if (contractBox.scrollHeight - contractBox.scrollTop <= contractBox.clientHeight + 20) { scrolled = true; saveBtn.disabled = false; saveBtn.innerText = 'Firmar y Descargar PDF'; }
        }
        setTimeout(checkScroll, 100); contractBox.addEventListener('scroll', checkScroll);
    </script>
</body>
</html>
