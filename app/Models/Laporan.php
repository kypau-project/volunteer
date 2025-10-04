<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal',
        'gambar',
        'status',
        'respon',
        'rating',
        'rating_comment',
    ];

    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: function ($gambar) {
                if ($gambar) {
                    return asset('/storage/laporan/' . $gambar);
                }

                // Using UI Avatars with the report title for placeholder
                $title = urlencode($this->judul ?? 'Laporan');
                return "https://ui-avatars.com/api/?background=random&name=" . $title;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
