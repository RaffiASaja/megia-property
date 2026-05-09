@extends('layouts.app')

@section('content')
    <style>
        .tanah-form-section {
            border: 1px solid #e3e6f0;
            border-radius: .35rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .tanah-form-section-title {
            background: #0ea5e9;
            color: #fff;
            padding: .65rem .9rem;
            font-weight: 600;
        }

        .tanah-form-section-body .table {
            margin-bottom: 0;
        }

        /* Mobile Optimization */
        @media (max-width: 576px) {
            .tanah-form-section-body .table, 
            .tanah-form-section-body tbody, 
            .tanah-form-section-body tr, 
            .tanah-form-section-body th, 
            .tanah-form-section-body td {
                display: block;
                width: 100% !important;
                border: 0;
            }

            .tanah-form-section-body tr {
                border-bottom: 1px solid #e3e6f0;
                padding: .75rem;
            }

            .tanah-form-section-body tr:last-child {
                border-bottom: 0;
            }

            .tanah-form-section-body th {
                background-color: transparent !important;
                padding: 0 0 .25rem 0;
                font-size: .75rem;
                text-transform: uppercase;
                color: #4e73df;
            }

            .tanah-form-section-body td {
                padding: 0;
            }

            .d-sm-flex.justify-content-between.mb-4 {
                flex-direction: column;
                gap: 10px;
            }

            .d-sm-flex.justify-content-between.mb-4 .btn {
                width: 100%;
            }
        }
    </style>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Tanah</h1>
        <a href="{{ url('/tanah') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10 col-xl-9">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ url('/tanah/'.$tanah->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php
                            $polygonText = old('polygon_text');
                            if ($polygonText === null) {
                                $polygonText = '';
                                $geo = $tanah->polygon ? json_decode($tanah->polygon, true) : null;
                                $coords = is_array($geo) && isset($geo['coordinates'][0]) && is_array($geo['coordinates'][0]) ? $geo['coordinates'][0] : [];
                                foreach ($coords as $p) {
                                    if (is_array($p) && count($p) >= 2) {
                                        $polygonText .= $p[0] . ' ' . $p[1] . "\n";
                                    }
                                }
                                $polygonText = trim($polygonText);
                            }
                        @endphp

                        <div class="tanah-form-section">
                            <div class="tanah-form-section-title">Informasi Tanah</div>
                            <div class="tanah-form-section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 240px; background-color: #f8f9fc;">Kode Tanah</th>
                                                <td>
                                                    <input type="text" name="kode_tanah" class="form-control @error('kode_tanah') is-invalid @enderror"
                                                        value="{{ old('kode_tanah', $tanah->kode_tanah) }}" required>
                                                    @error('kode_tanah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Nomor Sertifikat</th>
                                                <td>
                                                    <input type="text" name="ns" class="form-control @error('ns') is-invalid @enderror"
                                                        value="{{ old('ns', $tanah->ns) }}" maxlength="24" required>
                                                    @error('ns')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Luas Tanah</th>
                                                <td>
                                                    <input type="text" name="luas_tanah" class="form-control @error('luas_tanah') is-invalid @enderror"
                                                        value="{{ old('luas_tanah', $tanah->luas_tanah) }}" required>
                                                    @error('luas_tanah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Nama Pemilik</th>
                                                <td>
                                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ old('name', $tanah->name) }}" maxlength="100" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tanah-form-section">
                            <div class="tanah-form-section-title">Sertifikat</div>
                            <div class="tanah-form-section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 240px; background-color: #f8f9fc;">Jenis Sertifikat</th>
                                                <td>
                                                    <select name="jenis_sertifikat" class="form-control @error('jenis_sertifikat') is-invalid @enderror" required>
                                                        @php($jenis = old('jenis_sertifikat', $tanah->jenis_sertifikat))
                                                        <option value="SHM" {{ $jenis === 'SHM' ? 'selected' : '' }}>SHM</option>
                                                        <option value="HGB" {{ $jenis === 'HGB' ? 'selected' : '' }}>HGB</option>
                                                        <option value="HP" {{ $jenis === 'HP' ? 'selected' : '' }}>HP</option>
                                                        <option value="HGU" {{ $jenis === 'HGU' ? 'selected' : '' }}>HGU</option>
                                                    </select>
                                                    @error('jenis_sertifikat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Tanggal Terbit</th>
                                                <td>
                                                    <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror"
                                                        value="{{ old('tanggal_terbit', $tanah->tanggal_terbit) }}">
                                                    @error('tanggal_terbit')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Masa Berlaku</th>
                                                <td>
                                                    <input type="date" name="masa_berlaku" class="form-control @error('masa_berlaku') is-invalid @enderror"
                                                        value="{{ old('masa_berlaku', $tanah->masa_berlaku) }}">
                                                    @error('masa_berlaku')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tanah-form-section">
                            <div class="tanah-form-section-title">Lokasi</div>
                            <div class="tanah-form-section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 240px; background-color: #f8f9fc;">Alamat</th>
                                                <td>
                                                    <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $tanah->alamat) }}</textarea>
                                                    @error('alamat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Provinsi</th>
                                                <td>
                                                    <input type="text" name="provinsi" id="provinsi" class="form-control @error('provinsi') is-invalid @enderror" value="{{ old('provinsi', $tanah->provinsi) }}" required>
                                                    @error('provinsi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Kabupaten</th>
                                                <td>
                                                    <input type="text" name="kabupaten" id="kabupaten" class="form-control @error('kabupaten') is-invalid @enderror" value="{{ old('kabupaten', $tanah->kabupaten) }}" required>
                                                    @error('kabupaten')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Kecamatan</th>
                                                <td>
                                                    <input type="text" name="kecamatan" id="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ old('kecamatan', $tanah->kecamatan) }}" required>
                                                    @error('kecamatan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Desa</th>
                                                <td>
                                                    <input type="text" name="desa" id="desa" class="form-control @error('desa') is-invalid @enderror" value="{{ old('desa', $tanah->desa) }}" required>
                                                    @error('desa')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Kode Pos</th>
                                                <td>
                                                    <input type="text" name="kode_pos" class="form-control @error('kode_pos') is-invalid @enderror"
                                                        value="{{ old('kode_pos', $tanah->kode_pos) }}">
                                                    @error('kode_pos')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Latitude</th>
                                                <td>
                                                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                                                        value="{{ old('latitude', $tanah->latitude) }}" placeholder="contoh: -8.409518">
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Longitude</th>
                                                <td>
                                                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                                                        value="{{ old('longitude', $tanah->longitude) }}" placeholder="contoh: 115.188919">
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tanah-form-section">
                            <div class="tanah-form-section-title">Lainnya</div>
                            <div class="tanah-form-section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 240px; background-color: #f8f9fc;">Status Tanah</th>
                                                <td>
                                                    <select name="status_tanah" class="form-control @error('status_tanah') is-invalid @enderror" required>
                                                        @php($status = old('status_tanah', $tanah->status_tanah))
                                                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="sengketa" {{ $status === 'sengketa' ? 'selected' : '' }}>Sengketa</option>
                                                        <option value="dijual" {{ $status === 'dijual' ? 'selected' : '' }}>Dijual</option>
                                                    </select>
                                                    @error('status_tanah')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Link Map</th>
                                                <td>
                                                    <input type="text" name="link_map" class="form-control @error('link_map') is-invalid @enderror"
                                                        value="{{ old('link_map', $tanah->link_map) }}" placeholder="Tempel link Google Maps">
                                                    @error('link_map')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>

                                            <tr>
                                                <th style="background-color: #f8f9fc;">Koordinat Polygon</th>
                                                <td>
                                                    <textarea name="polygon_text" id="polygon_text" rows="10" class="form-control @error('polygon_text') is-invalid @enderror" placeholder="Tempel titik polygon, 1 baris per titik. Format: longitude latitude">{{ $polygonText }}</textarea>
                                                    @error('polygon_text')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Foto (maks 5)</th>
                                                <td>
                                                    <input type="file" name="foto[]" class="form-control-file @error('foto') is-invalid @enderror" accept="image/*" multiple onchange="previewImages(this, 'preview-foto')">
                                                    @error('foto')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                    @error('foto.*')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror

                                                    <div id="preview-foto" class="row mt-2" style="display: none;"></div>

                                                    @php($images = collect((array) ($tanah->foto ?? []))->take(5))
                                                    @if($images->count() > 0)
                                                        <div class="row mt-3">
                                                            @foreach($images as $path)
                                                                <div class="col-4 col-md-3 mb-3">
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset(ltrim((string) $path, '/')) }}" class="img-thumbnail w-100" style="height: 80px; object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #f8f9fc;">Video (maks 2)</th>
                                                <td>
                                                    <input type="file" name="video[]" class="form-control-file @error('video') is-invalid @enderror" accept="video/*" multiple>
                                                    @error('video')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                    @error('video.*')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror

                                                    @php($videos = collect((array) ($tanah->video ?? []))->take(2))
                                                    @if($videos->count() > 0)
                                                        <div class="row mt-3">
                                                            @foreach($videos as $path)
                                                                <div class="col-6 mb-3">
                                                                    <div class="position-relative border rounded p-1">
                                                                        <video src="{{ asset(ltrim((string) $path, '/')) }}" class="w-100" style="height: 80px; object-fit: cover;"></video>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th style="background-color: #f8f9fc;">Bukti Sertifikat (PDF)</th>
                                                <td>
                                                    <input type="file" name="bukti_sertifikat" class="form-control-file @error('bukti_sertifikat') is-invalid @enderror" accept="application/pdf">
                                                    @error('bukti_sertifikat')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror

                                                    @if(!empty($tanah->bukti_sertifikat))
                                                        <div class="mt-2">
                                                            <a href="{{ asset(ltrim((string) $tanah->bukti_sertifikat, '/')) }}" target="_blank" rel="noopener">Lihat PDF</a>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    });

    function previewImages(input, previewId) {
        const previewDiv = document.getElementById(previewId);
        previewDiv.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            previewDiv.style.display = 'flex';
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-md-3 mb-2';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-thumbnail w-100" style="height: 80px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                style="top: -5px; right: -5px; padding: 0 5px;"
                                onclick="removeSelectedFile(${index}, '${previewId}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewDiv.appendChild(col);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewDiv.style.display = 'none';
        }
    }

    function removeSelectedFile(index, previewId) {
        const input = document.querySelector('input[name="foto[]"]');
        const dt = new DataTransfer();
        const { files } = input;
        
        for (let i = 0; i < files.length; i++) {
            if (index !== i) {
                dt.items.add(files[i]);
            }
        }
        
        input.files = dt.files;
        previewImages(input, previewId);
    }

    </script>
@endsection
