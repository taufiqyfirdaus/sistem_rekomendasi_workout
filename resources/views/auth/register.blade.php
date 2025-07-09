<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FitQ Register</title>
    <link rel="stylesheet" href="style/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body style="overflow-x: hidden">
    <div class="container py-5">
        <div class="row justify-content-center mb-4">
            <div class="col-8 col-md-4 text-center">
                <a href="{{ route('homeUser') }}">
                    <img src="assets/logo1.svg" class="logo-main img-fluid" alt="logo">
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 px-3 px-sm-4">
                <div class="bg-white p-4 rounded-4 shadow">
                    <h2 class="text-center mb-4" style="font-size: 25px; font-weight: bold;">
                        Daftar Untuk Memulai Workout!
                    </h2>

                    {{-- message error --}}
                    @if (session('error'))
                        <div class="alert alert-danger fade show" id="error-alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- message success --}}
                    @if (session('success'))
                        <div class="alert alert-success fade show" id="success-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group my-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="{{ old('username') }}" required />
                            @error('username')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group my-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ old('email') }}" required />
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group my-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group my-3">
                            <label for="password" class="form-label">Buat Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="Minimal 8 karakter" name="password" required />
                                <span class="input-group-text" id="icon-klik" style="cursor: pointer;">
                                    <i class="bi bi-eye-fill" id="icon"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group my-3">
                            <label for="password_confirmation" class="form-label">Ulangi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" placeholder="Ulangi Kata Sandi" name="password_confirmation" required />
                                <span class="input-group-text" id="icon-klik2" style="cursor: pointer;">
                                    <i class="bi bi-eye-fill" id="icon2"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary fw-bold w-100 mt-3">Register</button>
                    </form>

                    <div class="mt-3">
                        Sudah punya akun? <a href="{{ route('login') }}">Login disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#icon-klik").click(function() {
                $("#icon").toggleClass("bi-eye-slash");
                let x = document.getElementById("password");
                if (x.type == "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            });
        });
        $(document).ready(function() {
            $("#icon-klik2").click(function() {
                $("#icon2").toggleClass("bi-eye-slash");
                let x = document.getElementById("password_confirmation");
                if (x.type == "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            });
        });
    </script>
    <script>
        setTimeout(function () {
            ['error-alert', 'success-alert'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.remove('show');
                    el.classList.add('fade');
                    setTimeout(() => el.remove(), 300);
                }
            });
        }, 5000);
    </script>
</body>

</html>
