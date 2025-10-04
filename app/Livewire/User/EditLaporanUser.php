<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditLaporanUser extends Component
{
    use WithFileUploads;

    public $laporanId, $judul, $deskripsi, $tanggal, $gambar, $gambarLama;

    public function mount($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Verifikasi kepemilikan dan status
        if ($laporan->user_id != Auth::user()->id) {
            abort(403);
        }

        // Jika status bukan pending, redirect ke halaman list
        if ($laporan->status !== 'pending') {
            session()->flash('message', 'Laporan yang sudah diproses atau selesai tidak dapat diedit.');
            return $this->redirect(route('user.laporan'), navigate: true);
        }

        $this->laporanId    = $id;
        $this->judul        = $laporan->judul;
        $this->deskripsi    = $laporan->deskripsi;
        $this->tanggal      = $laporan->tanggal;
        $this->gambarLama   = $laporan->gambar;
    }

    public function update()
    {
        $laporan = Laporan::findOrFail($this->laporanId);

        // Double check status sebelum update
        if ($laporan->status !== 'pending') {
            session()->flash('message', 'Laporan yang sudah diproses atau selesai tidak dapat diedit.');
            return $this->redirect(route('user.laporan'), navigate: true);
        }

        $this->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal'   => 'required|date',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $laporan->judul     = $this->judul;
        $laporan->deskripsi = $this->deskripsi;
        $laporan->tanggal   = $this->tanggal;

        // Handle upload gambar baru
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
        return view('livewire.user.edit-laporan-user');
    }
}
