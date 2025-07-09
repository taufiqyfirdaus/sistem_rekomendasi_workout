@extends('layout.masterAdmin')
@section('title', 'FitQ Admin Dashboard')

@section('content')
    <main class="main-dashboard py-5 px-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="fw-bold">Dashboard Admin</h4>
                </div>
            </div>

            <div class="card shadow-sm p-4 mb-4">
                <div class="ms-4">
                    <a href="{{ route('adminWorkout') }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-start mb-4">
                            <div class="me-5">
                                <div class="icon-dashboard rounded d-flex justify-content-center align-items-center">
                                    <i class="bi bi-person-walking fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold">Kelola Data Workout</h5>
                                <p class="text-muted mb-0">Kelola data workout meliputi tambah, edit dan hapus</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('adminUser') }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-start mb-4">
                            <div class="me-5">
                                <div class="icon-dashboard rounded d-flex justify-content-center align-items-center">
                                    <i class="bi bi-people-fill fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold">Kelola Data User</h5>
                                <p class="text-muted mb-0">Data pengguna yang melakukan workout</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('adminUser') }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-start mb-4">
                            <div class="me-5">
                                <div class="icon-dashboard rounded d-flex justify-content-center align-items-center">
                                    <i class="bi bi-person-fill-gear fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold">Kelola Data Admin</h5>
                                <p class="text-muted mb-0">Kelola data pengguna meliputi tambah, edit dan hapus</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('adminHistory') }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-start">
                            <div class="me-5">
                                <div class="icon-dashboard rounded d-flex justify-content-center align-items-center">
                                    <i class="bi bi-clock-fill fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold">Kelola History Workout</h5>
                                <p class="text-muted mb-0">Riwayat aktivitas workout user</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mb-4 justify-content-center">
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill fs-1 text-success me-3"></i>
                            <div>
                                <h6>Total User</h6>
                                <h4>{{ $jumlahUser }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-gear fs-1 text-primary me-3"></i>
                            <div>
                                <h6>Total Admin</h6>
                                <h4>{{ $jumlahAdmin }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-walking fs-1 text-danger me-3"></i>
                            <div>
                                <h6>Total Workout</h6>
                                <h4>{{ $jumlahWorkout }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-fill fs-1 text-warning me-3"></i>
                            <div>
                                <h6>Total History</h6>
                                <h4>{{ $jumlahHistory }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm p-4">
                <h6 class="mb-3">5 History Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Workout</th>
                                <th>Tanggal</th>
                                <th>Mood</th>
                                <th>Strategi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentHistories as $h)
                                <tr>
                                    <td>{{ $h->user->username }}</td>
                                    <td>{{ $h->workout->nama_workout }}</td>
                                    <td>{{ $h->tanggal }}</td>
                                    <td>{{ $h->mood }}</td>
                                    <td>{{ $h->strategi ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
