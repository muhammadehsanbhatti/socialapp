<?php
use App\Models\User;
use Laravel\Ui\Presets\Vue;

if (! function_exists('send_notification')) {
    function send_notification($posted_data = array(), $check_setting = false) {

    //    echo '<pre>'; print_r($posted_data['receiver_id']['user_id']); echo '</pre>'; exit;
        $notificationMessageObj = new \App\Models\NotificationMessage;
        $notificationObj = new \App\Models\Notification;
        $shortCodesObj = new \App\Models\ShortCode;
        $userObj = new \App\Models\User;
        $GroupObj = new \App\Models\Group;
        $fcmTookenObj = new \App\Models\FCM_Token;
        $messageStatusObj = new \App\Models\MessageStatus;
        $GroupMemberObj = new \App\Models\GroupMember;
        $SettingObj = new \App\Models\Setting;

        if($check_setting && isset($posted_data['receiver_id'])){
            $check_notification = array();
            $check_notification['user_id'] = $posted_data['receiver_id'];
            $check_notification['detail'] = true;
            if($check_setting == 'pitch_notification_check'){
                $check_notification['pitch_notification'] = 'Check';
            }

            if($check_setting == 'message_notification_check'){
                $check_notification['message_notification'] = 'Check';
            }
            $check_setting = $SettingObj->getSetting($check_notification);
            if (!$check_setting) {
                return 0;
            }
        }

        if(!isset($posted_data['is_group']) && !isset($posted_data['event_name'])  && !isset($posted_data['update_connection_event'])){
            $user_is_blocked = $messageStatusObj->getMessageStatus([
                'block_user_id_to' => $posted_data['user_id'],
                'block_user_id_from' => $posted_data['receiver_id'],
                'type' => 'Block',
                'detail' => true
            ]);
            if($user_is_blocked){
                return true;
            }
        }


        $get_notificaton_message =  $notificationMessageObj->getNotificationMessage(['id' => $posted_data['notification_message_id'], 'detail' => true]);
        $userDetail = $userObj->getUser(['id' => $posted_data['user_id'],'detail'=>true]);
        if(isset($posted_data['group_id'])){
            $groupDetail = $GroupObj->getGroup(['id' => $posted_data['group_id'],'detail'=>true]);
        }

        if ($get_notificaton_message) {
            $notification_title = $get_notificaton_message->title;
            $notification_message = $get_notificaton_message->message;

            $notification_params = array();
            $notification_params['sender'] = $posted_data['user_id'];
            if(isset($posted_data['receiver_id'])){
                $notification_params['receiver'] = $posted_data['receiver_id'];
            }
            $notification_params['notification_text'] = $notification_message;
            $notification_params['metadata'] = isset($posted_data['metadata'])? json_encode($posted_data['metadata']):"";
            if(isset($posted_data['json_metadata'])){
                $notification_params['metadata'] = $posted_data['json_metadata'];
            }


            $all_codes = $shortCodesObj->getEmailShortCode();

            foreach ($all_codes as $key => $code ) {
                $ss_search = $code['title'];

                if ($code['title'] == '[user_name]') {
                    $ss_replace = $userDetail ? ucwords($userDetail->first_name.' '.$userDetail->last_name) : $ss_search;
                }
                elseif($code['title'] == '[group_name]') {
                    $ss_replace = isset($groupDetail) && $groupDetail ? ucwords($groupDetail->name) : $ss_search;
                }
                elseif($code['title'] == '[notification_title]') {
                    $ss_replace = isset($groupDetail) && $groupDetail ? ucwords($groupDetail->name) : 'Jugu';
                }
                if(isset($ss_search) && isset($ss_replace)){
                    $notification_title = stripcslashes(str_replace($ss_search, $ss_replace, $notification_title));
                    $notification_message = stripcslashes(str_replace($ss_search, $ss_replace, $notification_message));
                }
            }
            // if(isset($posted_data['member_ids'])){
            //     $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id_in' => $posted_data['member_ids']])->toArray();
            // }else{
            //     $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
            // }

            if(isset($notification_params['receiver'])){
                $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
                $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');
            }

            $notification_params['metadata'] = json_decode($notification_params['metadata']);
            $notification_params['metadata']->is_archived = false;

            if (isset($posted_data['receiver_id']) && $posted_data['receiver_id'] > 0 && !isset($posted_data['group_id'])) {
                $user_archived_status = $messageStatusObj->getMessageStatus([
                    'block_user_id_to' => $posted_data['user_id'],
                    'block_user_id_from' => $posted_data['receiver_id'],
                    'type' => 'Archived',
                    'detail' => true
                ]);
                if($user_archived_status){
                    $notification_params['metadata']->is_archived = true;
                }
            }else if (isset($posted_data['group_id']) && $posted_data['group_id']>0) {
                // $member_archived_status = $GroupMemberObj->getGroupMember([
                //     'user_id' => $posted_data['receiver_id'],
                //     'group_id' => $posted_data['group_id'],
                //     'type' => 'Archived',
                //     // 'is_delete' => 'False',
                //     'orderBy_name' => 'group_members.id',
                //     'orderBy_value' => 'desc',
                //     'detail' =>true
                // ]);
                // if($member_archived_status){
                //     $notification_params['metadata']->is_archived = true;
                // }

            }
            $is_archived_value = $notification_params['metadata']->is_archived;
            // $notification_params['metadata'] = json_encode($notification_params['metadata']);
            // echo '<pre>'; print_r($notification_params['metadata']); echo '</pre>'; exit;

            if(isset($posted_data['receiver_id'])){
                $notification_params['metadata'] = json_encode($notification_params['metadata']);
                $response = $notificationObj->saveUpdateNotification([
                    'sender_id' => $notification_params['sender'],
                    'receiver_id' => $notification_params['receiver'],
                    'notification_message_id' => $get_notificaton_message->id,
                    'notification_text' => $notification_message,
                    'meta_data' => $notification_params['metadata']
                ]);
                if (isset($posted_data['event_name']) && $posted_data['event_name'] == 'connection_request') {
                    event (new \App\Events\ConnectionRequestEvent($notification_params['metadata'], $posted_data['receiver_id']));
                }
                else if (isset($posted_data['new_pitch_event']) && $posted_data['new_pitch_event'] == 'pitch_request') {
                    event (new \App\Events\NewPitchEvent($notification_params['metadata'], $posted_data['receiver_id']));
                }
                else if(isset($posted_data['update_connection_event']) && $posted_data['update_connection_event'] == 'update_connection_request'){
                    event (new \App\Events\ConnectionUpdateRequestEvent($notification_params['metadata'], $posted_data['receiver_id']));
                }
                else{
                    event (new \App\Events\ChatMessageEvent($notification_params['metadata'], $posted_data['receiver_id']));
                }
                $notification_params['metadata'] = json_decode($notification_params['metadata']);
                if($is_archived_value){
                    $response = false;
                }
            }else{
                $notification_params['registration_ids'] = array();
                $notification_group_data = array();
                $registration_user_ids = array();

                $member_archived_status = $GroupMemberObj->getGroupMember([
                    // 'user_id_in' => $posted_data['member_ids'],
                    'group_id' => $posted_data['group_id'],
                    'type' => 'Archived',
                    'orderBy_name' => 'group_members.id',
                    'orderBy_value' => 'asc',
                    'check_member_status' => true
                ])->ToArray();
                $get_member_ids = array_column($member_archived_status,'user_id');

                // echo '<pre>'; print_r($get_member_ids); echo '</pre>'; exit;


                // for ($i=0; $i < count($get_member_ids); $i++) {
                for ($i=0; $i < count($posted_data['member_ids']); $i++) {

                    $notification_params['metadata']->is_archived = false;

                    // $member_archived_status = $GroupMemberObj->getGroupMember([
                    //     'group_id' => $posted_data['group_id'],
                    //     'detail' =>true
                    // ]);
                    // echo '<pre>'; print_r($member_archived_status['user_id']); echo '</pre>';
                    if(in_array($posted_data['member_ids'][$i],$get_member_ids)){

                        // echo '<pre>'; print_r("sdfsadf"); echo '</pre>';
                        $notification_params['metadata']->is_archived = true;
                    }
                    else{
                        $registration_user_ids[] = $posted_data['member_ids'][$i];
                        // $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id' => $posted_data['member_ids'][$i]])->toArray();
                        // $merge_ary = array_column($firebase_devices, 'device_token');
                        // $notification_params['registration_ids'] = array_merge($merge_ary, $notification_params['registration_ids']);
                    }


                    // $member_archived_status = $GroupMemberObj->getGroupMember([
                    //     'user_id' => $posted_data['member_ids'][$i],
                    //     'group_id' => $posted_data['group_id'],
                    //     'type' => 'Archived',
                    //     // 'is_delete' => 'False',
                    //     'orderBy_name' => 'group_members.id',
                    //     'orderBy_value' => 'desc',
                    //     'detail' =>true
                    // ]);
                    // if($member_archived_status){
                    //     $notification_params['metadata']->is_archived = true;
                    // }else{
                    //     $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id' => $posted_data['member_ids'][$i]])->toArray();
                    //     $merge_ary = array_column($firebase_devices, 'device_token');
                    //     $notification_params['registration_ids'] = array_merge($merge_ary, $notification_params['registration_ids']);
                    // }
                    $notification_params['metadata'] = json_encode($notification_params['metadata']);

                    $posted_data_array = array();
                    $posted_data_array['sender_id'] = $notification_params['sender'];
                    $posted_data_array['receiver_id'] = $posted_data['member_ids'][$i];
                    $posted_data_array['notification_message_id'] =$get_notificaton_message->id;
                    $posted_data_array['notification_text'] = $notification_message;
                    $posted_data_array['meta_data'] = $notification_params['metadata'];

                    $notification_group_data[] = $posted_data_array;
                    // $response = $notificationObj->saveUpdateNotification([
                    //     'sender_id' => $notification_params['sender'],
                    //     'receiver_id' => $posted_data['member_ids'][$i],
                    //     'notification_message_id' => $get_notificaton_message->id,
                    //     'notification_text' => $notification_message,
                    //     'meta_data' => $notification_params['metadata']
                    // ]);
                    event (new \App\Events\ChatMessageEvent($notification_params['metadata'], $posted_data['member_ids'][$i]));
                    $notification_params['metadata'] = json_decode($notification_params['metadata']);
                }

                // echo '<pre>'; print_r($registration_user_ids); echo '</pre>'; exit;
                $response = $notificationObj->saveUpdateNotification($notification_group_data,true);

                $firebase_devices = $fcmTookenObj->getFCM_Tokens(['user_id_in' => $registration_user_ids])->toArray();
                $merge_ary = array_column($firebase_devices, 'device_token');
                $notification_params['registration_ids'] = array_merge($merge_ary, $notification_params['registration_ids']);
            }
        //    echo '<pre>response: '; print_r($response); echo '</pre>';
        //    echo '<pre>userDetail: '; print_r($userDetail); echo '</pre>'; exit;
            if ($response && $userDetail) {

                // echo '<pre>response: '; print_r($response); echo '</pre>';
                // echo '<pre>userDetail: '; print_r($userDetail); echo '</pre>'; exit;
                // if (isset($model_response['user']))
                //     unset($model_response['user']);
                // if (isset($model_response['post']))
                //     unset($model_response['post']);
                // if ($is_archived_value == false) {

                    $notification_params['metadata'] = json_encode($notification_params['metadata']);
                    $notification = $fcmTookenObj->sendFCM_Notification([
                        'title' => $notification_title,
                        'body' => $notification_message,
                        'receiver_id' => isset($notification_params['receiver'])? $notification_params['receiver']:0,
                        'metadata' => $notification_params['metadata'],
                        'registration_ids' => $notification_params['registration_ids'],
                        'details' => []
                    ]);
                // }

                // event (new \App\Events\ChatMessageEvent($notification_params['metadata'], $posted_data['receiver_id']));
            }

        }

    }
}


