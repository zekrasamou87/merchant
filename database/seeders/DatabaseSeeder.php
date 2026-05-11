<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $latakia = \App\Models\Province::create(['name' => 'اللاذقية']);
    
    $latakia->districts()->createMany([
        ['name' => 'جبلة'],
        ['name' => 'القرداحة'],
        ['name' => 'الحفة'],
        ['name' => 'اللاذقية (المركز)'],
    ]);

    \App\Models\Province::create(['name' => 'طرطوس'])
        ->districts()->createMany([
            ['name' => 'بانياس'],
            ['name' => 'صافيتا'],
    
    ]);
    }
}
