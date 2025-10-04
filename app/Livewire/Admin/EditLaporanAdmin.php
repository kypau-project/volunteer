<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditLaporanAdmin extends Component
{

    use WithFileUploads;

    public $laporanId, $judul, $deskripsi, $tanggal, $gambar, $gambarLama, $status, $respon;

    public function mount($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Admin dapat mengedit semua laporan, user hanya bisa mengedit laporan miliknya
        if (Auth::user()->role !== 'admin' && $laporan->user_id != Auth::user()->id) {
            abort(403);
        }

        $this->laporanId    = $id;
        $this->judul        = $laporan->judul;
        $this->deskripsi    = $laporan->deskripsi;
        $this->tanggal      = $laporan->tanggal;
        $this->gambarLama   = $laporan->gambar;
        $this->status       = $laporan->status;
        $this->respon       = $laporan->respon;
    }

    public function update()
    {
        $rules = [
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal'   => 'required|date',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Hanya admin yang bisa mengubah status dan memberikan respon
        if (Auth::user()->role === 'admin') {
            $rules['status'] = 'required|in:pending,diproses,selesai';
            $rules['respon'] = 'nullable|string|max:1000';
        }

        $this->validate($rules);

        $laporan = Laporan::findOrFail($this->laporanId);

        $laporan->judul     = $this->judul;
        $laporan->deskripsi = $this->deskripsi;
        $laporan->tanggal   = $this->tanggal;

        // Update status dan respon jika yang mengedit adalah admin
        if (Auth::user()->role === 'admin') {
            $laporan->status = $this->status;
            $laporan->respon = $this->respon;
        }

        // 🔹 handle upload gambar baru
        if ($this->gambar) {

            $gambarName = basename($laporan->getOriginal('gambar'));

            if ($gambarName && Storage::exists('public/laporan/' . $gambarName)) {
                Storage::delete('public/laporan/' . $gambarName);
            }

            $filename = $this->gambar->hashName();
            $this->gambar->storeAs('public/laporan', $filename);
            $laporan->gambar = $filename;
        }

        $laporan->save();

        $this->js(<<<'JS'
            Swal.fire({
                title: 'Berhasil!',
                text: 'Laporan berhasil diperbarui',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        JS);

        return $this->redirect(route('user.laporan'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.edit-laporan-admin');
    }
}
