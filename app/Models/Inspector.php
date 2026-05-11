<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    protected $guarded = [];

    // المراقب يشارك في العديد من الزيارات عبر جدول الوسيط (inspection_teams)
    public function inspections()
    {
        return $this->belongsToMany(Inspection::class, 'inspection_teams');
    }
}