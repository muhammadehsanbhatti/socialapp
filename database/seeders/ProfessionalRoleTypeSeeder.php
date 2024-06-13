<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalRoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            if (DB::table('prof_role_types')->count() == 0) {
                DB::table('prof_role_types')->insert([
                    [
                        'general_title_id' => 8,
                        'title' => 'bonds',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 8,
                        'title' => 'commodities/ real assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 8,
                        'title' => 'Real estate / infrastructure',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 8,
                        'title' => 'Private credit',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 8,
                        'title' => 'Private equities',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 8,
                        'title' => 'stock',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'bonds',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'commodities/ real assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'Real estate / infrastructure',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'Private credit',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'Private equities',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 9,
                        'title' => 'stock',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'bonds',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'commodities/ real assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'Real estate / infrastructure',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'Private credit',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'Private equities',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 10,
                        'title' => 'stock',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'bonds',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'commodities/ real assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'Real estate / infrastructure',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'Private credit',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'Private equities',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'general_title_id' => 11,
                        'title' => 'stock',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            } else {
                echo "<br>[Professional Role Type Table is not empty] ";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
