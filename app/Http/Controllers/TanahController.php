<?php

namespace App\Http\Controllers;

use App\Models\Tanah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Http\UploadedFile;

class TanahController extends Controller
{
    private function storeUploadedFileToPublic(?UploadedFile $file, string $subdir, array $allowedExtensions = []): ?string
    {
        if ($file === null) {
            return null;
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: '');
        if (!empty($allowedExtensions) && ($ext === '' || !in_array($ext, $allowedExtensions, true))) {
            $ext = $allowedExtensions[0];
        }

        $filename = uniqid('', true) . ($ext ? ('.' . $ext) : '');
        $targetDir = public_path('uploads/' . $subdir);
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        $file->move($targetDir, $filename);

        return 'uploads/' . $subdir . '/' . $filename;
    }

    private function parsePolygonText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $coords = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $line = str_replace(["\t"], ' ', $line);
            $line = preg_replace('/\s+/', ' ', $line);

            $parts = preg_split('/[ ,]+/', $line);
            if (!$parts || count($parts) < 2) {
                continue;
            }

            $a = (float) $parts[0];
            $b = (float) $parts[1];
            if (!is_finite($a) || !is_finite($b)) {
                continue;
            }

            // Default expected: lng lat (as in many export tools)
            $lng = $a;
            $lat = $b;

