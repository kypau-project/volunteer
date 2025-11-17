<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Form Feedback Acara</h3>
                <p class="text-subtitle text-muted">{{ $registration->event->title }}</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                            @endif

                            @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <form wire:submit.prevent="saveFeedback">
                                <div class="row">
                                    <!-- Rating -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="rating" class="form-label">Penilaian</label>
                                            <div class="rating-container">
                                                <div class="stars" x-data="{ temp: @entangle('rating') }">
                                                    @for ($i = 5; $i >= 1; $i--)
                                                    <button type="button"
                                                        class="star-btn {{ $rating >= $i ? 'active' : '' }}"
                                                        x-on:click="$wire.setRating({{ $i }})"
                                                        x-on:mouseover="temp = {{ $i }}"
                                                        x-on:mouseleave="temp = $wire.rating"
                                                        x-bind:class="{ 'active': temp >= {{ $i }} }"
                                                        data-value="{{ $i }}">★</button>
                                                    @endfor
                                                </div>
                                                <div class="rating-text" id="rating-text">
                                                    @if($rating == 0)
                                                    Klik bintang untuk memberi nilai
                                                    @else
                                                    {{ match($rating) {
                                                            5 => 'Sangat Baik',
                                                            4 => 'Baik',
                                                            3 => 'Cukup',
                                                            2 => 'Buruk',
                                                            1 => 'Sangat Buruk',
                                                            default => ''
                                                        } }}
                                                    @endif
                                                </div>
                                            </div>

                                            @push('styles')
                                            <style>
                                                .rating-container {
                                                    text-align: center;
                                                    padding: 20px;
                                                    max-width: 500px;
                                                    margin: 0 auto;
                                                }

                                                .stars {
                                                    display: flex;
                                                    justify-content: center;
                                                    gap: 20px;
                                                    padding: 20px 0;
                                                }

                                                .star-btn {
                                                    font-size: 80px;
                                                    background: none;
                                                    border: none;
                                                    cursor: pointer;
                                                    padding: 15px;
                                                    color: #ddd;
                                                    transition: all 0.3s ease;
                                                    outline: none !important;
                                                    -webkit-tap-highlight-color: transparent;
                                                    transform-origin: center;
                                                    line-height: 1;
                                                    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
                                                }

                                                .star-btn:hover,
                                                .star-btn:focus {
                                                    transform: scale(1.2);
                                                    color: #ffd700;
                                                }

                                                .star-btn:active {
                                                    transform: scale(0.95);
                                                }

                                                .star-btn.active {
                                                    color: #ffd700;
                                                    animation: pulse 0.3s ease-in-out;
                                                }

                                                @keyframes pulse {
                                                    0% {
                                                        transform: scale(1);
                                                    }

                                                    50% {
                                                        transform: scale(1.2);
                                                    }

                                                    100% {
                                                        transform: scale(1);
                                                    }
                                                }

                                                .rating-text {
                                                    margin: 15px 0;
                                                    font-size: 18px;
                                                    color: #555;
                                                    min-height: 27px;
                                                    font-weight: 500;
                                                }

                                                /* Mobile optimization */
                                                @media (max-width: 768px) {
                                                    .star-btn {
                                                        font-size: 60px;
                                                        padding: 10px;
                                                    }

                                                    .stars {
                                                        gap: 15px;
                                                    }
                                                }
                                            </style>
                                            @endpush @push('scripts')
                                            <script>
                                                function setRating(value) {
                                                    Livewire.emit('set:rating', value);
                                                }

                                                document.addEventListener('livewire:load', function() {
                                                    Livewire.on('ratingUpdated', function(value) {
                                                        const stars = document.querySelectorAll('.star-btn');
                                                        stars.forEach(star => {
                                                            const starValue = parseInt(star.dataset.value);
                                                            if (starValue <= value) {
                                                                star.classList.add('active');
                                                            } else {
                                                                star.classList.remove('active');
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                                            @endpush
                                            @error('rating')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div> <!-- Event Details -->
                                    <div class="col-md-6 col-12">
                                        <div class="alert alert-light-primary">
                                            <h6 class="alert-heading mb-2">Informasi Event</h6>
                                            <div class="mb-1">
                                                <i class="bi-calendar-event me-1"></i>
                                                {{ $registration->event->start_date->format('d F Y') }}
                                            </div>
                                            <div class="mb-1">
                                                <i class="bi-clock me-1"></i>
                                                {{ $registration->event->start_date->format('H:i') }} -
                                                {{ $registration->event->end_date->format('H:i') }}
                                            </div>
                                            <div>
                                                <i class="bi-geo-alt me-1"></i>
                                                {{ $registration->event->location }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comment -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="comment" class="form-label">Komentar dan Saran</label>
                                            <textarea wire:model="comment" id="comment" class="form-control"
                                                rows="4" placeholder="Berikan komentar dan saran Anda untuk event ini..."></textarea>
                                            <div class="form-text">
                                                Maksimal 500 karakter
                                            </div>
                                            @error('comment')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 d-flex justify-content-end mt-4">
                                        <a href="{{ route('volunteer.dashboard') }}" class="btn btn-light me-3">
                                            <i class="bi-x-circle"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi-send"></i> Kirim Feedback
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .rating-container {
            text-align: center;
            padding: 20px;
            max-width: 500px;
        }

        .stars {
            font-size: 72px;
            cursor: pointer;
            padding: 15px 0;
            user-select: none;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .star {
            color: #ddd;
            transition: color 0.2s;
            padding: 10px;
            display: inline-block;
            position: relative;
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .star:before {
            content: '★';
            display: block;
            transform-origin: center;
            transition: transform 0.2s;
        }

        .star:hover:before {
            transform: scale(1.1);
        }

        .star.active {
            color: #ffd700;
        }

        .star:hover,
        .star:hover~.star {
            color: #ffd700;
        }

        .stars:hover .star {
            color: #ddd;
        }

        .stars .star:hover~.star {
            color: #ddd;
        }

        .rating-text {
            margin: 15px 0;
            font-size: 18px;
            color: #555;
            min-height: 27px;
            font-weight: 500;
        }
    </style>
    @endpush
</div>