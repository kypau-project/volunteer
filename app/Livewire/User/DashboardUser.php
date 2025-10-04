<?php

namespace App\Livewire\User;

use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardUser extends Component
{
    public $totalLaporan;
    public $laporanPending;
    public $laporanProses;
    public $laporanSelesai;
    public $laporanPerBulan;

    // protected $listeners = [
    //     'laporanCreated' => 'mount',
    //     'laporanUpdated' => 'mount',
    //     'laporanDeleted' => 'mount',
    // ];

    public function mount()
    {
        $this->totalLaporan = Laporan::where('user_id', Auth::user()->id)->count();
        $this->laporanPending = Laporan::where('user_id', Auth::user()->id)->where('status', 'pending')->count();
        $this->laporanProses = Laporan::where('user_id', Auth::user()->id)->where('status', 'diproses')->count();
        $this->laporanSelesai = Laporan::where('user_id', Auth::user()->id)->where('status', 'selesai')->count();
    }
    public function render()
    {
        return view('livewire.user.dashboard-user');
    }
}
