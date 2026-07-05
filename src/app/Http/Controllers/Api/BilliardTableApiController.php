<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BilliardTable;

class BilliardTableApiController extends Controller
{
    public function index()
    {
        $tables = BilliardTable::with('floor')->get()->map(function ($table) {

            return [
                'id' => $table->id,
                'kode_meja' => 'MJ-' . str_pad($table->id, 3, '0', STR_PAD_LEFT),
                'nama_meja' => $table->name,
                'ruangan' => $table->room_name ?? 'Area Umum',
                'lantai' => $table->floor->name,

                'jenis_meja' => 'Billiard Pool',
                'ukuran_meja' => '9 Feet',
                'bahan_permukaan' => 'Slate Stone',
                'bahan_kain' => 'Simonis Cloth',
                'bahan_frame' => 'Kayu Solid',
                'warna_kain' => 'Hijau',
                'berat_meja' => '520 Kg',

                'jumlah_stik' => 4,
                'jumlah_bola' => 16,
                'tersedia_bridge_stick' => true,
                'tersedia_kapur' => true,
                'tersedia_rak_bola' => true,

                'deskripsi' =>
                    'Meja billiard profesional ukuran 9 Feet dengan permukaan slate stone dan kain Simonis berkualitas tinggi. Cocok digunakan untuk permainan santai maupun latihan.',

                // API
                'last_update' => now()->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar informasi meja billiard',
            'total' => $tables->count(),
            'data' => $tables,
        ]);
    }
}