<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. مسارات البيانات الأولية (تستخدم عند تشغيل التطبيق)
Route::get('/locations', [StoreController::class, 'getLocations']);
Route::get('/business-types', [StoreController::class, 'getBusinessTypes']);

// 2. مسارات التجار والمحلات
// جلب قائمة المبادرة (التجار الملتزمين) - هذا المسار الذي استدعيناه في الكنترولر المعدل
Route::get('/merchants/accepted', [StoreController::class, 'getAcceptedMerchants']);

// فحص QR Code (يجب أن يكون قبل /stores/{id} إذا وجد لتجنب التضارب)
Route::get('/stores/check/{qr_code}', [StoreController::class, 'checkQR']);

// الفلترة العامة (إذا كان التطبيق لا يزال يستخدمها)
Route::get('/stores/filter', [StoreController::class, 'filterStores']);

// جلب كل المحلات (اختياري حسب حاجتك)
Route::get('/stores', [StoreController::class, 'index']);

// 3. مسار الاختبار
Route::get('/test', function() {
    return response()->json(["message" => "API is working!"]);
});