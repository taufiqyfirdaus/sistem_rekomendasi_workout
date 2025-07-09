@extends('layout.master')
@section('title', 'FitQ Instruksi Penggunaan Sistem')

@section('content')

<main class="main-instruksi py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold">Instruksi Penggunaan Sistem</h4>
            </div>
        </div>

        {{-- Langkah 1 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">1.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Login ke sistem FitQ dengan cara pilih tombol Login pada navbar.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/1.png') }}" class="img-fluid rounded border" alt="Langkah 1">
                </div>
            </div>
        </div>

        {{-- Langkah 2 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">2.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Pada halaman login masukkan username dan password yang telah didaftarkan, Jika belum memiliki akun maka pilih link "Silahkan Daftar" pada halaman login untuk melakukan registrasi akun.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/2.png') }}" class="img-fluid rounded border" alt="Langkah 2">
                </div>
            </div>
        </div>

        {{-- Langkah 3 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">3.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Pada halaman beranda, lengkapi data kondisi tubuh serta preferensi pengguna dengan memilih tombol Kondisi Tubuh dan Preferensi.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/3.png') }}" class="img-fluid rounded border" alt="Langkah 3">
                </div>
            </div>
        </div>

        {{-- Langkah 4 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">4.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Pilih tombol "Rekomendasikan Workout" untuk melihat workout yang cocok untuk anda.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/4.png') }}" class="img-fluid rounded border" alt="Langkah 4">
                </div>
            </div>
        </div>

        {{-- Langkah 5 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">5.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Masukkan mood harian yang anda rasakan sebelum mendapatkan rekomendasi.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/5.png') }}" class="img-fluid rounded border" alt="Langkah 5">
                </div>
            </div>
        </div>

        {{-- Langkah 6 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">6.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Lihat hasil rekomendasi yang ditampilkan di halaman beranda.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/6.png') }}" class="img-fluid rounded border" alt="Langkah 6">
                </div>
            </div>
        </div>

        {{-- Langkah 7 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">7.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Jalankan workout dan pilih tombol Selesaikan Workout untuk mencatat workout yang diberikan sudah selesai dilakukan.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/7.png') }}" class="img-fluid rounded border" alt="Langkah 7">
                </div>
            </div>
        </div>

        {{-- Langkah 8 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">8.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Masukkan feedback untuk rekomendasi workout yang telah diberikan oleh sistem.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/8.png') }}" class="img-fluid rounded border" alt="Langkah 8">
                </div>
            </div>
        </div>

        {{-- Langkah 9 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">9.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda dapat melihat tanggal workout yang telah dilakukan pada bagian kalender yang ada di halaman Beranda.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/9.png') }}" class="img-fluid rounded border" alt="Langkah 9">
                </div>
            </div>
        </div>

        {{-- Langkah 10 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">10.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda bisa melihat riwayat rekomendasi workout anda dengan memilih menu Riwayat pada bagian navbar.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/10.png') }}" class="img-fluid rounded border" alt="Langkah 10">
                </div>
            </div>
        </div>

        {{-- Langkah 11 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">11.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda bisa melihat daftar workout serta detail workout yang tersedia pada sistem pada halaman Workout dengan memilih menu Workout pada bagian navbar.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/11.png') }}" class="img-fluid rounded border" alt="Langkah 10">
                </div>
            </div>
        </div>

        {{-- Langkah 12 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">12.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda bisa menggunakan fitur seperti pencarian dan tampilkan berdasarkan kategori pada halaman workout.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/12.png') }}" class="img-fluid rounded border" alt="Langkah 10">
                </div>
            </div>
        </div>

        {{-- Langkah 13 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">13.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda bisa melihat detail dari workout dengan memilih gambar atau card dari workout yang tersedia.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/13.png') }}" class="img-fluid rounded border" alt="Langkah 10">
                </div>
            </div>
        </div>

        {{-- Langkah 14 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h5 class="fw-bold">14.</h5>
                </div>
                <div class="col-md-7">
                    <p class="mb-0">Anda bisa melihat instruksi penggunaan sistem dengan memilih menu Instruksi pada bagian navbar.</p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-end">
                    <img src="{{ asset('assets/instruksi/14.png') }}" class="img-fluid rounded border" alt="Langkah 11">
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
