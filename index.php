<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. تعديل مسار وضع الصيانة
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. تعديل مسار الـ Autoloader (يبقى كما هو إذا كان مجلد vendor بجانب الملف)
require __DIR__.'/vendor/autoload.php';

// 3. تعديل مسار الـ Bootstrap (حذفنا النقاط ليكون المسار في نفس المجلد)
(require_once __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());