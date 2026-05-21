@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .data-table-wrap {
            border-radius: .5rem;
            overflow: hidden;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tanah-detail-section {
            border: 1px solid #e3e6f0;
            border-radius: .35rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .tanah-detail-section:last-child {
            margin-bottom: 0;
        }

        .tanah-detail-section-title {
            background: #0ea5e9;
            color: #fff;
            padding: .65rem .9rem;
            font-weight: 600;
        }

        .tanah-detail-table {
            margin-bottom: 0;
        }

        .tanah-detail-table th {
            width: 240px;
            color: #525F7F;
            font-weight: 700;
            background: #f8f9fc;
        }

        .tanah-detail-table td.sep {
            width: 22px;
            text-align: center;
            color: #6c757d;
        }

        .tanah-image-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .tanah-image-item {
            text-align: center;
        }

        .tanah-image-item-title {
            font-weight: 700;
            color: #6b7280;
            margin-bottom: .75rem;
        }

        .tanah-image-thumb {
            width: 100%;
            max-width: 220px;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: .5rem;
            border: 1px solid #e3e6f0;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
            cursor: pointer;
            background: #f8f9fc;
        }

        .tanah-image-empty {
            width: 100%;
            max-width: 220px;
            aspect-ratio: 1 / 1;
            border-radius: .5rem;
            border: 1px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            background: #fff;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 576px) {
            .tanah-detail-table,
            .tanah-detail-table thead, 
            .tanah-detail-table tbody, 
            .tanah-detail-table tr, 
            .tanah-detail-table th, 
            .tanah-detail-table td {
                display: block;
                width: 100% !important;
                border: 0;
            }

            .tanah-detail-table tr {
                border-bottom: 1px solid #e3e6f0;
                padding: .5rem .9rem;
            }

            .tanah-detail-table tr:last-child {
                border-bottom: 0;
            }

            .tanah-detail-table th {
                background: transparent;
                padding-bottom: 0;
                padding-top: .25rem;
                color: #525F7F;
                font-size: .7rem;
                text-transform: uppercase;
                letter-spacing: .03em;
                font-weight: 800;
            }

            .tanah-detail-table td {
                padding-top: .15rem;
                padding-bottom: .45rem;
                font-weight: 400;
                color: #3a3b45;
                font-size: .95rem;
            }

            .tanah-detail-table td.sep {
                display: none;
            }

            .tanah-image-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 420px) {
            .tanah-image-grid {
                grid-template-columns: 1fr;
            }
        }

        .tanah-image-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            z-index: 20000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .tanah-image-overlay.is-open {
            display: flex;
        }

        .tanah-image-overlay img {
            max-width: 95vw;
            max-height: 90vh;
            border-radius: .5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
        }

        .tanah-image-overlay .tanah-image-toolbar {
            position: absolute;
            bottom: 18px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 20002;
        }

        .tanah-image-overlay .tanah-image-toolbtn {
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .tanah-image-overlay .tanah-image-toolbtn:focus {
            outline: none;
        }

        .tanah-detail-map-wrap {
            position: relative;
        }

        .tanah-detail-map-pdf {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 999;
            border: 1px solid rgba(0, 0, 0, .12);
            background: #fff;
            border-radius: 10px;
            padding: 6px 10px;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
            display: none;
        }

        .tanah-detail-map-pdf.is-open {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 576px) {
            .tanah-image-overlay {
                padding: 14px;
            }

            .tanah-image-overlay .tanah-image-toolbar {
                bottom: 14px;
            }

            .tanah-image-overlay .tanah-image-toolbtn {
                width: 48px;
                height: 48px;
            }
        }

        .tanah-image-overlay .tanah-image-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: 26px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .tanah-image-overlay .tanah-image-close:focus {
            outline: none;
        }

        .data-table {
            margin-bottom: 0;
        }

        .data-table thead th {
            background: #2f80a8;
            color: #fff;
            text-transform: uppercase;
            font-size: .75rem;
            letter-spacing: .04em;
            border-color: rgba(255, 255, 255, .2);
            white-space: nowrap;
        }

        .data-table tbody tr:nth-child(even) {
            background: #eef6fb;
        }

        .data-table td,
        .data-table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        .data-table .cell-actions {
            min-width: 140px;
        }

        .data-table .btn-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .tanah-mobile-list {
            display: none;
        }

        .tanah-mobile-card {
            border: 1px solid #e3e6f0;
            border-radius: .75rem;
            padding: .95rem;
            background: #fff;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
            cursor: pointer;
        }

        .tanah-mobile-header {
            display: flex;
            gap: .9rem;
            align-items: flex-start;
            margin-bottom: .75rem;
        }

        .tanah-mobile-thumb {
            width: 62px;
            height: 62px;
            border-radius: .75rem;
            border: 1px solid #e3e6f0;
            background: #f8f9fc;
            object-fit: cover;
            flex: 0 0 62px;
        }

        .tanah-mobile-thumb--empty {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 22px;
        }

        .tanah-mobile-headtext {
            flex: 1 1 auto;
            min-width: 0;
        }

        .tanah-mobile-badge {
            display: inline-block;
            padding: .25rem .6rem;
            border-radius: 999px;
            background: #6c757d;
            color: #fff;
            font-weight: 700;
            font-size: .75rem;
            text-transform: capitalize;
            margin-bottom: .65rem;
        }

        .tanah-mobile-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            margin-bottom: .15rem;
        }

        .tanah-mobile-subtitle {
            font-size: .95rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0;
        }

        .tanah-mobile-row {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            color: #374151;
            margin-bottom: .45rem;
        }

        .tanah-mobile-row:last-child {
            margin-bottom: 0;
        }

        .tanah-mobile-row i {
            width: 18px;
            margin-top: .15rem;
            color: #9ca3af;
            flex: 0 0 18px;
            text-align: center;
        }

        .tanah-mobile-row span {
            flex: 1 1 auto;
        }

        @media (max-width: 768px) {
            .data-table-wrap {
                display: none;
            }

            .tanah-mobile-list {
                display: block;
            }
        }
    </style>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Tanah</h1>
        <a href="{{ url('/tanah/create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah </a>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive data-table-wrap">
                        <table class="table table-bordered table-hover data-table">
                            <thead>
                                <tr>
                                    <th>Kode Tanah</th>
                                    <th>Nomor Sertifikat</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Nama Pemilik</th>
                                    <th>Luas Tanah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($tanahs) || $tanahs->count() === 0)
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @else
                                    @foreach ($tanahs as $item)
                                        @php($mediaPayload = [
                                            'images' => array_values(array_map(fn($p) => asset(ltrim((string) $p, '/')), array_slice((array) ($item->foto ?? []), 0, 5))),
                                            'videos' => array_values(array_map(fn($p) => asset(ltrim((string) $p, '/')), array_slice((array) ($item->video ?? []), 0, 2))),
                                        ])
                                        <tr class="tanah-row" style="cursor: pointer;"
                                            data-id="{{ $item->id }}"
                                            data-kode_tanah="{{ $item->kode_tanah }}"
                                            data-ns="{{ $item->ns }}"
                                            data-name="{{ $item->name }}"
                                            data-luas_tanah="{{ $item->luas_tanah }}"
                                            data-jenis_sertifikat="{{ $item->jenis_sertifikat }}"
                                            data-tanggal_terbit="{{ $item->tanggal_terbit }}"
                                            data-masa_berlaku="{{ $item->masa_berlaku }}"
                                            data-alamat="{{ $item->alamat }}"
                                            data-provinsi="{{ $item->provinsi }}"
                                            data-kabupaten="{{ $item->kabupaten }}"
                                            data-kecamatan="{{ $item->kecamatan }}"
                                            data-desa="{{ $item->desa }}"
                                            data-kode_pos="{{ $item->kode_pos }}"
                                            data-latitude="{{ $item->latitude }}"
                                            data-longitude="{{ $item->longitude }}"
                                            data-status_tanah="{{ $item->status_tanah }}"
                                            data-link_map="{{ $item->link_map }}"
                                            data-bukti_sertifikat="{{ !empty($item->bukti_sertifikat) ? asset(ltrim((string) $item->bukti_sertifikat, '/')) : '' }}"
                                            data-polygon='@json($item->polygon)'
                                            data-media='@json($mediaPayload)'>
                                            <td>{{ $item->kode_tanah }}</td>
                                            <td>{{ $item->ns }}</td>
                                            <td>{{ $item->provinsi }}</td>
                                            <td>{{ $item->kabupaten }}</td>
                                            <td>{{ $item->kecamatan }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->luas_tanah }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tanah-mobile-list">
                        @if (empty($tanahs) || $tanahs->count() === 0)
                            <div class="text-center text-muted py-4">Tidak ada data</div>
                        @else
                            <div class="d-flex flex-column" style="gap: 12px;">
                                @foreach ($tanahs as $item)
                                    @php($images = collect((array) ($item->foto ?? []))->map(fn($p) => asset(ltrim((string) $p, '/')))->values())
                                    @php($videos = collect((array) ($item->video ?? []))->map(fn($p) => asset(ltrim((string) $p, '/')))->values())
                                    <div class="tanah-mobile-card tanah-row"
                                        data-id="{{ $item->id }}"
                                        data-kode_tanah="{{ $item->kode_tanah }}"
                                        data-ns="{{ $item->ns }}"
                                        data-name="{{ $item->name }}"
                                        data-luas_tanah="{{ $item->luas_tanah }}"
                                        data-jenis_sertifikat="{{ $item->jenis_sertifikat }}"
                                        data-tanggal_terbit="{{ $item->tanggal_terbit }}"
                                        data-masa_berlaku="{{ $item->masa_berlaku }}"
                                        data-alamat="{{ $item->alamat }}"
                                        data-provinsi="{{ $item->provinsi }}"
                                        data-kabupaten="{{ $item->kabupaten }}"
                                        data-kecamatan="{{ $item->kecamatan }}"
                                        data-desa="{{ $item->desa }}"
                                        data-kode_pos="{{ $item->kode_pos }}"
                                        data-latitude="{{ $item->latitude }}"
                                        data-longitude="{{ $item->longitude }}"
                                        data-status_tanah="{{ $item->status_tanah }}"
                                        data-link_map="{{ $item->link_map }}"
                                        data-bukti_sertifikat="{{ !empty($item->bukti_sertifikat) ? asset(ltrim((string) $item->bukti_sertifikat, '/')) : '' }}"
                                        data-polygon='@json($item->polygon)'
                                        data-media='@json(["images" => $images, "videos" => $videos])'>
                                        <div class="tanah-mobile-header">
                                            @php($firstImage = collect((array) ($item->foto ?? []))->first())
                                            @if (!empty($firstImage))
                                                <img class="tanah-mobile-thumb" src="{{ asset(ltrim((string) $firstImage, '/')) }}" alt="Foto Tanah">
                                            @else
                                                <div class="tanah-mobile-thumb tanah-mobile-thumb--empty" aria-hidden="true">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div class="tanah-mobile-meta">
                                                <div class="tanah-mobile-kode">{{ $item->kode_tanah }}</div>
                                                <div class="tanah-mobile-owner">{{ $item->name }}</div>
                                            </div>
                                        </div>

                                        <div class="tanah-mobile-row">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $item->alamat }}</span>
                                        </div>
                                        <div class="tanah-mobile-row">
                                            <i class="fas fa-city"></i>
                                            <span>{{ $item->kabupaten }}</span>
                                        </div>
                                        <div class="tanah-mobile-row">
                                            <i class="fas fa-vector-square"></i>
                                            <span>{{ $item->luas_tanah }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if (!empty($tanahs) && method_exists($tanahs, 'links'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $tanahs->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tanahDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Tanah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tanah-detail-section">
                        <div class="tanah-detail-section-title">Informasi Tanah</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered tanah-detail-table">
                                <tbody>
                                    <tr><th>Kode Tanah</th><td class="sep">:</td><td id="dt-kode_tanah"></td></tr>
                                    <tr><th>Nomor Sertifikat</th><td class="sep">:</td><td id="dt-ns"></td></tr>
                                    <tr><th>Nama Pemilik</th><td class="sep">:</td><td id="dt-name"></td></tr>
                                    <tr><th>Luas Tanah</th><td class="sep">:</td><td id="dt-luas_tanah"></td></tr>
                                    <tr><th>Jenis Sertifikat</th><td class="sep">:</td><td id="dt-jenis_sertifikat"></td></tr>
                                    <tr><th>Tanggal Terbit</th><td class="sep">:</td><td id="dt-tanggal_terbit"></td></tr>
                                    <tr><th>Masa Berlaku</th><td class="sep">:</td><td id="dt-masa_berlaku"></td></tr>
                                    <tr><th>Status Tanah</th><td class="sep">:</td><td id="dt-status_tanah"></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tanah-detail-section">
                        <div class="tanah-detail-section-title">Lokasi & Lainnya</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered tanah-detail-table">
                                <tbody>
                                    <tr><th>Alamat</th><td class="sep">:</td><td id="dt-alamat"></td></tr>
                                    <tr><th>Provinsi</th><td class="sep">:</td><td id="dt-provinsi"></td></tr>
                                    <tr><th>Kabupaten</th><td class="sep">:</td><td id="dt-kabupaten"></td></tr>
                                    <tr><th>Kecamatan</th><td class="sep">:</td><td id="dt-kecamatan"></td></tr>
                                    <tr><th>Desa</th><td class="sep">:</td><td><a href="#" class="dt-field-link" id="dt-desa" data-field="desa"></a></td></tr>
                                    <tr><th>Kode Pos</th><td class="sep">:</td><td><a href="#" class="dt-field-link" id="dt-kode_pos" data-field="kode_pos"></a></td></tr>
                                    <tr><th>Link Map</th><td class="sep">:</td><td><a href="#" class="dt-field-link" id="dt-link_map" data-field="link_map" target="_blank" rel="noopener"></a></td></tr>
                                    <tr><th>Sertifikat (PDF)</th><td class="sep">:</td><td><a href="#" class="dt-field-link" id="dt-bukti_sertifikat" data-field="bukti_sertifikat" target="_blank" rel="noopener"></a></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tanah-detail-section">
                        <div class="tanah-detail-section-title">Bukti / Foto</div>
                        <div class="tanah-form-section-body">
                            <div id="dt-media-grid" class="tanah-image-grid"></div>
                        </div>
                    </div>

                    <div class="tanah-detail-section">
                        <div class="tanah-detail-section-title">Peta / Poligon</div>
                        <div class="tanah-form-section-body">
                            <div class="tanah-detail-map-wrap">
                                <a href="#" class="tanah-detail-map-pdf" id="dt-map-pdf" target="_blank" rel="noopener">
                                    <i class="fas fa-file-pdf"></i>
                                    Lihat Sertifikat
                                </a>
                                <div id="dt-map" style="height: 320px; width: 100%;"></div>
                                <div class="p-2 border-top bg-light d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-primary" id="btnGoToDashboardMap">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka di Peta Utama
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-warning" id="dt-edit-btn">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form method="POST" action="#" class="m-0" id="dt-delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="dt-delete-btn">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        (function () {
            var dtMap = null;
            var dtLayer = null;
            var viewer = null;
            var viewerImg = null;
            var viewerScale = 1;
            var viewerTx = 0;
            var viewerTy = 0;
            var isPanning = false;
            var panStartX = 0;
            var panStartY = 0;
            var panStartTx = 0;
            var panStartTy = 0;
            var activePointerId = null;
            var currentSelectedKode = null;

            function setText(id, val) {
                var el = document.getElementById(id);
                if (!el) return;
                el.textContent = (val === null || val === undefined || val === '') ? '-' : val;
            }

            function ensureImageViewer() {
                if (viewer) return;
                viewer = document.createElement('div');
                viewer.className = 'tanah-image-overlay';
                viewer.setAttribute('aria-hidden', 'true');

                var closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'tanah-image-close';
                closeBtn.setAttribute('aria-label', 'Tutup');
                closeBtn.textContent = '×';

                viewerImg = document.createElement('img');
                viewerImg.alt = 'Preview';
                viewerImg.style.touchAction = 'none';

                var toolbar = document.createElement('div');
                toolbar.className = 'tanah-image-toolbar';

                var zoomOut = document.createElement('button');
                zoomOut.type = 'button';
                zoomOut.className = 'tanah-image-toolbtn';
                zoomOut.setAttribute('aria-label', 'Zoom out');
                zoomOut.textContent = '−';

                var zoomIn = document.createElement('button');
                zoomIn.type = 'button';
                zoomIn.className = 'tanah-image-toolbtn';
                zoomIn.setAttribute('aria-label', 'Zoom in');
                zoomIn.textContent = '+';

                toolbar.appendChild(zoomOut);
                toolbar.appendChild(zoomIn);

                viewer.appendChild(closeBtn);
                viewer.appendChild(viewerImg);
                viewer.appendChild(toolbar);
                document.body.appendChild(viewer);

                function applyTransform() {
                    if (!viewerImg) return;
                    viewerImg.style.transform = 'translate(' + viewerTx + 'px, ' + viewerTy + 'px) scale(' + viewerScale + ')';
                    viewerImg.style.transformOrigin = 'center center';
                    viewerImg.style.cursor = viewerScale > 1 ? (isPanning ? 'grabbing' : 'grab') : 'default';
                }

                function closeViewer() {
                    if (!viewer) return;
                    viewer.classList.remove('is-open');
                    viewer.setAttribute('aria-hidden', 'true');
                    viewerScale = 1;
                    viewerTx = 0;
                    viewerTy = 0;
                    applyTransform();
                }

                function openViewer(src) {
                    if (!viewer || !viewerImg) return;
                    viewerImg.src = src;
                    viewerScale = 1;
                    viewerTx = 0;
                    viewerTy = 0;
                    applyTransform();
                    viewer.classList.add('is-open');
                    viewer.setAttribute('aria-hidden', 'false');
                }

                viewer.__open = openViewer;
                viewer.__close = closeViewer;

                closeBtn.addEventListener('click', function () { closeViewer(); });
                viewer.addEventListener('click', function (e) {
                    if (e.target === viewer) closeViewer();
                });

                viewerImg.addEventListener('pointerdown', function (e) {
                    if (!viewer || !viewer.classList.contains('is-open')) return;
                    if (viewerScale <= 1) return;
                    if (activePointerId !== null) return;

                    activePointerId = e.pointerId;
                    isPanning = true;
                    panStartX = e.clientX;
                    panStartY = e.clientY;
                    panStartTx = viewerTx;
                    panStartTy = viewerTy;
                    try { viewerImg.setPointerCapture(activePointerId); } catch (err) { }
                    applyTransform();
                    e.preventDefault();
                });

                viewerImg.addEventListener('pointermove', function (e) {
                    if (!isPanning) return;
                    if (activePointerId !== e.pointerId) return;
                    viewerTx = panStartTx + (e.clientX - panStartX);
                    viewerTy = panStartTy + (e.clientY - panStartY);
                    applyTransform();
                    e.preventDefault();
                });

                function endPan(e) {
                    if (!isPanning) return;
                    if (activePointerId !== null && e && e.pointerId !== undefined && e.pointerId !== activePointerId) return;
                    isPanning = false;
                    activePointerId = null;
                    applyTransform();
                }

                viewerImg.addEventListener('pointerup', endPan);
                viewerImg.addEventListener('pointercancel', endPan);

                zoomIn.addEventListener('click', function () {
                    viewerScale = Math.min(5, +(viewerScale + 0.25).toFixed(2));
                    applyTransform();
                });

                zoomOut.addEventListener('click', function () {
                    viewerScale = Math.max(1, +(viewerScale - 0.25).toFixed(2));
                    if (viewerScale === 1) {
                        viewerTx = 0;
                        viewerTy = 0;
                    }
                    applyTransform();
                });

                document.addEventListener('keydown', function (e) {
                    if (!viewer || !viewer.classList.contains('is-open')) return;
                    if (e.key === 'Escape') {
                        closeViewer();
                    }
                });
            }

            function ensureMap() {
                if (dtMap || !window.L) return;
                var el = document.getElementById('dt-map');
                if (!el) return;

                dtMap = L.map(el, { zoomControl: true });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(dtMap);

                dtLayer = L.featureGroup().addTo(dtMap);
                dtMap.setView([-8.65, 115.22], 12);

                if (window.$) {
                    $('#tanahDetailModal').on('shown.bs.modal', function () {
                        setTimeout(function () {
                            if (dtMap) dtMap.invalidateSize();
                        }, 50);
                    });
                }
            }

            function renderPolygonOrMarker(row) {
                ensureMap();
                if (!dtMap || !dtLayer) return;

                dtLayer.clearLayers();

                var polygonRaw = row.getAttribute('data-polygon');
                var polygonVal = null;
                currentSelectedKode = row.getAttribute('data-kode_tanah');

                try {
                    polygonVal = polygonRaw ? JSON.parse(polygonRaw) : null;
                } catch (e) {
                    polygonVal = polygonRaw;
                }

                var geo = null;
                if (polygonVal) {
                    try {
                        geo = (typeof polygonVal === 'string') ? JSON.parse(polygonVal) : polygonVal;
                    } catch (e) {
                        geo = null;
                    }
                }

                if (geo && window.L && L.geoJSON) {
                    try {
                        var gj = L.geoJSON(geo, {
                            style: function () {
                                return {
                                    color: '#2563eb',
                                    weight: 2,
                                    fillColor: '#60a5fa',
                                    fillOpacity: 0.25
                                };
                            }
                        });
                        dtLayer.addLayer(gj);
                        var b = gj.getBounds();
                        if (b && b.isValid && b.isValid()) {
                            dtMap.fitBounds(b, { padding: [20, 20] });
                            return;
                        }
                    } catch (e) {
                        // ignore
                    }
                }

                var lat = parseFloat(row.getAttribute('data-latitude') || '');
                var lng = parseFloat(row.getAttribute('data-longitude') || '');
                if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                    var m = L.marker([lat, lng]);
                    dtLayer.addLayer(m);
                    dtMap.setView([lat, lng], 16);
                }
            }

            document.addEventListener('click', function (e) {
                var row = e.target.closest('.tanah-row');
                if (!row) return;

                var id = row.getAttribute('data-id');
                var tanahBaseUrl = @json(url('/tanah'));
                var mediaRaw = row.getAttribute('data-media');

                setText('dt-kode_tanah', row.getAttribute('data-kode_tanah'));
                setText('dt-ns', row.getAttribute('data-ns'));
                setText('dt-name', row.getAttribute('data-name'));
                setText('dt-luas_tanah', row.getAttribute('data-luas_tanah'));
                setText('dt-jenis_sertifikat', row.getAttribute('data-jenis_sertifikat'));
                setText('dt-tanggal_terbit', row.getAttribute('data-tanggal_terbit'));
                setText('dt-masa_berlaku', row.getAttribute('data-masa_berlaku'));
                setText('dt-alamat', row.getAttribute('data-alamat'));
                setText('dt-provinsi', row.getAttribute('data-provinsi'));
                setText('dt-kabupaten', row.getAttribute('data-kabupaten'));
                setText('dt-kecamatan', row.getAttribute('data-kecamatan'));
                setText('dt-desa', row.getAttribute('data-desa'));
                setText('dt-kode_pos', row.getAttribute('data-kode_pos'));
                setText('dt-status_tanah', row.getAttribute('data-status_tanah'));
                var linkMap = row.getAttribute('data-link_map');
                var linkEl = document.getElementById('dt-link_map');
                if (linkEl) {
                    if (linkMap && linkMap !== '-' && linkMap !== '#') {
                        linkEl.textContent = 'buka google maps';
                        linkEl.setAttribute('href', linkMap);
                    } else {
                        linkEl.textContent = '-';
                        linkEl.setAttribute('href', '#');
                    }
                }

                var pdfUrl = row.getAttribute('data-bukti_sertifikat') || '';
                var pdfEl = document.getElementById('dt-bukti_sertifikat');
                if (pdfEl) {
                    if (pdfUrl) {
                        pdfEl.textContent = 'Lihat Sertifikat';
                        pdfEl.setAttribute('href', pdfUrl);
                    } else {
                        pdfEl.textContent = '-';
                        pdfEl.setAttribute('href', '#');
                    }
                }

                var mapPdf = document.getElementById('dt-map-pdf');
                if (mapPdf) {
                    if (pdfUrl) {
                        mapPdf.setAttribute('href', pdfUrl);
                        mapPdf.classList.add('is-open');
                    } else {
                        mapPdf.setAttribute('href', '#');
                        mapPdf.classList.remove('is-open');
                    }
                }

                var mediaGrid = document.getElementById('dt-media-grid');
                if (mediaGrid) {
                    mediaGrid.innerHTML = '';

                    var media = null;
                    try {
                        media = mediaRaw ? JSON.parse(mediaRaw) : null;
                    } catch (err) {
                        media = null;
                    }

                    var images = (media && Array.isArray(media.images)) ? media.images : [];
                    var videos = (media && Array.isArray(media.videos)) ? media.videos : [];

                    function appendImage(title, src) {
                        var wrap = document.createElement('div');
                        wrap.className = 'tanah-image-item';

                        var t = document.createElement('div');
                        t.className = 'tanah-image-item-title';
                        t.textContent = title;

                        var img = document.createElement('img');
                        img.className = 'tanah-image-thumb';
                        img.src = src;
                        img.alt = title;
                        img.setAttribute('data-src', src);
                        img.addEventListener('click', function (ev) {
                            ev.preventDefault();
                            ev.stopPropagation();
                            ensureImageViewer();
                            if (viewer && viewer.__open) {
                                viewer.__open(src);
                            }
                        });

                        wrap.appendChild(t);
                        wrap.appendChild(img);
                        mediaGrid.appendChild(wrap);
                    }

                    function appendVideo(title, src) {
                        var wrap = document.createElement('div');
                        wrap.className = 'tanah-image-item';

                        var t = document.createElement('div');
                        t.className = 'tanah-image-item-title';
                        t.textContent = title;

                        var v = document.createElement('video');
                        v.className = 'tanah-image-thumb';
                        v.controls = true;
                        v.src = src;
                        v.playsInline = true;

                        wrap.appendChild(t);
                        wrap.appendChild(v);
                        mediaGrid.appendChild(wrap);
                    }

                    var count = 0;
                    images.slice(0, 5).forEach(function (src, idx) {
                        appendImage('Foto ' + (idx + 1), src);
                        count++;
                    });

                    videos.slice(0, 2).forEach(function (src, idx) {
                        appendVideo('Video ' + (idx + 1), src);
                        count++;
                    });

                    if (count === 0) {
                        var emptyWrap = document.createElement('div');
                        emptyWrap.className = 'tanah-image-item';
                        var t = document.createElement('div');
                        t.className = 'tanah-image-item-title';
                        t.textContent = 'Media';
                        var e = document.createElement('div');
                        e.className = 'tanah-image-empty';
                        e.textContent = '-';
                        emptyWrap.appendChild(t);
                        emptyWrap.appendChild(e);
                        mediaGrid.appendChild(emptyWrap);
                    }
                }

                var editBtn = document.getElementById('dt-edit-btn');
                if (editBtn && id) {
                    var editUrl = tanahBaseUrl + '/' + id + '/edit';
                    var urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('page')) {
                        editUrl += '?page=' + urlParams.get('page');
                    }
                    editBtn.setAttribute('href', editUrl);
                }

                var deleteForm = document.getElementById('dt-delete-form');
                if (deleteForm && id) {
                    deleteForm.setAttribute('action', tanahBaseUrl + '/' + id);
                }

                renderPolygonOrMarker(row);

                var btnGoToMap = document.getElementById('btnGoToDashboardMap');
                if (btnGoToMap) {
                    btnGoToMap.addEventListener('click', function() {
                        if (currentSelectedKode) {
                            window.location.href = "{{ url('/dashboard') }}?q=" + encodeURIComponent(currentSelectedKode);
                        }
                    });
                }

                window.closeTanahDetail = function () {
                    if (window.$) $('#tanahDetailModal').modal('hide');
                };

                if (window.$ && $('#tanahDetailModal').modal) {
                    $('#tanahDetailModal').modal('show');
                }
            });

            document.addEventListener('submit', function (e) {
                var form = e.target.closest('#dt-delete-form');
                if (!form) return;

                var ok = window.confirm('Apakah anda ingin menghapus data ini?');
                if (!ok) {
                    e.preventDefault();
                }
            });

            document.addEventListener('click', function (e) {
                var img = e.target.closest('#dt-media-grid img.tanah-image-thumb');
                if (!img) return;
            });

            document.addEventListener('click', function (e) {
                var link = e.target.closest('.dt-field-link');
                if (!link) return;
                if (link.getAttribute('data-field') === 'link_map') return;
                if (link.getAttribute('data-field') === 'bukti_sertifikat') return;
                e.preventDefault();
            });

            // Auto-open detail if show parameter exists
            window.addEventListener('load', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var showId = urlParams.get('show');
                if (showId) {
                    // Beri sedikit delay agar rendering tabel selesai sempurna
                    setTimeout(function() {
                        var row = document.querySelector('.tanah-row[data-id="' + showId + '"]');
                        if (row) {
                            row.click();
                        }
                    }, 500);
                }
            });
        })();
    </script>
@endsection