<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FitQ Login</title>
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
                    <h2 class="text-center" style="font-size: 25px; font-weight: bold;">
                        Selamat Datang Kembali
                    </h2>

                    {{-- message --}}
                    @if (session('success') || $errors->has('login'))
                        <div class="position-fixed top-0 start-50 translate-middle-x mt-3 z-3" style="z-index: 1055; min-width: 300px;">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center shadow" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->has('login'))
                                <div class="alert alert-danger alert-dismissible fade show text-center shadow" role="alert">
                                    {{ $errors->first('login') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group my-4">
                            <label for="username" class="form-label">Username</label>
                            <input type="username" class="form-control" id="username" name="username" placeholder="Masukan Username" value="{{ old('username') }}" required />
                        </div>
                        <div class="form-group my-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="Masukan Password" name="password" required />
                                <span class="input-group-text" id="icon-klik" style="cursor: pointer;">
                                    <i class="bi bi-eye-fill" id="icon"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary fw-bold w-100">
                                Login
                            </button>
                        </div>
                        <div class="my-3">
                            Belum punya akun? <a href="{{ route('register') }}">Silahkan Daftar</a>
                        </div>
                    </form>
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
    </script>
    <script>
        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                setTimeout(() => alertEl.remove(), 300);
            }
        }, 5000);
    </script>
</body>

</html>
