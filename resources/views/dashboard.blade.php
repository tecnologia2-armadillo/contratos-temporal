<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Armadillo | Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        :root {
            --primary: #f59e0b;
            --primary-hover: #d97706;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --border: rgba(255, 255, 255, 0.05);
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
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
        }

        nav {
            background-color: var(--bg-card);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .nav-logo h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: -0.025em;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logout-btn {
            background-color: transparent;
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: var(--danger);
        }

        main {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h2 {
            font-size: 1.875rem;
            color: var(--text-main);
        }

        /* Advanced Filters Styles */
        .filters-section {
            background-color: var(--bg-card);
            padding: 1.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            animation: fadeIn 0.6s ease-out;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
        }

        .filter-group input {
            padding: 0.6rem 1rem;
            background-color: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: white;
            outline: none;
            transition: all 0.3s ease;
        }

        .filter-group input:focus {
            border-color: var(--primary);
        }

        .table-container {
            background-color: var(--bg-card);
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
            overflow-x: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* DataTables Custom Styles */
        .dataTables_wrapper {
            color: var(--text-main);
        }

        table.dataTable {
            border-collapse: collapse !important;
            border-bottom: none !important;
            margin-top: 1rem !important;
        }

        table.dataTable thead th {
            background-color: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid var(--border) !important;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem 1.5rem !important;
        }

        table.dataTable tbody td {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 1.5rem !important;
            color: var(--text-main);
        }

        .dataTables_filter, .dataTables_length {
            margin-bottom: 1.5rem;
        }

        .dataTables_filter input, .dataTables_length select {
            background-color: rgba(15, 23, 42, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.5rem !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
        }

        .dataTables_paginate .paginate_button {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid transparent !important;
            border-radius: 0.5rem !important;
            color: var(--text-main) !important;
            transition: all 0.3s ease;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            color: var(--bg-dark) !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: var(--primary-hover) !important;
            color: white !important;
        }

        .dataTables_info {
            color: var(--text-muted) !important;
            padding-top: 1.5rem !important;
        }

        /* User & UI Elements */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .avatar-placeholder {
            font-weight: 600;
            color: var(--bg-dark);
            font-size: 1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .bank-info {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .bank-name {
            font-weight: 600;
            color: var(--primary);
            display: block;
        }

        .account-number {
            color: var(--text-muted);
            font-family: monospace;
        }

        .tag {
            display: inline-block;
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--primary);
            padding: 0.1rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 90%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: modalFade 0.3s ease-out;
        }

        @keyframes modalFade {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .qr-container {
            background: white;
            padding: 1rem;
            border-radius: 1rem;
            display: inline-block;
            margin: 1.5rem 0;
        }

        .share-link {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            padding: 0.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        .copy-btn {
            background: var(--primary);
            color: var(--bg-dark);
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 0.4rem;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn-action {
            background: rgba(245, 158, 11, 0.1);
            color: var(--primary);
            border: 1px solid rgba(245, 158, 11, 0.2);
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: var(--primary);
            color: var(--bg-dark);
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-logo">
            <h1>ARMADILLO</h1>
        </div>
        <div class="user-info">
            <span class="user-name" id="user-display">Hola, <strong>{{ Session::get('username') }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <main>
        <div class="page-header">
            <div>
                <h2>Personal de Armadillo</h2>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Gestión centralizada con filtros avanzados.</p>
            </div>
        </div>

        <!-- Advanced Filters -->
        <section class="filters-section">
            <div class="filter-group">
                <label>Filtro Global</label>
                <input type="text" id="global-search" placeholder="Nombre, correo, teléfono...">
            </div>
            <div class="filter-group">
                <label>Nacimiento Desde</label>
                <input type="date" id="birth-start">
            </div>
            <div class="filter-group">
                <label>Nacimiento Hasta</label>
                <input type="date" id="birth-end">
            </div>
        </section>

        <div class="table-container">
            <table id="personalTable" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre / Cargo</th>
                        <th>Identificación</th>
                        <th>Contacto</th>
                        <th>Nacimiento</th>
                        <th>Información Bancaria</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Contrato</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Rows will be drawn by DataTables Server-Side --}}
                </tbody>
            </table>
        </div>
    </main>

    <!-- Share Modal -->
    <div id="shareModal" class="modal">
        <div class="modal-content">
            <h3 id="modal-title" style="margin-bottom: 0.5rem;">Cargar Contrato</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Escanea el QR o copia el enlace para compartir con el trabajador.</p>
            
            <div class="qr-container" id="qrcode"></div>
            
            <div class="share-link">
                <input type="text" id="share-url" readonly style="background:transparent; border:none; color:white; width:100%; outline:none; font-size: 0.75rem;">
                <button class="copy-btn" id="copy-btn" onclick="copyUrl()">Copiar</button>
            </div>
            
            <button class="btn-outline" onclick="closeModal()" style="margin-top: 1.5rem; width: 100%; border-radius: 0.5rem; padding: 0.75rem;">Cerrar</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const signBaseUrl = "{{ route('contract.show', '') }}";

            // DataTables Initialization
            var table = $('#personalTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('dashboard') }}",
                    "data": function(d) {
                        d.birth_start = $('#birth-start').val();
                        d.birth_end = $('#birth-end').val();
                    }
                },
                "columns": [
                    { 
                        "data": "nombre_completo",
                        "render": function(data, type, row) {
                            let perfiles = row.perfiles.map(p => p.perf_nombre_perfil).join(', ');
                            let avatar = row.per_foto 
                                ? `<img src="${row.per_foto}" alt="${data}">` 
                                : `<div class="avatar-placeholder">${row.per_primer_nombre.charAt(0)}${row.per_primer_apellido.charAt(0)}</div>`;
                            
                            return `<div class="user-cell">
                                        <div class="avatar">${avatar}</div>
                                        <div>
                                            <div style="font-weight: 600;">${data}</div>
                                            <div style="font-size: 0.7rem; color: var(--text-muted);">${perfiles}</div>
                                        </div>
                                    </div>`;
                        }
                    },
                    { 
                        "data": "per_num_doc",
                        "render": function(data, type, row) {
                            return `<div style="font-size: 0.85rem;">${row.per_tipo_doc}</div>
                                    <div style="font-weight: 600;">${data}</div>`;
                        }
                    },
                    { 
                        "data": "per_correo",
                        "render": function(data, type, row) {
                            return `<div style="font-size: 0.85rem;">${data}</div>
                                    <div style="color: var(--text-muted);">${row.per_telefono_whatsapp}</div>`;
                        }
                    },
                    { "data": "per_fecha_nacimiento" },
                    { 
                        "data": "id",
                        "render": function(data, type, row) {
                            if (row.dato_bancario) {
                                return `<div class="bank-info">
                                            <span class="bank-name">${row.dato_bancario.banco ? row.dato_bancario.banco.ban_banco_nombre : 'Sin Banco'}</span>
                                            <span class="account-number">${row.dato_bancario.dba_num_cuenta}</span>
                                        </div>`;
                            }
                            return `<span style="color: var(--text-muted);">Sin datos</span>`;
                        }
                    },
                    { 
                        "data": "ciudad.ciu_nombre",
                        "defaultContent": "N/A"
                    },
                    { 
                        "data": "status.spe_status_personal",
                        "defaultContent": "Activo",
                        "render": function(data) {
                            return `<span class="status-badge">${data}</span>`;
                        }
                    },
                    { 
                        "data": "contrato_firmado",
                        "render": function(data, type, row) {
                            let badge = data 
                                ? `<span class="status-badge" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.2);">Firmado</span>`
                                : `<span class="status-badge" style="background-color: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">Pendiente</span>`;
                            
                            let link = row.contrato_src 
                                ? `<a href="${row.contrato_src}" target="_blank" style="display: block; font-size: 0.7rem; color: var(--primary); margin-top: 0.5rem; text-decoration: none;">📄 Ver Contrato</a>`
                                : '';
                            
                            return badge + link;
                        }
                    },
                    { 
                        "data": "signature_token",
                        "render": function(data, type, row) {
                            if (!row.contrato_firmado) {
                                return `<button class="btn-action" onclick="showShareModal('${row.nombre_completo}', '${signBaseUrl}/${data}')">
                                            🔗 Compartir
                                        </button>`;
                            }
                            return `<span style="color: var(--text-muted); font-size: 0.7rem;">Finalizado</span>`;
                        }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                "dom": 'lrtip',
                "pageLength": 10,
                "responsive": true
            });

            // Re-draw table on custom filter change
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#birth-start, #birth-end').on('change', function() {
                table.draw();
            });
        });

        // QR and Modal Logic
        let qrcode = null;

        function showShareModal(name, url) {
            document.getElementById('modal-title').innerText = "Firma de " + name;
            document.getElementById('share-url').value = url;
            
            const qrContainer = document.getElementById("qrcode");
            qrContainer.innerHTML = ""; // Clear previous QR
            
            qrcode = new QRCode(qrContainer, {
                text: url,
                width: 200,
                height: 200,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            document.getElementById('shareModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('shareModal').style.display = 'none';
        }

        function copyUrl() {
            const copyText = document.getElementById("share-url");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            
            const btn = document.getElementById('copy-btn');
            btn.innerText = "¡Copiado!";
            btn.style.backgroundColor = "#10b981";
            
            setTimeout(() => {
                btn.innerText = "Copiar";
                btn.style.backgroundColor = "";
            }, 2000);
        }

        // Close modal on click outside
        window.onclick = function(event) {
            const modal = document.getElementById('shareModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
