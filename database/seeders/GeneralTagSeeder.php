<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          try {
            if (DB::table('general_tags')->count() == 0) {
                DB::table('general_tags')->insert([
                    [
                        'title' => 'Bank',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Bank Loan',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Bank finances',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Bank Investor',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            } else {
                echo "<br>[General Tags Table is not empty] ";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}