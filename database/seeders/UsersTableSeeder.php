<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'                => 1,
                'name'              => 'Sojib',
                'email'             => 'sajibhashi1@gmail.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$KxrLes3pVQHyANyp9duwf.D8zVuR9w0JSS7gOiHpgF8Z9dY2rwzdS',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-10 05:47:21',
                'updated_at'        => '2025-11-11 01:41:45',
            ],
            [
                'id'                => 2,
                'name'              => 'Admin',
                'email'             => 'admin@admin.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$jN79VwE50CkfDGAvtOML5eQ8lVzqvussAgRBYr7mn4hrDPOPZFJVG',
                'image'             => 'images/avatars/8OP3bPUPrDOqlaL32xqcsaoYPOXYs6adWqExMJSn.jpg',
                'remember_token'    => null,
                'created_at'        => '2025-11-10 05:59:47',
                'updated_at'        => '2025-11-15 23:00:27',
            ],
            [
                'id'                => 3,
                'name'              => 'Repudiandae quos vol',
                'email'             => 'rujycyvuqi@mailinator.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$OECMPdO/FPm8wfw6a0I6HezI/3EnOg5Xn4jGgVJc55YYHbUsZhQxW',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-10 06:53:04',
                'updated_at'        => '2025-11-10 06:53:04',
            ],
            [
                'id'                => 4,
                'name'              => 'Rajib',
                'email'             => 'rajib@gmail.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$lHNoQMJL74IsExvVwZdDTumyofN18iDTpMBEq0ZBxUg0Ccvds8T0a',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-11 02:35:01',
                'updated_at'        => '2025-11-15 03:56:13',
            ],
            [
                'id'                => 5,
                'name'              => 'Sojib',
                'email'             => 'sojib@gmai.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$2N/V0aC8RemwB4qV7GV0l.9d0XFqAuayjK/utuSRDvwBKSyojGJfS',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-12 03:53:35',
                'updated_at'        => '2025-11-13 05:56:37',
            ],
            [
                'id'                => 6,
                'name'              => 'emi',
                'email'             => 'fysozywyzi@mailinator.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$ZCutZtm1K6WIzYyuOTXDWuJiKcauNllR7pEDEtW0qjodq/jFxS34a',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-12 04:55:59',
                'updated_at'        => '2025-11-12 04:55:59',
            ],
            [
                'id'                => 9,
                'name'              => 'emi333',
                'email'             => 'fysozywyzi@ma3333ilinator.com',
                'email_verified_at' => null,
                'password'          => '$2y$12$g/iIiVWAn0E81SHN7H007OkZ3Xkvtxpso5k7oBJ1Jqg6VSuZHHinu',
                'image'             => null,
                'remember_token'    => null,
                'created_at'        => '2025-11-18 00:29:39',
                'updated_at'        => '2025-11-18 00:29:39',
            ],
        ]);
    }
}