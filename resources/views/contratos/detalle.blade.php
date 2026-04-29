<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contrato->nombre }} | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        :root {
            --primary:#f59e0b; --primary-hover:#d97706;
            --bg-dark:#0f172a; --bg-card:#1e293b;
            --text-main:#f8fafc; --text-muted:#94a3b8;
            --success:#10b981; --danger:#ef4444;
            --border:rgba(255,255,255,0.07); --input-bg:rgba(15,23,42,0.6);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; background:radial-gradient(circle at top left,#1e293b,#0f172a); color:var(--text-main); min-height:100vh; }

        nav { background:var(--bg-card); padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); box-shadow:0 4px 6px -1px rgba(0,0,0,.15); position:sticky; top:0; z-index:100; }
        .nav-logo h1 { font-size:1.25rem; font-weight:700; color:var(--primary); }
        .nav-right { display:flex; align-items:center; gap:1.25rem; }
        .nav-link { color:var(--text-muted); text-decoration:none; font-size:.875rem; font-weight:600; transition:color .2s; }
        .nav-link:hover { color:var(--primary); }
        .logout-btn { background:transparent; color:var(--text-main); border:1px solid rgba(255,255,255,.1); padding:.45rem 1rem; border-radius:.5rem; cursor:pointer; font-size:.875rem; font-family:'Outfit',sans-serif; transition:all .3s; text-decoration:none; }
        .logout-btn:hover { background:rgba(239,68,68,.1); color:var(--danger); border-color:var(--danger); }

        main { padding:2rem; max-width:1500px; margin:0 auto; }

        /* ── Contrato Header Card ── */
        .contrato-card {
            background:var(--bg-card);
            border:1px solid var(--border);
            border-radius:1.25rem;
            padding:1.75rem 2rem;
            margin-bottom:2rem;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:1.5rem;
            flex-wrap:wrap;
            animation:fadeUp .4s ease-out;
        }
        .contrato-card h2 { font-size:1.5rem; font-weight:700; margin-bottom:.5rem; }
        .contrato-card .meta { display:flex; gap:1rem; flex-wrap:wrap; margin-top:.5rem; }
        .meta-chip { background:rgba(99,102,241,.1); color:#a5b4fc; border:1px solid rgba(99,102,241,.2); padding:.25rem .75rem; border-radius:9999px; font-size:.8rem; font-weight:600; }
        .back-btn { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:var(--text-muted); padding:.5rem 1.1rem; border-radius:.6rem; cursor:pointer; font-size:.875rem; font-family:'Outfit',sans-serif; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; }
        .back-btn:hover { background:rgba(255,255,255,.1); color:var(--text-main); }

        /* ── Tabs ── */
        .tabs { display:flex; gap:1rem; margin-bottom:2rem; border-bottom:1px solid var(--border); padding-bottom:1rem; }
        .tab-btn { background:transparent; border:none; color:var(--text-muted); font-size:1rem; font-weight:600; padding:.5rem 1rem; cursor:pointer; transition:all .3s; position:relative; font-family:'Outfit',sans-serif; }
        .tab-btn:hover { color:var(--text-main); }
        .tab-btn.active { color:var(--primary); }
        .tab-btn.active::after { content:''; position:absolute; bottom:-1rem; left:0; width:100%; height:2px; background:var(--primary); border-radius:2px; }
        .tab-pane { display:none; }
        .tab-pane.active { display:block; animation:fadeUp .4s ease-out; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        .table-container { background:var(--bg-card); border-radius:1.25rem; padding:1.5rem; border:1px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,.2); overflow-x:auto; }

        /* DataTables */
        .dataTables_wrapper { color:var(--text-main); }
        table.dataTable { border-collapse:collapse !important; margin-top:1rem !important; }
        table.dataTable thead th { background:rgba(15,23,42,.6); border-bottom:1px solid var(--border) !important; color:var(--text-muted); text-transform:uppercase; font-size:.7rem; letter-spacing:.05em; padding:1rem 1.25rem !important; }
        table.dataTable tbody td { background:transparent !important; border-bottom:1px solid var(--border); padding:1rem 1.25rem !important; color:var(--text-main); vertical-align:middle; }
        .dataTables_filter input,.dataTables_length select { background:var(--input-bg) !important; border:1px solid rgba(255,255,255,.1) !important; border-radius:.5rem !important; color:white !important; padding:.4rem .75rem !important; }
        .dataTables_paginate .paginate_button { background:rgba(255,255,255,.05) !important; border:1px solid transparent !important; border-radius:.4rem !important; color:var(--text-main) !important; transition:all .2s; margin:0 2px; }
        .dataTables_paginate .paginate_button.current { background:var(--primary) !important; color:#0f172a !important; }
        .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--primary-hover) !important; color:white !important; }
        .dataTables_info { color:var(--text-muted) !important; padding-top:1rem !important; }

        /* Badges */
        .badge-signed { display:inline-flex; align-items:center; gap:.3rem; background:rgba(16,185,129,.12); color:var(--success); border:1px solid rgba(16,185,129,.25); padding:.3rem .75rem; border-radius:9999px; font-size:.75rem; font-weight:700; }
        .badge-pending { display:inline-flex; align-items:center; gap:.3rem; background:rgba(239,68,68,.1); color:var(--danger); border:1px solid rgba(239,68,68,.2); padding:.3rem .75rem; border-radius:9999px; font-size:.75rem; font-weight:700; }
        .badge-status { display:inline-block; background:rgba(16,185,129,.1); color:var(--success); border:1px solid rgba(16,185,129,.2); padding:.25rem .75rem; border-radius:9999px; font-size:.75rem; font-weight:600; }

        .user-cell { display:flex; align-items:center; gap:.75rem; }
        .avatar { width:38px; height:38px; border-radius:50%; background:var(--primary); display:flex; align-items:center; justify-content:center; overflow:hidden; border:2px solid rgba(255,255,255,.1); flex-shrink:0; }
        .avatar-placeholder { font-weight:700; color:#0f172a; font-size:.9rem; }
        .bank-info { font-size:.75rem; line-height:1.4; }
        .bank-name { font-weight:600; color:var(--primary); display:block; }
        .account-number { color:var(--text-muted); font-family:monospace; }
        .tag { display:inline-block; background:rgba(245,158,11,.1); color:var(--primary); padding:.1rem .5rem; border-radius:.25rem; font-size:.7rem; margin-right:.2rem; margin-bottom:.2rem; border:1px solid rgba(245,158,11,.2); }

        .contract-link { display:block; font-size:.7rem; color:var(--primary); margin-top:.4rem; text-decoration:none; transition:opacity .2s; }
        .contract-link:hover { opacity:.75; }

        .ip-info { font-size:.7rem; color:var(--text-muted); margin-top:.25rem; font-family:monospace; }
    </style>
</head>
<body>
    <nav>
        <div class="nav-logo"><h1>ARMADILLO</h1></div>
        <div class="nav-right">
            <a href="{{ route('dashboard') }}" class="nav-link">👥 Personal</a>
            <a href="{{ route('contratos.index') }}" class="nav-link">📋 Contratos</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <main>
        <!-- Contrato Info Card -->
        <div class="contrato-card">
            <div>
                <h2>{{ $contrato->nombre }}</h2>
                <div class="meta">
                    <span class="meta-chip">📅 Inicio: {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</span>
                    <span class="meta-chip">📅 Fin: {{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}</span>
                </div>
            </div>
            <a href="{{ route('contratos.index') }}" class="back-btn">← Volver a Contratos</a>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('vinculado')">👔 Personal Vinculado</button>
            <button class="tab-btn" onclick="switchTab('no_vinculado')">🔗 Personal No Vinculado</button>
        </div>

        <!-- Tab: Personal Vinculado -->
        <div id="vinculado" class="tab-pane active">
            <div class="table-container">
                <table id="personalTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre / Cargo</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Nacimiento</th>
                            <th>Información Bancaria</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            <th>Firma en este Contrato</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Personal No Vinculado -->
        <div id="no_vinculado" class="tab-pane">
            <div class="table-container">
                <table id="personalNVTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Nacimiento</th>
                            <th>Información Bancaria</th>
                            <th>Firma en este Contrato</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        const contratoId = {{ $contrato->id }};

        $(document).ready(function () {

            // ── Personal Vinculado ────────────────
            $('#personalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: "{{ route('contratos.personal', $contrato->id) }}" },
                columns: [
                    {
                        data: 'nombre_completo',
                        render: function (data, type, row) {
                            const perfiles = (row.perfiles || []).map(p => `<span class="tag">${p.perf_nombre_perfil}</span>`).join('');
                            const avatar = row.per_foto
                                ? `<img src="${row.per_foto}" alt="${data}" style="width:100%;height:100%;object-fit:cover;">`
                                : `<div class="avatar-placeholder">${row.per_primer_nombre.charAt(0)}${row.per_primer_apellido.charAt(0)}</div>`;
                            return `<div class="user-cell">
                                <div class="avatar">${avatar}</div>
                                <div>
                                    <div style="font-weight:600;">${data}</div>
                                    <div style="margin-top:.2rem;">${perfiles}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'per_num_doc',
                        render: (data, type, row) =>
                            `<div style="font-size:.8rem;color:var(--text-muted);">${row.per_tipo_doc}</div><div style="font-weight:600;">${data}</div>`
                    },
                    {
                        data: 'per_correo',
                        render: (data, type, row) =>
                            `<div style="font-size:.85rem;">${data}</div><div style="color:var(--text-muted);">${row.per_telefono_whatsapp}</div>`
                    },
                    { data: 'per_fecha_nacimiento' },
                    {
                        data: 'dato_bancario',
                        render: function (data) {
                            if (data) return `<div class="bank-info"><span class="bank-name">${data.banco ? data.banco.ban_banco_nombre : 'Sin Banco'}</span><span class="account-number">${data.dba_num_cuenta}</span></div>`;
                            return `<span style="color:var(--text-muted);">Sin datos</span>`;
                        }
                    },
                    { data: 'ciudad.ciu_nombre', defaultContent: 'N/A' },
                    {
                        data: 'status.spe_status_personal', defaultContent: 'Activo',
                        render: d => `<span class="badge-status">${d}</span>`
                    },
                    {
                        data: 'firmado_contrato',
                        render: function (data, type, row) {
                            if (data) {
                                const link = row.contrato_src_pivot
                                    ? `<a href="${row.contrato_src_pivot}" target="_blank" class="contract-link">📄 Ver PDF del Contrato</a>`
                                    : '';
                                const ip = row.ip_firma
                                    ? `<div class="ip-info">📍 IP: ${row.ip_firma}</div>`
                                    : '';
                                return `<div><span class="badge-signed">✅ Firmado</span>${link}${ip}</div>`;
                            }
                            return `<span class="badge-pending">⏳ Pendiente</span>`;
                        }
                    }
                ],
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                dom: 'lrtip', pageLength: 10, responsive: true
            });

            // ── Personal No Vinculado ─────────────
            $('#personalNVTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: "{{ route('contratos.personal_nv', $contrato->id) }}" },
                columns: [
                    {
                        data: 'nombre',
                        render: (data, type, row) =>
                            `<div class="user-cell">
                                <div class="avatar"><div class="avatar-placeholder">${row.nombre.charAt(0)}${row.apellido.charAt(0)}</div></div>
                                <div style="font-weight:600;">${row.nombre} ${row.apellido}</div>
                            </div>`
                    },
                    {
                        data: 'identificacion',
                        render: (data, type, row) =>
                            `<div style="font-size:.8rem;color:var(--text-muted);">${row.tipo_identificacion}</div><div style="font-weight:600;">${data}</div>`
                    },
                    {
                        data: 'correo',
                        render: (data, type, row) =>
                            `<div style="font-size:.85rem;">${data}</div><div style="color:var(--text-muted);">${row.telefono}</div>`
                    },
                    { data: 'fecha_nacimiento' },
                    {
                        data: 'numero_cuenta',
                        render: (data, type, row) =>
                            `<div class="bank-info"><span class="bank-name">${row.banco} (${row.tipo_cuenta})</span><span class="account-number">${data}</span></div>`
                    },
                    {
                        data: 'firmado_contrato',
                        render: function (data, type, row) {
                            if (data) {
                                const link = row.contrato_src_pivot
                                    ? `<a href="${row.contrato_src_pivot}" target="_blank" class="contract-link">📄 Ver PDF del Contrato</a>`
                                    : '';
                                const ip = row.ip_firma
                                    ? `<div class="ip-info">📍 IP: ${row.ip_firma}</div>`
                                    : '';
                                return `<div><span class="badge-signed">✅ Firmado</span>${link}${ip}</div>`;
                            }
                            return `<span class="badge-pending">⏳ Pendiente</span>`;
                        }
                    }
                ],
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                dom: 'lrtip', pageLength: 10, responsive: true
            });
        });

        // ── Tab Switcher ──────────────────────────
        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelector(`button[onclick="switchTab('${tabId}')"]`).classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
