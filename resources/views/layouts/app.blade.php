<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DATA INTARAN - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        .mobile-bottom-nav {
            display: none;
        }

        .mobile-add-menu {
            display: none;
        }

        @media (max-width: 768px) {
            #accordionSidebar {
                display: none !important;
            }

            #content-wrapper {
                margin-left: 0 !important;
            }

            .container-fluid {
                padding-bottom: 92px;
            }

            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                height: 72px;
                background: #ffffff;
                border-top: 1px solid rgba(0, 0, 0, .08);
                z-index: 1040;
                align-items: center;
                justify-content: space-around;
                padding: 8px 10px;
            }

            .mobile-bottom-nav a {
                text-decoration: none;
            }

            .mobile-bottom-nav-item {
                flex: 1 1 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #6c757d;
                font-size: 11px;
                font-weight: 700;
                gap: 4px;
            }

            .mobile-bottom-nav-item i {
                font-size: 18px;
            }

            .mobile-bottom-nav-item.is-active {
                color: #4e73df;
            }

            .mobile-bottom-nav-add-wrap {
                flex: 1 1 0;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .mobile-bottom-nav-add {
                width: 52px;
                height: 52px;
                border-radius: 999px;
                border: 0;
                background: #111827;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
            }

            .mobile-bottom-nav-add i {
                font-size: 20px;
            }

            .mobile-add-menu {
                position: fixed;
                left: 50%;
                bottom: 84px;
                transform: translateX(-50%);
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 14px;
                box-shadow: 0 18px 40px rgba(0, 0, 0, .18);
                padding: 10px;
                width: min(320px, calc(100vw - 24px));
                display: none;
                z-index: 1050;
            }

            .mobile-add-menu.is-open {
                display: block;
            }

            .mobile-add-menu a {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 10px;
                color: #111827;
                font-weight: 800;
                text-decoration: none;
            }

            .mobile-add-menu a:active {
                background: rgba(0, 0, 0, .04);
            }

            .mobile-add-menu a i {
                width: 22px;
                text-align: center;
                color: #4e73df;
            }
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                    <!-- Page Heading -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js')}}" ></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('template/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js')}}"></script>

    <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
        <a href="{{ url('/dashboard') }}" class="mobile-bottom-nav-item {{ request()->is('dashboard') ? 'is-active' : '' }}">
            <i class="fas fa-home"></i>
            <span>DASHBOARD</span>
        </a>

        <div class="mobile-bottom-nav-add-wrap">
            <button type="button" class="mobile-bottom-nav-add" id="mobileAddBtn" aria-label="Add">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <a href="{{ url('/tanah') }}" class="mobile-bottom-nav-item {{ request()->is('tanah*') ? 'is-active' : '' }}">
            <i class="fas fa-map"></i>
            <span>TANAH</span>
        </a>
    </nav>

    <div class="mobile-add-menu" id="mobileAddMenu" aria-hidden="true">
        <a href="{{ url('/tanah/create') }}">
            <i class="fas fa-map"></i>
            <span>Tambah Tanah</span>
        </a>
    </div>

    <script>
        (function () {
            function isOpen(menu) {
                return menu && menu.classList.contains('is-open');
            }

            function openMenu(menu) {
                if (!menu) return;
                menu.classList.add('is-open');
                menu.setAttribute('aria-hidden', 'false');
            }

            function closeMenu(menu) {
                if (!menu) return;
                menu.classList.remove('is-open');
                menu.setAttribute('aria-hidden', 'true');
            }

            var btn = document.getElementById('mobileAddBtn');
            var menu = document.getElementById('mobileAddMenu');
            if (!btn || !menu) return;

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (isOpen(menu)) {
                    closeMenu(menu);
                } else {
                    openMenu(menu);
                }
            });

            document.addEventListener('click', function (e) {
                if (e.target.closest('#mobileAddBtn')) return;
                if (e.target.closest('#mobileAddMenu')) return;
                closeMenu(menu);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeMenu(menu);
                }
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