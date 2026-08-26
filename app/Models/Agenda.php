<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'is_published',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Scope a query to only include agendas based on filters.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('judul', 'like', '%' . $search . '%');
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            if ($status === 'Aktif') {
                $query->where('is_published', true);
            } elseif ($status === 'Non-Aktif') {
                $query->where('is_published', false);
            }
        });
    }
}
