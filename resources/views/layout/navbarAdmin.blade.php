<nav class="navbar navbar-dark" style="margin-left: 220px; height: 70px; background-color: #52649d;">
    <div class="container-fluid justify-content-end">
        <h6 class="text-light mb-0 me-3 fs-6"><b>{{ Auth::user()->username }}</b></h6>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger px-4 mx-4">Log Out</button>
        </form>
    </div>
</nav>
