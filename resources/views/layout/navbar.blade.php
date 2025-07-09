<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container d-flex align-items-center">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('homeUser') }}">
            <img src="{{ asset('assets/logo.svg') }}" class="img-fluid" style="width: 150px;" alt="logo" />
        </a>

        {{-- Tombol toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navigasi --}}
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-light {{ Route::currentRouteName() === 'homeUser' ? 'fw-bold' : '' }}" href="{{ route('homeUser') }}">Home</a>
                </li>
                <li class="nav-item">
                    @auth
                        <a class="nav-link text-light {{ Route::currentRouteName() === 'historyUser' ? 'fw-bold' : '' }}" href="{{ route('historyUser') }}">Riwayat</a>
                    @else
                        <a class="nav-link text-light disabled" href="#" tabindex="-1" aria-disabled="true" title="Login untuk mengakses riwayat" style="opacity: 0.6; pointer-events: none;">Riwayat</a>
                    @endauth
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light {{ Route::currentRouteName() === 'workoutsUser' ? 'fw-bold' : '' }}" href="{{ route('workoutsUser') }}">Workout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light {{ Route::currentRouteName() === 'instruksiUser' ? 'fw-bold' : '' }}" href="{{ route('instruksiUser') }}">Instruksi</a>
                </li>
                <li class="nav-item d-lg-none my-3 mx-3">
                    @if (Auth::check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold w-50">Logout</button>
                        </form>
                    @else
                        <a class="btn btn-primary fw-bold w-50" href="{{ route('login') }}">Login</a>
                    @endif
                </li>
            </ul>
        </div>

        {{-- Tombol login logout --}}
        <div class="d-none d-lg-block ms-auto">
            @if (Auth::check())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger fw-bold text-light" style="width: 150px;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary fw-bold text-light" style="width: 150px;">Login</a>
            @endif
        </div>
    </div>
</nav>
