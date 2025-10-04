<?php

namespace App\Livewire\Front\Konten;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;

#[Layout('components.layouts.front')]
class Grafik extends Component
{

    public $totalLaporan;
    public $laporanPending;
    public $laporanProses;
    public $laporanSelesai;
    public $laporanPerBulan;
    public $userStats = [];

    // protected $listeners = [
    //     'laporanCreated' => 'mount',
    //     'laporanUpdated' => 'mount',
    //     'laporanDeleted' => 'mount',
    // ];

    public function mount()
    {
        // Laporan statistics
        $this->totalLaporan = Laporan::count();
        $this->laporanPending = Laporan::where('status', 'pending')->count();
        $this->laporanProses = Laporan::where('status', 'diproses')->count();
        $this->laporanSelesai = Laporan::where('status', 'selesai')->count();
        $this->laporanPerBulan = Laporan::where('status', 'selesai')->count();

        $this->laporanPerBulan = Laporan::whereYear('tanggal', now()->year)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('m');
            })
            ->map->count()
            ->toArray();

        // User statistics
        $this->userStats = [
            'total' => User::count(),
            'user' => User::where('role', 'user')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'active_today' => User::whereDate('last_login', Carbon::today())->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.front.konten.grafik');
    }
}
