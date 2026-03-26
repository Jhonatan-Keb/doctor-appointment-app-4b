<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'status',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Pendiente',
            'processing' => 'Procesando',
            'completed'  => 'Completado',
            'failed'     => 'Fallido',
            default      => $this->status,
        };
    }
}
