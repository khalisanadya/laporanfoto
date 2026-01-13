<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'nama_kegiatan',
        'waktu_kegiatan',
        'jenis_kegiatan',
        'lokasi_kegiatan',
        'title',
        'photo_path',
    ];

    public function items()
    {
        return $this->hasMany(ReportItem::class);
    }

    public function photos()
    {
        return $this->hasMany(ReportPhoto::class);
    }
}