if (! function_exists('compareSettings')) {
    function compareSettings($userSettings, $authSettings) {

        foreach ($userSettings as $userSetting) {

            foreach ($authSettings as $authSetting) {

                if ($userSetting == $authSetting) {
                    // echo '<pre>'; print_r("SDfasdf"); echo '</pre>'; exit;
                    return true;
                }
            }
        }
        return false;
    }
}


if (! function_exists('user_gender')) {
    function user_gender($userGender) {

        if ($userGender == 'Male') {
            $userGender = 1;
        }
        if ($userGender == 'Female') {
            $userGender = 2;
        }
        if ($userGender == 'Other') {
            $userGender = 3;
        }
        return $userGender;

    }
}


if (! function_exists('calculateAge')) {
        function calculateAge($dob) {
        $dob = new DateTime($dob);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
        return $age;
    }
}

if (! function_exists('unread_message_count')) {
    function unread_message_count($params_data =array()) {

        $ConversationDeleteMessageObj = new \App\Models\ConversationDeleteMessage;
        $MessageStatusObj = new \App\Models\MessageStatus;
        $GroupMemberObj = new \App\Models\GroupMember;
        $GroupObj = new \App\Models\Group;
        $MessageObj = new \App\Models\Message;
        $ReadMessageObj = new \App\Models\ReadMessage;

        $merge_ary = array();
        $request_data = array();
        $requested_data = array();
        $unread_group_message_ids = array();

        if (isset($params_data['receiver_id'])) {
             $getuser_id =   $params_data['receiver_id'];
        }else{
            $getuser_id =  \Auth::user()->id;
        }
        if (!isset($params_data['conversation_message_ids'])) {
            $delete_conversation_messages = $ConversationDeleteMessageObj->getConversationDeleteMessage([
                'user_id_from' => $getuser_id
            ]);
            $get_columns_id = $delete_conversation_messages->ToArray();
            $get_columns_id = array_column($get_columns_id, 'message_id');
            $request_data['conversation_message_ids'] =  implode(',', $get_columns_id);
        }else{
            $request_data['conversation_message_ids'] =  $params_data['conversation_message_ids'];
        }

        $total_messages = 0;
        if(!isset($params_data['exect_group_id'])){
            if(isset($params_data['exect_sender_id']) && isset($params_data['exect_receiver_id'])){
                $requested_data['exect_sender_id'] =  $params_data['exect_sender_id'];
                $requested_data['exect_receiver_id'] =  $params_data['exect_receiver_id'];
            }else{
                $requested_data['receiver_id'] =  $getuser_id ;
            }
            $requested_data['orderBy_name'] = 'messages.id';
            $requested_data['orderBy_value'] = 'DESC';
            $requested_data['where_null_message_status'] = true;
            $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);

            if(isset($params_data['search'])){
                $requested_data['search'] = $params_data['search'];
            }
            $requested_data['for_me_and_null'] = true;
            // $requested_data['printsql'] = true;
            if(!\Auth::check()){
                $requested_data['auth_user_id'] = $getuser_id;
            }

            $c_message = $MessageObj->getMessage($requested_data);
            // echo '<pre>'; print_r(count($c_message)); echo '</pre>'; exit;

            $unread_message_ids = $c_message->ToArray();
            $unread_message_ids = array_column($unread_message_ids, 'id');
            $merge_ary = $unread_message_ids;
            $total_messages = count($c_message);
        }


        if(isset($params_data['exect_sender_id']) && isset($params_data['exect_receiver_id'])){
        }else{
            $group_member_req = array();
            $group_member_req['user_id'] = $getuser_id;
            if(isset($params_data['exect_group_id'])){
                $group_member_req['group_id'] =$params_data['exect_group_id'];
            }
            $group_member_req['userData'] = true;
            $group_member_req['type'] = 'Normal';
            // $group_member_req['check_member_status'] = true;
            $group_member_req['groupBy'] = 'group_members.group_id';
            $check_group_member_status = $GroupMemberObj->getGroupMember($group_member_req);
            // echo '<pre>'; print_r($check_group_member_status->ToArray()); echo '</pre>'; exit;
            // $check_group_member_status_ids = $check_group_member_status->ToArray();
            // $check_group_member_status_ids = array_column($check_group_member_status_ids, 'group_id');
            // $check_group_member_status_ids = array_values(array_unique($check_group_member_status_ids));

            // echo '<pre>'; print_r($check_group_member_status_ids); echo '</pre>'; exit;
            // $groupData = $GroupObj->getGroup([
            //     'ids' => $check_group_member_status_ids
            // ]);
            // echo '<pre>'; print_r($groupData->ToArray()); echo '</pre>'; exit;

            // if(isset($groupData)){
            //     foreach ($groupData as $key => $value) {
            //         $group_id = $value->id;
            // if(isset($check_group_member_status_ids) && count($check_group_member_status_ids)>0){
            //     foreach ($check_group_member_status_ids as $key => $group_id) {

            if(isset($check_group_member_status)){
                foreach ($check_group_member_status as $key => $value) {
                    $group_id = $value->group_id;
                    $requested_data = array();
                    $requested_data['group_id'] = $group_id;
                    $requested_data['sender_id_not'] = $getuser_id;
                    $requested_data['orderBy_name'] = 'messages.id';
                    $requested_data['orderBy_value'] = 'DESC';
                    $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);

                    // $check_group_member_status = $GroupMemberObj->getGroupMember([
                    //     'user_id' => $getuser_id,
                    //     'group_id' => $group_id,
                    //     'type' => 'Normal',
                    //     'detail' => true,
                    // ]);
                    // // $check_group_member_status =false;
                    // if($check_group_member_status){

                        // if($check_group_member_status && $check_group_member_status->member_last_message_id>0){
                        //     $requested_data['last_seen_message_id'] = $check_group_member_status->member_last_message_id;
                        // }
                        if(isset($value->member_last_message_id) && $value->member_last_message_id && $value->member_last_message_id>0){
                            $requested_data['last_seen_message_id'] = $value->member_last_message_id;
                        }
                        if(isset($value->member_start_message_id) && $value->member_start_message_id && $value->member_start_message_id>0){
                            $requested_data['member_start_message_id'] = $value->member_start_message_id;
                        }

                        $requested_data['for_me'] = true;
                        // $requested_data['printsql'] = true;

                        if(!\Auth::check()){
                            $requested_data['auth_user_id'] = $getuser_id;
                        }
                        $c_message = $MessageObj->getMessage($requested_data);
                        if(count($c_message)>0){
                            $c_message = $c_message->ToArray();
                            $unread_group_message_ids = array_column($c_message, 'id');
                            // echo '<pre>'; print_r($unread_group_message_ids); echo '</pre>';
                            // $commsa_separated = implode(',',$unread_group_message_ids);
                            // echo '<pre>'; print_r($commsa_separated); echo '</pre>';
                            // echo '<pre>before: '; print_r(implode(',',$merge_ary)); echo '</pre>';
                            // echo '<br><pre>after: '; print_r($commsa_separated); echo '</pre>';
                            $merge_ary = array_merge($merge_ary, $unread_group_message_ids);
                            // $total_messages = $total_messages + count($unread_group_message_ids);
                            $total_messages = $total_messages + count($c_message);
                        }

                        // echo '<pre>'; print_r($total_messages); echo '</pre>'; exit;
                        // echo '<pre>'; print_r(count($unread_group_message_ids)); echo '</pre>';
                        // $merge_ary = array_merge($merge_ary, $unread_group_message_ids);
                    // }

                }
            }

        }

        // echo '<pre>'; print_r(implode(',',$merge_ary)); echo '</pre>';
        // echo '<pre>'; print_r($merge_ary); echo '</pre>'; exit;
        $read_message_count = $ReadMessageObj->getReadMessage([
            'conversation_message_ids_ary' =>  explode(',', $request_data['conversation_message_ids']),
            'user_id' => $getuser_id,
            'message_ids' => $merge_ary,
            'read_message_status' => 'Read',
            // 'count' => true,
            'groupBy' => 'read_messages.message_id'
            // ,'printsql' => true
        ])->ToArray();

        // $get_messae = array_column($read_message_count, 'message_id');
        // echo '<pre>'; print_r(implode(',',$get_messae)); echo '</pre>';

        $read_message_count = count($read_message_count);
        // $total_unread_messages = count($merge_ary) - $read_message_count;
        $total_unread_messages = $total_messages - $read_message_count;


        // echo '<pre> total_messages:'; print_r($total_messages); echo '</pre>';
        // echo '<pre> read_message_count: '; print_r($read_message_count); echo '</pre>';
        // echo '<pre>total_unread_messages: '; print_r($total_unread_messages); echo '</pre>';

        return $total_unread_messages;
    }
}

