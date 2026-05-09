@extends('layouts.app')@extends('layouts.app')

@section('content')
    <style>
        .data-table-wrap {
            border-radius: .5rem;
            overflow: hidden;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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

        @media (max-width: 768px) {
            .data-table {
                min-width: 980px;
            }
        }
    </style>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Tanah</h1>
        <a href="/tanah/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
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
                                    <th>Nama Pemilik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($tanahs) || $tanahs->count() === 0)
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                @else
                                    @foreach ($tanahs as $item)
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
                                            >
                                            <td>{{ $item->kode_tanah }}</td>
                                            <td>{{ $item->ns }}</td>
                                            <td>{{ $item->name }}</td>
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
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <tbody>
                                <tr><th style="width: 220px;">Kode Tanah</th><td id="dt-kode_tanah"></td></tr>
                                <tr><th>Nomor Sertifikat</th><td id="dt-ns"></td></tr>
                                <tr><th>Nama Pemilik</th><td id="dt-name"></td></tr>
                                <tr><th>Luas Tanah</th><td id="dt-luas_tanah"></td></tr>
                                <tr><th>Jenis Sertifikat</th><td id="dt-jenis_sertifikat"></td></tr>
                                <tr><th>Tanggal Terbit</th><td id="dt-tanggal_terbit"></td></tr>
                                <tr><th>Masa Berlaku</th><td id="dt-masa_berlaku"></td></tr>
                                <tr><th>Alamat</th><td id="dt-alamat"></td></tr>
                                <tr><th>Provinsi</th><td id="dt-provinsi"></td></tr>
                                <tr><th>Kabupaten</th><td id="dt-kabupaten"></td></tr>
                                <tr><th>Kecamatan</th><td id="dt-kecamatan"></td></tr>
                                <tr><th>Desa</th><td id="dt-desa"></td></tr>
                                <tr><th>Kode Pos</th><td id="dt-kode_pos"></td></tr>
                                <tr><th>Status Tanah</th><td id="dt-status_tanah"></td></tr>
                                <tr><th>Link Map</th><td id="dt-link_map"></td></tr>
                            </tbody>
                        </table>
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

    <script>
        (function () {
            function setText(id, value) {
                var el = document.getElementById(id);
                if (!el) return;
                el.textContent = value ? String(value) : '-';
            }

            document.addEventListener('click', function (e) {
                var row = e.target.closest('.tanah-row');
                if (!row) return;

                var id = row.getAttribute('data-id');

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
                setText('dt-link_map', row.getAttribute('data-link_map'));

                var editBtn = document.getElementById('dt-edit-btn');
                if (editBtn && id) {
                    editBtn.setAttribute('href', '/tanah/' + id);
                }

                var deleteForm = document.getElementById('dt-delete-form');
                if (deleteForm && id) {
                    deleteForm.setAttribute('action', '/tanah/' + id);
                }

                if (window.$ && $('#tanahDetailModal').modal) {

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
                    setText('dt-link_map', row.getAttribute('data-link_map'));

                    var editBtn = document.getElementById('dt-edit-btn');
                    if (editBtn && id) {
                        editBtn.setAttribute('href', '/tanah/' + id);
                    }

                    var deleteForm = document.getElementById('dt-delete-form');
                    if (deleteForm && id) {
                        deleteForm.setAttribute('action', '/tanah/' + id);
                    }

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
            })();
        </script>
    @endsection