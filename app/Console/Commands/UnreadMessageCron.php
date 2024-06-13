<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ConversationDeleteMessage;
use App\Models\Message;

class UnreadMessageCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unreadmessage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $data['users'] = User::getUser();

        foreach ($data['users'] as $key => $user_data) {
            
            $request_data = array();
            $message = array();
            
            $delete_conversation_messages = ConversationDeleteMessage::getConversationDeleteMessage([
                'user_id_from' => $user_data->id
            ]);
    
            $get_columns_id = $delete_conversation_messages->ToArray();
            $get_columns_id = array_column($get_columns_id, 'message_id');
            $request_data['conversation_message_ids'] =  implode(',', $get_columns_id);

            
            $request_data['latest_data'] = true;
            $request_data['user_id'] =  $user_data->id;
            $request_data['auth_user_id'] =  $user_data->id;
            $request_data['type'] =  'Archived';
            $request_data['count'] =  true;

            $total_archived_messages_count = Message::getMessage($request_data);
            
            $total_unread_messages_count = unread_message_count(['receiver_id' => $user_data->id, 'conversation_message_ids' => $request_data['conversation_message_ids']]);
            unset($request_data['type']);
            $total_unarchived_messages_count = Message::getMessage($request_data);

            User::saveUpdateUser([
                'update_id' => $user_data->id,
                'total_archived_messages_tmp' => isset($total_archived_messages_count[0]->total_count)? $total_archived_messages_count[0]->total_count:0,
                'total_unarchived_messages_tmp' => isset($total_unarchived_messages_count[0]->total_count)? $total_unarchived_messages_count[0]->total_count:0,
                'total_unread_messages_tmp' => $total_unread_messages_count,
            ]);

            // echo '<pre>user_id: '; print_r($user_data->id); echo '</pre>'; 
            // echo '<pre>total_archived_messages: '; print_r($total_archived_messages_count); echo '</pre>'; 
            // echo '<pre>total_unread_messages: '; print_r($total_unread_messages_count); echo '</pre>'; 
            // echo '<pre>total_unarchived_messages: '; print_r($total_unarchived_messages_count); echo '</pre>'; 
        }
        echo '<pre>user_id: '; print_r("cronjob works properly"); echo '</pre>'; exit;
       

        

    }
}