if (! function_exists('is_image_exist')) {
    function is_image_exist($image_path = '', $type = "image", $is_public_path = false, $inStorage = true) {

        $default_asset = ($type == "image") ? 'default-image.png' : 'default-profile-image.png';

        $local_paths_url = array('127.0.0.1', '::1');
        if(in_array($_SERVER['REMOTE_ADDR'], $local_paths_url))
            $base_url = url('/');
        else
            $base_url = url('/').'/public';

        $asset_url = $base_url.'/'.$image_path;

        $storagePath = '';
        if($inStorage){
            $storagePath = 'storage/';
        }


        if($image_path == '' || is_null($image_path)){
            return $asset_url;
        }else if($is_public_path && (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$image_path) || file_exists(public_path().'/'.$image_path))){
            return $base_url.'/'.$image_path;
        }else if(!$is_public_path && (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$storagePath.''.$image_path) || file_exists(public_path().'/'.$storagePath.''.$image_path))){
            return $base_url.'/'.$storagePath.''.$image_path;
        }else{
            return $asset_url;
        }
    }
}


if (! function_exists('upload_files_to_storage')) {
    function upload_files_to_storage($request, $file_param, $path)
    {
        $response = array();

        $file_name = time().'_'.$file_param->getClientOriginalName();
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $file_request = $file_param->storeAs($path, $file_name, ['disk' => 'public']);
        // $file_path = 'storage/'.$path.'/'.$file_name;
        $file_path = $path.'/'.$file_name;

        if( $file_param->isValid() )
            return $response = array(
                'action'        => true,
                'message'       => 'Requested file is uploaded successfully.',
                'file_name'     => $file_name,
                'file_path'     => $file_path
            );
        else
            return $response = array(
                'action'        => false,
                'message'       => 'Something went wrong during uploading.'
            );
    }
}

