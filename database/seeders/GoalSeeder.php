<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('goals')->count() == 0){

                DB::table('goals')->insert([

                    [
                        'icon' => 'storage/goal_icon/portfolio.png',
                        'goal_number' => '1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/travel-bag.png',
                        'goal_number' => '2',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/dollar.png',
                        'goal_number' => '3',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/building.png',
                        'goal_number' => '4',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/men.png',
                        'goal_number' => '5',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/people_group.png',
                        'goal_number' => '6',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/hand_take.png',
                        'goal_number' => '7',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/hand_take_love.png',
                        'goal_number' => '8',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/group_circle.png',
                        'goal_number' => '9',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/find_mesage.png',
                        'goal_number' => '10',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/hand_point.png',
                        'goal_number' => '11',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/char_flow.png',
                        'goal_number' => '12',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'icon' => 'storage/goal_icon/chart.png',
                        'goal_number' => '13',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
                
            } else { echo "<br>[Goals Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
