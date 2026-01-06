<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
  protected $fillable = ['report_id','section','photo_path','caption'];
}

