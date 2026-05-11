<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon; // نحتاجه للتعامل مع الوقت

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name', 'owner_name', 'manager_name', 
        'business_type_id', 'province_id', 'district_id', 
        'neighborhood', 'phone_number', 'license_number',
        'latitude', 'longitude', 'is_active', 'qr_code',
        'status_start_date', 'status_end_date', 'sale_type',
        'opening_time', 'closing_time', 
        'off_days',           // حقل أيام العطلة الجديد
        'is_always_open',     // حقل يعمل 24 ساعة
        'delivery_service', 'rating', 'notes'
    ];

    // تحويل الحقول تلقائياً عند جلب البيانات
    protected $casts = [
        'off_days' => 'array',       // سيحول النص في قاعدة البيانات إلى Array تلقائياً
        'is_always_open' => 'boolean',
        'is_active' => 'boolean',
        'delivery_service' => 'boolean',
    ];

    // --- خاصية ذكية لمعرفة حالة المحل الآن (مفتوح/مغلق) ---
    // يمكنك استدعاؤها في الـ API بـ $store->is_open_now
    public function getIsOpenNowAttribute()
    {
        $now = now(); // التوقيت الحالي
        $today = $now->format('l'); // اسم اليوم بالإنجليزية (e.g., Friday)

        // 1. إذا كان المحل يفتح دائماً
        if ($this->is_always_open) return true;

        // 2. إذا كان اليوم ضمن أيام العطلة
        if ($this->off_days && in_array($today, $this->off_days)) return false;

        // 3. التحقق من وقت الدوام
        if ($this->opening_time && $this->closing_time) {
            $open = Carbon::createFromTimeString($this->opening_time);
            $close = Carbon::createFromTimeString($this->closing_time);
            
            // في حال كان المحل يغلق بعد منتصف الليل
            if ($close->lessThan($open)) {
                $close->addDay();
            }

            return $now->between($open, $close);
        }

        return false;
    }

    protected static function booted()
    {
        static::creating(function ($store) {
            if (empty($store->qr_code)) {
                do {
                    $code = 'ST-' . strtoupper(Str::random(6));
                } while (static::where('qr_code', $code)->exists());
                $store->qr_code = $code;
            }
        });
    }

    // العلاقات (كما هي في كودك الأصلي)
    public function businessType() { return $this->belongsTo(BusinessType::class, 'business_type_id'); }
    public function provinceRelation() { return $this->belongsTo(Province::class, 'province_id'); }
    public function districtRelation() { return $this->belongsTo(District::class, 'district_id'); }
    public function inspections() { return $this->hasMany(Inspection::class, 'store_id'); }
    public function applicationRequest() { return $this->hasOne(ApplicationRequest::class, 'store_id'); }
}