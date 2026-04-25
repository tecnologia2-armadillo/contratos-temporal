<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Exitosa | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f59e0b;
            --success: #10b981;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
        }

        .card {
            background-color: var(--bg-card);
            border-radius: 1.5rem;
            padding: 3.5rem;
            border: 1px solid var(--border);
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 2rem;
            border: 2px solid rgba(16, 185, 129, 0.2);
        }

        h1 {
            color: var(--text-main);
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }

        p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            background-color: var(--primary);
            color: var(--bg-dark);
            margin-bottom: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            background-color: #d97706;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-box">✓</div>
        <h1>¡Registro y Firma Exitosa!</h1>
        <p>Gracias, <strong>{{ $model->nombre }} {{ $model->apellido }}</strong>. Hemos guardado tus datos y tu contrato de forma segura.</p>
        
        @if(isset($driveLink) && $driveLink)
            <a href="{{ $driveLink }}" target="_blank" class="btn">Ver mi Contrato en PDF</a>
        @endif

        <br>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Ya puedes cerrar esta pestaña y estar atento/a a nuevas convocatorias.</span>
    </div>
</body>
</html>
