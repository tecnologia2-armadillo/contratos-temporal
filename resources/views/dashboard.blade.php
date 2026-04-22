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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personal as $p)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">
                                        @if($p->per_foto)
                                            <img src="{{ $p->per_foto }}" alt="{{ $p->nombre_completo }}">
                                        @else
                                            <div class="avatar-placeholder">{{ substr($p->per_primer_nombre, 0, 1) }}{{ substr($p->per_primer_apellido, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $p->nombre_completo }}</div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted);">
                                            @foreach($p->perfiles as $perfil)
                                                {{ $perfil->perf_nombre_perfil }}{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-search="{{ $p->per_num_doc }}">
                                <div style="font-size: 0.85rem;">{{ $p->per_tipo_doc }}</div>
                                <div style="font-weight: 600;">{{ $p->per_num_doc }}</div>
                            </td>
                            <td data-search="{{ $p->per_correo }} {{ $p->per_telefono_whatsapp }}">
                                <div style="font-size: 0.85rem;">{{ $p->per_correo }}</div>
                                <div style="color: var(--text-muted);">{{ $p->per_telefono_whatsapp }}</div>
                            </td>
                            <td>{{ $p->per_fecha_nacimiento }}</td>
                            <td>
                                <div class="bank-info">
                                    @if($p->datoBancario)
                                        <span class="bank-name">{{ $p->datoBancario->banco->ban_banco_nombre ?? 'Sin Banco' }}</span>
                                        <span class="account-number">{{ $p->datoBancario->dba_num_cuenta }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">Sin datos</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $p->ciudad->ciu_nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge">
                                    {{ $p->status->spe_status_personal ?? 'Activo' }}
                                </span>
                            </td>
                            <td>
                                @if($p->contrato_firmado)
                                    <span class="status-badge" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.2);">Firmado</span>
                                @else
                                    <span class="status-badge" style="background-color: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">Pendiente</span>
                                @endif

                                @if($p->contrato_src)
                                    <a href="{{ $p->contrato_src }}" target="_blank" style="display: block; font-size: 0.7rem; color: var(--primary); margin-top: 0.5rem; text-decoration: none;">📄 Ver Contrato</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // DataTables Initialization
            var table = $('#personalTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                "dom": 'lrtip', // Hide default search bar to use our custom one
                "pageLength": 10,
                "responsive": true,
                "order": [[0, "asc"]]
            });

            // Custom Global Search
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Date Range Filtering Logic
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#birth-start').val();
                    var max = $('#birth-end').val();
                    var birthDate = data[3]; // Use the index of the Birth Date column

                    if (min === "" && max === "") return true;
                    if (min === "" && birthDate <= max) return true;
                    if (max === "" && birthDate >= min) return true;
                    if (birthDate >= min && birthDate <= max) return true;
                    
                    return false;
                }
            );

            // Re-draw table on date change
            $('#birth-start, #birth-end').on('change', function() {
                table.draw();
            });
        });
    </script>
</body>
</html>
