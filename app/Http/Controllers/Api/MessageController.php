<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request_param_data = $request->all();
        $request_data = array();
        $message = array();

        if((isset($request->sender_id) && isset($request->receiver_id)) || isset($request->group_id) ){
             // || isset($request->search)

            if(isset($request->group_id)){
                $group_messages = $this->MessageObj->getMessage([
                    'group_id' => $request->group_id,
                    'sender_id_not' => \Auth::user()->id,
                    'type' => 'Group'
                ]);

            }
            else if (isset($request->sender_id) && isset($request->receiver_id)){
                $group_messages = $this->MessageObj->getMessage([
                    //'sender_id_not' => \Auth::user()->id,
                    'type_not' => 'Group',
                    'read_sender_id' => $request->sender_id,
                    'read_receiver_id' => $request->receiver_id,
                ]);
            }
            // else{
            //     $group_messages = $this->MessageObj->getMessage([
            //         'search' => $request->search,
            //     ]);
            // }
            if (isset($group_messages)) {
                $group_messages_ids = $group_messages->ToArray();
                $group_messages_ids = array_column($group_messages_ids, 'id');
                foreach ($group_messages_ids as $group_messages_ids_key => $message_id) {
                    $isReadMessage = $this->ReadMessageObj->getReadMessage([
                        'user_id' => \Auth::user()->id,
                        'message_id' => $message_id,
                        'detail' => true
                    ]);
                    $tmp_data = array();
                    if($isReadMessage){
                        $tmp_data['update_id'] = $isReadMessage->id;
                    }else{
                        $tmp_data['delivered_at'] = date("Y-m-d H:i:s");
                    }

                    if(!$isReadMessage || ($isReadMessage && $isReadMessage->read_message_status == 'Delivered')){
                        $tmp_data['user_id'] = \Auth::user()->id;
                        $tmp_data['message_id'] = $message_id;
                        $tmp_data['read_message_status'] = 'Read';
                        $tmp_data['read_at'] = date("Y-m-d H:i:s");
                        $this->ReadMessageObj->saveUpdateReadMessage($tmp_data);
                    }


                }

            }

            // $retun_ary= $this->MessageObj->getMessage([
            //     'id' => $retun_ary->id,
            // ]);


            // foreach ($message as $key => $value) {
            //     if($value->group_id > 0){
            //         $group_messages = $this->MessageObj->getMessage([
            //             'group_id' => $value->group_id,
            //         ]);
            //     }else{
            //         if(isset($value->otherUserData->id)){
            //             $sender_id = $value->otherUserData->id;
            //         }else{
            //             $sender_id = $value->sender_id;
            //             if($sender_id == \Auth::user()->id){
            //                 $sender_id = $value->receiver_id;
            //             }
            //         }
            //         $group_messages = $this->MessageObj->getMessage([
            //             'sender_id' => $sender_id,
            //         ]);
            //     }

            // }
        }

        $delete_conversation_messages = $this->ConversationDeleteMessageObj->getConversationDeleteMessage([
            'user_id_from' => \Auth::user()->id
        ]);

        $get_columns_id = $delete_conversation_messages->ToArray();
        $get_columns_id = array_column($get_columns_id, 'message_id');
        $request_data['conversation_message_ids'] =  implode(',', $get_columns_id);
        if(isset($request->sender_id) && isset($request->receiver_id)){

            $requested_data = array();
            $requested_data['receiver_id'] = $request->receiver_id;
            $requested_data['sender_id'] = $request->sender_id;
            $requested_data['orderBy_name'] = 'messages.id';
            $requested_data['orderBy_value'] = 'DESC';
            $requested_data['where_null_message_status'] = true;
            $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);

            $check_sender_message_status = $this->MessageStatusObj->getMessageStatus([
                'block_user_id_from' => $request->sender_id,
                'block_user_id_to' => $request->receiver_id,
                'type' => 'Block',
                'detail' => true
            ]);

            if($check_sender_message_status){
                $requested_data['where_null_message_status'] = true;
            }
            $requested_data['paginate'] = 15;
            $requested_data['search'] = $request->search;
            $requested_data['for_me'] = true;
            // $requested_data['printsql'] = true;
            // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
            $message = $this->MessageObj->getMessage($requested_data);

            // if ($message) {
            //     $posted_data['update_id'] =  $message_id;
            //     $posted_data['message_delete'] = $requested_data['message_delete'];
            //     if (isset($message_record)  && $requested_data['message_delete'] == 'For Me') {
            //         $this->MessageObj->saveUpdateMessage($posted_data);
            //      }
            //     if (isset($message_record)  && $requested_data['message_delete'] == 'For Every One') {
            //         $this->MessageObj->saveUpdateMessage($posted_data);
            //         $this->MessageObj->deleteMessage($message_id);
            //     }
            // }

        }
        elseif(isset($request->group_id)){
            $requested_data = array();
            $requested_data['group_id'] = $request->group_id;
            // $requested_data['sender_id'] = \Auth::user()->id;
            $requested_data['orderBy_name'] = 'messages.id';
            $requested_data['orderBy_value'] = 'DESC';
            $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);
            // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;

            $check_group_member_status = $this->GroupMemberObj->getGroupMember([
                'user_id' => \Auth::user()->id,
                'group_id' => $request->group_id,
                'check_member_last_id' => true,
                'detail' => true
            ]);
            if($check_group_member_status){
                $requested_data['last_seen_message_id'] = $check_group_member_status->member_last_message_id;
            }
            $requested_data['paginate'] = 15;
            $requested_data['for_me'] = true;
            $message = $this->MessageObj->getMessage($requested_data);
        }
        else{

            $request_data['latest_data'] = true;
            $request_data['user_id'] =  \Auth::user()->id;
            $request_data['type'] =  $request->type;
            $request_data['receiver_id'] =  $request->receiver_id;
            $request_data['search'] =  $request->search;
            $request_data['per_page'] = $request->per_page? $request->per_page: 10;
            $request_data['page'] = $request->page? $request->page: 1;

            $message = $this->MessageObj->getMessage($request_data);
            unset($request_data['per_page']);
            unset($request_data['page']);
            $request_data['type'] =  'Archived';
            $request_data['count'] =  true;
            $total_archived_messages = isset($request->total_count) ?$this->MessageObj->getMessage($request_data) :0;
            // $total_archived_messages = $this->MessageObj->getMessage($request_data);
            // ++++++++++++++++++++++++++++++


                    // $unread_group_message_ids = array();
                    // $requested_data = array();
                    // $requested_data['receiver_id'] = \Auth::user()->id;
                    // $requested_data['orderBy_name'] = 'messages.id';
                    // $requested_data['orderBy_value'] = 'DESC';
                    // $requested_data['where_null_message_status'] = true;
                    // $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);

                    // // $check_sender_message_status = $this->MessageStatusObj->getMessageStatus([
                    // //     'block_user_id_from' => $request->sender_id,
                    // //     'block_user_id_to' => $request->receiver_id,
                    // //     'type' => 'Block',
                    // //     'detail' => true
                    // // ]);

                    // // if($check_sender_message_status){
                    // //     $requested_data['where_null_message_status'] = true;
                    // // }
                    // $requested_data['search'] = $request->search;
                    // $requested_data['for_me'] = true;
                    // // $requested_data['printsql'] = true;
                    // // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
                    // $c_message = $this->MessageObj->getMessage($requested_data);
                    // $unread_message_ids = array();
                    // $unread_message_ids = $c_message->ToArray();
                    // $unread_message_ids = array_column($unread_message_ids, 'id');
                    // $merge_ary = $unread_message_ids;
                    // $total_unread_messages = count($c_message);


                    // $check_group_member_status = $this->GroupMemberObj->getGroupMember([
                    //     'user_id' => \Auth::user()->id,
                    // ]);
                    // $check_group_member_status_ids = $check_group_member_status->ToArray();
                    // $check_group_member_status_ids = array_column($check_group_member_status_ids, 'group_id');

                    // $groupData = $this->GroupObj->getGroup([
                    //     'ids' => $check_group_member_status_ids
                    // ]);
                    // $group_messages_count = 0;

                    // foreach ($groupData as $key => $value) {
                    //     $requested_data = array();
                    //     $requested_data['group_id'] = $value->id;
                    //     $requested_data['sender_id_not'] = \Auth::user()->id;
                    //     $requested_data['orderBy_name'] = 'messages.id';
                    //     $requested_data['orderBy_value'] = 'DESC';
                    //     $requested_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);
                    //     // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;

                    //     $check_group_member_status = $this->GroupMemberObj->getGroupMember([
                    //         'user_id' => \Auth::user()->id,
                    //         'group_id' => $value->id,
                    //         'check_member_last_id' => true,
                    //         'detail' => true
                    //     ]);
                    //     if($check_group_member_status){
                    //         $requested_data['last_seen_message_id'] = $check_group_member_status->member_last_message_id;
                    //     }
                    //     // $requested_data['paginate'] = 15;
                    //     $requested_data['for_me'] = true;
                    //     $c_message = $this->MessageObj->getMessage($requested_data);

                    //     if(count($c_message)>0){
                    //         $c_message = $c_message->ToArray();
                    //         $unread_group_message_ids = array_column($c_message, 'id');
                    //         $merge_ary = array_merge($merge_ary, $unread_group_message_ids);
                    //         $group_messages_count = $group_messages_count + count($c_message);
                    //     }
                    // }

                    // $total_unread_messages = $total_unread_messages + $group_messages_count;
                    // $read_message_count = $this->ReadMessageObj->getReadMessage([
                    //     'user_id' => \Auth::user()->id,
                    //     'message_ids' => $merge_ary,
                    //     'read_message_status' => 'Read',
                    //     'count' => true
                    //     // ,'printsql' => true
                    // ]);

                    // $total_unread_messages = $total_unread_messages - $read_message_count;
                    $total_unread_messages = isset($request->total_count) ? unread_message_count(['conversation_message_ids' => $request_data['conversation_message_ids']]): 0;
                    // $total_unread_messages = unread_message_count([
                    //     'conversation_message_ids' => $request_data['conversation_message_ids']
                    // ]);
                    // $total_unread_messages = unread_message_count($request_param_data);


                    // $merge_ary = array_merge($unread_message_ids, $unread_group_message_ids);
                    // echo '<pre>$unread_message_ids'; print_r($unread_message_ids); echo '</pre>';
                    // echo '<pre>$unread_group_message_ids'; print_r($unread_group_message_ids); echo '</pre>';
                    // echo '<pre>$unread_group_message_ids'; print_r($merge_ary); echo '</pre>';
                    // exit;

            // +++++++++++++++++++++++++++++++++

            $request_data['count'] =  true;
            unset($request_data['type']);
            $total_unarchived_messages = isset($request->total_count) ? $this->MessageObj->getMessage($request_data) :0 ;
            // echo '<pre>'; print_r('test'); echo '</pre>'; exit;

            if(isset($message) && count($message)){
                foreach ($message as $key => $value) {
                    $value->message =  isset($value->message) ?Crypt::decrypt($value->message):'';

                    $receiverData =  $this->MessageObj->find($value->id)->receiverData;
                    $senderData =  $this->MessageObj->find($value->id)->senderData;
                    $groupData =  $this->MessageObj->find($value->id)->groupData;
                    $messageAssets =  $this->MessageObj->find($value->id)->messageAsset;

                    $pitchData =  $this->MessageObj->find($value->id)->pitchData;
                    $messageReplyDetail =  $this->MessageObj->find($value->id)->messageReplyId;
                    $readMessageStatus =  $this->MessageObj->find($value->id)->readMessageStatus;
                    if(\Auth::user()->id == $senderData->id){
                        $otherUserData =  $receiverData;
                    }else{
                        $otherUserData =  $senderData;
                    }

                    $value->receiver_data = $receiverData;
                    $value->sender_data = $senderData;
                    $value->otherUserData = $otherUserData;
                    $value->groupData = $groupData;
                    $value->pitchData = $pitchData;
                    $value->messageReplyDetail = $messageReplyDetail;
                    $value->readMessageStatus = $readMessageStatus;
                    $value->is_blocked = false;
                    // $value->blockMessageStatus = $blockMessageStatus;
                    // $value->unread_count = 0;

                    if(isset($groupData->id) && $groupData->id > 0){
                        // $message_ary = $this->MessageObj->getMessage([
                        //     'unread_group_count' => true,
                        //     'user_id' => \Auth::user()->id,
                        //     'group_id' => $groupData->id
                        //     // 'count' => true
                        //     // ,'printsql' => true
                        // ]);
                        // $message_ary_ids = $message_ary->ToArray();
                        // $message_ary_ids = array_column($message_ary_ids, 'id');
                        // $read_message_count = $this->ReadMessageObj->getReadMessage([
                        //     'user_id' => \Auth::user()->id,
                        //     'message_ids' => $message_ary_ids,
                        //     'read_message_status' => 'Read',
                        //     // 'groupBy' => 'read_messages.user_id',
                        //     'count' => true
                        //     // ,'printsql' => true
                        // ]);

                        // $value->unread_count = count($message_ary) - $read_message_count;
                        // if($value->unread_count < 0){
                        //     $value->unread_count = 0;
                        // }

                        $value->unread_count = isset($request->message_count) ? unread_message_count(['exect_group_id' => $groupData->id,'conversation_message_ids' => $request_data['conversation_message_ids']]) :0;

                    }else if(isset($otherUserData->id)){
                        $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
                            'block_user_id_from' => \Auth::user()->id,
                            'block_user_id_to' => $otherUserData->id,
                            'type' => 'Block',
                            'detail' => true
                        ]);
                        if($user_is_blocked){
                            $value->is_blocked = true;
                        }
                        // $value->unread_count = $this->MessageObj->getMessage([
                        //     'unread_count' => true,
                        //     'sender_id' => \Auth::user()->id,
                        //     'receiver_id' => $otherUserData->id,
                        //     'count' => true
                        //     // ,'printsql' => true
                        // ]);
                        $value->unread_count = isset($request->message_count) ? unread_message_count(['exect_sender_id' => $otherUserData->id,'exect_receiver_id' => \Auth::user()->id,'conversation_message_ids' => $request_data['conversation_message_ids']]):0;
                    }
                }
            }
            $message = $this->arrayPaginator($message, $request);
        }
        $retun_ary = array();
        $retun_ary['total_archived_messages'] = isset($total_archived_messages[0]->total_count)? $total_archived_messages[0]->total_count:0;
        $retun_ary['total_unarchived_messages'] = isset($total_unarchived_messages[0]->total_count)? $total_unarchived_messages[0]->total_count:0;
        $retun_ary['total_unread_messages'] =  isset($total_unread_messages)? $total_unread_messages:0;
        $retun_ary['message'] = $message;



        return $this->sendResponse($retun_ary,"List of record");
    }

    public function message_list(Request $request){

        $requested_data = $request->all();
        if (isset($requested_data['message_id'])) {
            $rules = array(
                'message_id' => 'exists:messages,id',
            );
            $validator = \Validator::make($requested_data, $rules);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
            }
        }

        // $requested_data['sender_id'] =\Auth::user()->id;
        $posted_data['where_ids'] = true;
        $check_other_admin = $this->GroupMemberObj->getGroupMember([
            'user_id' => \Auth::user()->id,
            'check_member_last_id_null' => true,
        ])->ToArray();

        $check_other_admin =  array_column($check_other_admin,'group_id');
        if (isset($request->message_id)) {
            $posted_data['user_message_id'] = $request->message_id;
        }

        $delete_conversation_messages = $this->ConversationDeleteMessageObj->getConversationDeleteMessage([
            'user_id_from' => \Auth::user()->id
        ]);

        $get_columns_id = $delete_conversation_messages->ToArray();
        $get_columns_id = array_column($get_columns_id, 'message_id');
        $request_data['conversation_message_ids'] =  implode(',', $get_columns_id);

        $posted_data['where_null_message_status'] = true;
        $posted_data['conversation_message_ids_ary']  = explode(',', $request_data['conversation_message_ids']);

        // $posted_data['for_me'] =true;
        $posted_data['group_member_id_in'] = $check_other_admin;
        $posted_data['orderBy_name'] = 'messages.id';
        $posted_data['orderBy_value'] = 'DESC';
        // $posted_data['printsql'] = true;

        $data['messages_list'] =  $this->MessageObj->getMessage($posted_data);
        return $this->sendResponse($data, 'List of messages');
    }

    public function arrayPaginator($array, $request)
    {
        // echo '<pre>'; print_r($request); echo '</pre>';
        $page = 1;
        $perPage = isset($request->per_page)? $request->per_page:15;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    public function specific_general_title(){
        $posted_data = array();
        $posted_data['title_status_in'] = [1,2];
        foreach ($posted_data['title_status_in'] as $key => $value) {
            $posted_data['title_status'] = $value;
            if ($value == 1) {
                $data['career_status_position'] = $this->GeneralObj->getGeneralTitle($posted_data);
            }
            if ($value == 2) {
                $data['professional_role'] = $this->GeneralObj->getGeneralTitle($posted_data);
            }
        }
        return $this->sendResponse($data, 'List of specific general titles');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Add members to database
        // $get_users = $this->UserObj->getUser([
        //     'groupBy'=> 'users.id',
        //     'deleted_at'=> NULL,
        // ]);
        // $array_column = $get_users->ToArray();
        // $array_column = array_column($array_column,'id');

        // foreach ($array_column as $key => $array_column_id) {
        //     $group_member['member_id'] = $array_column_id;
        //     $group_member['group_id'] = 295;
        //     $this->GroupMemberObj->saveUpdateGroupMember($group_member);
        // }
        // echo '<pre>'; print_r("members added successfully"); echo '</pre>'; exit;

        $returnData = array();
        $message_assets = array();
        $requested_data = $request->all();
        if (isset($requested_data['group_id'])) {
            $rules = array(
                'receiver_id' => 'NULL',
                'group_id' => 'required|exists:groups,id',
                'message' => 'required'
            );
        }
        else if (isset($requested_data['pitch_id'])) {
            $rules = array(
                'receiver_id' => 'required|exists:users,id',
                'pitch_id' => 'required|exists:pitches,id',
                'message' => 'required'
            );
        }
        else{
            $rules = array(
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required',
            );
        }
        if (isset($requested_data['send_file']) && !isset($requested_data['message'])) {
            unset($rules['message']);
        }

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $response_msg= 'Message send successfully';
        $conversation_messages = $this->MessageStatusObj->getMessageStatus([
            'block_user_id_to' => \Auth::user()->id,
            'type' => 'Block'
        ]);
        if (isset($conversation_messages)) {
            $get_columns_id = $conversation_messages->ToArray();
            $get_columns_id = array_column($get_columns_id, 'block_user_id_from');

        }

        $conversation_messages_not_sent = $this->MessageStatusObj->getMessageStatus([
            'block_user_id_from' => \Auth::user()->id,
            'type' => 'Block'
        ]);
        if (isset($conversation_messages_not_sent)) {
            $get_columns_conversation_id = $conversation_messages_not_sent->ToArray();
            $get_columns_conversation_id = array_column($get_columns_conversation_id, 'block_user_id_to');

        }
        $posted_data = array();
        $posted_data['sender_id'] = \Auth::user()->id;
        if (isset($requested_data['share_user_id'])) {
            $posted_data['share_user_id'] = $requested_data['share_user_id'];
        }
        if (isset($requested_data['message'])) {
            $posted_data['message'] = $requested_data['message'];
        }
        // if (isset($requested_data['message_asset_reply_id'])) {

        //     $get_messages =  $this->MessageAssetObj->getMessageAsset([
        //          'id' => $requested_data['message_asset_reply_id'],
        //          'detail' =>true
        //      ]);
        //      if (isset($get_messages)) {
        //          $posted_data['message_asset_reply_id'] =  $get_messages->id;
        //      }
        // }
        $posted_data['message_status'] = null;
        if (isset($requested_data['receiver_id'])) {
            $errorMessage = true;

            if (is_array($request->receiver_id)) {

                $intersetct_ids= array_intersect($get_columns_id, $requested_data['receiver_id']);
                foreach ($requested_data['receiver_id'] as $requested_data_key => $requested_data_value) {
                    if (!in_array($requested_data_value, $get_columns_conversation_id)) {
                        $errorMessage = false;
                        if (in_array($requested_data_value, $intersetct_ids)) {
                            $posted_data['message_status'] =  'Block';
                        }
                        $posted_data['type'] =  'Broadcast';
                        $posted_data['receiver_id'] =  $requested_data_value;

                        if (isset($requested_data['send_file'])) {
                            foreach ($requested_data['send_file'] as $file_key => $file_value) {
                                $returnData = $this->MessageObj->saveUpdateMessage($posted_data);
                                if ($request->file('send_file')) {
                                        $extension = $file_value->getClientOriginalExtension();
                                        $file_name = $file_value->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                                        $filePath = $file_value->storeAs('messages_file', $file_name, 'public');
                                        $posted_data['send_file'] = 'storage/messages_file/' . $file_name;
                                }
                                $this->MessageAssetObj->saveUpdateMessageAsset([
                                    'message_id' => $returnData['id'],
                                    'path' => $posted_data['send_file'],
                                    'extension' => $extension,
                                ]);
                                $returnData = $this->MessageObj->getMessage([
                                    'id' => $returnData->id,
                                    'detail' => true
                                ]);
                                send_notification([
                                    'user_id' => \Auth::user()->id,
                                    'receiver_id' => $requested_data_value,
                                    'notification_message_id' => 1,
                                    'metadata' => $returnData
                                ],'message_notification_check');
                            }
                        }else{
                            $returnData = $this->MessageObj->saveUpdateMessage($posted_data);
                            send_notification([
                                'user_id' => \Auth::user()->id,
                                'receiver_id' => $requested_data_value,
                                'notification_message_id' => 1,
                                'metadata' => $returnData
                            ],'message_notification_check');
                        }
                        $get_receiver_record = $this->UserObj->getUser([
                            'id' =>$requested_data_value,
                            'detail' => true,
                        ]);
                        $this->UserObj->saveUpdateUser([
                            'update_id' => $requested_data_value,
                            'total_unread_messages_tmp' => $get_receiver_record->total_unread_messages_tmp + 1,
                        ]);
                    }
                    // $returnData[] = $return_record;
                }
            }
            else{
                if (!in_array($requested_data['receiver_id'], $get_columns_conversation_id)) {
                    $errorMessage = false;
                    $intersetct_ids= array_intersect($get_columns_id, [$requested_data['receiver_id']]);
                    if (in_array($requested_data['receiver_id'], $intersetct_ids)) {
                        $posted_data['message_status'] =  'Block';
                    }
                    $posted_data['receiver_id'] =  $requested_data['receiver_id'];
                    $posted_data['type'] =  'Single';
                    if (isset($requested_data['message_reply_id'])) {
                        $get_messages =  $this->MessageObj->getMessage([
                             'sender_id' => \Auth::user()->id,
                             'receiver_id' => $requested_data['receiver_id'],
                             'id' => $requested_data['message_reply_id'],
                             'detail' =>true
                         ]);

                         if ($get_messages) {
                             $posted_data['message_reply_id'] =  $get_messages->id;
                         }
                    }
                    if (isset($requested_data['pitch_id'])) {
                        $posted_data['pitch_id'] =  $requested_data['pitch_id'];

                    }

                    if (isset($requested_data['send_file'])) {
                        foreach ($requested_data['send_file'] as $file_key => $file_value) {
                            $returnData = $this->MessageObj->saveUpdateMessage($posted_data);
                            if ($request->file('send_file')) {
                                    $extension = $file_value->getClientOriginalExtension();
                                    $file_name = $file_value->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                                    $filePath = $file_value->storeAs('messages_file', $file_name, 'public');
                                    $posted_data['send_file'] = 'storage/messages_file/' . $file_name;
                                    // $message_assets[] =  $posted_data['message_assets'];
                            }
                            $this->MessageAssetObj->saveUpdateMessageAsset([
                                'message_id' => $returnData->id,
                                'path' => $posted_data['send_file'],
                                'extension' => $extension,
                            ]);
                            $returnData = $this->MessageObj->getMessage([
                                'id' => $returnData->id,
                                'detail' => true
                            ]);
                            send_notification([
                                'user_id' => \Auth::user()->id,
                                'receiver_id' => $posted_data['receiver_id'],
                                'notification_message_id' => 1,
                                'metadata' => $returnData
                            ],'message_notification_check');
                        }
                    }
                    else{
                        $returnData = $this->MessageObj->saveUpdateMessage($posted_data);

                        send_notification([
                            'user_id' => \Auth::user()->id,
                            'receiver_id' => $posted_data['receiver_id'],
                            'notification_message_id' => 1,
                            'metadata' => $returnData
                        ],'message_notification_check');
                    }
                    $get_receiver_record = $this->UserObj->getUser([
                        'id' => $posted_data['receiver_id'],
                        'detail' => true,
                    ]);
                    $this->UserObj->saveUpdateUser([
                        'update_id' => $posted_data['receiver_id'],
                        'total_unread_messages_tmp' => $get_receiver_record->total_unread_messages_tmp + 1,
                    ]);
                }
            }

            if ($errorMessage) {
                return $this->sendError("error", "First you unblock user");
            }

        }

        if (isset($requested_data['group_id'])) {

            $response_msg= 'You did not send message';
            $group_detail = $this->GroupMemberObj->getGroupMember([
                'group_id' => $requested_data['group_id'],
                'check_member_status' => true,
                // 'not_blocked' => true,
                'user_id' => \Auth::user()->id,
                'detail' =>true
            ]);
            if($group_detail){
                if (isset($requested_data['message_reply_id'])) {
                    $get_messages =  $this->MessageObj->getMessage([
                        // 'sender_id' => \Auth::user()->id,
                        'group_id' => $requested_data['group_id'],
                        'id' => $requested_data['message_reply_id'],
                        'detail' =>true
                    ]);
                    if ($get_messages) {
                        $posted_data['message_reply_id'] =  $get_messages->id;
                    }
                }

                $returnData = $group_detail;
                if($group_detail){
                    // if($group_detail && $group_detail->member_status == 'Block'){
                    //     $get_latest_group_message =  $this->MessageObj->getMessage([
                    //         'group_id' => $requested_data['group_id'],
                    //         'orderBy_name' => 'messages.id',
                    //         'orderBy_value' => 'DESC',
                    //         'detail' =>true
                    //     ]);
                    //     $returnData = $this->GroupMemberObj->saveUpdateGroupMember([
                    //         'member_last_message_id' => $get_latest_group_message->id,
                    //         'update_id' => $group_detail->id
                    //     ]);
                    //     $response_msg= 'You did not send message';
                    // }else{
                    $posted_data['type'] =  'Group';
                    $posted_data['group_id'] =  $requested_data['group_id'];

                    $get_group_members = $this->GroupMemberObj->getGroupMember([
                        'group_id' => $requested_data['group_id'],
                        // 'not_blocked' => true,
                        'check_member_status' =>true,
                        'user_id_not'=> \Auth::user()->id,
                        // 'type' => 'Normal',
                    ]);
                    $get_member_ids = $get_group_members->ToArray();
                    $get_member_ids = array_column($get_member_ids, 'user_id');

                    if (isset($requested_data['send_file'])) {
                        foreach ($requested_data['send_file'] as $file_key => $file_value) {
                            $returnData = $this->MessageObj->saveUpdateMessage($posted_data);
                            if ($request->file('send_file')) {
                                    $extension = $file_value->getClientOriginalExtension();
                                    $file_name = $file_value->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                                    $filePath = $file_value->storeAs('messages_file', $file_name, 'public');
                                    $posted_data['send_file'] = 'storage/messages_file/' . $file_name;
                            }
                            $this->MessageAssetObj->saveUpdateMessageAsset([
                                'message_id' => $returnData['id'],
                                'path' => $posted_data['send_file'],
                                'extension' => $extension,
                            ]);
                            $returnData = $this->MessageObj->getMessage([
                                'id' => $returnData->id,
                                'detail' => true
                            ]);
                            if(isset($get_group_members) && count($get_group_members)>0){
                                send_notification([
                                    'user_id' => \Auth::user()->id,
                                    'notification_message_id' => 1,
                                    'metadata' => $returnData,
                                    'is_group' => true,
                                    'group_id' => $posted_data['group_id'],
                                    'member_ids' => $get_member_ids,
                                ],'message_notification_check');
                                // foreach ($get_group_members as $key => $group_member_id) {
                                //     send_notification([
                                //         'user_id' => \Auth::user()->id,
                                //         'receiver_id' => $group_member_id['user_id'],
                                //         'notification_message_id' => 1,
                                //         'metadata' => $returnData,
                                //         'is_group' => true,
                                //         'group_id' => $posted_data['group_id']
                                //     ]);
                                // }
                            }
                        }
                    }else{
                        // echo '<pre>'; print_r($get_member_ids); echo '</pre>'; exit;

                        $returnData = $this->MessageObj->saveUpdateMessage($posted_data);

                        $json_metadata = json_encode($returnData);
                        if(isset($get_group_members) && count($get_group_members)>0){
                            send_notification([
                                'user_id' => \Auth::user()->id,
                                'notification_message_id' => 1,
                                'json_metadata' => $json_metadata,
                                'is_group' => true,
                                'group_id' => $posted_data['group_id'],
                                'member_ids' => $get_member_ids,
                            ],'message_notification_check');
                            // foreach ($get_group_members as $key => $group_member_id) {
                            //     send_notification([
                            //         'user_id' => \Auth::user()->id,
                            //         'receiver_id' => $group_member_id['user_id'],
                            //         'notification_message_id' => 1,
                            //         'json_metadata' => $json_metadata,
                            //         'is_group' => true,
                            //         'group_id' => $posted_data['group_id']
                            //     ]);
                            // }
                        }
                    }
                }
            }
        }

        if(isset($returnData->id)){
            $returnData = $this->MessageObj->getMessage([
                'id' => $returnData->id,
                'detail' => true
            ]);
        }
        return $this->sendResponse($returnData, $response_msg);

    }
    public function forward_message_to(Request $request){
        $requested_data = $request->all();

        $request_data['sender_id'] = \Auth::user()->id;
        $returnData = $this->MessageObj->getMessage();
    }

    public function send_dynamic_link(Request $request)
    {
        $requested_data = $request->all();
        $posted_data =array();
        $group_posted_data =array();
        $returnData = array();
        $rules = array(
            'message' => 'required',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        // if (isset($requested_data['message_id'])) {
            // $get_message = $this->MessageObj->getMessage([
            //     'message_id' => $requested_data['message_id']
            // ])->ToArray();
            // foreach ($get_message as $key => $value) {
                if (isset($requested_data['share_user_id'])) {
                    $posted_data['share_user_id'] = $requested_data['share_user_id'];
                }
                if (isset($requested_data['receiver_id'])) {
                    foreach ($requested_data['receiver_id'] as $get_receiver_key => $posted_receiver_id) {
                        $posted_data['sender_id'] =  \Auth::user()->id;
                        $posted_data['receiver_id'] =  $posted_receiver_id;
                        $posted_data['message'] =  $requested_data['message'];
                        $posted_data['type'] =  'Single';
                        // $posted_data['is_forwarded'] =  'True';
                        $single_user_data = $this->MessageObj->saveUpdateMessage($posted_data);
                        // if(isset($value['message_asset']['id'])){
                        //     $this->MessageAssetObj->saveUpdateMessageAsset([
                        //         'message_id' => $single_user_data->id,
                        //         'path' => $value['message_asset']['path'],
                        //         'extension' => $value['message_asset']['extension'],
                        //     ]);
                        // }
                        $latestData = $this->MessageObj->getMessage([
                            'id' => $single_user_data->id,
                            'detail' => true
                        ]);
                        send_notification([
                            'user_id' => \Auth::user()->id,
                            'receiver_id' => $posted_receiver_id,
                            'notification_message_id' => 1,
                            'metadata' => $latestData
                        ]);

                        $returnData[] = $latestData;
                    }
                }
                if (isset($requested_data['group_id'])) {
                    foreach ($requested_data['group_id'] as $get_group_key => $posted_group_id) {
                        $group_posted_data['sender_id'] =  \Auth::user()->id;
                        $group_posted_data['group_id'] =  $posted_group_id;
                        $group_posted_data['message'] = $requested_data['message'];
                        $group_posted_data['type'] =  'Group';
                        // $group_posted_data['is_forwarded'] =  'True';

                        $group_data = $this->MessageObj->saveUpdateMessage($group_posted_data);

                        // if(isset($value['message_asset']['id'])){
                        //     $this->MessageAssetObj->saveUpdateMessageAsset([
                        //         'message_id' => $group_data->id,
                        //         'path' => $value['message_asset']['path'],
                        //         'extension' => $value['message_asset']['extension'],
                        //     ]);
                        // }
                        $latestData = $this->MessageObj->getMessage([
                            'id' => $group_data->id,
                            'detail' => true
                        ]);

                        $get_group_members = $this->GroupMemberObj->getGroupMember([
                            'group_id' => $posted_group_id,
                            'not_blocked' => true,
                            'user_id_not'=> \Auth::user()->id,
                            'type' => 'Normal',
                        ]);
                        if(isset($get_group_members) && count($get_group_members)>0){
                            foreach ($get_group_members as $key => $group_member_id) {
                                send_notification([
                                    'user_id' => \Auth::user()->id,
                                    'receiver_id' => $group_member_id['user_id'],
                                    'notification_message_id' => 1,
                                    'metadata' => $latestData,
                                    'is_group' => true
                                ]);
                            }
                        }

                        $returnData[] = $latestData;
                    }
                }
            // }
        return $this->sendResponse($returnData,'Message Forwarded successfully');
    }

    public function forward_message(Request $request)
    {
        $requested_data = $request->all();
        $posted_data =array();
        $group_posted_data =array();
        $returnData = array();
        $rules = array(
            'message_id' => 'exists:messages,id',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        // if (isset($requested_data['message_id'])) {
            $get_message = $this->MessageObj->getMessage([
                'message_id' => $requested_data['message_id']
            ])->ToArray();
            foreach ($get_message as $key => $value) {
                if (isset($requested_data['receiver_id'])) {
                    foreach ($requested_data['receiver_id'] as $get_receiver_key => $posted_receiver_id) {
                        $posted_data['sender_id'] =  \Auth::user()->id;
                        $posted_data['receiver_id'] =  $posted_receiver_id;
                        $posted_data['message'] =  $value['message'];
                        $posted_data['type'] =  'Single';
                        $posted_data['is_forwarded'] =  'True';
                        $single_user_data = $this->MessageObj->saveUpdateMessage($posted_data);
                        if(isset($value['message_asset']['id'])){
                            $this->MessageAssetObj->saveUpdateMessageAsset([
                                'message_id' => $single_user_data->id,
                                'path' => $value['message_asset']['path'],
                                'extension' => $value['message_asset']['extension'],
                            ]);
                        }
                        $latestData = $this->MessageObj->getMessage([
                            'id' => $single_user_data->id,
                            'detail' => true
                        ]);
                        send_notification([
                            'user_id' => \Auth::user()->id,
                            'receiver_id' => $posted_receiver_id,
                            'notification_message_id' => 1,
                            'metadata' => $latestData
                        ]);

                        $returnData[] = $latestData;
                    }
                }
                if (isset($requested_data['group_id'])) {
                    foreach ($requested_data['group_id'] as $get_group_key => $posted_group_id) {
                        $group_posted_data['sender_id'] =  \Auth::user()->id;
                        $group_posted_data['group_id'] =  $posted_group_id;
                        $group_posted_data['message'] = $value['message'];
                        $group_posted_data['type'] =  'Group';
                        $group_posted_data['is_forwarded'] =  'True';

                        $group_data = $this->MessageObj->saveUpdateMessage($group_posted_data);

                        if(isset($value['message_asset']['id'])){
                            $this->MessageAssetObj->saveUpdateMessageAsset([
                                'message_id' => $group_data->id,
                                'path' => $value['message_asset']['path'],
                                'extension' => $value['message_asset']['extension'],
                            ]);
                        }
                        $latestData = $this->MessageObj->getMessage([
                            'id' => $group_data->id,
                            'detail' => true
                        ]);

                        $get_group_members = $this->GroupMemberObj->getGroupMember([
                            'group_id' => $posted_group_id,
                            'not_blocked' => true,
                            'user_id_not'=> \Auth::user()->id,
                            'type' => 'Normal',
                        ]);
                        if(isset($get_group_members) && count($get_group_members)>0){
                            foreach ($get_group_members as $key => $group_member_id) {
                                send_notification([
                                    'user_id' => \Auth::user()->id,
                                    'receiver_id' => $group_member_id['user_id'],
                                    'notification_message_id' => 1,
                                    'metadata' => $latestData,
                                    'is_group' => true
                                ]);
                            }
                        }

                        $returnData[] = $latestData;
                    }
                }
            }

            // echo '<pre>'; print_r($get_message); echo '</pre>'; exit;

            // $get_message_assets = $this->MessageAssetObj->getMessageAsset([
            //     'message_id' => $requested_data['message_id']
            // ]);
            // $get_columns_id = $get_message->ToArray();
        //     $get_columns_id = array_column($get_message, 'message');
        //     foreach ($get_columns_id as $get_column_key => $message_text) {

        //         if (isset($requested_data['receiver_id'])) {
        //             foreach ($requested_data['receiver_id'] as $get_receiver_key => $posted_receiver_id) {
        //                 $posted_data['sender_id'] =  \Auth::user()->id;
        //                 $posted_data['receiver_id'] =  $posted_receiver_id;
        //                 $posted_data['message'] =  $message_text;
        //                 $posted_data['type'] =  'Single';
        //                 $posted_data['is_forwarded'] =  'True';
        //                 $single_user_data = $this->MessageObj->saveUpdateMessage($posted_data);
        //                 $returnData[] = $single_user_data;
        //                 if (isset($get_message[$get_receiver_key]['message_asset'])) {
        //                     $this->MessageAssetObj->saveUpdateMessageAsset([
        //                         'message_id' => $single_user_data['id'],
        //                         'path' => $get_message_assets[$get_receiver_key]['path'],
        //                         'extension' => $get_message_assets[$get_receiver_key]['extension'],
        //                     ]);

        //                 }

        //             }
        //         }
        //         if (isset($requested_data['group_id'])) {
        //             foreach ($requested_data['group_id'] as $group_key => $posted_group_id) {
        //                 $group_posted_data['sender_id'] =  \Auth::user()->id;
        //                 $group_posted_data['group_id'] =  $posted_group_id;
        //                 $group_posted_data['message'] =  $message_text;
        //                 $group_posted_data['type'] =  'Group';
        //                 $group_posted_data['is_forwarded'] =  'True';

        //                 $group_data = $this->MessageObj->saveUpdateMessage($group_posted_data);
        //                 $returnData[] = $group_data;

        //                 if (isset($get_message[$group_key]['message_asset'])) {

        //                     $this->MessageAssetObj->saveUpdateMessageAsset([
        //                         'message_id' => $group_data['id'],
        //                         'path' => $get_message_assets[$group_key]['path'],
        //                         'extension' => $get_message_assets[$group_key]['extension'],
        //                     ]);

        //                 }
        //             }
        //         }
        //     }
        // }

        // if (isset($requested_data['message_asset_id'])) {
        //     $get_message_assets = $this->MessageAssetObj->getMessageAsset([
        //         'message_assets_id' => $requested_data['message_asset_id']
        //     ])->ToArray();
        //     foreach ($get_message_assets as $file_key => $file_value) {

        //         if (isset($requested_data['receiver_id'])) {
        //             foreach ($requested_data['receiver_id'] as $get_receiver_key => $posted_receiver_id) {
        //                 $posted_data['sender_id'] =  \Auth::user()->id;
        //                 $posted_data['receiver_id'] =  $posted_receiver_id;
        //                 $posted_data['message'] =  NULL;
        //                 $posted_data['type'] =  'Single';
        //                 $posted_data['is_forwarded'] =  'True';
        //                 $single_user_data = $this->MessageObj->saveUpdateMessage($posted_data);
        //                 $returnData[] = $single_user_data;

        //                 $this->MessageAssetObj->saveUpdateMessageAsset([
        //                     'message_id' => $single_user_data['id'],
        //                     'path' => $file_value['path'],
        //                     'extension' => $file_value['extension'],
        //                 ]);
        //             }
        //         }
        //         if (isset($requested_data['group_id'])) {
        //             foreach ($requested_data['group_id'] as $get_group_key => $posted_group_id) {
        //                 $group_posted_data['sender_id'] =  \Auth::user()->id;
        //                 $group_posted_data['group_id'] =  $posted_group_id;
        //                 $group_posted_data['message'] =   NULL;
        //                 $group_posted_data['type'] =  'Group';
        //                 $group_posted_data['is_forwarded'] =  'True';

        //                 $group_data = $this->MessageObj->saveUpdateMessage($group_posted_data);
        //                 $returnData[] = $group_data;

        //                 $this->MessageAssetObj->saveUpdateMessageAsset([
        //                     'message_id' => $group_data['id'],
        //                     'path' => $file_value['path'],
        //                     'extension' => $file_value['extension'],
        //                 ]);
        //             }
        //         }
        //     }
        // }

        return $this->sendResponse($returnData,'Message Forwarded successfully');



    }

    public function message_read(Request $request){

        $requested_data = $request->all();
        $rules = array(
            'message_id' => 'required|exists:messages,id',
            'read_message_status' => 'required|in:Delivered,Read',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $posted_data['user_id'] = \Auth::user()->id;
        $posted_data['message_id'] = $requested_data['message_id'];

        if($requested_data['read_message_status'] == 'Read'){
            $message_detail = $this->MessageObj->getMessage([
                'id' => $requested_data['message_id'],
                'detail' =>  true
            ]);
            if(isset($message_detail->type)){

                if($message_detail->sender_id != \Auth::user()->id){
                    $message_detail->receiver_id = $message_detail->sender_id;
                    $message_detail->sender_id = \Auth::user()->id;
                }

               if($message_detail->type == 'Group'){
                   $unread_messages = $this->MessageObj->getMessage([
                       'group_id' => $message_detail->group_id,
                       'sender_id_not' => \Auth::user()->id,
                       'type' => 'Group'
                   ]);

               }
               else if ($message_detail->type != 'Group'){
                   $unread_messages = $this->MessageObj->getMessage([
                       'type_not' => 'Group',
                       'read_sender_id' => $message_detail->sender_id,
                       'read_receiver_id' => $message_detail->receiver_id,
                   ]);
               }
               if (isset($unread_messages)) {
                   $unread_messages_ids = $unread_messages->ToArray();
                   $unread_messages_ids = array_column($unread_messages_ids, 'id');
                   foreach ($unread_messages_ids as $unread_messages_ids_key => $message_id) {
                       $isReadMessage = $this->ReadMessageObj->getReadMessage([
                           'user_id' => \Auth::user()->id,
                           'message_id' => $message_id,
                           'detail' => true
                       ]);
                       $tmp_data = array();
                       if($isReadMessage){
                           $tmp_data['update_id'] = $isReadMessage->id;
                       }else{
                           $tmp_data['delivered_at'] = date("Y-m-d H:i:s");
                       }

                       if(!$isReadMessage || ($isReadMessage && $isReadMessage->read_message_status == 'Delivered')){
                           $tmp_data['user_id'] = \Auth::user()->id;
                           $tmp_data['message_id'] = $message_id;
                           $tmp_data['read_message_status'] = 'Read';
                           $tmp_data['read_at'] = date("Y-m-d H:i:s");
                           $this->ReadMessageObj->saveUpdateReadMessage($tmp_data);
                       }
                   }
               }
           }
        }

        $check_message_status = $this->ReadMessageObj->getReadMessage([
            'user_id' => \Auth::user()->id,
            'message_id' =>  $requested_data['message_id'],
            'detail' => true,
        ]);

        if (isset($check_message_status)) {
            $posted_data['update_id'] = $check_message_status->id;
            if (isset($requested_data['read_message_status']) && $requested_data['read_message_status'] == 'Delivered') {
                if (!isset($check_message_status->delivered_at)) {
                    $posted_data['delivered_at'] = date("Y-m-d H:i:s");
                }
                $posted_data['read_message_status'] = 'Delivered';
            }
            if (isset($requested_data['read_message_status']) && $requested_data['read_message_status'] == 'Read') {
                if (!isset($check_message_status->read_at)) {
                    $posted_data['read_at'] = date("Y-m-d H:i:s");
                }
                $posted_data['read_message_status'] = 'Read';
            }
        }
        else{
            if (isset($requested_data['read_message_status']) && $requested_data['read_message_status'] == 'Delivered') {
                $posted_data['delivered_at'] = date("Y-m-d H:i:s");
                $posted_data['read_message_status'] = 'Delivered';
            }
            if (isset($requested_data['read_message_status']) && $requested_data['read_message_status'] == 'Read') {
                $posted_data['read_at'] = date("Y-m-d H:i:s");
                $posted_data['read_message_status'] = 'Read';
            }
        }
        $data = $this->ReadMessageObj->saveUpdateReadMessage($posted_data);
        $sender_id = $data->messageData->sender_id;
        unset($data->messageData);
        event (new \App\Events\ChatMessageStatusEvent($data, $sender_id));
        return $this->sendResponse($data, 'You have read message');
    }

    public function user_message_status(Request $request){

        // $requested_data = array();
        $return_data = array();
        $requested_data = $request->all();
        $rules = array(
            'type' => 'required|in:Block,Archived,Report,UnArchived,Unblock',
        );
        if (isset($requested_data['type']) && $requested_data['type'] == 'Report') {
            $rules = array(
                'general_title_id' => 'required|exists:general_titles,id',
                'report_message' => 'required||regex:/^[a-zA-Z ]+$/u',
            );
        }
        if (isset($requested_data['group_id'])) {
            $rules = array(
                'group_id' => 'required|exists:groups,id',
            );
        }

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        if (isset($requested_data['block_user_id_to'])) {
            $user_message_record['message_status'] = $this->MessageStatusObj->getMessageStatus([
                'block_user_id_from' => \Auth::user()->id,
                'block_user_id_to_in' => $requested_data['block_user_id_to'],
                // 'type' => $requested_data['type']
            ]);
            foreach ($requested_data['block_user_id_to'] as $block_user_key => $requested_block_user_id) {
                if ($user_message_record['message_status']) {
                    if (isset($requested_data['type']) && $requested_data['type'] == 'UnArchived') {
                        // $this->MessageStatusObj->deleteMessageStatus($user_message_record['message_status'][$block_user_key]['id']);
                        $this->MessageStatusObj->deleteMessageStatus(0,['block_user_id_from' => \Auth::user()->id, 'block_user_id_to' => $requested_block_user_id, 'type' => 'Archived']);
                    }
                    if (isset($requested_data['type']) && $requested_data['type'] == 'Unblock') {
                        // $this->MessageStatusObj->deleteMessageStatus($user_message_record['message_status'][$block_user_key]['id']);
                        $this->MessageStatusObj->deleteMessageStatus(0,['block_user_id_from' => \Auth::user()->id, 'block_user_id_to' => $requested_block_user_id, 'type' => 'Block']);
                    }

                }
                $posted_data = array();
                $message_status =array();
                $posted_data['block_user_id_from'] = \Auth::user()->id;
                $posted_data['block_user_id_to'] = $requested_block_user_id;
                $posted_data['type'] = $requested_data['type'];
                if (isset($requested_data['type']) && $requested_data['type'] == 'Report') {
                    foreach ($requested_data['general_title_id'] as $general_key => $general_value) {
                        $posted_data = array();
                        $posted_data['block_user_id_from'] = \Auth::user()->id;
                        $posted_data['block_user_id_to'] = $requested_block_user_id;
                        $posted_data['type'] = $requested_data['type'];
                        $posted_data['general_title_id'] = $general_value;
                        $posted_data['report_message'] = $requested_data['report_message'];

                        $return_array[] = $this->MessageStatusObj->saveUpdateMessageStatus($posted_data);
                        $message_status['message_status'] = $return_array;
                    }
                }
                elseif($requested_data['type'] == 'Archived' || $requested_data['type'] == 'Block'){
                    $message_status['message_status'] = $this->MessageStatusObj->saveUpdateMessageStatus($posted_data);
                    $return_data[] = $message_status['message_status'] ;
                }
            }
        }


        if (isset($requested_data['group_id']) && ($requested_data['type'] == 'Archived' || $requested_data['type'] == 'UnArchived')) {
            foreach ($requested_data['group_id'] as $block_group_key => $block_group_value) {
               $group_member =  $this->GroupMemberObj->getGroupMember([
                    'user_id' =>  \Auth::user()->id,
                    'group_id' => $block_group_value,
                    'detail' => true
                ]);
                // echo '<pre>'; print_r($group_member->ToArray()); echo '</pre>';
                $posted_group_data['type'] = 'Normal';
                $posted_group_data['update_id'] = $group_member->id;

                if ($requested_data['type'] == 'Archived') {
                    $posted_group_data['type'] = 'Archived';
                }
                $message_status['message_status'] = $this->GroupMemberObj->saveUpdateGroupMember($posted_group_data);
                $return_data[] = $message_status['message_status'] ;
            }
        }
        return $this->sendResponse($return_data, 'User message record updated successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function  delete_user_converstaion(Request $request){
        $posted_data = array();
        $posted_data = $request->all();
        if (isset($posted_data['delete_user_id_to'])) {
            foreach ($posted_data['delete_user_id_to'] as $delete_user_key => $delete_user_value) {
                $message_record = $this->MessageObj->getMessage([
                    'where_sender_ids' => $delete_user_value,
                    'where_receiver_ids' => $delete_user_value,
                    // 'type' => 'Single',
                    // 'group_id'=>true
                ]);

                $get_columns_id = $message_record->ToArray();
                $get_columns_id = array_column($get_columns_id, 'id');

                $message_conversation_record = $this->ConversationDeleteMessageObj->getConversationDeleteMessage([
                    'user_id_from' => \Auth::user()->id,
                    'message_id_in' => $get_columns_id,
                    // 'printsql'=>true
                ]);
                $message_conversation_ids = $message_conversation_record->ToArray();
                $message_conversation_ids = array_column($message_conversation_ids, 'message_id');

                $delete_user_array = array_diff($get_columns_id, $message_conversation_ids);

                if (isset($delete_user_array)) {
                    foreach ($delete_user_array as $get_columns_id_value) {
                        $this->ConversationDeleteMessageObj->saveUpdateConversationDeleteMessage([
                            'user_id_from' => \Auth::user()->id,
                            'message_id' => $get_columns_id_value,
                        ]);
                    }
                }
            }

        }
        if (isset($posted_data['group_id'])) {
            foreach ($posted_data['group_id'] as $group_key => $group_value) {
                $message_record = $this->MessageObj->getMessage([
                    // 'sender_id' => \Auth::user()->id,
                    'group_id' => $group_value,
                    'type' => 'Group',
                    // 'printsql'=>true
                ]);

                $get_columns_id = $message_record->ToArray();
                $get_columns_id = array_column($get_columns_id, 'id');

                $message_conversation_record = $this->ConversationDeleteMessageObj->getConversationDeleteMessage([
                    'user_id_from' => \Auth::user()->id,
                    'message_id_in' => $get_columns_id,
                    // 'printsql'=>true
                ]);
                $message_conversation_ids = $message_conversation_record->ToArray();
                $message_conversation_ids = array_column($message_conversation_ids, 'message_id');

                $delete_group_array = array_diff($get_columns_id, $message_conversation_ids);

                if (isset($delete_group_array)) {
                    foreach ($delete_group_array as $get_columns_id_value) {
                        $this->ConversationDeleteMessageObj->saveUpdateConversationDeleteMessage([
                            'user_id_from' => \Auth::user()->id,
                            'message_id' => $get_columns_id_value,
                        ]);
                    }
                }
            }
        }
        return $this->sendResponse('Success', 'Chat deleted successfully');
    }

    public function destroy($id)
     {
        // $message_record = $this->MessageObj->getMessage([
        //     'where_sender_ids' => $id,
        //     'where_receiver_ids' => $id,
        //     'type' => 'Single'
        // ]);
        // // echo '<pre>'; print_r($message_record->ToArray()); echo '</pre>'; exit;

        // $get_columns_id = $message_record->ToArray();
        // $get_columns_id = array_column($get_columns_id, 'id');

        // foreach ($get_columns_id as $get_columns_id_value) {
        //    $this->ConversationDeleteMessageObj->saveUpdateConversationDeleteMessage([
        //         'user_id_from' => \Auth::user()->id,
        //         'message_id' => $get_columns_id_value,
        //     ]);
        // }

        // // $this->MessageObj->deleteMessage(0,['receiver_id' => $get_columns_id]);
        // return $this->sendResponse('Success', 'Chat deleted successfully');

    }
    // Delete single message
    // public function delete_messages(Request $request){

    //     $requested_data = $request->all();
    //     $rules = array(
    //         'message_delete' => 'required|in:For Me,For Every One',
    //         'message_id' => 'required'
    //     );

    //     $validator = \Validator::make($requested_data, $rules);

    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
    //     }

    //     $message_record = $this->MessageObj->getMessage([
    //         'id' => $requested_data['message_id'],
    //         'sender_id' => \Auth::user()->id
    //     ]);
    //     $posted_data['update_id'] = $requested_data['message_id'];
    //     $posted_data['message_delete'] = $requested_data['message_delete'];

    //     if (isset($message_record)  && $requested_data['message_delete'] == 'For Me') {
    //        $this->MessageObj->saveUpdateMessage($posted_data);
    //     }
    //     if (isset($message_record)  && $requested_data['message_delete'] == 'For Every One') {
    //         $this->MessageObj->saveUpdateMessage($posted_data);
    //         $this->MessageObj->deleteMessage($requested_data['message_id']);
    //     }
    //     return $this->sendResponse('Success', 'Message deleted successfully');
    // }

    //Delete multiple message
    public function delete_messages(Request $request){
        $requested_data =array();
        $requested_data = $request->all();
        $rules = array(
            'message_delete' => 'required|in:For Me,For Every One',
            'message_id' => 'required|exists:messages,id',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        foreach ($requested_data['message_id'] as $key => $message_id) {
            $message_record = $this->MessageObj->getMessage([
                'id' => $message_id,
                'auth_id' => true,
                'detail' => true
            ]);
            if ($message_record) {
                // echo'<pre>'; print_r($message_record); echo'</pre>';exit;
                $posted_data['update_id'] =  $message_id;
                $posted_data['message_delete'] = $requested_data['message_delete'];
                if (isset($message_record)  && $requested_data['message_delete'] == 'For Me') {
                    if ($message_record['sender_id'] == \Auth::user()->id) {
                        $this->MessageObj->saveUpdateMessage($posted_data);
                    }
                    $check_conversation_messae = $this->ConversationDeleteMessageObj->getConversationDeleteMessage([
                        'user_id_from' => \Auth::user()->id,
                        'message_id' => $message_id,
                        'detail' => true
                    ]);
                    if (!$check_conversation_messae) {
                        $this->ConversationDeleteMessageObj->saveUpdateConversationDeleteMessage([
                            'user_id_from' => \Auth::user()->id,
                            'message_id' => $message_id,
                        ]);
                    }


                 }
                if (isset($message_record)  && $requested_data['message_delete'] == 'For Every One') {
                    // echo'<pre>'; print_r($posted_data); echo'</pre>';exit;
                    $data  = $this->MessageObj->saveUpdateMessage($posted_data);
                    $data->is_archived = false;
                    if ($data->group_id) {
                        $get_group_members = $this->GroupMemberObj->getGroupMember([
                            'group_id' => $data->group_id,
                            'not_blocked' => true,
                            'user_id_not'=> \Auth::user()->id,
                            'type' => 'Normal',
                            // 'printsql' => 'Normal',
                        ]);
                        // echo'<pre>'; print_r($get_group_members); echo'</pre>';exit;
                        if(isset($get_group_members) && count($get_group_members)>0){
                            foreach ($get_group_members as $key => $group_member_id) {

                                $member_archived_status = $this->GroupMemberObj->getGroupMember([
                                    'group_id' => $data->group_id,
                                    'user_id' => $group_member_id,
                                    'type' => 'Archived',
                                    'orderBy_name' => 'group_members.id',
                                    'orderBy_value' => 'desc',
                                    'detail' =>true
                                ]);
                                if($member_archived_status){
                                    $data->is_archived = true;
                                }
                                event (new \App\Events\ChatDeleteForEveryOneEvent($data, $group_member_id['user_id']));
                            }
                        }
                    }
                    else{
                        if (isset($data->receiver_id) && $data->receiver_id>0) {
                            $user_archived_status = $this->MessageStatusObj->getMessageStatus([
                                'block_user_id_from' =>  $data->receiver_id,
                                'block_user_id_to' => \Auth::user()->id,
                                'type' => 'Archived',
                                'detail' => true
                            ]);

                            if($user_archived_status){
                                $data->is_archived = true;
                            }
                        }
                        // echo '<pre>'; print_r($data->ToArray()); echo '</pre>'; exit;
                        event (new \App\Events\ChatDeleteForEveryOneEvent($data->ToArray(), $data->receiver_id));
                    }
                    $this->MessageObj->deleteMessage($message_id);
                }
            }
        }
        return $this->sendResponse('Success', 'Message deleted successfully');
    }

}