if (! function_exists('delete_files_from_storage')) {
    function delete_files_from_storage($file)
    {
        if( $file != "" ) {
            // File::delete(public_path('upload/bio.png'));
            $process = File::delete(public_path('storage').'/'.$file);
            // $process = File::delete(storage_path().'/'.$file);

            if ( $process )
                return $response = array('action' => true, 'message'   => 'Requested file is delete successfully.');
            else
                return $response = array('action' => false, 'message'   => 'Requested file is not exist.', 'file' => public_path('storage').'/'.$file);
        }
        else
            return $response = array('action' => false, 'message'   => 'There is no file available to delete.');
    }
}

if (! function_exists('isApiRequest')) {
    function isApiRequest($request)
    {
        $isApiRequest = false;
        if( $request->is('api/*')){
            $isApiRequest = true;
        }
        return $isApiRequest;
    }
}

if (! function_exists('array_flatten')) {
    function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}

if (! function_exists('multidimentional_array_flatten')) {
    function multidimentional_array_flatten($array, $key) {
        $unique_ids = array_unique(array_map(
            function ($i) use ($key) {
                return $i[$key];
            }, $array)
        );

        return $unique_ids;
    }
}

if (! function_exists('swap_array_indexes')) {
    function swap_array_indexes($data_sources, $sort_key, $current_val, $new_val) {
        $status = false;
        $arr_size = count($data_sources);

        if ($arr_size <= 1) {
            $data_sources['status'] = true;
            return $data_sources;
        }

        $mode = "swap";

        // means user want to delete an item from the chain
        if ( $new_val == 0) {
            $new_val = $arr_size;
            $mode = "delete";
        }

        foreach ($data_sources as $key => $value) {
            if ($current_val < $new_val) {
                if ( $current_val <= $value[$sort_key] && $new_val >= $value[$sort_key] ) {
                    $data_sources[$key][$sort_key] = --$value[$sort_key];
                }
            }
            else if ($current_val > $new_val) {
                if ( $current_val >= $value[$sort_key] && $new_val <= $value[$sort_key] ) {
                    $data_sources[$key][$sort_key] = ++$value[$sort_key];
                }
            }
            $status = true;
        }
        $data_sources[$current_val-1][$sort_key] = $new_val;

        if ($mode == "delete") {
            $status = true;
            unset($data_sources[$current_val-1]);
        }

        $data_sources['status'] = $status;
        return $data_sources;
    }
}

