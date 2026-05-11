<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\BusinessType;
use App\Models\Store;

class StoreController extends Controller
{

// جلب كل المحلات مع علاقاتها
public function index()
{
    // نستخدم نفس أسماء العلاقات التي ثبتناها في الموديل
    $stores = Store::with(['businessType', 'provinceRelation', 'districtRelation'])->get();

    return response()->json([
        'status' => 'success',
        'data' => $stores
    ]);
}
    // جلب المحافظات (متوافق مع Flutter)
    public function getLocations() {
        // الجداول: provinces, districts
        $provinces = Province::with('districts:id,province_id,name')->get(['id', 'name']);
        return response()->json($provinces);
    }

    // جلب الأصناف
    public function getBusinessTypes() {
        // الجدول: business_types
        $types = BusinessType::all(['id', 'name', 'icon']);
        return response()->json($types);
    }

    // جلب التجار الملتزمين (المبادرة) مع الفلترة
    public function getAcceptedMerchants(Request $request)
    {
        // الاستعلام من جدول stores مع التحقق من نتائج التفتيش
        $query = Store::query()
            // التعديل الجوهري: البحث عن حالة الالتزام في آخر تفتيش
            ->whereHas('inspections', function ($q) {
                $q->where('result', 'committed');
            })
            // جلب العلاقات للتأكد من عرض الأسماء (اللاذقية، جبلة، نوع العمل)
            ->with(['businessType', 'provinceRelation', 'districtRelation']);

        // فلترة المحافظة (اللاذقية مثلاً ID=1)
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // فلترة المنطقة (جبلة مثلاً ID=1)
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        // فلترة نوع العمل (ألبسة وأحذية مثلاً ID=6)
        if ($request->filled('business_type_id')) {
            $query->where('business_type_id', $request->business_type_id);
        }

        // استخدام الترقيم ليتناسب مع تطبيق Flutter
        $merchants = $query->paginate(15); 

        return response()->json([
            'status' => 'success',
            'data' => $merchants
        ]);
    }

    // جلب تفاصيل المتجر عبر QR
    public function checkQR($qr_code)
    {
        // البحث في جدول stores باستخدام العمود qr_code
        $store = Store::with(['businessType', 'provinceRelation', 'districtRelation', 'inspections'])
                      ->where('qr_code', $qr_code)
                      ->first();

        if (!$store) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'المحل غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $store
        ]);
    }
}