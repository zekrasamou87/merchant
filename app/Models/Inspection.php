<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($inspection) {
            $store = $inspection->store;
            if ($store) {
                // تفعيل المحل فقط إذا لم تكن النتيجة "مخالف"
                $isActive = $inspection->result !== 'violating';
                
                $store->update([
                    'is_active' => $isActive,
                    'rating' => $inspection->rating_score ?? 0,
                    'status_start_date' => $isActive ? now() : $store->status_start_date,
                    'status_end_date' => $isActive ? now()->addMonths(3) : $store->status_end_date,
                ]);
            }
        });
    }

    public function store() { return $this->belongsTo(Store::class); }

    public function inspectors() {
        return $this->belongsToMany(Inspector::class, 'inspection_teams');
    }
}