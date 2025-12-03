<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id'         => 1,
                'name'       => 'admin',
                'guard_name' => 'web',
                'created_at' => '2025-11-11 04:05:17',
                'updated_at' => '2025-11-11 04:05:58',
            ],
            [
                'id'         => 3,
                'name'       => 'sales',
                'guard_name' => 'web',
                'created_at' => '2025-11-11 05:46:41',
                'updated_at' => '2025-11-11 05:46:41',
            ],
        ]);
    }
}