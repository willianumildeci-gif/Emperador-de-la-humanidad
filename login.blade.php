<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema de Ventas Web</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
</head>
<body class="login-page">
    <main class="login-card">
        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <header class="login-heading">
                <div class="login-mark" aria-hidden="true">SV</div>
                <h1>Login</h1>
                <p>Bienvenido al Sistema</p>
            </header>
            @if ($errors->has('credentials'))
                <div class="alert alert-danger" role="alert">{{ $errors->first('credentials') }}</div>
            @endif
            @if ($errors->has('username') || $errors->has('password'))
                <div class="alert alert-danger" role="alert">Ingrese su usuario y contraseña.</div>
            @endif
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input id="username" class="form-control" type="text" name="username"
                       value="{{ old('username') }}" placeholder="Ingrese su Usuario" maxlength="8" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input id="password" class="form-control" type="password" name="password"
                       placeholder="Ingrese su Contraseña" required>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Ingresar</button>
        </form>
    </main>
</body>
</html>
