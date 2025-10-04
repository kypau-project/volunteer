<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanAdmin extends Component
{

    public $search = '';
    public $editingLaporan = null;
    public $status = '';

    public function changeStatus($id)
    {
        $laporan = Laporan::findOrFail($id);

        $this->js(<<<JS
            Swal.fire({
                title: 'Ubah Status',
                input: 'select',
                inputOptions: {
                    'pending': 'Pending',
                    'diproses': 'Diproses',
                    'selesai': 'Selesai'
                },
                inputValue: '{$laporan->status}',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Status harus dipilih!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    \$wire.updateStatus($id, result.value);
                }
            });
        JS);
    }

    public function updateStatus($id, $status)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->status = $status;
        $laporan->save();

        $this->js(<<<JS
            Swal.fire({
                icon: 'success',
                title: 'Status berhasil diubah',
                showConfirmButton: false,
                timer: 1500
            });
        JS);
    }

    public function deleteConfirm($id, $judul)
    {
        $this->js(<<<JS
            Swal.fire({
                title : "Hapus $judul ?",
                text : "Laporan ini akan dihapus",
                icon : 'warning',
                showCancelButton : true,
                confirmButtonColor : '#3085d6',
                cancelButtonColor : '#d33', 
                confirmButtonText : 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    \$wire.delete($id);
                }
            });
        JS);
    }

    public function showRespon($id)
    {
        $laporan = Laporan::findOrFail($id);

        $message = $laporan->rating_comment ?? $laporan->respon;

        $this->js(<<<JS
            Swal.fire({
                title: 'Komentar',
                text: '{$message}',
                confirmButtonText: 'Tutup'
            });
        JS);
    }

    public function delete($id)
    {
        $laporan = Laporan::findOrFail($id);

        if (Auth::user()->role === 'admin') {

            $gambarName = basename($laporan->getOriginal('gambar'));

            if ($gambarName && Storage::exists('public/laporan/' . $gambarName)) {
                Storage::delete('public/laporan/' . $gambarName);
            }

            $laporan->delete();

            $this->js(<<<JS
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan berhasil dihapus',
                    showConfirmButton: false,
                    timer: 1500
                });
            JS);
        } else {

            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Illegal Access',
                    showConfirmButton: false,
                    timer: 1500
                });
            JS);
        }
    }

    public function render()
    {
        $laporans = Laporan::query();

        if (!empty($this->search)) {
            // $laporans->where('judul', 'like', '%' . $this->search . '%');
            $laporans->where(function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            });
            $laporans->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.laporan-admin', [
            'laporans' => $laporans->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
