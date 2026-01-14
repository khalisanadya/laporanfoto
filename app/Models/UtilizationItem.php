<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilizationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilization_section_id',
        'nama_interface',
        'label',
        'inbound_current',
        'inbound_average',
        'inbound_maximum',
        'outbound_current',
        'outbound_average',
        'outbound_maximum',
        'inbound_value',
        'outbound_value',
        'gambar_graph',
        'urutan',
    ];

    public function section()
    {
        return $this->belongsTo(UtilizationSection::class, 'utilization_section_id');
    }
}
