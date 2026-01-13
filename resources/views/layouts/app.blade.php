<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Report System')</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
  :root{
    --bg: #f1f5f9;
    --card:#ffffff;
    --border:#e2e8f0;
    --text:#1e293b;
    --muted:#64748b;
    --muted2:#94a3b8;

    --primary:#0369a1;
    --primary-light:#0ea5e9;
    --primary-dark:#075985;
    --primary-bg:#f0f9ff;
    --primary-border:#bae6fd;

    --success:#10b981;
    --success-bg:#ecfdf5;
    --success-border:#a7f3d0;

    --danger:#ef4444;
    --danger-bg:#fef2f2;
    --danger-border:#fecaca;

    --warning:#f59e0b;
    --warning-bg:#fffbeb;

    --sidebar-width: 260px;
    --header-height: 64px;

    --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
    --shadow-lg: 0 10px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.06);
  }

  *{box-sizing:border-box; margin:0; padding:0;}

  body{
    font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
  }

  /* Sidebar */
  .sidebar{
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: var(--sidebar-width);
    background: linear-gradient(180deg, #0c4a6e 0%, #075985 50%, #0369a1 100%);
    color: #fff;
    z-index: 200;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 20px rgba(0,0,0,.1);
  }

  .sidebar-header{
    padding: 24px 20px;
    border-bottom: 1px solid rgba(255,255,255,.1);
  }

  .sidebar-logo{
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .sidebar-logo-icon{
    width: 42px;
    height: 42px;
    background: rgba(255,255,255,.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 14px;
  }

  .sidebar-logo-text{
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.3px;
  }

  .sidebar-logo-sub{
    font-size: 11px;
    opacity: 0.7;
    margin-top: 2px;
  }

  .sidebar-nav{
    flex: 1;
    padding: 20px 12px;
    overflow-y: auto;
  }

  .nav-label{
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,.5);
    padding: 0 12px;
    margin-bottom: 10px;
    margin-top: 20px;
  }

  .nav-label:first-child{
    margin-top: 0;
  }

  .nav-item{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    color: rgba(255,255,255,.8);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all .2s ease;
    margin-bottom: 4px;
  }

  .nav-item:hover{
    background: rgba(255,255,255,.1);
    color: #fff;
  }

  .nav-item.active{
    background: rgba(255,255,255,.2);
    color: #fff;
    font-weight: 600;
  }

  .nav-icon{
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
  }

  .sidebar-footer{
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,.1);
    font-size: 11px;
    color: rgba(255,255,255,.5);
    text-align: center;
  }

  /* Main Content */
  .main-wrapper{
    margin-left: var(--sidebar-width);
    min-height: 100vh;
  }

  /* Header */
  .top-header{
    height: var(--header-height);
    background: #fff;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow);
  }

  .header-title{
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
  }

  .header-date{
    font-size: 13px;
    color: var(--muted);
    background: var(--bg);
    padding: 8px 14px;
    border-radius: 8px;
  }

  /* Page Content */
  .page-content{
    padding: 28px;
  }

  /* Cards */
  .card{
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px;
    box-shadow: var(--shadow);
    margin-bottom: 20px;
  }

  .card-header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
    gap: 12px;
  }

  .card-header-left{
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .card-title{
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
  }

  /* Buttons */
  .btn{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: all .2s ease;
    text-decoration: none;
    border: none;
  }

  .btn-primary{
    background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(3,105,161,.3);
  }

  .btn-primary:hover{
    box-shadow: 0 4px 12px rgba(3,105,161,.4);
    transform: translateY(-1px);
  }

  .btn-secondary{
    background: #fff;
    color: var(--primary);
    border: 1.5px solid var(--primary);
  }

  .btn-secondary:hover{
    background: var(--primary-bg);
  }

  .btn-sm{
    padding: 6px 12px;
    font-size: 12px;
  }

  .btn-success{
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
  }

  .btn-danger{
    background: #fff;
    color: var(--danger);
    border: 1.5px solid var(--danger);
  }

  .btn-danger:hover{
    background: var(--danger-bg);
  }

  /* Responsive */
  @media (max-width: 900px){
    .sidebar{
      transform: translateX(-100%);
    }
    .main-wrapper{
      margin-left: 0;
    }
  }

  @yield('styles')
  </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon">RS</div>
      <div>
        <div class="sidebar-logo-text">Report System</div>
        <div class="sidebar-logo-sub">Sistem Pelaporan</div>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">Menu</div>
    
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="nav-icon">◉</span>
      Dashboard
    </a>

    <div class="nav-label">Laporan</div>
    
    <a href="{{ route('reports.create') }}" class="nav-item {{ request()->routeIs('reports.create') ? 'active' : '' }}">
      <span class="nav-icon">+</span>
      Buat Report Kegiatan
    </a>
    
    <a href="{{ route('reports.riwayat') }}" class="nav-item {{ request()->routeIs('reports.riwayat') ? 'active' : '' }}">
      <span class="nav-icon">≡</span>
      Riwayat Laporan
    </a>
  </nav>

  <div class="sidebar-footer">
    &copy; {{ date('Y') }} Report System
  </div>
</aside>

<!-- Main Content -->
<div class="main-wrapper">
  <header class="top-header">
    <h1 class="header-title">@yield('header', 'Dashboard')</h1>
    <div class="header-date">{{ now()->timezone('Asia/Jakarta')->format('l, d M Y') }}</div>
  </header>

  <main class="page-content">
    @yield('content')
  </main>
</div>

@yield('scripts')

</body>
</html>
