<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_user')->insert([
            ['id' => 1,  'menu_id' => 1,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 2,  'menu_id' => 2,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 3,  'menu_id' => 1,  'user_id' => 5, 'created_at' => null, 'updated_at' => null],
            ['id' => 4,  'menu_id' => 2,  'user_id' => 5, 'created_at' => null, 'updated_at' => null],
            ['id' => 6,  'menu_id' => 1,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 7,  'menu_id' => 2,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 8,  'menu_id' => 3,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 9,  'menu_id' => 4,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'menu_id' => 5,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'menu_id' => 6,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'menu_id' => 3,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 15, 'menu_id' => 4,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 16, 'menu_id' => 5,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 17, 'menu_id' => 6,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 18, 'menu_id' => 8,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 19, 'menu_id' => 8,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'menu_id' => 1,  'user_id' => 6, 'created_at' => null, 'updated_at' => null],
            ['id' => 21, 'menu_id' => 8,  'user_id' => 6, 'created_at' => null, 'updated_at' => null],
            ['id' => 22, 'menu_id' => 5,  'user_id' => 6, 'created_at' => null, 'updated_at' => null],
            ['id' => 23, 'menu_id' => 9,  'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 24, 'menu_id' => 9,  'user_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 27, 'menu_id' => 10, 'user_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 28, 'menu_id' => 10, 'user_id' => 4, 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
