<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilizationSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilization_section_id',
        'kategori',
        'inbound_value',
        'outbound_value',
        'urutan',
    ];

    public function section()
    {
        return $this->belongsTo(UtilizationSection::class, 'utilization_section_id');
    }
}
