<?php

namespace App\Livewire\Front\Konten;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.front')]
class LaporanForm extends Component
{
    public function render()
    {
        return view('livewire.front.konten.laporan-form');
    }
}
