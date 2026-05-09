<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('template/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        .auth-bg {
            min-height: 100vh;
            background: #f6f7fb;
        }

        .auth-card {
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, .06);
            box-shadow: 0 18px 48px rgba(17, 24, 39, .10);
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(78, 115, 223, .10);
            color: #4e73df;
            margin-bottom: 16px;
        }

        .auth-logo i {
            font-size: 30px;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .auth-label {
            font-weight: 700;
            color: #374151;
            font-size: 13px;
        }

        .auth-input {
            border-radius: 10px;
            padding: 12px 14px;
            height: auto;
        }

        .auth-password-group {
            position: relative;
        }

        .auth-password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #6b7280;
            padding: 6px;
            line-height: 1;
        }

        .auth-password-toggle:focus {
            outline: none;
        }

        .auth-btn {
            border: 0;
            border-radius: 12px;
            padding: 12px 14px;
            font-weight: 800;
            background: linear-gradient(90deg, #7c3aed 0%, #2563eb 45%, #06b6d4 100%);
        }

        .auth-terms {
            margin-top: 18px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body class="auth-bg d-flex align-items-center">

    <div class="container">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <div class="auth-logo">
                                <i class="fas fa-laugh-wink"></i>
                            </div>
                            <div class="auth-title">Welcome To Intaran</div>
                            <div class="auth-subtitle">Website data sertifikat tanah</div>
                        </div>

                        <form method="POST" action="{{ route('login.attempt') }}">
                            @csrf

                            <div class="form-group">
                                <label class="auth-label">Email</label>
                                <input type="email" class="form-control auth-input @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="auth-label">Password</label>
                                <div class="auth-password-group">
                                    <input type="password" id="passwordInput" class="form-control auth-input @error('password') is-invalid @enderror"
                                        name="password" placeholder="Password" required>
                                    <button type="button" class="auth-password-toggle" id="passwordToggle" aria-label="Lihat password">
                                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group d-flex align-items-center">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Remember me</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary auth-btn btn-block">
                                Login
                            </button>
                        </form>

                        <div class="auth-terms">
                            By continuing, you agree to Intaran's User Agreement and Privacy Policy.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js')}}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function () {
            var input = document.getElementById('passwordInput');
            var btn = document.getElementById('passwordToggle');
            var icon = document.getElementById('passwordToggleIcon');
            if (!input || !btn || !icon) return;

            btn.addEventListener('click', function () {
                var show = input.getAttribute('type') === 'password';
                input.setAttribute('type', show ? 'text' : 'password');
                icon.classList.toggle('fa-eye', !show);
                icon.classList.toggle('fa-eye-slash', show);
            });
        })();
    </script>

    <script>
        (function () {
            var message = @json(session('success') ?? session('error') ?? session('warning') ?? session('info'));
            if (!message) return;

            var type = 'success';
            @if (session('error'))
                type = 'error';
            @elseif (session('warning'))
                type = 'warning';
            @elseif (session('info'))
                type = 'info';
            @endif

            if (typeof Swal === 'undefined') return;

            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Success' : (type === 'error' ? 'Error' : (type === 'warning' ? 'Warning' : 'Info')),
                text: message,
                confirmButtonText: 'Ok',
                confirmButtonColor: '#22c55e'
            });
        })();
    </script>
</body>

</html>
