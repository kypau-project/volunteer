<?php

namespace App\Livewire\Volunteer;

use App\Models\Registration;
use App\Models\Feedback;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class FeedbackForm extends Component
{
    public Registration $registration;
    public $rating = 0;
    public $comment;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ];

    public function setRating($value)
    {
        $this->rating = $value;
    }

    protected $listeners = ['set:rating' => 'setRating'];

    public function mount(Registration $registration)
    {
        // Check if the registration belongs to the current user
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke registrasi ini.');
        }

        // Check if the event is complete and the volunteer attended
        // Note: The event end_date check is tricky. For simplicity, we check if check_out is filled.
        if (!$registration->attended || !$registration->check_out) {
            session()->flash('error', 'Feedback hanya bisa diberikan setelah acara selesai dan Anda hadir.');
            return redirect()->route('volunteer.dashboard');
        }

        // Check if feedback already exists
        if ($registration->feedback) {
            session()->flash('info', 'Anda sudah memberikan feedback untuk acara ini.');
            return redirect()->route('volunteer.dashboard');
        }

        $this->registration = $registration;
    }

    public function saveFeedback()
    {
        $this->validate();

        Feedback::create([
            'registration_id' => $this->registration->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        session()->flash('message', 'Terima kasih atas feedback Anda!');
        return redirect()->route('volunteer.dashboard');
    }

    public function render()
    {
        return view('livewire.volunteer.feedback-form');
    }
}
