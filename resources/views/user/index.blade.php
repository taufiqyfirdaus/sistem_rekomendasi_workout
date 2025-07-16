@extends('layout.master')
@section('title', 'FitQ Beranda')

@section('content')

    <main class="main-user py-5">
        <div class="container">
            <div class="row">
                {{-- Bagian kiri --}}
                <div class="col-md-8">
                    {{-- Welcome --}}
                    <div class="mb-3">
                        @auth
                            <h4 class="fw-bold">Selamat Datang, {{ Auth::user()->username }}</h4>
                        @else
                            <h4 class="fw-bold">Selamat Datang Pengguna Baru</h4>
                        @endauth
                    </div>
                    {{-- Kondisi & Preferensi --}}
                    <div class="bg-white rounded shadow p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        {{-- Pesan status --}}
                        <div class="d-flex align-items-center gap-2 flex-grow-1">
                            <i class="bi bi-info-circle fs-5 @guest text-danger @else {{ $statusColor ?? '' }} @endguest"></i>

                            @guest
                                <span class="text-danger">Silakan login untuk rekomendasi workout!</span>
                            @else
                                @php
                                    $kondisiLengkap = $kondisi && $kondisi->tanggal_lahir && $kondisi->berat && $kondisi->tinggi && $kondisi->kondisi_kesehatan && $kondisi->tingkat_kebugaran;
                                    $preferensiLengkap = $preferensi && $preferensi->jenis_olahraga_favorit && $preferensi->tujuan_workout && $preferensi->durasi && $preferensi->alat;

                                    if (!$kondisiLengkap && !$preferensiLengkap) {
                                        $statusText = 'Lengkapi data kondisi tubuh dan preferensi anda!';
                                        $statusColor = 'text-danger';
                                    } elseif (!$kondisiLengkap) {
                                        $statusText = 'Lengkapi data kondisi tubuh anda!';
                                        $statusColor = 'text-danger';
                                    } elseif (!$preferensiLengkap) {
                                        $statusText = 'Lengkapi data preferensi anda!';
                                        $statusColor = 'text-danger';
                                    } else {
                                        $statusText = 'Data kondisi tubuh dan preferensi anda sudah lengkap!';
                                        $statusColor = 'text-success';
                                    }
                                @endphp

                                <span class="{{ $statusColor }}">{{ $statusText }}</span>
                            @endguest
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex gap-2">
                            @auth
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalKondisi">
                                    Kondisi Tubuh
                                </button>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalPreferensi">
                                    Preferensi
                                </button>
                            @else
                                <button class="btn btn-outline-primary" disabled title="Silakan login terlebih dahulu">
                                    Kondisi Tubuh
                                </button>
                                <button class="btn btn-outline-secondary" disabled title="Silakan login terlebih dahulu">
                                    Preferensi
                                </button>
                            @endauth
                        </div>
                    </div>

                    {{-- Modal Kondisi Tubuh --}}
                    <div class="modal fade" id="modalKondisi" tabindex="-1" aria-labelledby="modalKondisiLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md mt-5">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="modalKondisiLabel">Kondisi Tubuh</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="form-kondisi" method="POST" action="{{ route('updateKondisi') }}">
                                        @csrf
                                        @auth
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Username</label>
                                                <input type="text" class="form-control" name="username" value="{{ Auth::user()->username }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                                <input type="text" class="form-control" name="jenis_kelamin" value="{{ Auth::user()->jenis_kelamin }}" readonly>
                                            </div>
                                        @endauth

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Tanggal Lahir</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Tanggal lahir digunakan untuk menghitung usia Anda</i></div>
                                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $kondisi?->tanggal_lahir) }}"
                                                placeholder="Masukkan tanggal lahir" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Usia</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Dihitung otomatis berdasarkan tanggal lahir Anda</i></div>
                                            <input type="text" class="form-control" name="usia" id="usia" placeholder="Usia anda" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Berat Badan (kg)</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Masukkan berat Anda saat ini dalam satuan kilogram</i></div>
                                            <input type="number" step="any" class="form-control" name="berat" id="berat" value="{{ old('berat', $kondisi?->berat) }}"
                                                placeholder="Masukkan berat badan" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Tinggi Badan (cm)</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Masukkan tinggi Anda saat ini dalam satuan centimeter</i>
                                            </div>
                                            <input type="number" step="any" class="form-control" name="tinggi" id="tinggi" value="{{ old('tinggi', $kondisi?->tinggi) }}"
                                                placeholder="Masukkan tinggi badan" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">BMI (Body Mass Index)</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Dihitung otomatis berdasarkan berat dan tinggi badan</i>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="bmi-index" id="bmi-index" placeholder="Index" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="bmi-kategori" id="bmi-kategori" placeholder="Klasifikasi" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Kondisi Kesehatan</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Kondisi kesehatan yang Anda rasakan saat ini</i></div>
                                            <select class="form-select" name="kondisi_kesehatan" required>
                                                <option disabled selected>Pilih Kondisi Kesehatan</option>
                                                @foreach (['Normal', 'Cedera', 'Hipertensi', 'Hipotensi', 'Diabetes', 'Obesitas', 'Penyakit Jantung', 'Asma'] as $kesehatan)
                                                    <option value="{{ $kesehatan }}" {{ old('kondisi_kesehatan', $kondisi?->kondisi_kesehatan) === $kesehatan ? 'selected' : '' }}>
                                                        {{ $kesehatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Tingkat Kebugaran</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Perkiraan intensitas aktivitas fisik sehari-hari Anda</i>
                                            </div>
                                            <select class="form-select" name="tingkat_kebugaran" required>
                                                <option disabled selected>Pilih Tingkat Kebugaran</option>
                                                @foreach (['Rendah', 'Sedang', 'Tinggi'] as $tingkat)
                                                    <option value="{{ $tingkat }}" {{ old('tingkat_kebugaran', $kondisi?->tingkat_kebugaran) === $tingkat ? 'selected' : '' }}>
                                                        {{ $tingkat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" form="form-kondisi" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Preferensi --}}
                    <div class="modal fade" id="modalPreferensi" tabindex="-1" aria-labelledby="modalPreferensiLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md mt-5">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="modalPreferensiLabel">Preferensi Workout</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="form-preferensi" method="POST" action="{{ route('updatePreferensi') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Jenis Olahraga Favorit</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Jenis olahraga yang paling anda minati. Contoh :
                                                    <br><b>- Kardio :</b> Lari, Bersepeda, dan lain-lain
                                                    <br><b>- Bodyweight Training :</b> Push-up, Sit-up, dan lain-lain
                                                    <br><b>- Fleksibilitas :</b> Stretching, Yoga, dan lain-lain
                                                    <br><b>- Dance Fitness :</b> Zumba, Aerobik, dan lain-lain
                                                    <br><b>- HIIT :</b> HIIT, Cardio Boxing, dan lain-lain
                                                    <br><b>- Kekuatan :</b> Latihan dumbbell, Latihan Beban dan lain-lain
                                                </i>
                                            </div>
                                            <select class="form-select" name="jenis_olahraga_favorit" required>
                                                <option disabled selected>Pilih Jenis Olahraga Favorit</option>
                                                @foreach (['Kardio', 'Bodyweight Training', 'Fleksibilitas', 'Dance Fitness', 'HIIT', 'Kekuatan'] as $jenis)
                                                    <option value="{{ $jenis }}" {{ old('jenis_olahraga_favorit', $preferensi?->jenis_olahraga_favorit) === $jenis ? 'selected' : '' }}>
                                                        {{ $jenis }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Tujuan Melakukan Workout</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Tujuan utama Anda dalam melakukan workout</i></div>
                                            <select class="form-select" name="tujuan_workout" required>
                                                <option disabled selected>Pilih Tujuan Melakukan Workout</option>
                                                @foreach (['Menurunkan Berat Badan', 'Meningkatkan Massa Otot & Kekuatan', 'Meningkatkan Kebugaran Kardiovaskular', 'Meningkatkan Fleksibilitas', 'Relaksasi'] as $tujuan)
                                                    <option value="{{ $tujuan }}" {{ old('tujuan_workout', $preferensi?->tujuan_workout) === $tujuan ? 'selected' : '' }}>{{ $tujuan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Durasi Latihan (menit)</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;"><i class="bi bi-info-circle"> Waktu yang Anda siapkan untuk melakukan workout</i></div>
                                            <select class="form-select" name="durasi" required>
                                                <option disabled selected>Pilih Durasi Latihan</option>
                                                @foreach (['<=30 menit', '<=60 menit', '<=120 menit'] as $durasi)
                                                    <option value="{{ $durasi }}" {{ old('durasi', $preferensi?->durasi) === $durasi ? 'selected' : '' }}>{{ $durasi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label mb-0 fw-semibold">Kelengkapan Alat</label>
                                            <div class="form-text text-muted mt-0 mb-1" style="font-size: 10px;">
                                                <i class="bi bi-info-circle"> Tingkat kelengkapan alat yang anda miliki. Keterangan :
                                                    <br><b>- Tidak ada :</b> Tidak memiliki peralatan
                                                    <br><b>- Dasar :</b> Peralatan seperti sepatu lari, tali lompat, sepeda, dan matras
                                                    <br><b>- Lengkap :</b> Peralatan dasar + peralatan lain seperti dumbbell, barbell, dan sepeda statis
                                                </i>
                                            </div>
                                            <select class="form-select" name="alat" required>
                                                <option disabled selected>Pilih Kelengkapan Alat</option>
                                                @foreach (['Tidak ada', 'Dasar', 'Lengkap'] as $alat)
                                                    <option value="{{ $alat }}" {{ old('alat', $preferensi?->alat) === $alat ? 'selected' : '' }}>{{ $alat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" form="form-preferensi" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rekomendasi Workout --}}
                    <div class="bg-white rounded shadow p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mb-3 workout-header">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                                @auth
                                    @php
                                        $kondisiLengkap = $kondisi && $kondisi->tanggal_lahir && $kondisi->berat && $kondisi->tinggi && $kondisi->kondisi_kesehatan && $kondisi->tingkat_kebugaran;
                                        $preferensiLengkap = $preferensi && $preferensi->jenis_olahraga_favorit && $preferensi->tujuan_workout && $preferensi->durasi && $preferensi->alat;
                                        $dataLengkap = $kondisiLengkap && $preferensiLengkap;
                                    @endphp

                                    <button class="btn btn-primary workout-btn fw-bold text-nowrap w-100 w-md-auto px-4" id="btnRekomendasi" data-bs-toggle="modal" data-bs-target="#modalMood"
                                        {{ !$dataLengkap || session('rekomendasi') ? 'disabled' : '' }}
                                        title="{{ !$dataLengkap ? 'Lengkapi kondisi tubuh & preferensi dahulu' : (session('rekomendasi') ? 'Rekomendasi sudah diberikan' : '') }}">
                                        Rekomendasikan Workout
                                    </button>

                                    @if (session('rekomendasi'))
                                        <button class="btn btn-success fw-bold text-nowrap w-100 w-md-auto px-4" data-bs-toggle="modal" data-bs-target="#modalFeedback">
                                            Selesaikan Workout
                                        </button>
                                    @else
                                        <button class="btn btn-success fw-bold text-nowrap w-100 w-md-auto px-4" disabled>
                                            Selesaikan Workout
                                        </button>
                                    @endif
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-primary fw-bold text-nowrap w-100 w-md-auto px-4">
                                        Rekomendasikan Workout
                                    </a>
                                    <button class="btn btn-success fw-bold text-nowrap w-100 w-md-auto px-4" disabled>
                                        Selesaikan Workout
                                    </button>
                                @endguest
                            </div>
                            <h5 class="mb-0 fw-bold text-center text-md-end">Hasil Rekomendasi</h5>
                        </div>

                        {{-- Modal Mood Harian --}}
                        <div class="modal fade" id="modalMood" tabindex="-1" aria-labelledby="modalMoodLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md mt-5 pe-0">
                                <div class="modal-content">
                                    <form id="form-mood" method="POST" action="{{ route('qLearningProses') }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="modalMoodLabel">Mood Hari Ini</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p class="mb-3">Bagaimana mood kamu hari ini?</p>
                                            <div class="d-flex justify-content-center gap-4">
                                                <label>
                                                    <input type="radio" name="mood" value="bagus" class="d-none" required>
                                                    <span style="font-size: 2rem; cursor: pointer;">😊</span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="mood" value="jelek" class="d-none" required>
                                                    <span style="font-size: 2rem; cursor: pointer;">😞</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Feedback --}}
                        <div class="modal fade" id="modalFeedback" tabindex="-1" aria-labelledby="modalFeedbackLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md mt-5 pe-0">
                                <form method="POST" action="{{ route('qLearningFeedback') }}" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Feedback Workout</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="mb-3">Bagaimana workout yang telah direkomendasikan?</p>
                                        <div class="d-flex justify-content-center gap-3">
                                            <input type="radio" class="btn-check" name="feedback" id="feedbackPas" value="5" required>
                                            <label class="btn btn-success flex-fill d-flex justify-content-center align-items-center py-3" for="feedbackPas">
                                                Pas
                                            </label>

                                            <input type="radio" class="btn-check" name="feedback" id="feedbackRingan" value="2" required>
                                            <label class="btn btn-warning flex-fill d-flex justify-content-center align-items-center text-dark py-3" for="feedbackRingan">
                                                Terlalu Ringan
                                            </label>

                                            <input type="radio" class="btn-check" name="feedback" id="feedbackBerat" value="-5" required>
                                            <label class="btn btn-danger flex-fill d-flex justify-content-center align-items-center py-3" for="feedbackBerat">
                                                Terlalu Berat
                                            </label>
                                        </div>

                                        @if (session('rekomendasi'))
                                            <input type="hidden" name="workout_id" value="{{ session('rekomendasi')->id }}">
                                        @endif
                                        @if (session('last_history_id'))
                                            <input type="hidden" name="history_id" value="{{ session('last_history_id') }}">
                                        @endif
                                        <input type="hidden" name="local_date" id="local_date">
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button class="btn btn-primary">Kirim Feedback</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr class="my-4" style="border-top: 3px solid #868686;">
                        @if (session('rekomendasi'))
                            @php
                                $rekomendasi = session('rekomendasi');
                            @endphp
                            <div class="row">
                                {{-- Kiri: Ilustrasi dan info --}}
                                <h3 class="text-center mb-4"><b>{{ $rekomendasi->nama_workout }}</b></h3>
                                <div class="col-md-5 text-center mb-3 mb-md-0">

                                    <img src="{{ $rekomendasi->ilustrasi ?? 'https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg' }}" alt="Ilustrasi"
                                        class="img-fluid w-100 rounded mb-3" style="max-height: 300px; object-fit: cover;">
                                    <p><b>Kategori:</b> {{ $rekomendasi->kategori }}</p>
                                    <p><b>Tingkat Kesulitan:</b> {{ $rekomendasi->tingkat_kesulitan }}</p>
                                    <p><b>Durasi:</b> {{ $rekomendasi->durasi }} menit</p>
                                    <p><b>Alat:</b> {{ $rekomendasi->alat }}</p>
                                </div>

                                {{-- Kanan: Deskripsi dan instruksi --}}
                                <div class="col-md-7">
                                    <h6><b>Deskripsi</b></h6>
                                    <div class="trix-content">{!! $rekomendasi->deskripsi ?? '-' !!}</div>

                                    <h6 class="mt-3"><b>Instruksi</b></h6>
                                    <div class="trix-content">{!! $rekomendasi->instruksi ?? '-' !!}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <em>Belum ada rekomendasi ditampilkan.</em>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Bagian Kanan --}}
                <div class="col-md-4 mt-4 mt-md-0">
                    {{-- Kalender --}}
                    <div class="bg-white rounded shadow p-3 mb-4 calendar-container" id="calendar-container">
                        @include('user.partials.calendar', ['currentMonth' => $currentMonth, 'progress' => $progress])
                    </div>

                    {{-- Tips --}}
                    @auth
                        @php
                            $tips = [
                                'Jangan lupa melakukan pemanasan sebelum workout serta pendinginan setelah workout.',
                                'Pastikan kamu cukup minum air sebelum, saat, dan setelah berolahraga.',
                                'Makan makanan bergizi seimbang untuk mendukung performa latihan.',
                                'Tidur yang cukup membantu proses pemulihan tubuh setelah workout.',
                                'Lakukan olahraga sesuai dengan kemampuan dan kondisi tubuhmu.',
                                'Gunakan pakaian yang nyaman saat berolahraga.',
                                'Jangan paksakan diri jika merasa lelah atau tidak enak badan.',
                                'Fokus pada teknik yang benar untuk mencegah cedera.',
                                'Nyeri otot setelah pertama kali berolahraga adalah hal yang wajar — tubuhmu sedang beradaptasi.',
                                'Jika tujuanmu menurunkan berat badan, cobalah menjaga pola makan dengan defisit kalori yang sehat.',
                                'Berikanlah waktu istirahat setiap minggunya paling tidak 1 atau 2 hari untuk membantu memulihkan diri dari kelelahan, memperbaiki jaringan yang rusak, dan mencegah cedera.',
                            ];
                            $randomTip = $randomTip ?? $tips[array_rand($tips)];
                        @endphp

                        <div class="bg-white rounded shadow p-3">
                            <h5><i class="bi bi-info-circle"></i> Tips</h5>
                            <hr class="my-2" style="border-top: 3px solid #868686;">
                            <p class="text-muted mt-3">{{ $randomTip ?? 'Belum ada tips saat ini.' }}</p>
                        </div>
                    @endauth
                    @guest
                        <div class="bg-white rounded shadow p-3">
                            <h5><i class="bi bi-info-circle"></i> Tips</h5>
                            <hr class="my-2" style="border-top: 3px solid #868686;">
                            <p class="text-muted">Belum ada tips saat ini.</p>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </main>

@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadCalendar(monthString) {
            // update URL
            const url = new URL(window.location.href);
            url.searchParams.set('month', monthString);
            window.history.pushState({}, '', url);

            // load partial
            $.get("{{ route('calendarPartial') }}", {
                month: monthString
            }, function(data) {
                $('#calendar-container').html(data);
                highlightToday();
            });
        }

        // Event tombol prev / next
        $(document).on('click', '.btn-change-month', function() {
            const month = $(this).data('month');
            loadCalendar(month);
        });

        // Event dropdown bulan / tahun
        $(document).on('change', '#select-month, #select-year', function() {
            const selectedMonth = $('#select-month').val();
            const selectedYear = $('#select-year').val();
            const monthString = selectedYear + '-' + selectedMonth;
            loadCalendar(monthString);
        });
    </script>
    <script>
        function hitungBMI() {
            const berat = parseFloat(document.getElementById('berat').value);
            const tinggi = parseFloat(document.getElementById('tinggi').value);

            if (!berat || !tinggi || tinggi === 0) {
                document.getElementById('bmi-index').value = '';
                document.getElementById('bmi-kategori').value = '';
                return;
            }

            const tinggiMeter = tinggi / 100;
            const bmi = berat / (tinggiMeter * tinggiMeter);
            const bmiFix = bmi.toFixed(1);

            // Tampilkan hasil
            document.getElementById('bmi-index').value = bmiFix;

            let kategori = '';
            if (bmi < 18.5) {
                kategori = 'Berat badan kurang';
            } else if (bmi < 25.0) {
                kategori = 'Berat badan normal';
            } else if (bmi < 30.0) {
                kategori = 'Berat badan berlebih';
            } else {
                kategori = 'Obesitas';
            }

            document.getElementById('bmi-kategori').value = kategori;
        }
    </script>
    <script>
        function hitungUsia() {
            const tglLahirInput = document.querySelector('input[type="date"]');
            const usiaInput = document.getElementById('usia');

            if (!tglLahirInput || !usiaInput || !tglLahirInput.value) {
                usiaInput.value = '';
                return;
            }

            const tglLahir = new Date(tglLahirInput.value);
            const today = new Date();

            let usia = today.getFullYear() - tglLahir.getFullYear();
            const m = today.getMonth() - tglLahir.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < tglLahir.getDate())) {
                usia--;
            }

            usiaInput.value = usia + ' tahun';
        }
    </script>
    <script>
        $('#modalKondisi').on('shown.bs.modal', function() {
            hitungBMI();
            hitungUsia();

            document.getElementById('berat').addEventListener('input', hitungBMI);
            document.getElementById('tinggi').addEventListener('input', hitungBMI);
            document.getElementById('tanggal_lahir').addEventListener('change', hitungUsia);
        });
    </script>
    <script>
        // Saat modal feedback muncul, isi tanggal lokal
        $('#modalFeedback').on('shown.bs.modal', function() {
            // const localDate = new Date().toISOString().slice(0, 10);
            const d = new Date();
            const localDate = d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');

            document.getElementById('local_date').value = localDate;
        });
    </script>
    <script>
        function highlightToday() {
            const today = new Date();
            const localDate = today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');

            const todayCell = document.querySelector(`td[data-date="${localDate}"]`);
            if (todayCell && !todayCell.classList.contains('bg-success')) {
                todayCell.classList.add('bg-secondary-subtle', 'fw-bold');
            }
        }

        document.addEventListener('DOMContentLoaded', highlightToday);
    </script>
@endpush
