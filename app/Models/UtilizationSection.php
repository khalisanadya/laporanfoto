<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilizationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilization_report_id',
        'nama_section',
        'warna_header',
        'urutan',
    ];

    public function report()
    {
        return $this->belongsTo(UtilizationReport::class, 'utilization_report_id');
    }

    public function items()
    {
        return $this->hasMany(UtilizationItem::class)->orderBy('urutan');
    }

    public function summaries()
    {
        return $this->hasMany(UtilizationSummary::class)->orderBy('urutan');
    }
}
