<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Ventas Web')</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
</head>
<body>
    <nav class="navbar">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Sistema de Ventas Web</a>
        <div class="navbar-links">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Home</a>
            <a class="nav-link {{ request()->routeIs('productos') ? 'active' : '' }}" href="{{ route('productos') }}">Producto</a>
            <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}">Empleado</a>
            <a class="nav-link {{ request()->routeIs('clientes') ? 'active' : '' }}" href="{{ route('clientes') }}">Clientes</a>
            <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.create') }}">Nueva Venta</a>
        </div>
        <details class="user-menu">
            <summary>{{ Auth::user()->Nombres ?? Auth::user()->User }}</summary>
            <div class="user-menu-content">
                <strong>{{ Auth::user()->User }}</strong>
                <span>{{ Auth::user()->Telefono }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-action" type="submit">Salir</button>
                </form>
            </div>
        </details>
    </nav>
    <main class="page-content">
        @yield('content')
    </main>
</body>
</html>
