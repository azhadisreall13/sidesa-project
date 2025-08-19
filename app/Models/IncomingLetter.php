<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    protected $table = 'incoming_letters';

    protected $fillable = [
        'resident_id',
        'type',
        'description',
        'status',
        'file_path'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Terkirim',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            default => 'Tidak Diketahui'
        };
    }
}
