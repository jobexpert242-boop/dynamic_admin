<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['id' => 1,  'name' => 'permission.show',   'guard_name' => 'web', 'created_at' => '2025-11-11 04:25:54', 'updated_at' => '2025-11-15 23:41:24'],
            ['id' => 2,  'name' => 'permission.create', 'guard_name' => 'web', 'created_at' => '2025-11-11 05:05:12', 'updated_at' => '2025-11-15 23:42:18'],
            ['id' => 3,  'name' => 'permission.update', 'guard_name' => 'web', 'created_at' => '2025-11-11 05:08:08', 'updated_at' => '2025-11-15 23:41:02'],
            ['id' => 4,  'name' => 'menu.create',       'guard_name' => 'web', 'created_at' => '2025-11-11 06:32:34', 'updated_at' => '2025-11-11 06:32:34'],
            ['id' => 5,  'name' => 'menu.update',       'guard_name' => 'web', 'created_at' => '2025-11-11 06:33:02', 'updated_at' => '2025-11-11 06:33:02'],
            ['id' => 6,  'name' => 'menu.delete',       'guard_name' => 'web', 'created_at' => '2025-11-11 06:33:09', 'updated_at' => '2025-11-11 06:33:09'],
            ['id' => 7,  'name' => 'menu.show',         'guard_name' => 'web', 'created_at' => '2025-11-13 01:50:47', 'updated_at' => '2025-11-13 01:50:47'],
            ['id' => 8,  'name' => 'dashboard.show',    'guard_name' => 'web', 'created_at' => '2025-11-15 03:55:06', 'updated_at' => '2025-11-15 03:55:06'],
            ['id' => 9,  'name' => 'role.show',         'guard_name' => 'web', 'created_at' => '2025-11-15 08:18:44', 'updated_at' => '2025-11-15 08:18:44'],
            ['id' => 10, 'name' => 'role.create',       'guard_name' => 'web', 'created_at' => '2025-11-15 08:19:59', 'updated_at' => '2025-11-15 23:42:27'],
            ['id' => 11, 'name' => 'role.delete',       'guard_name' => 'web', 'created_at' => '2025-11-15 08:20:11', 'updated_at' => '2025-11-15 08:20:11'],
            ['id' => 12, 'name' => 'role.update',       'guard_name' => 'web', 'created_at' => '2025-11-15 08:21:12', 'updated_at' => '2025-11-15 08:21:12'],
            ['id' => 13, 'name' => 'permission.delete', 'guard_name' => 'web', 'created_at' => '2025-11-15 23:41:32', 'updated_at' => '2025-11-15 23:41:32'],
            ['id' => 14, 'name' => 'user.show',         'guard_name' => 'web', 'created_at' => '2025-11-15 23:47:02', 'updated_at' => '2025-11-16 00:09:12'],
            ['id' => 15, 'name' => 'user.create',       'guard_name' => 'web', 'created_at' => '2025-11-15 23:52:21', 'updated_at' => '2025-11-16 00:09:06'],
            ['id' => 16, 'name' => 'user.update',       'guard_name' => 'web', 'created_at' => '2025-11-15 23:54:51', 'updated_at' => '2025-11-16 00:08:59'],
            ['id' => 17, 'name' => 'user.delete',       'guard_name' => 'web', 'created_at' => '2025-11-15 23:54:57', 'updated_at' => '2025-11-16 00:08:50'],
            ['id' => 18, 'name' => 'setting.show',      'guard_name' => 'web', 'created_at' => '2025-11-16 00:07:52', 'updated_at' => '2025-11-16 00:07:52'],
            ['id' => 19, 'name' => 'docs.show',         'guard_name' => 'web', 'created_at' => '2025-11-16 00:15:56', 'updated_at' => '2025-11-16 00:15:56'],
            ['id' => 20, 'name' => 'show.sitesetting',  'guard_name' => 'web', 'created_at' => '2025-11-16 04:29:59', 'updated_at' => '2025-11-16 04:29:59'],
            ['id' => 60, 'name' => 'grapjs.show',       'guard_name' => 'web', 'created_at' => '2025-11-18 05:29:43', 'updated_at' => '2025-11-18 05:29:43'],
            ['id' => 61, 'name' => 'grapjs.edit',       'guard_name' => 'web', 'created_at' => '2025-11-18 05:29:58', 'updated_at' => '2025-11-18 05:29:58'],
            ['id' => 62, 'name' => 'grapjs.create',     'guard_name' => 'web', 'created_at' => '2025-11-18 05:30:12', 'updated_at' => '2025-11-18 05:30:12'],
            ['id' => 63, 'name' => 'grapjs.delete',     'guard_name' => 'web', 'created_at' => '2025-11-18 05:30:22', 'updated_at' => '2025-11-18 05:30:22'],
        ]);
    }
}