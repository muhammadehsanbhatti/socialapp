<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\NotificationMessage;
use DB;
use Exception;

class NotificationMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('notification_messages')->count() == 0){
                DB::table('notification_messages')->insert([

                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] send a new message!',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] added you to the group [group_name]',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] send a new request',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] accept your update request',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] remove you in connection',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => '[notification_title]',
                        'message' => '[user_name] create a new pitch',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                ]);
            } else { echo "<br>[Notification Message Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
