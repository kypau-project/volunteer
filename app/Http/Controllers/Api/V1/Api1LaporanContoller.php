<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;

class Api1LaporanContoller extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('user')->get();

        $mapped = $laporan->map(function ($item) {
            return [
                'id'        => $item->id,
                'judul'     => $item->judul,
                'deskripsi' => $item->deskripsi,
                'tanggal'   => $item->tanggal,
                'status'    => $item->status,
                'gambar'    => $item->gambar,
                'respon'    => $item->respon ?? 'Belum ada respon',
                'user'      => [
                    'name'  => $item->user->name,
                    'email' => $item->user->email
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $mapped
        ]);
    }
}
