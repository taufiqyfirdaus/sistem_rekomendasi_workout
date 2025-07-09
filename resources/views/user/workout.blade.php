@extends('layout.master')
@section('title', 'FitQ Workout')

@section('content')
    <main class="main-workout py-5">
        <div class="container">
            {{-- Bagian atas --}}
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h4 class="fw-bold">Daftar Workout</h4>
                </div>
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <form method="GET" action="{{ route('workoutsUser') }}">
                        <div class="row g-2">
                            {{-- Limit & Kategori --}}
                            <div class="col-6 col-md-3">
                                <select name="limit" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Tampilkan 10</option>
                                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Tampilkan 20</option>
                                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Tampilkan 50</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Search & Tombol --}}
                            <div class="col-12 col-md-4">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search nama/kategori..." value="{{ $search }}">
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Grid Workout --}}
            <div class="row" id="workout-list">
                @forelse ($workouts as $workout)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100 cursor-pointer" style="cursor:pointer;" data-workout='@json($workout)'>
                            <img src="{{ $workout->ilustrasi ?? 'https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg' }}"
                                class="card-img-top workout-img border-bottom border-2" alt="{{ $workout->nama_workout }}">
                            <div class="card-body text-center">
                                <p class="card-text fw-bold mb-1" style="font-size: 18px;">
                                    {{ $workout->nama_workout }}
                                </p>
                                <p class="card-text fw-semibold" style="font-size: 13px;">
                                    Kategori : {{ $workout->kategori }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted no-workout">
                        <p>Workout tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Modal Detail Workout --}}
            <div class="modal fade" id="modalDetailWorkout" tabindex="-1" aria-labelledby="modalDetailWorkoutLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDetailWorkoutLabel">Detail Workout <span id="detail-nama"></span></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- Bagian kiri --}}
                                <div class="col-md-5 text-center mb-3 mb-md-0">
                                    <h6 class="text-start"><b>Ilustrasi</b></h6>
                                    <img id="detail-ilustrasi" src="" alt="Ilustrasi" class="img-fluid w-100 rounded mb-3" style="max-height: 300px; object-fit: cover;">
                                    <p><b>Kategori:</b> <span id="detail-kategori"></span></p>
                                    <p><b>Tingkat Kesulitan:</b> <span id="detail-tingkat"></span></p>
                                    <p><b>Durasi:</b> <span id="detail-durasi"></span> menit</p>
                                    <p><b>Alat:</b> <span id="detail-alat"></span></p>
                                </div>

                                {{-- Bagian kanan --}}
                                <div class="col-md-7">
                                    <h6><b>Deskripsi</b></h6>
                                    <div id="detail-deskripsi" class="trix-content"></div>

                                    <h6><b>Instruksi</b></h6>
                                    <div id="detail-instruksi" class="trix-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center text-center">
                    <div class="pagination-workout">
                        {{ $workouts->links('pagination::bootstrap-5') }}
                    </div>
                    <div class="text-muted fs-6 d-block d-sm-none">
                        Showing <strong>{{ $workouts->firstItem() }}</strong> to <strong>{{ $workouts->lastItem() }}</strong> of <strong>{{ $workouts->total() }}</strong> results
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('modalDetailWorkout'));

            document.querySelectorAll('.card[data-workout]').forEach(card => {
                card.addEventListener('click', () => {
                    const workout = JSON.parse(card.dataset.workout);

                    document.getElementById('detail-nama').textContent = workout.nama_workout;
                    document.getElementById('detail-ilustrasi').src = workout.ilustrasi ||
                        'https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg';
                    document.getElementById('detail-kategori').textContent = workout.kategori;
                    document.getElementById('detail-tingkat').textContent = workout.tingkat_kesulitan;
                    document.getElementById('detail-durasi').textContent = workout.durasi;
                    document.getElementById('detail-alat').textContent = workout.alat;
                    document.getElementById('detail-deskripsi').innerHTML = workout.deskripsi || '-';
                    document.getElementById('detail-instruksi').innerHTML = workout.instruksi || '-';

                    modal.show();
                });
            });
        });
    </script>
@endpush
