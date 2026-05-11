<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'ألبان وأجبان'],
            ['name' => 'خضروات وفواكه'],
            ['name' => 'لحوم ودواجن'],
            ['name' => 'سوبر ماركت'],
            ['name' => 'ألبسة وأحذية'],
            ['name' => 'محل حلاقة'],
            ['name' => 'مغاسل سيارات'],
            ['name' => 'حرفيين'],
            ['name' => 'إلكترونيات وموبايلات'],
            ['name' => 'صيدليات ومستلزمات طبية'],   
            ['name' => 'أدوات منزلية'],
            ['name' => 'مكتبات وقرطاسية'],
            ['name' => 'عطورات وإكسسوارات'],
            ['name' => 'خدمات'],
            
        ];

        foreach ($types as $type) {
            BusinessType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}