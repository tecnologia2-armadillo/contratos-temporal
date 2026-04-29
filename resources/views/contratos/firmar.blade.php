<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmar Contrato | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f59e0b; --primary-hover: #d97706;
            --bg-dark: #0f172a; --bg-card: #1e293b;
            --text-main: #f8fafc; --text-muted: #94a3b8;
            --success: #10b981; --danger: #ef4444;
            --border: rgba(255,255,255,0.08); --input-bg: rgba(15,23,42,0.6);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Outfit',sans-serif;
            background:radial-gradient(circle at top right,#1e293b,#0f172a);
            color:var(--text-main);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2rem 1rem;
        }

        .wrapper { width:100%; max-width:480px; }

        /* Logo */
        .logo {
            text-align:center;
            margin-bottom:2rem;
            animation: fadeDown .5s ease-out;
        }
        .logo span {
            font-size:1.75rem;
            font-weight:700;
            color:var(--primary);
            letter-spacing:-.025em;
        }
        .logo p { color:var(--text-muted); font-size:.875rem; margin-top:.25rem; }

        /* Card */
        .card {
            background:var(--bg-card);
            border:1px solid var(--border);
            border-radius:1.5rem;
            padding:2.5rem;
            box-shadow:0 25px 60px rgba(0,0,0,.4);
            animation: fadeUp .5s ease-out;
        }

        /* Contrato info */
        .contrato-header {
            background:rgba(245,158,11,.07);
            border:1px solid rgba(245,158,11,.15);
            border-radius:.75rem;
            padding:1rem 1.25rem;
            margin-bottom:2rem;
        }
        .contrato-header p { font-size:.75rem; text-transform:uppercase; color:var(--primary); font-weight:700; letter-spacing:.05em; margin-bottom:.35rem; }
        .contrato-header h2 { font-size:1.1rem; font-weight:700; color:var(--text-main); }
        .contrato-header .dates { display:flex; gap:1rem; margin-top:.5rem; flex-wrap:wrap; }
        .date-tag { font-size:.75rem; color:var(--text-muted); }

        /* Form */
        .section-title {
            font-size:.95rem;
            font-weight:700;
            color:var(--text-main);
            margin-bottom:1.25rem;
        }

        .form-group { margin-bottom:1.25rem; }
        .form-group label {
            display:block;
            font-size:.75rem;
            text-transform:uppercase;
            color:var(--text-muted);
            font-weight:600;
            letter-spacing:.04em;
            margin-bottom:.5rem;
        }
        .form-group select,
        .form-group input {
            width:100%;
            background:var(--input-bg);
            border:1px solid rgba(255,255,255,.1);
            border-radius:.65rem;
            color:var(--text-main);
            padding:.7rem 1rem;
            font-family:'Outfit',sans-serif;
            font-size:.95rem;
            outline:none;
            transition:border-color .2s, box-shadow .2s;
            appearance:none;
        }
        .form-group select { background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem; }
        .form-group select option { background:#1e293b; color:#f8fafc; }
        .form-group select:focus,
        .form-group input:focus {
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(245,158,11,.12);
        }
        .form-group input::placeholder { color:var(--text-muted); }

        /* Error */
        .error-msg {
            background:rgba(239,68,68,.1);
            border:1px solid rgba(239,68,68,.2);
            border-radius:.65rem;
            color:#f87171;
            padding:.85rem 1rem;
            font-size:.875rem;
            margin-bottom:1.25rem;
            display:flex;
            align-items:flex-start;
            gap:.6rem;
        }

        /* Button */
        .btn-submit {
            width:100%;
            background:linear-gradient(135deg,var(--primary),var(--primary-hover));
            color:#0f172a;
            border:none;
            padding:.85rem;
            border-radius:.75rem;
            font-size:1rem;
            font-weight:700;
            font-family:'Outfit',sans-serif;
            cursor:pointer;
            transition:all .3s;
            box-shadow:0 4px 18px rgba(245,158,11,.35);
            margin-top:.5rem;
        }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,.45); }
        .btn-submit:active { transform:translateY(0); }

        /* Footer */
        .footer { text-align:center; color:var(--text-muted); font-size:.8rem; margin-top:1.5rem; }

        @keyframes fadeDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeUp   { from{opacity:0;transform:translateY(12px)}  to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="logo">
            <span>ARMADILLO</span>
            <p>Sistema de contratación digital</p>
        </div>

        <div class="card">
            <!-- Info del contrato -->
            <div class="contrato-header">
                <p>📋 Contrato</p>
                <h2>{{ $contrato->nombre }}</h2>
                <div class="dates">
                    <span class="date-tag">📅 Inicio: {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</span>
                    <span class="date-tag">📅 Fin: {{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}</span>
                </div>
            </div>

            <p class="section-title">Ingresa tu identificación para continuar</p>

            @if($errors->any())
                <div class="error-msg">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('firmar.validar', $contrato->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="tipo_identificacion">Tipo de identificación</label>
                    <select id="tipo_identificacion" name="tipo_identificacion" required>
                        <option value="" disabled selected>Selecciona...</option>
                        <option value="CC"  {{ old('tipo_identificacion') === 'CC'  ? 'selected' : '' }}>Cédula de Ciudadanía (CC)</option>
                        <option value="CE"  {{ old('tipo_identificacion') === 'CE'  ? 'selected' : '' }}>Cédula de Extranjería (CE)</option>
                        <option value="PA"  {{ old('tipo_identificacion') === 'PA'  ? 'selected' : '' }}>Pasaporte (PA)</option>
                        <option value="TI"  {{ old('tipo_identificacion') === 'TI'  ? 'selected' : '' }}>Tarjeta de Identidad (TI)</option>
                        <option value="NIT" {{ old('tipo_identificacion') === 'NIT' ? 'selected' : '' }}>NIT</option>
                        <option value="PPT" {{ old('tipo_identificacion') === 'PPT' ? 'selected' : '' }}>Permiso por Protección Temporal (PPT)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="numero_identificacion">Número de identificación</label>
                    <input
                        type="text"
                        id="numero_identificacion"
                        name="numero_identificacion"
                        value="{{ old('numero_identificacion') }}"
                        placeholder="Ej: 1234567890"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                    >
                </div>

                <button type="submit" class="btn-submit">Continuar →</button>
            </form>
        </div>

        <p class="footer">© {{ date('Y') }} Operadores Armadillo SAS &nbsp;·&nbsp; Firma Digital Segura</p>
    </div>
</body>
</html>
