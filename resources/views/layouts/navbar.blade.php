<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <span class="d-md-none font-weight-bold text-dark mr-3" style="letter-spacing: .08em;">INTARAN</span>

                    <style>
                        .navbar-filter-overlay {
                            position: fixed;
                            inset: 0;
                            background: rgba(0, 0, 0, .25);
                            opacity: 0;
                            visibility: hidden;
                            transition: opacity .2s ease;
                            z-index: 30000;
                        }

                        .navbar-filter-overlay.show {
                            opacity: 1;
                            visibility: visible;
                        }

                        .navbar-filter-drawer {
                            position: fixed;
                            top: 0;
                            right: 0;
                            height: 100vh;
                            height: 100dvh;
                            width: 380px;
                            max-width: 92vw;
                            background: #fff;
                            box-shadow: -10px 0 30px rgba(0, 0, 0, .15);
                            transform: translateX(100%);
                            transition: transform .25s ease;
                            z-index: 30010;
                            display: flex;
                            flex-direction: column;
                            min-height: 0;
                        }

                        .navbar-filter-drawer.show {
                            transform: translateX(0);
                        }

                        .navbar-filter-header {
                            padding: 14px 16px;
                            border-bottom: 1px solid rgba(0, 0, 0, .08);
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            flex-shrink: 0;
                        }

                        .navbar-filter-body {
                            padding: 14px 16px;
                            overflow: auto;
                            flex: 1;
                            min-height: 0;
                        }

                        .navbar-filter-form {
                            display: flex;
                            flex-direction: column;
                            flex: 1;
                            min-height: 0;
                        }

                        .navbar-filter-body .form-group {
                            margin-bottom: 14px;
                        }

                        .navbar-filter-body label {
                            font-size: 12px;
                            font-weight: 800;
                            color: #4b5563;
                            text-transform: uppercase;
                            letter-spacing: .04em;
                        }

                        .navbar-filter-body .form-control {
                            border-radius: 10px;
                            border-color: #e5e7eb;
                            background: #fff;
                        }

                        .navbar-filter-body .form-control:focus {
                            border-color: #93c5fd;
                            box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .15);
                        }

                        .navbar-filter-body .custom-control {
                            padding-left: 0;
                            margin-bottom: 0;
                        }

                        .navbar-filter-body .custom-control-input {
                            position: absolute;
                            opacity: 0;
                        }

                        .navbar-filter-body .custom-control-label {
                            padding: .4rem .7rem;
                            border-radius: 999px;
                            border: 1px solid #e5e7eb;
                            background: #f9fafb;
                            font-weight: 700;
                            color: #374151;
                            cursor: pointer;
                            user-select: none;
                        }

                        .navbar-filter-body .custom-control-label::before,
                        .navbar-filter-body .custom-control-label::after {
                            display: none;
                        }

                        .navbar-filter-body .custom-control-input:checked ~ .custom-control-label {
                            background: #4e73df;
                            border-color: #4e73df;
                            color: #fff;
                        }

                        .navbar-filter-body .custom-control-input:focus ~ .custom-control-label {
                            box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .15);
                        }

                        .navbar-filter-title {
                            font-size: 12px;
                            font-weight: 700;
                            color: #6c757d;
                            margin-bottom: 6px;
                        }

                        .navbar-filter-actions {
                            padding: 12px 16px;
                            padding-bottom: calc(12px + env(safe-area-inset-bottom));
                            border-top: 1px solid rgba(0, 0, 0, .08);
                            display: flex;
                            gap: 10px;
                            justify-content: flex-end;
                            flex-shrink: 0;
                            background: #fff;
                            position: sticky;
                            bottom: 0;
                            z-index: 1;
                        }

                        .topbar .navbar-nav {
                            align-items: center;
                        }

                        .topbar .navbar-nav .nav-item {
                            display: flex;
                            align-items: center;
                        }

                        #openFilterDrawer {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            line-height: 1;
                            padding-top: .25rem;
                            padding-bottom: .25rem;
                        }

                        #openFilterDrawer i {
                            display: inline-block;
                            line-height: 1;
                            margin-top: 0;
                        }
                    </style>

                    <!-- Topbar Search -->
                    <form
                        method="GET"
                        action="{{ url()->current() }}"
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 small" placeholder="Cari kode tanah..."
                                aria-label="Search" aria-describedby="basic-addon2">

                            <input type="hidden" name="f[provinsi]" value="{{ request('f.provinsi') }}">
                            <input type="hidden" name="f[kabupaten]" value="{{ request('f.kabupaten') }}">
                            <input type="hidden" name="f[kecamatan]" value="{{ request('f.kecamatan') }}">
                            <input type="hidden" name="f[desa]" value="{{ request('f.desa') }}">
                            <input type="hidden" name="f[name]" value="{{ request('f.name') }}">
                            <input type="hidden" name="f[ns]" value="{{ request('f.ns') }}">
                            <input type="hidden" name="f[kode_pos]" value="{{ request('f.kode_pos') }}">
                            @foreach((array) request('f.jenis_sertifikat', []) as $v)
                                <input type="hidden" name="f[jenis_sertifikat][]" value="{{ $v }}">
                            @endforeach
                            @foreach((array) request('f.status_tanah', []) as $v)
                                <input type="hidden" name="f[status_tanah][]" value="{{ $v }}">
                            @endforeach
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form method="GET" action="{{ url()->current() }}" class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 small"
                                            placeholder="Cari kode tanah..." aria-label="Search"
                                            aria-describedby="basic-addon2">

                                        <input type="hidden" name="f[provinsi]" value="{{ request('f.provinsi') }}">
                                        <input type="hidden" name="f[kabupaten]" value="{{ request('f.kabupaten') }}">
                                        <input type="hidden" name="f[kecamatan]" value="{{ request('f.kecamatan') }}">
                                        <input type="hidden" name="f[desa]" value="{{ request('f.desa') }}">
                                        <input type="hidden" name="f[name]" value="{{ request('f.name') }}">
                                        <input type="hidden" name="f[ns]" value="{{ request('f.ns') }}">
                                        <input type="hidden" name="f[kode_pos]" value="{{ request('f.kode_pos') }}">
                                        @foreach((array) request('f.jenis_sertifikat', []) as $v)
                                            <input type="hidden" name="f[jenis_sertifikat][]" value="{{ $v }}">
                                        @endforeach
                                        @foreach((array) request('f.status_tanah', []) as $v)
                                            <input type="hidden" name="f[status_tanah][]" value="{{ $v }}">
                                        @endforeach
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item no-arrow">
                            <button class="btn btn-sm btn-outline-primary mr-2" type="button" id="openFilterDrawer" aria-label="Filter">
                                <i class="fas fa-filter"></i>
                                <span class="d-none d-md-inline ml-1">Filter</span>
                            </button>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('template/img/undraw_profile.svg')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                    <div class="navbar-filter-overlay" id="filterOverlay"></div>
                    <div class="navbar-filter-drawer" id="filterDrawer" aria-hidden="true">
                        <div class="navbar-filter-header">
                            <div class="font-weight-bold">Filter</div>
                            <button type="button" class="btn btn-sm btn-light" id="closeFilterDrawer" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form method="GET" action="{{ url()->current() }}" class="navbar-filter-form">
                            <input type="hidden" name="q" value="{{ request('q') }}">

                            <div class="navbar-filter-body">
                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Provinsi</label>
                                    <input type="text" name="f[provinsi]" value="{{ request('f.provinsi') }}" class="form-control form-control-sm" placeholder="contoh: Bali">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Kabupaten</label>
                                    <input type="text" name="f[kabupaten]" value="{{ request('f.kabupaten') }}" class="form-control form-control-sm" placeholder="contoh: Badung">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Kecamatan</label>
                                    <input type="text" name="f[kecamatan]" value="{{ request('f.kecamatan') }}" class="form-control form-control-sm" placeholder="contoh: Kuta">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Desa</label>
                                    <input type="text" name="f[desa]" value="{{ request('f.desa') }}" class="form-control form-control-sm" placeholder="contoh: Legian">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Nama Pemilik</label>
                                    <input type="text" name="f[name]" value="{{ request('f.name') }}" class="form-control form-control-sm" placeholder="contoh: Budi">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Nomor Sertifikat</label>
                                    <input type="text" name="f[ns]" value="{{ request('f.ns') }}" class="form-control form-control-sm" placeholder="contoh: NS-2026">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Kode Pos</label>
                                    <input type="text" name="f[kode_pos]" value="{{ request('f.kode_pos') }}" class="form-control form-control-sm" placeholder="contoh: 80221">
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold mb-1">Jenis Sertifikat</label>
                                    @php($jenis = (string) request('f.jenis_sertifikat', ''))
                                    <div class="d-flex flex-wrap" style="gap: 10px 14px;">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="drawerJenisSHM" name="f[jenis_sertifikat]" value="SHM" {{ $jenis === 'SHM' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerJenisSHM">SHM</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="drawerJenisHGB" name="f[jenis_sertifikat]" value="HGB" {{ $jenis === 'HGB' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerJenisHGB">HGB</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="drawerJenisHP" name="f[jenis_sertifikat]" value="HP" {{ $jenis === 'HP' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerJenisHP">HP</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="drawerJenisHGU" name="f[jenis_sertifikat]" value="HGU" {{ $jenis === 'HGU' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerJenisHGU">HGU</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="navbar-filter-title">Status Tanah</div>
                                    @php($status = (array) request('f.status_tanah', []))

                                    <div class="d-flex flex-wrap" style="gap: 10px 14px;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="drawerStatusAktif" name="f[status_tanah][]" value="aktif" {{ in_array('aktif', $status, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerStatusAktif">Aktif</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="drawerStatusSengketa" name="f[status_tanah][]" value="sengketa" {{ in_array('sengketa', $status, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerStatusSengketa">Sengketa</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="drawerStatusDijual" name="f[status_tanah][]" value="dijual" {{ in_array('dijual', $status, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="drawerStatusDijual">Dijual</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="navbar-filter-actions">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ url()->current() }}">Hapus semua filter</a>
                                <button class="btn btn-sm btn-primary" type="submit">Terapkan</button>
                            </div>
                        </form>
                    </div>

                    <script>
                        (function() {
                            var openBtn = document.getElementById('openFilterDrawer');
                            var closeBtn = document.getElementById('closeFilterDrawer');
                            var drawer = document.getElementById('filterDrawer');
                            var overlay = document.getElementById('filterOverlay');

                            function openDrawer() {
                                if (!drawer || !overlay) return;
                                drawer.classList.add('show');
                                overlay.classList.add('show');
                                drawer.setAttribute('aria-hidden', 'false');
                                document.body.style.overflow = 'hidden';
                            }

                            function closeDrawer() {
                                if (!drawer || !overlay) return;
                                drawer.classList.remove('show');
                                overlay.classList.remove('show');
                                drawer.setAttribute('aria-hidden', 'true');
                                document.body.style.overflow = '';
                            }

                            window.openFilterDrawer = openDrawer;
                            window.closeFilterDrawer = closeDrawer;

                            if (openBtn) openBtn.addEventListener('click', openDrawer);
                            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
                            if (overlay) overlay.addEventListener('click', closeDrawer);
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') closeDrawer();
                            });
                        })();
                    </script>

                </nav>