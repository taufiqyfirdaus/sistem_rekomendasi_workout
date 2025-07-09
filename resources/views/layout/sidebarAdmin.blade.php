<aside class="sidebar position-fixed top-0 start-0 vh-100" style="width: 220px; background-color: #3b4d81; z-index: 1030;">
    <div class="d-flex align-items-center gap-2 p-3">
        <a class="navbar-brand" href="{{ route('homeUser') }}">
            <img src="{{ asset('assets/logo1_light.svg') }}" alt="Logo" style="width: 50px; height: 50px;">
        </a>
        <h5 class="text-white fw-bold mb-0">ADMIN</h5>
    </div>

    <ul class="nav flex-column px-2 pt-3">
        <li class="nav-item mb-2">
            <a href="{{ route('adminIndex') }}" class="nav-link text-white {{ Route::currentRouteName() === 'adminIndex' ? 'fw-bold' : '' }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('adminWorkout') }}" class="nav-link text-white {{ Route::currentRouteName() === 'adminWorkout' ? 'fw-bold' : '' }}">
                Data Workout
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('adminUser') }}" class="nav-link text-white {{ Route::currentRouteName() === 'adminUser' ? 'fw-bold' : '' }}">
                Data User
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('adminHistory') }}" class="nav-link text-white {{ Route::currentRouteName() === 'adminHistory' ? 'fw-bold' : '' }}">
                Data History
            </a>
        </li>
    </ul>
</aside>
