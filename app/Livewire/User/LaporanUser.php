<?php

namespace App\Livewire\User;

use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Rule;

class LaporanUser extends Component
{
    public $search = '';
    protected $currentRating = [
        'id' => null,
        'rating' => 0,
        'comment' => ''
    ];

    public function submitRating($id, $rating, $comment)
    {
        try {
            $laporan = Laporan::findOrFail($id);

            if ($laporan->user_id != Auth::user()->id) {
                return $this->showError('Laporan ini bukan milik anda');
            }

            if (!$rating || $rating < 1 || $rating > 5) {
                return $this->showError('Rating harus dipilih (1-5)');
            }

            $laporan->rating = $rating;
            $laporan->rating_comment = $comment;
            $laporan->save();

            $this->dispatch('closeModal');
            $this->showSuccess('Penilaian berhasil disimpan');

            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            return $this->showError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function showError($message)
    {
        $this->js("Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '$message'
        })");
        return ['success' => false];
    }

    protected function showSuccess($message)
    {
        $this->js("Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '$message',
            showConfirmButton: false,
            timer: 1500
        }).then(() => { window.location.reload(); })");
    }

    public function showRespon($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->user_id != Auth::user()->id) {
            return;
        }

        if ($laporan->respon) {
            $respon = addslashes($laporan->respon);
            $this->js(<<<JS
                Swal.fire({
                    title: "$respon",
                    confirmButtonText: 'Tutup'
                });
            JS);
        }
    }

    public function showRating($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->user_id != Auth::user()->id || !$laporan->rating) {
            return;
        }

        $stars = str_repeat('★', $laporan->rating) . str_repeat('☆', 5 - $laporan->rating);
        $ratingText = match ($laporan->rating) {
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik',
            default => ''
        };

        $html = addslashes("<div style='font-size: 24px; color: #ffd700; margin: 10px 0;'>{$stars}</div>");
        $html .= addslashes("<div style='color: #666; margin-bottom: 15px;'>{$ratingText}</div>");

        if ($laporan->rating_comment) {
            $comment = e($laporan->rating_comment);
            $html .= addslashes("<div style='font-size: 14px; margin-top: 10px;'>");
            $html .= addslashes("<strong>Komentar:</strong><br>");
            $html .= addslashes($comment);
            $html .= addslashes("</div>");
        }

        $html .= addslashes("<div style='margin-top: 20px;'>");
        $html .= addslashes("<button onclick='editRating()' class='btn btn-warning btn-sm'>");
        $html .= addslashes("<i class='bi bi-pencil'></i> Edit Penilaian");
        $html .= addslashes("</button>");
        $html .= addslashes("</div>");

        $this->js(<<<JS
            function editRating() {
                Swal.close();
                setTimeout(() => {
                    \$wire.showRatingDialog({$id});
                }, 100);
            }

            Swal.fire({
                title: 'Penilaian Anda',
                html: "$html",
                showConfirmButton: true,
                confirmButtonText: 'Tutup'
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

    public function delete($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->user_id == Auth::user()->id) {
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
                    title: 'Laporan ini bukan milik anda',
                    showConfirmButton: false,
                    timer: 1500
                });
            JS);
        }
    }

    public function showRatingDialog($id)
    {
        $laporan = Laporan::findOrFail($id);
        if ($laporan->user_id != Auth::user()->id) return;

        $this->currentRating = [
            'id' => $id,
            'rating' => $laporan->rating ?? 0,
            'comment' => $laporan->rating_comment ?? ''
        ];

        $this->js(<<<JS
            Swal.fire({
                title: 'Berikan Penilaian',
                html: `
                    <div class="rating-container">
                        <div class="stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <div class="rating-text" id="rating-text">Klik bintang untuk memberi nilai</div>
                        <textarea id="rating-comment" class="swal2-textarea" 
                            placeholder="Tambahkan komentar (opsional)" 
                            style="height: 60px; margin: 10px auto; width: 90%; max-width: 300px; resize: none;">{$this->currentRating['comment']}</textarea>
                    </div>
                    <style>
                        .rating-container {
                            text-align: center;
                            padding: 5px;
                            max-width: 350px;
                            margin: 0 auto;
                        }
                        .stars {
                            font-size: 32px;
                            cursor: pointer;
                            padding: 5px 0;
                            user-select: none;
                        }
                        .star {
                            color: #ddd;
                            transition: color 0.2s;
                            margin: 0 3px;
                        }
                        .star.active {
                            color: #ffd700;
                        }
                        .rating-text {
                            margin: 5px 0;
                            font-size: 13px;
                            color: #666;
                            min-height: 20px;
                        }
                    </style>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                showCloseButton: true,
                allowOutsideClick: false,
                didOpen: (modal) => {
                    const stars = modal.querySelectorAll('.star');
                    const ratingText = modal.querySelector('#rating-text');
                    let selectedRating = {$this->currentRating['rating']};
                    const ratingTexts = {
                        0: 'Klik bintang untuk memberi nilai',
                        1: 'Sangat Buruk',
                        2: 'Buruk',
                        3: 'Cukup',
                        4: 'Baik',
                        5: 'Sangat Baik'
                    };

                    function updateStars(rating) {
                        stars.forEach(star => {
                            const value = parseInt(star.dataset.value);
                            star.classList.toggle('active', value <= rating);
                        });
                        ratingText.textContent = ratingTexts[rating] || ratingTexts[0];
                        selectedRating = rating;
                    }

                    // Set initial state
                    if (selectedRating > 0) {
                        updateStars(selectedRating);
                    }

                    // Handle star clicks
                    stars.forEach(star => {
                        star.addEventListener('click', () => {
                            const rating = parseInt(star.dataset.value);
                            updateStars(rating);
                        });

                        // Hover effects
                        star.addEventListener('mouseenter', () => {
                            const rating = parseInt(star.dataset.value);
                            stars.forEach(s => {
                                const value = parseInt(s.dataset.value);
                                s.classList.toggle('active', value <= rating);
                            });
                            ratingText.textContent = ratingTexts[rating];
                        });

                        star.addEventListener('mouseleave', () => {
                            updateStars(selectedRating);
                        });
                    });
                },
                preConfirm: () => {
                    const modal = Swal.getPopup();
                    const activeStars = modal.querySelectorAll('.star.active').length;
                    const comment = modal.querySelector('#rating-comment').value;

                    if (activeStars === 0) {
                        Swal.showValidationMessage('Silakan pilih rating terlebih dahulu');
                        return false;
                    }

                    return \$wire.submitRating({$id}, activeStars, comment);
                }
            });
        JS);
    }



    public function render()
    {
        $laporans = Laporan::query();

        if (Auth::user()->role == 'user') {
            $laporans->where('user_id', Auth::user()->id);
        }

        if (!empty($this->search)) {
            $laporans->where(function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.user.laporan-user', [
            'laporans' => $laporans->orderBy('created_at', 'desc')->get()
        ]);
    }
}
