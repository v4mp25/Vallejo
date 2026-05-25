@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
<div class="cv-login-page">
    <div class="cv-login-card">
        <div class="cv-login-header">
            <img src="{{ asset('img/Yachachin1.png') }}" alt="Logo I.E. César Vallejo"
                 onerror="this.outerHTML='<i class=\'fas fa-graduation-cap fa-3x mb-2\'></i>'">
            <h4 class="mb-1">I.E. César Vallejo</h4>
            <p class="mb-0 opacity-75 small">Sistema de gestión escolar</p>
        </div>

        <div class="cv-login-body">
            <h5 class="fw-bold text-center mb-4 cv-primary">Iniciar sesión</h5>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Usuario / Correo / DNI</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-user cv-primary"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Ingresa tu usuario"
                               required
                               autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-lock cv-primary"></i>
                        </span>
                        <input type="password"
                               class="form-control border-start-0 @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>

                <button type="submit" class="btn cv-btn-login-submit">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar
                </button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
                © {{ date('Y') }} I.E. César Vallejo
            </p>
        </div>
    </div>
</div>
@endsection
