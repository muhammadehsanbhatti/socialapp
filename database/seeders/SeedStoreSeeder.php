<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeedStore;
use DB;

class SeedStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('seed_stores')->count() == 0){

                DB::table('seed_stores')->insert([

                    [
                        'package_name' => 'Small Pack',
                        'price' => '50',
                        'seeds_count' => '500',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'package_name' => 'Medium Pack',
                        'price' => '100',
                        'seeds_count' => '1000',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'package_name' => 'Large Pack',
                        'price' => '500',
                        'seeds_count' => '5000',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]

                ]);
                
            } else { echo "<br>[Role Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
