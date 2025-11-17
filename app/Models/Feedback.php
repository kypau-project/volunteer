<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'registration_id',
        'rating',
        'comment',
    ];

    /**
     * Get the registration associated with the feedback.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}

