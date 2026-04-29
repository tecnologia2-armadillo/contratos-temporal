<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Exitosa | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#f59e0b; --bg-dark:#0f172a; --bg-card:#1e293b; --text-main:#f8fafc; --text-muted:#94a3b8; --success:#10b981; --border:rgba(255,255,255,0.08); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; background:radial-gradient(circle at top right,#1e293b,#0f172a); color:var(--text-main); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
        .card { background:var(--bg-card); border:1px solid var(--border); border-radius:1.5rem; padding:3rem 2.5rem; max-width:480px; width:100%; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,.4); animation:fadeUp .5s ease-out; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        .icon { font-size:4rem; margin-bottom:1.5rem; display:block; animation:pop .4s cubic-bezier(.175,.885,.32,1.275) .2s both; }
        @keyframes pop { from{opacity:0;transform:scale(.5)} to{opacity:1;transform:scale(1)} }
        h1 { font-size:1.75rem; font-weight:700; color:var(--success); margin-bottom:.5rem; }
        .subtitle { color:var(--text-muted); font-size:.95rem; margin-bottom:2rem; line-height:1.5; }
        .contrato-box { background:rgba(16,185,129,.07); border:1px solid rgba(16,185,129,.15); border-radius:.75rem; padding:1rem 1.25rem; margin-bottom:2rem; text-align:left; }
        .contrato-box p { font-size:.75rem; text-transform:uppercase; color:var(--success); font-weight:700; margin-bottom:.25rem; }
        .contrato-box h2 { font-size:1rem; font-weight:700; }
        .btn-drive { display:inline-flex; align-items:center; gap:.5rem; background:linear-gradient(135deg,var(--primary),#d97706); color:#0f172a; padding:.8rem 1.75rem; border-radius:.75rem; text-decoration:none; font-weight:700; font-size:.95rem; box-shadow:0 4px 18px rgba(245,158,11,.35); transition:all .3s; margin-bottom:1.5rem; }
        .btn-drive:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,.45); }
        .footer { color:var(--text-muted); font-size:.8rem; }
    </style>
</head>
<body>
    <div class="card">
        <span class="icon">✅</span>
        <h1>¡Contrato Firmado!</h1>
        <p class="subtitle">Hola <strong>{{ $nombre }}</strong>, tu firma ha sido registrada exitosamente.</p>

        <div class="contrato-box">
            <p>📋 Contrato firmado</p>
            <h2>{{ $contrato->nombre }}</h2>
        </div>

        @if($driveLink)
            <a href="{{ $driveLink }}" target="_blank" class="btn-drive">
                📄 Ver PDF del Contrato
            </a>
        @else
            <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:1.5rem;">El documento PDF se está procesando. Contacta al administrador si necesitas una copia.</p>
        @endif

        <p class="footer">© {{ date('Y') }} Operadores Armadillo SAS &nbsp;·&nbsp; Firma Digital Segura</p>
    </div>
</body>
</html>
