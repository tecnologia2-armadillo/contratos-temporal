<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contratos | Armadillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        :root {
            --primary: #f59e0b; --primary-hover: #d97706;
            --bg-dark: #0f172a; --bg-card: #1e293b; --bg-modal: #162032;
            --text-main: #f8fafc; --text-muted: #94a3b8;
            --success: #10b981; --danger: #ef4444;
            --border: rgba(255,255,255,0.07); --input-bg: rgba(15,23,42,0.6);
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

        main { padding:2rem; max-width:1400px; margin:0 auto; }
        .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:2rem; }
        .page-header h2 { font-size:1.875rem; font-weight:700; }
        .page-header p { color:var(--text-muted); font-size:.875rem; margin-top:.25rem; }

        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-hover)); color:#0f172a; border:none; padding:.6rem 1.25rem; border-radius:.6rem; cursor:pointer; font-size:.875rem; font-weight:700; font-family:'Outfit',sans-serif; transition:all .3s; box-shadow:0 4px 14px rgba(245,158,11,.3); display:inline-flex; align-items:center; gap:.4rem; }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); }
        .btn-action { background:rgba(245,158,11,.1); color:var(--primary); border:1px solid rgba(245,158,11,.25); padding:.35rem .75rem; border-radius:.45rem; cursor:pointer; font-size:.75rem; font-weight:600; font-family:'Outfit',sans-serif; transition:all .25s; }
        .btn-action:hover { background:var(--primary); color:#0f172a; }
        .btn-view { background:rgba(99,102,241,.12); color:#818cf8; border:1px solid rgba(99,102,241,.25); padding:.35rem .75rem; border-radius:.45rem; cursor:pointer; font-size:.75rem; font-weight:600; font-family:'Outfit',sans-serif; transition:all .25s; text-decoration:none; display:inline-block; }
        .btn-view:hover { background:#6366f1; color:white; }

        .table-container { background:var(--bg-card); border-radius:1.25rem; padding:1.5rem; border:1px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,.2); overflow-x:auto; animation:fadeUp .5s ease-out; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        .dataTables_wrapper { color:var(--text-main); }
        table.dataTable { border-collapse:collapse !important; margin-top:1rem !important; }
        table.dataTable thead th { background:rgba(15,23,42,.6); border-bottom:1px solid var(--border) !important; color:var(--text-muted); text-transform:uppercase; font-size:.7rem; letter-spacing:.05em; padding:1rem 1.25rem !important; }
        table.dataTable tbody td { background:transparent !important; border-bottom:1px solid var(--border); padding:1rem 1.25rem !important; color:var(--text-main); vertical-align:middle; }
        .dataTables_filter input,.dataTables_length select { background:var(--input-bg) !important; border:1px solid rgba(255,255,255,.1) !important; border-radius:.5rem !important; color:white !important; padding:.4rem .75rem !important; }
        .dataTables_paginate .paginate_button { background:rgba(255,255,255,.05) !important; border:1px solid transparent !important; border-radius:.4rem !important; color:var(--text-main) !important; transition:all .2s; margin:0 2px; }
        .dataTables_paginate .paginate_button.current { background:var(--primary) !important; color:#0f172a !important; }
        .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--primary-hover) !important; color:white !important; }
        .dataTables_info { color:var(--text-muted) !important; padding-top:1rem !important; }

        .terminos-preview { max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--text-muted); font-size:.8rem; }
        .date-chip { display:inline-block; background:rgba(99,102,241,.1); color:#a5b4fc; border:1px solid rgba(99,102,241,.2); padding:.2rem .6rem; border-radius:9999px; font-size:.75rem; font-weight:600; }

        .modal-overlay { display:none; position:fixed; inset:0; z-index:500; background:rgba(0,0,0,.75); backdrop-filter:blur(8px); align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:var(--bg-modal); border:1px solid rgba(255,255,255,.08); border-radius:1.25rem; padding:2rem; width:90%; max-width:620px; max-height:90vh; overflow-y:auto; box-shadow:0 25px 60px rgba(0,0,0,.5); animation:modalIn .25s ease-out; }
        @keyframes modalIn { from{opacity:0;transform:scale(.96) translateY(-8px)} to{opacity:1;transform:scale(1) translateY(0)} }
        .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .modal-header h3 { font-size:1.25rem; font-weight:700; }
        .modal-close { background:rgba(255,255,255,.05); border:none; color:var(--text-muted); width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:1.1rem; display:flex; align-items:center; justify-content:center; transition:all .2s; }
        .modal-close:hover { background:rgba(239,68,68,.2); color:var(--danger); }

        .form-group { margin-bottom:1.25rem; }
        .form-group label { display:block; font-size:.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:600; letter-spacing:.04em; margin-bottom:.5rem; }
        .form-group input,.form-group textarea { width:100%; background:var(--input-bg); border:1px solid rgba(255,255,255,.1); border-radius:.6rem; color:var(--text-main); padding:.65rem .9rem; font-family:'Outfit',sans-serif; font-size:.9rem; outline:none; transition:border-color .2s; }
        .form-group input:focus,.form-group textarea:focus { border-color:var(--primary); }
        .form-group textarea { resize:vertical; min-height:140px; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-actions { display:flex; justify-content:flex-end; gap:.75rem; margin-top:1.5rem; }
        .btn-cancel { background:transparent; border:1px solid rgba(255,255,255,.1); color:var(--text-muted); padding:.6rem 1.25rem; border-radius:.6rem; cursor:pointer; font-family:'Outfit',sans-serif; font-size:.875rem; transition:all .2s; }
        .btn-cancel:hover { border-color:var(--danger); color:var(--danger); }

        .btn-link { background:rgba(16,185,129,.1); color:#34d399; border:1px solid rgba(16,185,129,.25); padding:.35rem .75rem; border-radius:.45rem; cursor:pointer; font-size:.75rem; font-weight:600; font-family:'Outfit',sans-serif; transition:all .25s; }
        .btn-link:hover { background:#10b981; color:white; }

        .toast { position:fixed; bottom:2rem; right:2rem; background:var(--bg-card); border:1px solid rgba(255,255,255,.1); border-radius:.75rem; padding:1rem 1.5rem; color:var(--text-main); font-size:.875rem; font-weight:600; box-shadow:0 10px 30px rgba(0,0,0,.3); z-index:9999; display:none; align-items:center; gap:.75rem; min-width:250px; animation:toastIn .3s ease-out; }
        .toast.show { display:flex; }
        .toast.success { border-left:4px solid var(--success); }
        .toast.error   { border-left:4px solid var(--danger); }
        @keyframes toastIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }

        /* QR Modal Styles */
        .qr-container { display:flex; flex-direction:column; align-items:center; gap:1.5rem; padding:1rem 0; }
        .qr-box { background:white; padding:1.25rem; border-radius:1rem; box-shadow:0 10px 25px rgba(0,0,0,.2); }
        .qr-link-display { width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border); border-radius:.75rem; padding:.75rem 1rem; color:var(--text-muted); font-size:.85rem; word-break:break-all; text-align:center; }
        .btn-copy-modal { background:var(--primary); color:#0f172a; border:none; padding:.75rem 1.5rem; border-radius:.6rem; font-weight:700; cursor:pointer; font-family:'Outfit',sans-serif; transition:all .3s; display:flex; align-items:center; gap:.5rem; }
        .btn-copy-modal:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(245,158,11,.3); }
    </style>
</head>
<body>
    <nav>
        <div class="nav-logo"><h1>ARMADILLO</h1></div>
        <div class="nav-right">
            <a href="{{ route('dashboard') }}" class="nav-link">👥 Personal</a>
            <span style="color:var(--primary);font-size:.875rem;font-weight:700;">📋 Contratos</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <main>
        <div class="page-header">
            <div>
                <h2>📋 Contratos</h2>
                <p>Gestión centralizada de contratos y firmantes.</p>
            </div>
            <button class="btn-primary" id="btnNuevoContrato">➕ Nuevo Contrato</button>
        </div>

        <div class="table-container">
            <table id="contratosTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Términos (vista previa)</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th style="min-width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </main>

    <!-- Modal Crear / Editar -->
    <div class="modal-overlay" id="contratoModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modalTitle">Nuevo Contrato</h3>
                <button class="modal-close" id="btnCloseModal">✕</button>
            </div>
            <form id="contratoForm">
                @csrf
                <input type="hidden" id="contratoId" value="">
                <div class="form-group">
                    <label for="nombre">Nombre del Contrato</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Contrato de prestación de servicios 2026">
                </div>
                <div class="form-group">
                    <label for="terminos">Términos y Condiciones</label>
                    <textarea id="terminos" name="terminos" placeholder="Escribe aquí el cuerpo completo del contrato..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" id="btnCancelModal">Cancelar</button>
                    <button type="submit" class="btn-primary" id="btnSubmitModal">Guardar Contrato</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal QR -->
    <div class="modal-overlay" id="qrModal">
        <div class="modal-box" style="max-width:400px;">
            <div class="modal-header">
                <h3>🔗 Enlace de Firma</h3>
                <button class="modal-close" onclick="closeQrModal()">✕</button>
            </div>
            <div class="qr-container">
                <p style="color:var(--text-muted); font-size:.85rem; text-align:center;">Escanea el código QR para firmar desde tu dispositivo móvil:</p>
                <div class="qr-box" id="qrcode"></div>
                <div class="qr-link-display" id="qrLinkText"></div>
                <button class="btn-copy-modal" id="btnCopyFromModal">
                    📋 Copiar Enlace
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        let table;
        $(document).ready(function () {
            table = $('#contratosTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: "{{ route('contratos.index') }}" },
                columns: [
                    { data: 'nombre', render: d => `<div style="font-weight:600;">${d}</div>` },
                    { data: 'terminos', render: d => `<div class="terminos-preview">${d.length > 80 ? d.substring(0,80)+'…' : d}</div>` },
                    { data: 'fecha_inicio', render: d => `<span class="date-chip">📅 ${d}</span>` },
                    { data: 'fecha_fin',    render: d => `<span class="date-chip">📅 ${d}</span>` },
                    {
                        data: 'id', orderable: false,
                        render: function (data, type, row) {
                            const detalleUrl = "{{ url('/contratos') }}/" + data + "/detalle";
                            const firmarUrl  = "{{ url('/firmar') }}/" + data;
                            const t = row.terminos.replace(/\\/g,'\\\\').replace(/`/g,'\\`').replace(/\$/g,'\\$');
                            return `<div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                <button class="btn-action" onclick="openEditModal(${data},\`${row.nombre}\`,\`${t}\`,'${row.fecha_inicio}','${row.fecha_fin}')">✏️ Editar</button>
                                <a href="${detalleUrl}" class="btn-view">👁 Ver</a>
                                <button class="btn-link" onclick="openQrModal('${firmarUrl}')">🔗 Firma</button>
                            </div>`;
                        }
                    }
                ],
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                dom: 'lrtip', pageLength: 10, responsive: true, order: [[0,'asc']]
            });
        });

        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Nuevo Contrato';
            document.getElementById('btnSubmitModal').innerText = 'Crear Contrato';
            document.getElementById('contratoId').value = '';
            document.getElementById('contratoForm').reset();
            document.getElementById('contratoModal').classList.add('open');
        }
        function openEditModal(id, nombre, terminos, fi, ff) {
            document.getElementById('modalTitle').innerText = 'Editar Contrato';
            document.getElementById('btnSubmitModal').innerText = 'Guardar Cambios';
            document.getElementById('contratoId').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('terminos').value = terminos;
            document.getElementById('fecha_inicio').value = fi;
            document.getElementById('fecha_fin').value = ff;
            document.getElementById('contratoModal').classList.add('open');
        }
        function closeModal() { document.getElementById('contratoModal').classList.remove('open'); }

        document.getElementById('btnNuevoContrato').addEventListener('click', openCreateModal);
        document.getElementById('btnCloseModal').addEventListener('click', closeModal);
        document.getElementById('btnCancelModal').addEventListener('click', closeModal);
        document.getElementById('contratoModal').addEventListener('click', e => { if(e.target===document.getElementById('contratoModal')) closeModal(); });

        document.getElementById('contratoForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('contratoId').value;
            const isEdit = id !== '';
            $.ajax({
                url:    isEdit ? `/contratos/${id}` : "{{ route('contratos.store') }}",
                method: isEdit ? 'PUT' : 'POST',
                data: {
                    nombre:       $('#nombre').val(),
                    terminos:     $('#terminos').val(),
                    fecha_inicio: $('#fecha_inicio').val(),
                    fecha_fin:    $('#fecha_fin').val(),
                },
                success: function (res) {
                    if (res.success) { closeModal(); table.ajax.reload(); showToast('success', res.message); }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON?.errors;
                    showToast('error', errors ? Object.values(errors).flat().join(' ') : 'Error al guardar.');
                }
            });
        });

        let toastTimer;
        function showToast(type, msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMsg').innerText = msg;
            document.getElementById('toastIcon').innerText = type === 'success' ? '✅' : '❌';
            toast.className = `toast show ${type}`;
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => { toast.className = 'toast'; }, 3500);
        }

        let qrcode = null;
        function openQrModal(url) {
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            document.getElementById('qrLinkText').innerText = url;
            
            qrcode = new QRCode(qrContainer, {
                text: url,
                width: 200,
                height: 200,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            document.getElementById('btnCopyFromModal').onclick = () => copyToClipboard(url);
            document.getElementById('qrModal').classList.add('open');
        }

        function closeQrModal() { document.getElementById('qrModal').classList.remove('open'); }

        function copyToClipboard(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => showToast('success', '🔗 Link copiado al portapapeles.'));
            } else {
                const ta = document.createElement('textarea');
                ta.value = url; ta.style.position = 'fixed'; ta.style.left = '-9999px';
                document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy'); showToast('success', '🔗 Link copiado.'); }
                catch(e) { showToast('error', 'No se pudo copiar.'); }
                ta.remove();
            }
        }

        // Cerrar modal QR al hacer click fuera
        document.getElementById('qrModal').addEventListener('click', e => {
            if(e.target === document.getElementById('qrModal')) closeQrModal();
        });
    </script>
</body>
</html>
