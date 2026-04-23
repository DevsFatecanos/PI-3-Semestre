<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Loja')</title>
    

      <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }

        body {
            background-color: #011931;
        }

        .navbar {
            background: linear-gradient(135deg, #2d50ec 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .sidebar {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #495057;
            border-left: 3px solid transparent;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #f8f9fa;
            color: #667eea;
            border-left-color: #667eea;
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding-top: 20px;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .btn-group-sm .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.875rem;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-primary:hover {
            background-color: #5568d3;
            border-color: #5568d3;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #2d3748;
        }

        .form-label {
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .alert {
            border: none;
            border-radius: 6px;
        }

        .pagination .page-link {
            color: #667eea;
        }

        .pagination .page-link:hover {
            background-color: #667eea;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        footer {
            margin-top: 40px;
            padding: 20px 0;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
    </style>
    
    @yield('extra-css')
</head>
<body>
    <!-- Navbar -->
    <style>
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>

<nav class="glass sticky top-0 z-50 border-b border-white/10">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 md:px-8">
        
        <a href="{{ route('admin.dashboard') }}" class="shrink-0 flex items-center gap-2 no-underline">
            <i class="fas fa-cogs text-white text-xl"></i>
            <span class="text-white font-bold tracking-tight text-lg">Admin Loja</span>
        </a>

        <div class="hidden flex-1 items-center gap-3 lg:flex">
            <div class="rounded-full bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-200">
                Painel de Controle
            </div>
            <div class="rounded-full bg-white/10 px-4 py-2 text-xs text-slate-200">
                Modo Administrador
            </div>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <div class="hidden rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-slate-100 md:block">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                </div>

                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-black text-slate-900 transition hover:bg-slate-100 no-underline">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="hidden md:inline">Sair</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endauth
        </div>
    </div>
</nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.produtos.*') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-box"></i> Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-store"></i> Ver Loja
                        </a>
                    </li>
                </ul>
                
                <hr>
                
                <div class="px-3">
                    <small class="text-muted d-block">Versão 1.0</small>
                    <small class="text-muted">© 2026 Admin Loja</small>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 main-content">
                @yield('content')
                
                <footer>
                    <p>&copy; 2026 Sistema de Administração. Todos os direitos reservados.</p>
                </footer>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (opcional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('extra-js')
</body>
</html>
