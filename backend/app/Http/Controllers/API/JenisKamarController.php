<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisKamarStoreRequest;
use App\Http\Resources\JenisKamarResource;
use App\Models\JenisKamar;

class JenisKamarController extends Controller
{
    /**
     * GET - Ambil semua data jenis kamar
     */
    public function index()
    {
        $jenisKamar = JenisKamar::with('fasilitas')->get();

        if ($jenisKamar->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Belum ada data jenis kamar',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Daftar jenis kamar berhasil diambil',
            'data'    => JenisKamarResource::collection($jenisKamar)
        ], 200);
    }

    /**
     * POST - Tambah data jenis kamar baru
     */
    public function store(JenisKamarStoreRequest $request)
    {
        $payload = $request->validated();

        // Ambil fasilitas (jika ada) lalu hapus dari payload
        $fasilitas = $payload['fasilitas'] ?? null;
        unset($payload['fasilitas']);

        // Handle upload gambar
        if ($request->hasFile('url_gambar')) {
            $file = $request->file('url_gambar');
            $path = $file->store('jenis-kamar', 'public'); // folder: storage/app/public/jenis-kamar
            $payload['url_gambar'] = $path;
        }

        // Insert jenis kamar
        $jenis = JenisKamar::create($payload);

        // Jika fasilitas diberikan → attach ke pivot
        if ($fasilitas !== null) {
            $fasilitasIds = is_array($fasilitas) ? $fasilitas : [$fasilitas];
            $jenis->fasilitas()->attach($fasilitasIds);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil menambahkan jenis kamar',
            'data'    => new JenisKamarResource($jenis->load('fasilitas'))
        ], 201);
    }

    /**
     * GET - Ambil detail jenis kamar berdasarkan ID
     */
    public function show($id_jenis_kamar)
    {
        $jenis = JenisKamar::with('fasilitas')
        ->where('id_jenis_kamar', $id_jenis_kamar)
        ->first();

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail jenis kamar berhasil diambil',
            'data'    => new JenisKamarResource($jenis)
        ], 200);
    }

    /**
     * PUT - Update data jenis kamar berdasarkan ID
     */
    public function update(JenisKamarStoreRequest $request, $id)
    {
        $jenis = JenisKamar::find($id);

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $payload = $request->validated();

        // Ambil fasilitas (jika ada) lalu hapus dari payload
        $fasilitas = $payload['fasilitas'] ?? null;
        unset($payload['fasilitas']);

        // Jika update gambar
        if ($request->hasFile('url_gambar')) {

            // Hapus file lama jika ada
            if ($jenis->url_gambar && \Storage::disk('public')->exists($jenis->url_gambar)) {
                \Storage::disk('public')->delete($jenis->url_gambar);
            }

            // Upload file baru
            $file = $request->file('url_gambar');
            $path = $file->store('jenis-kamar', 'public');
            $payload['url_gambar'] = $path;
        }

        // Update data selain fasilitas & gambar
        $jenis->update($payload);

        // Update fasilitas (sync = replace semua)
        if ($fasilitas !== null) {
            $fasilitasIds = is_array($fasilitas) ? $fasilitas : [$fasilitas];
            $jenis->fasilitas()->sync($fasilitasIds);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil memperbarui jenis kamar',
            'data'    => new JenisKamarResource($jenis->load('fasilitas'))
        ], 200);
    }

    /**
     * DELETE - Hapus jenis kamar berdasarkan ID
     */
    public function destroy($id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $jenis->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Jenis kamar berhasil dihapus',
            'data'    => []
        ], 202);
    }
}