if (! function_exists('split_metadata_strings')) {
    function split_metadata_strings($string = "") {
        $final_result = array();

        foreach (explode('&', $string) as $piece) {
            $result = array();
            $result = explode('=', $piece);
            $final_result[$result[0]] = $result[1];
        }

        return $final_result;
    }
}

if (! function_exists('updateTimeSpent')) {
    function updateTimeSpent()
    {

        $last_seen = date("Y-m-d H:i:s");
        $login = Auth::user()->last_seen;
        if( Auth::user()->last_seen == NULL ) {
            $login = date("Y-m-d H:i:s");
        }
        $logout = date("Y-m-d H:i:s");
        // $login = '2022-01-28 20:38:20';
        // $logout = '2022-01-28 21:48:35';
        $time_spent = round(abs(strtotime($login) - strtotime($logout)) / 3600, 2);
        $time_spent = $time_spent + Auth::user()->time_spent;

        // echo '<pre>';
        // print_r($time_spent);
        // exit;
        // $UserObj = new User();
        $user = User::find(Auth::user()->id);
        $user->time_spent = $time_spent;
        $user->last_seen = $last_seen;
        $user->update();
        return true;

    }
}

if (! function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if (! function_exists('decodeShortCodesTemplate')) {
        function decodeShortCodesTemplate($posted_data = array()) {

        // $email_subject = isset($posted_data['subject']) ? $posted_data['subject'] : '';
        // $email_body = isset($posted_data['body']) ? $posted_data['body'] : '';
        // $email_message_id = isset($posted_data['email_message_id']) ? $posted_data['email_message_id'] : 0;
        // $user_id = isset($posted_data['user_id']) ? $posted_data['user_id'] : 0;
        // $sender_id = isset($posted_data['sender_id']) ? $posted_data['sender_id'] : 0;
        // $receiver_id = isset($posted_data['receiver_id']) ? $posted_data['receiver_id'] : 0;
        // $new_password = isset($posted_data['new_password']) ? $posted_data['new_password'] : '[Something went wrong with server. Please request again]';
        // $verification_code = isset($posted_data['email_verification_url']) ? $posted_data['email_verification_url'] : '[Something went wrong with server. Please request again]';

        $EmailTemplateObj = new \App\Models\EmailTemplate;
        $ShortCodesObj = new \App\Models\ShortCode;
        $OrderObj = new \App\Models\Order;
        $InvoiceObj = new \App\Models\Invoice;

        $received_payment =  $received_payment_via_card = 0;
        $message_id = isset($posted_data['message_id']) ? $posted_data['message_id'] : 0;
        $order_id = isset($posted_data['order_id']) ? $posted_data['order_id'] : 0;
        $invoice_id = isset($posted_data['invoice_id']) ? $posted_data['invoice_id'] : 0;
        $order_data = $OrderObj->getOrders(['id' => $order_id, 'detail' => true]);
        $invoice_data = $InvoiceObj->getInvoice(['id' => $invoice_id, 'detail' => true]);

        $emailMessageDetail = $EmailTemplateObj->getEmailTemplates([
            'detail' => true,
            'id' => $message_id,
        ]);
        if(!$emailMessageDetail){
            return $response = [
                'email_subject' => false,
                'email_body' => false
            ];
        }

        $email_subject = $emailMessageDetail->subject;
        $email_body = $emailMessageDetail->message;

        if (isset($invoice_data->invoice_step) && ($invoice_data->invoice_step == 1))  {
            $received_payment = ($order_data->proposal_cost * 30) / 100;
            $received_payment_via_card = (($received_payment * 1.5) / 100) + $received_payment;
        }
        elseif (isset($invoice_data->invoice_step) && ($invoice_data->invoice_step == 2)) {
            $received_payment = ($order_data->proposal_cost * 40) / 100;
            $received_payment_via_card = (($received_payment * 1.5) / 100) + $received_payment;
            $invoice_file = @$order_data->invoice_file;
        }
        elseif (isset($invoice_data->invoice_step) && ($invoice_data->invoice_step == 3)) {
            $received_payment = ($order_data->proposal_cost * 20) / 100;
            $received_payment_via_card = (($received_payment * 1.5) / 100) + $received_payment;
        }
        elseif (isset($invoice_data->invoice_step) && ($invoice_data->invoice_step == 4)) {
            $received_payment = ($order_data->proposal_cost * 10) / 100;
            $received_payment_via_card = (($received_payment * 1.5) / 100) + $received_payment;
        }

        $all_codes = $ShortCodesObj->getEmailShortCode();
        foreach ($all_codes as $key => $code ) {

            if ($code['title'] == '[sales_date_time]') {
                $search = $code['title'];
                $replace = $order_data ? ucwords($order_data->survey_datetime) : $search;
            }
            else if ($code['title'] == '[user_name]') {
                $search = $code['title'];
                $user_name = @$order_data->receiverUser->first_name.' '. @$order_data->receiverUser->last_name;
                $replace = $order_data ? ucwords($user_name) : $search;
            }
            else if ($code['title'] == '[invoice_number]') {
                $search = $code['title'];
                $replace = $invoice_data ? ucwords($invoice_data->invoice_number) : $search;
            }
            else if ($code['title'] == '[invoice_preferred_model_pod]') {
                $search = $code['title'];
                $replace = $order_data ? ucwords($order_data->enquiryDetail->preferred_model_of_pod) : $search;
            }
            else if ($code['title'] == '[received_payment_with_discount]') {
                $search = $code['title'];
                $replace = $invoice_data ? ucwords($received_payment) : $search;
            }
            else if ($code['title'] == '[received_payment_via_card]') {
                $search = $code['title'];
                $replace = $invoice_data ? ucwords($received_payment_via_card) : $search;
            }
            else if ($code['title'] == '[installation_date_time]') {
                $search = $code['title'];
                $replace = $order_data ? ucwords($order_data->installation_datetime) : $search;
            }
            else if ($code['title'] == '[login_url]') {
                $search = $code['title'];

                $redirect_url = url('sp-login');
                $login_url = '<a class="text-primary" href="'.$redirect_url.'">'.$redirect_url.'</a>';

                $replace = $order_data ? ucwords($login_url) : $search;
            }
            else if ($code['title'] == '[invoice_link]') {
                $search = $code['title'];

                $asset_url = config('app.url').'/public/';
                $image_url = $asset_url.@$invoice_data->invoice_file;

                $invoice_file = '<a class="text-primary" href="'.$image_url.'">Download file</a>';
                $replace = $order_data ? ucwords($invoice_file) : $search;
            }
            if(isset($search)){
                $email_subject = stripcslashes(str_replace($search, $replace, $email_subject));
                $email_body = stripcslashes(str_replace($search, $replace, $email_body));
            }

        }
        // $SettingObj = new Setting();
        return $response = [
            'email_subject' => $email_subject,
            'email_body' => $email_body
        ];
    }
}

if (! function_exists('str_replace_first')) {
    function str_replace_first($search, $replace, $subject) {
        $search = '/'.preg_quote($search, '/').'/';
        return preg_replace($search, $replace, $subject, 1);
    }
}

if (! function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (! function_exists('generateRandomNumbers')) {
    function generateRandomNumbers($length = 4) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomNumbers = '';
        for ($i = 0; $i < $length; $i++) {
            $randomNumbers .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomNumbers;
    }
}

if (! function_exists('uploadAssets')) {
    function uploadAssets($imageData, $original = false, $optimized = false, $thumbnail = false, $inStorage = true) {

        if(isset($imageData['fileName']) && isset($imageData['uploadfileObj']) && isset($imageData['fileObj']) && isset($imageData['folderName'])){
            $fileName = $imageData['fileName'];
            $uploadfileObj = $imageData['uploadfileObj'];
            $fileObj = $imageData['fileObj'];
            $folderName = $imageData['folderName'];
            $storagePath = '';
            if($inStorage){
                $storagePath = 'storage/';
            }

            if($original){
                $destinationPath = public_path('/'.$storagePath.''.$folderName);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $uploadfileObj->move($destinationPath, $fileName);
                $imagePath = $folderName.'/'.$fileName;
            }

            if($optimized){
                $destinationPath = public_path('/'.$storagePath.''.$folderName.'/optimized');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileObj->save($destinationPath.'/'.$fileName, 25);
                $imagePath = $folderName.'/optimized/'.$fileName;
            }

            if($thumbnail){
                $destinationPath = public_path('/'.$storagePath.''.$folderName.'/thumbnail');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileObj->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$fileName);
            }

        }


        if(isset($imagePath)){
            return $imagePath;
        }else{
            return false;
        }
    }
}

if (! function_exists('unlinkUploadedAssets')) {
    function unlinkUploadedAssets($imageData, $inStorage = true) {

        if(isset($imageData['imagePath'])){
            $imagePath = $imageData['imagePath'];
            $base_url = public_path();
            $storagePath = '';
            if($inStorage){
                $storagePath = 'storage/';
            }
            $url = $base_url.'/'.$storagePath.''.$imagePath;

            if (file_exists($url)) {
                unlink($url);
            }
        }
        return true;
    }
}
