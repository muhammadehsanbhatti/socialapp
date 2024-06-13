<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('goal_items')->count() == 0){

                DB::table('goal_items')->insert([

                    [
                        'goal_id' => '1',
                        'title' => 'Hire full-time employees',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '1',
                        'title' => 'Hire part-time employees',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '1',
                        'title' => 'Headhunt for employers',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '2',
                        'title' => 'Seek full-time job',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '2',
                        'title' => 'Seek part-time job',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '2',
                        'title' => 'Seek freelance gig',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '3',
                        'title' => 'Invest in deals / projects',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '3',
                        'title' => 'Invest in listed securities',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '3',
                        'title' => 'Invest in real estate real assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '4',
                        'title' => 'Acquire new companies / assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '4',
                        'title' => 'Dispose companies / assets',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '5',
                        'title' => 'Seek professional advice',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '5',
                        'title' => 'Offer professional advice',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '6',
                        'title' => 'Find co founder',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '6',
                        'title' => 'find business partnership / JV',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '7',
                        'title' => 'Source a product',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '7',
                        'title' => 'Sell a product',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '8',
                        'title' => 'Request a service',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '8',
                        'title' => 'Offer a service',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '9',
                        'title' => 'Collaborate on deal dyndication',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '9',
                        'title' => 'Collaborate on projects',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '10',
                        'title' => 'Find mentors',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '10',
                        'title' => 'Mentor others',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '11',
                        'title' => 'Exchange ideas',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '11',
                        'title' => 'Make new friends',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '12',
                        'title' => 'Grow my business',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '12',
                        'title' => 'Increase market share',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '12',
                        'title' => 'Increase profitability',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '13',
                        'title' => 'Improve customer satisfaction',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '13',
                        'title' => 'Increase productivity',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'goal_id' => '13',
                        'title' => 'Enhance innovation',
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]

                ]);
                
            } else { echo "<br>[Goals Items Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