            // Heuristic: if first value looks like latitude and second looks like longitude, swap
            if (abs($a) <= 90 && abs($b) > 90) {
                $lat = $a;
                $lng = $b;
            }

            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                continue;
            }

            $coords[] = [(float) $lng, (float) $lat];
        }

        if (count($coords) < 3) {
            return null;
        }

        $first = $coords[0];
        $last = $coords[count($coords) - 1];
        if ($first[0] !== $last[0] || $first[1] !== $last[1]) {
            $coords[] = $first;
        }

        return json_encode([
            'type' => 'Polygon',
            'coordinates' => [$coords],
        ], JSON_UNESCAPED_SLASHES);
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $f = (array) $request->query('f', []);

        $tanahs = Tanah::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('kode_tanah', 'like', '%' . $q . '%')
                        ->orWhere('ns', 'like', '%' . $q . '%');
                });
            })
            ->when(!empty($f['provinsi']), function ($query) use ($f) {
                $query->where('provinsi', 'like', '%' . $f['provinsi'] . '%');
            })
            ->when(!empty($f['kabupaten']), function ($query) use ($f) {
                $query->where('kabupaten', 'like', '%' . $f['kabupaten'] . '%');
            })
            ->when(!empty($f['kecamatan']), function ($query) use ($f) {
                $query->where('kecamatan', 'like', '%' . $f['kecamatan'] . '%');
            })
            ->when(!empty($f['desa']), function ($query) use ($f) {
                $query->where('desa', 'like', '%' . $f['desa'] . '%');
            })
            ->when(!empty($f['name']), function ($query) use ($f) {
                $query->where('name', 'like', '%' . $f['name'] . '%');
            })
            ->when(!empty($f['ns']), function ($query) use ($f) {
                $query->where('ns', 'like', '%' . $f['ns'] . '%');
            })
            ->when(!empty($f['kode_pos']), function ($query) use ($f) {
                $query->where('kode_pos', 'like', '%' . $f['kode_pos'] . '%');
            })
            ->when(!empty($f['jenis_sertifikat']), function ($query) use ($f) {
                $val = is_array($f['jenis_sertifikat']) ? (string) ($f['jenis_sertifikat'][0] ?? '') : (string) $f['jenis_sertifikat'];
                $val = trim($val);
                if ($val !== '') {
                    $query->where('jenis_sertifikat', $val);
                }
            })
            ->when(!empty($f['status_tanah']) && is_array($f['status_tanah']), function ($query) use ($f) {
                $vals = array_values(array_filter($f['status_tanah'], fn($v) => $v !== null && $v !== ''));
                if (!empty($vals)) {
                    $query->whereIn('status_tanah', $vals);
                }
            })
            ->paginate(10)
            ->withQueryString();
        return view('pages.tanah.index',[
            'tanahs' => $tanahs,
        ]);
    }

    public function create()
    {
        return view('pages.tanah.tanah_create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_tanah' => ['required', 'unique:tanahs,kode_tanah'],
            'ns' => ['required', 'max:24'],
            'name' => ['required', 'max:100'],
            'luas_tanah' => ['required', 'max:100'],
            'jenis_sertifikat' => ['required', Rule::in('SHM', 'HGB', 'HP', 'HGU')],
            'tanggal_terbit' => ['nullable'],
            'masa_berlaku' => ['nullable'],
            'alamat' => ['required', 'string'],
            'provinsi' => ['required', 'string'],
            'kabupaten' => ['required', 'string'],
            'kecamatan' => ['required', 'string'],
            'desa' => ['required', 'string'],
            'kode_pos' => ['nullable'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status_tanah' => ['required', Rule::in('aktif', 'sengketa', 'dijual')],
            'link_map' => ['nullable', 'string'],
            'polygon_text' => ['nullable', 'string'],
            'foto' => ['nullable', 'array', 'max:5'],
            'foto.*' => ['file', 'image', 'max:4096'],
            'video' => ['nullable', 'array', 'max:2'],
            'video.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-matroska,video/webm', 'max:51200'],
            'bukti_sertifikat' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:20480'],
        ]);

        $validated['polygon'] = $this->parsePolygonText($request->input('polygon_text'));
        unset($validated['polygon_text']);

        $mediaFoto = $request->file('foto');
        $mediaVideo = $request->file('video');
        $buktiSertifikat = $request->file('bukti_sertifikat');
        unset($validated['foto'], $validated['video'], $validated['bukti_sertifikat']);

        $fotoPaths = [];
        if ($mediaFoto) {
            foreach ($mediaFoto as $file) {
                $stored = $this->storeUploadedFileToPublic($file, 'foto', ['jpg', 'jpeg', 'png', 'webp']);
                if ($stored) {
                    $fotoPaths[] = $stored;
                }
            }
        }

        $videoPaths = [];
        if ($mediaVideo) {
            foreach ($mediaVideo as $file) {
                $stored = $this->storeUploadedFileToPublic($file, 'video', ['mp4', 'mov', 'mkv', 'webm']);
                if ($stored) {
                    $videoPaths[] = $stored;
                }
            }
        }

        $validated['foto'] = !empty($fotoPaths) ? $fotoPaths : null;
        $validated['video'] = !empty($videoPaths) ? $videoPaths : null;
        $validated['bukti_sertifikat'] = $this->storeUploadedFileToPublic($buktiSertifikat, 'sertifikat', ['pdf']);

        $tanah = Tanah::create($validated);

        return redirect('/tanah')->with('success', 'berhasil menambahkan data');
    }

    public function edit($id)
    {
        $tanah = Tanah::findOrFail($id);
        return view('pages.tanah.tanah_edit', compact('tanah'));
    }

    public function show($id)
    {
        return redirect()->route('tanah.edit', $id);
    }

    public function update(Request $request, $id)
    {
        $tanah = Tanah::findOrFail($id);

        $validated = $request->validate([
            'kode_tanah' => ['required', Rule::unique('tanahs', 'kode_tanah')->ignore($tanah->id)],
            'ns' => ['required', 'max:24'],
            'name' => ['required', 'max:100'],
            'luas_tanah' => ['required', 'max:100'],
            'jenis_sertifikat' => ['required', Rule::in('SHM', 'HGB', 'HP', 'HGU')],
            'tanggal_terbit' => ['nullable'],
            'masa_berlaku' => ['nullable'],
            'alamat' => ['required', 'string'],
            'provinsi' => ['required', 'string'],
            'kabupaten' => ['required', 'string'],
            'kecamatan' => ['required', 'string'],
            'desa' => ['required', 'string'],
            'kode_pos' => ['nullable'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status_tanah' => ['required', Rule::in('aktif', 'sengketa', 'dijual')],
            'link_map' => ['nullable', 'string'],
            'polygon_text' => ['nullable', 'string'],
            'foto' => ['nullable', 'array', 'max:5'],
            'foto.*' => ['file', 'image', 'max:4096'],
            'video' => ['nullable', 'array', 'max:2'],
            'video.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-matroska,video/webm', 'max:51200'],
            'bukti_sertifikat' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:20480'],
        ]);

        $validated['polygon'] = $this->parsePolygonText($request->input('polygon_text'));
        unset($validated['polygon_text']);

        $mediaFoto = $request->file('foto');
        $mediaVideo = $request->file('video');
        $buktiSertifikat = $request->file('bukti_sertifikat');
        unset($validated['foto'], $validated['video'], $validated['bukti_sertifikat']);

        $existingFoto = is_array($tanah->foto) ? $tanah->foto : [];
        $existingVideo = is_array($tanah->video) ? $tanah->video : [];

        $tanah->update($validated);

        if ($mediaFoto) {
            foreach ($mediaFoto as $file) {
                if (count($existingFoto) >= 5) {
                    break;
                }
                $stored = $this->storeUploadedFileToPublic($file, 'foto', ['jpg', 'jpeg', 'png', 'webp']);
                if ($stored) {
                    $existingFoto[] = $stored;
                }
            }
        }

        if ($mediaVideo) {
            foreach ($mediaVideo as $file) {
                if (count($existingVideo) >= 2) {
                    break;
                }
                $stored = $this->storeUploadedFileToPublic($file, 'video', ['mp4', 'mov', 'mkv', 'webm']);
                if ($stored) {
                    $existingVideo[] = $stored;
                }
            }
        }

        $updates = [];
        if ($request->hasFile('foto')) {
            $updates['foto'] = $existingFoto;
        }
        if ($request->hasFile('video')) {
            $updates['video'] = $existingVideo;
        }
        if ($buktiSertifikat) {
            $updates['bukti_sertifikat'] = $this->storeUploadedFileToPublic($buktiSertifikat, 'sertifikat', ['pdf']);
        }
        if (!empty($updates)) {
            $tanah->update($updates);
        }

        $page = $request->input('page');
        $redirectUrl = '/tanah?show=' . $id;
        if ($page) {
            $redirectUrl .= '&page=' . $page;
        }

        return redirect($redirectUrl)->with('success', 'berhasil memperbarui data');
    }

    public function destroy($id)
    {
        $tanah = Tanah::findOrFail($id);

        $tanah->delete();

        return redirect('/tanah')->with('success', 'berhasil menghapus data'
        );
    }
}
