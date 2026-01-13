<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bap extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_bap',
        'nomor_bap',
        'nomor_surat_permohonan',
        'tanggal_surat_permohonan',
    ];

    protected $casts = [
        'tanggal_bap' => 'date',
        'tanggal_surat_permohonan' => 'date',
    ];
}
