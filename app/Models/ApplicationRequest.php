<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationRequest extends Model
{
    protected $guarded = [];

    // الطلب يتبع لمحل معين
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}