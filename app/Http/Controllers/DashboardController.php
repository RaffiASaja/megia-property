<?php

namespace App\Http\Controllers;

use App\Models\Tanah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $f = (array) $request->query('f', []);

        $total = Tanah::count();
        $aktif = Tanah::where('status_tanah', 'aktif')->count();
        $sengketa = Tanah::where('status_tanah', 'sengketa')->count();
        $dijual = Tanah::where('status_tanah', 'dijual')->count();

        $latest = Tanah::latest()->take(5)->get();

        $locations = Tanah::query()
            ->select(['id', 'kode_tanah', 'ns', 'name', 'luas_tanah', 'jenis_sertifikat', 'status_tanah', 'tanggal_terbit', 'masa_berlaku', 'alamat', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kode_pos', 'latitude', 'longitude', 'link_map', 'polygon', 'bukti_sertifikat', 'foto', 'video'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
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
            ->when(!empty($f['jenis_sertifikat']) && is_array($f['jenis_sertifikat']), function ($query) use ($f) {
                $vals = array_values(array_filter($f['jenis_sertifikat'], fn($v) => $v !== null && $v !== ''));
                if (!empty($vals)) {
                    $query->whereIn('jenis_sertifikat', $vals);
                }
            })
            ->when(!empty($f['status_tanah']) && is_array($f['status_tanah']), function ($query) use ($f) {
                $vals = array_values(array_filter($f['status_tanah'], fn($v) => $v !== null && $v !== ''));
                if (!empty($vals)) {
                    $query->whereIn('status_tanah', $vals);
                }
            })
            ->get();

        return view('pages.dashboard', [
            'totalTanah' => $total,
            'tanahAktif' => $aktif,
            'tanahSengketa' => $sengketa,
            'tanahDijual' => $dijual,
            'tanahLatest' => $latest,
            'tanahLocations' => $locations,
            'q' => $q,
        ]);
    }
}
