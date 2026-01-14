<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilizationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'periode_mulai',
        'periode_selesai',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    public function sections()
    {
        return $this->hasMany(UtilizationSection::class)->orderBy('urutan');
    }
}
