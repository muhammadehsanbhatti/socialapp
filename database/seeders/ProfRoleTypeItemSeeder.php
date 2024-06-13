<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfRoleTypeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            if (DB::table('pro_role_type_items')->count() == 0) {
                DB::table('pro_role_type_items')->insert([
                    [
                        'pro_role_type_id' => 2,
                        'title' => 'Developed Market',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'pro_role_type_id' => 2,
                        'title' => 'Emerging Market',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'pro_role_type_id' => 3,
                        'title' => 'Build With Infrastructure',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'pro_role_type_id' => 3,
                        'title' => 'Provide Guidline',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            } else {
                echo "<br>[Professional Role Type Item Table is not empty] ";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
