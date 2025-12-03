<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('model_has_permissions')->insert([
            ['permission_id' => 1,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 2,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 3,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 4,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 5,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 6,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 7,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 8,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 9,  'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 10, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 11, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 12, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 13, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 14, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 15, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 16, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 17, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 18, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 19, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 20, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 60, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 61, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 62, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['permission_id' => 63, 'model_type' => 'App\\Models\\User', 'model_id' => 2],

            ['permission_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 6, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 7, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['permission_id' => 8, 'model_type' => 'App\\Models\\User', 'model_id' => 4],

            ['permission_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            ['permission_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            ['permission_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            ['permission_id' => 6, 'model_type' => 'App\\Models\\User', 'model_id' => 5],

            ['permission_id' => 8,  'model_type' => 'App\\Models\\User', 'model_id' => 6],
            ['permission_id' => 18, 'model_type' => 'App\\Models\\User', 'model_id' => 6],
            ['permission_id' => 19, 'model_type' => 'App\\Models\\User', 'model_id' => 6],
        ]);
    }
}