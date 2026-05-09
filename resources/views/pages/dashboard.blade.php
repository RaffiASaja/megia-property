@extends('layouts.app')

@section('content')
    <style>
        @media (max-width: 768px) {
            .dashboard-stats-row {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 12px;
                padding-bottom: 6px;
                margin-left: 0;
                margin-right: 0;
            }

            .dashboard-stats-row > [class^="col-"] {
                flex: 0 0 78%;
                max-width: 78%;
                padding-left: 0;
                padding-right: 0;
                margin-bottom: 0 !important;
            }

            .dashboard-stats-row::-webkit-scrollbar {
                display: none;
            }

            .dashboard-stats-row .card {
                border: 0 !important;
                box-shadow: none !important;
            }
        }

        .dashboard-map-wrap {
            position: relative;
        }

        .dashboard-map-fullscreen-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 500;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, .12);
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
        }

        .dashboard-map-fullscreen {
            position: fixed;
            inset: 0;
            background: #fff;
            z-index: 20000;
            display: none;
        }

        .dashboard-map-fullscreen.is-open {
            display: block;
        }

        .dashboard-map-fullscreen-close {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 20001;
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 0;
            background: rgba(17, 24, 39, .92);
            color: #fff;
            font-size: 22px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-map-fullscreen-tools {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 64px;
            z-index: 20002;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .dashboard-map-fullscreen-search {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .dashboard-map-fullscreen-search .input-group {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
            overflow: hidden;
        }

        .dashboard-map-fullscreen-filter-btn {
            flex: 0 0 auto;
        }

        .dashboard-map-fullscreen-search input {
            border: 0;
        }

        .dashboard-map-fullscreen-filter-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, .12);
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
        }

        .dashboard-map-fullscreen #tanahMap {
            width: 100% !important;
            height: 100vh !important;
            border-radius: 0 !important;
        }

        /* Side Info Panel Style */
        .map-side-info {
            position: absolute;
            top: 10px;
            left: 10px;
            bottom: 10px;
            width: 300px;
            background: rgba(255, 255, 255, 0.95);
            z-index: 1000;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        #mapSideInfo {
            display: none !important;
        }

        .dashboard-map-fullscreen .map-side-info {
            z-index: 20005;
        }

        .map-side-info.is-open {
            display: flex;
        }

        .map-side-info-header {
            padding: 12px 15px;
            background: #4e73df;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .map-side-info-header h6 {
            margin: 0;
            font-weight: 700;
        }

        .map-side-info-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .map-side-info-body {
            padding: 15px;
            overflow-y: auto;
            font-size: 0.85rem;
        }

        .info-item {
            margin-bottom: 10px;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 5px;
        }

        .info-label {
            font-weight: 700;
            color: #4e73df;
            display: block;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-size: 0.65rem;
        }

        .info-value {
            color: #3a3b45;
            word-break: break-word;
        }

        @media (max-width: 576px) {
            .map-side-info {
                width: calc(100% - 20px);
                max-height: 50%;
                top: auto;
            }
        }

        .dashboard-media-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        @media (min-width: 768px) {
            .dashboard-media-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .dashboard-media-item {
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .dashboard-media-thumb {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
            cursor: pointer;
        }

        .dashboard-media-video {
            width: 100%;
            height: 180px;
            display: block;
            background: #000;
        }

        .dashboard-image-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.9);
            z-index: 30050;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .dashboard-image-overlay.is-open {
            display: flex;
        }

        .dashboard-image-overlay img {
            max-width: 92vw;
            max-height: 86vh;
            user-select: none;
            -webkit-user-drag: none;
        }

        .dashboard-image-close {
            position: absolute;
            top: 10px;
            right: 12px;
            border: none;
            background: rgba(255,255,255,.12);
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 999px;
            font-size: 26px;
            line-height: 42px;
            cursor: pointer;
        }

        .dashboard-image-toolbar {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .dashboard-image-toolbtn {
            border: none;
            background: rgba(255,255,255,.15);
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            font-size: 22px;
            cursor: pointer;
        }

        .map-side-info .dashboard-media-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .map-side-info .dashboard-media-thumb {
            height: 120px;
        }

        .map-side-info .dashboard-media-video {
            height: 160px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row dashboard-stats-row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tanah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTanah ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fa-landmark fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tanah Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tanahAktif ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tanah Sengketa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tanahSengketa ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tanah Dijual</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tanahDijual ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lokasi Distribusi Tanah</h6>
                </div>
                <div class="card-body">
                    <div class="dashboard-map-wrap">
                        <button type="button" class="dashboard-map-fullscreen-btn" id="mapFullscreenOpen" aria-label="Perbesar Peta">
                            <i class="fas fa-expand"></i>
                        </button>
                        
                        <!-- Side Info Panel -->
                        <div id="mapSideInfo" class="map-side-info">
                            <div class="map-side-info-header">
                                <h6 id="info-title">Detail Lokasi</h6>
                                <button type="button" class="map-side-info-close" onclick="closeSideInfo()">&times;</button>
                            </div>
                            <div class="map-side-info-body">
                                <div class="info-item">
                                    <span class="info-label">Kode Tanah</span>
                                    <span class="info-value" id="info-kode"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nama Pemilik</span>
                                    <span class="info-value" id="info-nama"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Luas Tanah</span>
                                    <span class="info-value" id="info-luas"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Jenis Sertifikat</span>
                                    <span class="info-value" id="info-sertifikat"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span id="info-status"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value" id="info-alamat"></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Wilayah</span>
                                    <span class="info-value" id="info-wilayah"></span>
                                </div>
                            </div>
                        </div>

                        <div id="tanahMap" style="width: 100%; height: 500px; border-radius: .5rem;"></div>
                    </div>
                    <div class="mt-3 text-muted small">Menampilkan titik lokasi tanah yang memiliki koordinat.</div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tanah Terbaru</h6>
                    <a href="{{ url('/tanah') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Kode Tanah</th>
                                    <th>Nomor Sertifikat</th>
                                    <th>Nama Pemilik</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($tanahLatest) || $tanahLatest->count() === 0)
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                @else
                                    @foreach ($tanahLatest as $item)
                                        <tr>
                                            <td>{{ $item->kode_tanah }}</td>
                                            <td>{{ $item->ns }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @if ($item->status_tanah === 'aktif')
                                                    <span class="badge badge-success">Aktif</span>
                                                @elseif ($item->status_tanah === 'sengketa')
                                                    <span class="badge badge-warning">Sengketa</span>
                                                @elseif ($item->status_tanah === 'dijual')
                                                    <span class="badge badge-danger">Dijual</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $item->status_tanah }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="dashboard-map-fullscreen" id="mapFullscreen" aria-hidden="true">
        <button type="button" class="dashboard-map-fullscreen-close" id="mapFullscreenClose" aria-label="Tutup">×</button>
        <div class="dashboard-map-fullscreen-tools">
            <form method="GET" action="{{ url()->current() }}" class="dashboard-map-fullscreen-search">
                <div class="input-group">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kode tanah..." aria-label="Search">
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
                        <button class="btn btn-primary" type="submit" aria-label="Cari">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

            <button type="button" class="dashboard-map-fullscreen-filter-btn" id="mapFullscreenFilter" aria-label="Filter">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        <div id="mapSideInfoFullscreen" class="map-side-info"></div>
    </div>

    <div class="modal fade" id="dashboardDokumentasiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dashboardDokumentasiTitle">Dokumentasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="dashboardDokumentasiGrid" class="dashboard-media-grid"></div>
                    <div id="dashboardDokumentasiEmpty" class="text-muted" style="display:none;">Tidak ada dokumentasi.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            if (!window.L) return;

            var el = document.getElementById('tanahMap');
            if (!el) return;

            var locations = @json($tanahLocations ?? []);

            var map = L.map('tanahMap');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            if (!locations || locations.length === 0) {
                map.setView([-8.409518, 115.188919], 8);
                return;
            }

            var bounds = [];
            var activePolygonLayer = null;
            var lastSelectedTanah = null;

            window.closeSideInfo = function() {
                document.getElementById('mapSideInfo').classList.remove('is-open');
                document.getElementById('mapSideInfoFullscreen').classList.remove('is-open');
                clearActivePolygon();
            };

            var docViewer = null;
            var docViewerImg = null;
            var docViewerScale = 1;
            var docViewerTx = 0;
            var docViewerTy = 0;
            var docIsPanning = false;
            var docPanStartX = 0;
            var docPanStartY = 0;
            var docPanStartTx = 0;
            var docPanStartTy = 0;
            var docActivePointerId = null;

            function ensureDocImageViewer() {
                if (docViewer) return;

                docViewer = document.createElement('div');
                docViewer.className = 'dashboard-image-overlay';
                docViewer.setAttribute('aria-hidden', 'true');

                var closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'dashboard-image-close';
                closeBtn.setAttribute('aria-label', 'Tutup');
                closeBtn.textContent = '×';

                docViewerImg = document.createElement('img');
                docViewerImg.alt = 'Preview';
                docViewerImg.style.touchAction = 'none';

                var toolbar = document.createElement('div');
                toolbar.className = 'dashboard-image-toolbar';

                var zoomOut = document.createElement('button');
                zoomOut.type = 'button';
                zoomOut.className = 'dashboard-image-toolbtn';
                zoomOut.setAttribute('aria-label', 'Zoom out');
                zoomOut.textContent = '−';

                var zoomIn = document.createElement('button');
                zoomIn.type = 'button';
                zoomIn.className = 'dashboard-image-toolbtn';
                zoomIn.setAttribute('aria-label', 'Zoom in');
                zoomIn.textContent = '+';

                toolbar.appendChild(zoomOut);
                toolbar.appendChild(zoomIn);

                docViewer.appendChild(closeBtn);
                docViewer.appendChild(docViewerImg);
                docViewer.appendChild(toolbar);
                document.body.appendChild(docViewer);

                function applyTransform() {
                    if (!docViewerImg) return;
                    docViewerImg.style.transform = 'translate(' + docViewerTx + 'px, ' + docViewerTy + 'px) scale(' + docViewerScale + ')';
                    docViewerImg.style.transformOrigin = 'center center';
                    docViewerImg.style.cursor = docViewerScale > 1 ? (docIsPanning ? 'grabbing' : 'grab') : 'default';
                }

                function closeViewer() {
                    if (!docViewer) return;
                    docViewer.classList.remove('is-open');
                    docViewer.setAttribute('aria-hidden', 'true');
                    docViewerScale = 1;
                    docViewerTx = 0;
                    docViewerTy = 0;
                    applyTransform();
                }

                function openViewer(src) {
                    if (!docViewer || !docViewerImg) return;
                    docViewerImg.src = src;
                    docViewerScale = 1;
                    docViewerTx = 0;
                    docViewerTy = 0;
                    applyTransform();
                    docViewer.classList.add('is-open');
                    docViewer.setAttribute('aria-hidden', 'false');
                }

                docViewer.__open = openViewer;
                docViewer.__close = closeViewer;

                closeBtn.addEventListener('click', function () { closeViewer(); });
                docViewer.addEventListener('click', function (e) {
                    if (e.target === docViewer) closeViewer();
                });

                docViewerImg.addEventListener('pointerdown', function (e) {
                    if (!docViewer || !docViewer.classList.contains('is-open')) return;
                    if (docViewerScale <= 1) return;
                    if (docActivePointerId !== null) return;

                    docActivePointerId = e.pointerId;
                    docIsPanning = true;
                    docPanStartX = e.clientX;
                    docPanStartY = e.clientY;
                    docPanStartTx = docViewerTx;
                    docPanStartTy = docViewerTy;
                    try { docViewerImg.setPointerCapture(docActivePointerId); } catch (err) { }
                    applyTransform();
                    e.preventDefault();
                });

                docViewerImg.addEventListener('pointermove', function (e) {
                    if (!docIsPanning) return;
                    if (docActivePointerId !== e.pointerId) return;
                    docViewerTx = docPanStartTx + (e.clientX - docPanStartX);
                    docViewerTy = docPanStartTy + (e.clientY - docPanStartY);
                    applyTransform();
                    e.preventDefault();
                });

                function endPan(e) {
                    if (!docIsPanning) return;
                    if (docActivePointerId !== null && e && e.pointerId !== undefined && e.pointerId !== docActivePointerId) return;
                    docIsPanning = false;
                    docActivePointerId = null;
                    applyTransform();
                }

                docViewerImg.addEventListener('pointerup', endPan);
                docViewerImg.addEventListener('pointercancel', endPan);

                zoomIn.addEventListener('click', function () {
                    docViewerScale = Math.min(5, +(docViewerScale + 0.25).toFixed(2));
                    applyTransform();
                });

                zoomOut.addEventListener('click', function () {
                    docViewerScale = Math.max(1, +(docViewerScale - 0.25).toFixed(2));
                    if (docViewerScale === 1) {
                        docViewerTx = 0;
                        docViewerTy = 0;
                    }
                    applyTransform();
                });

                document.addEventListener('keydown', function (e) {
                    if (!docViewer || !docViewer.classList.contains('is-open')) return;
                    if (e.key === 'Escape') {
                        closeViewer();
                    }
                });
            }

            function normalizeMediaArray(val) {
                if (Array.isArray(val)) return val.filter(Boolean);
                if (!val) return [];
                if (typeof val === 'string') {
                    try {
                        var parsed = JSON.parse(val);
                        return Array.isArray(parsed) ? parsed.filter(Boolean) : [];
                    } catch (e) {
                        return [];
                    }
                }
                return [];
            }

            function toPublicUrl(path) {
                if (!path) return '';
                if (typeof path !== 'string') return '';
                if (path.startsWith('http://') || path.startsWith('https://')) return path;
                if (path.startsWith('/')) return path;
                return '/' + path;
            }

            function renderDokumentasi(panelEl, t) {
                if (!panelEl) return;
                var grid = panelEl.querySelector('[data-dashboard-doc-grid]');
                var emptyEl = panelEl.querySelector('[data-dashboard-doc-empty]');
                var titleEl = panelEl.querySelector('[data-dashboard-doc-title]');
                if (!grid || !emptyEl || !titleEl) return;

                grid.innerHTML = '';

                var fotos = normalizeMediaArray(t && t.foto);
                var videos = normalizeMediaArray(t && t.video);
                var hasAny = (fotos.length + videos.length) > 0;

                titleEl.textContent = 'Dokumentasi';
                emptyEl.style.display = hasAny ? 'none' : '';
                grid.style.display = hasAny ? '' : 'none';

                if (!hasAny) return;

                function appendImage(src) {
                    var item = document.createElement('div');
                    item.className = 'dashboard-media-item';

                    var img = document.createElement('img');
                    img.className = 'dashboard-media-thumb';
                    img.src = src;
                    img.alt = 'Dokumentasi';
                    img.addEventListener('click', function (ev) {
                        ev.preventDefault();
                        ensureDocImageViewer();
                        if (docViewer && docViewer.__open) {
                            docViewer.__open(src);
                        }
                    });

                    item.appendChild(img);
                    grid.appendChild(item);
                }

                function appendVideo(src) {
                    var item = document.createElement('div');
                    item.className = 'dashboard-media-item';

                    var v = document.createElement('video');
                    v.className = 'dashboard-media-video';
                    v.src = src;
                    v.controls = true;
                    v.playsInline = true;

                    item.appendChild(v);
                    grid.appendChild(item);
                }

                fotos.forEach(function (src) {
                    var url = toPublicUrl(src);
                    if (url) appendImage(url);
                });

                videos.forEach(function (src) {
                    var url = toPublicUrl(src);
                    if (url) appendVideo(url);
                });
            }

            function showSideInfo(t) {
                const isFullscreen = document.getElementById('mapFullscreen').classList.contains('is-open');
                lastSelectedTanah = t || null;
                const infoPanel = isFullscreen ? document.getElementById('mapSideInfoFullscreen') : document.getElementById('mapSideInfo');
                var hasDocs = normalizeMediaArray(t && t.foto).length > 0 || normalizeMediaArray(t && t.video).length > 0;
                var pdfUrl = toPublicUrl(t && t.bukti_sertifikat);
                
                // Pastikan panel yang tidak aktif tertutup
                if (isFullscreen) {
                    document.getElementById('mapSideInfo').classList.remove('is-open');
                } else {
                    document.getElementById('mapSideInfoFullscreen').classList.remove('is-open');
                }

                infoPanel.innerHTML = `
                    <div class="map-side-info-header">
                        <h6>Detail Lokasi</h6>
                        <button type="button" class="map-side-info-close" onclick="closeSideInfo()">&times;</button>
                    </div>
                    <div class="map-side-info-body">
                        <div class="info-item">
                            <span class="info-label">Kode Tanah</span>
                            <span class="info-value">${t.kode_tanah || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nomor Sertifikat</span>
                            <span class="info-value">${t.ns || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama Pemilik</span>
                            <span class="info-value">${t.name || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Luas Tanah</span>
                            <span class="info-value">${(t.luas_tanah || '-')} m²</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Sertifikat</span>
                            <span class="info-value">${t.jenis_sertifikat || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tanggal Terbit</span>
                            <span class="info-value">${t.tanggal_terbit || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Masa Berlaku</span>
                            <span class="info-value">${t.masa_berlaku || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="badge badge-${t.status_tanah === 'aktif' ? 'success' : (t.status_tanah === 'sengketa' ? 'warning' : 'danger')}">${t.status_tanah || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">${t.alamat || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Desa</span>
                            <span class="info-value">${t.desa || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kecamatan</span>
                            <span class="info-value">${t.kecamatan || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kabupaten</span>
                            <span class="info-value">${t.kabupaten || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Provinsi</span>
                            <span class="info-value">${t.provinsi || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kode Pos</span>
                            <span class="info-value">${t.kode_pos || '-'}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-dashboard-doc-title>Dokumentasi</span>
                            <div class="dashboard-media-grid" data-dashboard-doc-grid style="display:none;"></div>
                            <div class="text-muted" data-dashboard-doc-empty style="display:none;">Tidak ada dokumentasi.</div>
                        </div>
                        ${pdfUrl ? `<div class="info-item"><span class="info-label">Sertifikat (PDF)</span><span class="info-value"><a href="${pdfUrl}" target="_blank" rel="noopener">Lihat Sertifikat</a></span></div>` : ''}
                        ${t.link_map ? `<div class="info-item"><span class="info-label">Link Map</span><span class="info-value"><a href="${t.link_map}" target="_blank" rel="noopener">Buka di Map</a></span></div>` : ''}
                        <div class="mt-3">
                            <a href="{{ url('/tanah') }}?q=${encodeURIComponent(t.kode_tanah)}&show=${t.id}" class="btn btn-sm btn-primary btn-block">
                                <i class="fas fa-list mr-1"></i> Lihat Selengkapnya
                            </a>
                        </div>
                    </div>
                `;
                
                infoPanel.classList.add('is-open');
                showPolygon(t.polygon);
                renderDokumentasi(infoPanel, t);
            }

            function clearActivePolygon() {
                if (activePolygonLayer) {
                    map.removeLayer(activePolygonLayer);
                    activePolygonLayer = null;
                }
            }

            function showPolygon(polygonValue) {
                if (!polygonValue) {
                    clearActivePolygon();
                    return;
                }

                var geo = polygonValue;
                if (typeof polygonValue === 'string') {
                    try {
                        geo = JSON.parse(polygonValue);
                    } catch (e) {
                        clearActivePolygon();
                        return;
                    }
                }

                clearActivePolygon();
                activePolygonLayer = L.geoJSON(geo, {
                    style: function () {
                        return {
                            color: '#2563eb',
                            weight: 3,
                            opacity: 1,
                            fillColor: '#3b82f6',
                            fillOpacity: 0.25,
                        };
                    }
                }).addTo(map);

                var polyBounds = activePolygonLayer.getBounds && activePolygonLayer.getBounds();
                if (polyBounds && polyBounds.isValid && polyBounds.isValid()) {
                    map.fitBounds(polyBounds, { padding: [20, 20] });
                }
            }

            var markerByKode = {};
            locations.forEach(function (t) {
                var lat = Number(t.latitude);
                var lng = Number(t.longitude);
                if (!isFinite(lat) || !isFinite(lng)) return;

                bounds.push([lat, lng]);

                var title = (t.kode_tanah || '-') + ' - ' + (t.name || '-');
                var place = [t.kabupaten, t.provinsi].filter(Boolean).join(', ');
                var link = t.link_map ? '<div class="mt-2"><a href="' + t.link_map + '" target="_blank" rel="noopener">Buka Link Map</a></div>' : '';

                var marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('<div style="font-weight:800;">' + title + '</div>' + (place ? ('<div>' + place + '</div>') : '') + link)
                    .on('click', function () {
                        lastSelectedTanah = t;
                        if (fullscreen && fullscreen.classList.contains('is-open')) {
                            showSideInfo(t);
                        } else {
                            showPolygon(t.polygon);
                        }
                    });

                if (t.kode_tanah) {
                    markerByKode[String(t.kode_tanah).toLowerCase()] = { marker: marker, data: t };
                }
            });

            if (bounds.length === 0) {
                map.setView([-8.409518, 115.188919], 8);
                return;
            }

            map.fitBounds(bounds, { padding: [20, 20] });

            var q = @json($q ?? '');
            if (q) {
                var key = String(q).toLowerCase();
                var hit = markerByKode[key];
                if (hit && hit.marker) {
                    map.setView(hit.marker.getLatLng(), 17);
                    hit.marker.openPopup();
                    lastSelectedTanah = hit.data;
                    showPolygon(hit.data.polygon);

                    // Auto open fullscreen if redirected with search query
                    setTimeout(function() {
                        if (typeof openFullscreen === 'function') {
                            openFullscreen();
                        }
                    }, 100);
                }
            }

            var fullscreen = document.getElementById('mapFullscreen');
            var openBtn = document.getElementById('mapFullscreenOpen');
            var closeBtn = document.getElementById('mapFullscreenClose');
            var originalParent = el.parentNode;
            var originalNextSibling = el.nextSibling;

            function openFullscreen() {
                if (!fullscreen) return;
                fullscreen.classList.add('is-open');
                fullscreen.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                fullscreen.appendChild(el);
                setTimeout(function () {
                    map.invalidateSize(true);
                    if (lastSelectedTanah) {
                        showSideInfo(lastSelectedTanah);
                    }
                }, 50);
            }

            function closeFullscreen() {
                if (!fullscreen) return;
                fullscreen.classList.remove('is-open');
                fullscreen.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                closeSideInfo();
                if (originalNextSibling) {
                    originalParent.insertBefore(el, originalNextSibling);
                } else {
                    originalParent.appendChild(el);
                }
                setTimeout(function () { map.invalidateSize(true); }, 50);
            }

            if (openBtn) {
                openBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openFullscreen();
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeFullscreen();
                });
            }

            var filterBtn = document.getElementById('mapFullscreenFilter');
            if (filterBtn) {
                filterBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (window.openFilterDrawer) {
                        window.openFilterDrawer();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && fullscreen && fullscreen.classList.contains('is-open')) {
                    closeFullscreen();
                }
            });
        })();
    </script>
@endsection