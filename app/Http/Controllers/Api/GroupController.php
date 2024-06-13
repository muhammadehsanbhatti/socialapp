<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requested_data = array();
        if ($request->group_id) {
            $requested_data['group_id'] = $request->group_id;
            $requested_data['detail'] = true;
        }
        else{
            $group_record = $this->GroupMemberObj->getGroupMember([
                'user_id' => \Auth::user()->id,
            ])->ToArray();
            $get_group_ids = array_column($group_record,'group_id');
            $requested_data['ids'] = $get_group_ids;
            $requested_data['orderBy_name'] = 'groups.id';
            $requested_data['orderBy_value'] = 'DESC';
        }
        $group_record = $this->GroupObj->getGroup($requested_data);
        return $this->sendResponse($group_record, 'Group detail');
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
        $requested_data = array();
        $posted_data = array();
        $requested_data = $request->all();
        $rules = array(
            'name' => 'required',
            'member_id' => 'required|exists:users,id',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $posted_data['user_id'] = \Auth::user()->id;
        $posted_data['name'] = $requested_data['name'];
        if ($request->file('image')) {
            $extension = $request->image->getClientOriginalExtension();
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                $filePath = $request->file('image')->storeAs('group_image', $file_name, 'public');
                $posted_data['image'] = 'storage/group_image/' . $file_name;
            } else {
                $error_message['error'] = 'Group Image Only allowled jpg, jpeg or png image format.';
                return $this->sendError($error_message['error'], $error_message);
            }
        }
        $group_record = array();
        $group_record['group_detail'] = $this->GroupObj->saveUpdateGroup($posted_data);

        if (isset($group_record['group_detail'])) {
            $group_member_requestd_data = array();
            $group_member_requestd_data['group_id'] = $group_record['group_detail']['id'];
            
            foreach ($requested_data['member_id'] as $key => $requested_data_value) {
                $group_member_requestd_data['member_id'] = $requested_data_value;
                $this->GroupMemberObj->saveUpdateGroupMember($group_member_requestd_data);
            }
            $group_member_requestd_data['member_id'] = \Auth::user()->id;
            $group_member_requestd_data['is_admin'] = true;
            $this->GroupMemberObj->saveUpdateGroupMember($group_member_requestd_data);
        }

        $data = $this->GroupObj->getGroup([
            'id' => $group_record['group_detail']['id'],
            'detail' =>true
        ]);
        // echo '<pre>'; print_r($data->ToArray()); echo '</pre>'; exit;
        // echo '<pre>'; print_r($data->groupMember); echo '</pre>'; exit;
        
        
        
        $group_message = $this->MessageObj->saveUpdateMessage([
            'sender_id' => $group_record['group_detail']['user_id'],
            'group_id' => $group_record['group_detail']['id'],
            'type' => 'Group',
            'message' => Null,
        ]);
        foreach ($data->groupMember as $group_member_key => $group_member_value) {
            if($group_member_value->user_id != \Auth::user()->id){
                send_notification([
                    'user_id' => \Auth::user()->id,
                    'receiver_id' => $group_member_value->user_id,
                    'notification_message_id' => 2,
                    'group_id' => $group_record['group_detail']['id'],
                    'metadata' => $group_message
                ]);
            }
        }

        return $this->sendResponse($data, 'Group created successfully');
    }

    
    public function group_message(Request $request){
        
        $requested_data = array();
        $requested_data = $request->all();
        $rules = array(
            'group_id' => 'required|exists:groups,id',
            'message' => 'required||regex:/^[a-zA-Z ]+$/u',
        );

        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $group_record['group_message'] = $this->MessageObj->saveUpdateMessage([
            'sender_id' => \Auth::user()->id,
            'message' => $requested_data['message'],
            'type' => 'Group'
        ]);

        if (isset($group_record['group_message'])) {
            $this->GroupMessageObj->saveUpdateGroupMessage([
                'group_id' => $requested_data['group_id'],
                'message_id' => $group_record['group_message']['id'],
            ]); 
        }
        return $this->sendResponse($group_record, 'Group message send successfully');
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
        $requested_data = array();
        $posted_data = array();
        $requested_data = $request->all();
        $rules =array();
        $posted_data['update_id'] = $id;
        $rules = array(
            // 'name' => 'regex:/^[a-zA-Z0-9 ]+$/u|unique:groups,name,'.$request->id,
            // 'name' => 'regex:/^[a-zA-Z0-9 ]+$/u',
            'member_id' => 'exists:users,id',
        );
        if (!is_array($request['member_id'])) {
            $rules = array(
                // 'type' => 'required|in:Normal,Archived',
                'member_id' => 'exists:users,id',
            );
        }
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        
        $posted_data['user_id'] = \Auth::user()->id;
        if (isset($requested_data['name'])) {
            $posted_data['name'] = $requested_data['name'];
        }
        $group_detail = $this->GroupObj->getGroup([
            'id' => $id,
            'detail' =>true
        ]);

        $check_admin = $this->GroupMemberObj->getGroupMember([
            'user_id' => \Auth::user()->id,
            'group_id' => $id,
            'is_admin' => 'True',
            'detail' => true
        ]);

        if (isset($check_admin)) {
            // echo '<pre>'; print_r($group_detail['groupMember']->ToArray()); echo '</pre>'; exit;
            if ($request->file('image')) {
                if (isset($group_detail['image'])) {

                    $url = public_path().'/'.$group_detail['image'];
                    if (file_exists($url)) {
                        unlink($url);
                    }
                }
                $extension = $request->image->getClientOriginalExtension();
                if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                    $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;
                    $filePath = $request->file('image')->storeAs('group_image', $file_name, 'public');
                    $posted_data['image'] = 'storage/group_image/' . $file_name;
                } else {
                    $error_message['error'] = 'Group Image Only allowled jpg, jpeg or png image format.';
                    return $this->sendError($error_message['error'], $error_message);
                }
            }

            $group_record['group_detail']['id'] = $this->GroupObj->saveUpdateGroup($posted_data);

            if (isset($requested_data['member_id']) &&  !is_array($requested_data['member_id'])) {
                $group_member_detail = $this->GroupMemberObj->getGroupMember([
                    'user_id' => $requested_data['member_id'],
                    'group_id' => $id,
                    'detail' => true
                ]);

                if (isset($group_member_detail) && $group_member_detail->id) {
                    $this->GroupMemberObj->saveUpdateGroupMember([
                        'update_id' => $group_member_detail->id,
                        'type' => isset($requested_data['type']) ? $requested_data['type']: 'Normal'
                    ]);
                }
            }
            else{
                if (isset($requested_data['member_id'])) {
                    foreach ($requested_data['member_id'] as $key => $member_id) {
                        
                        $is_group_member = $this->GroupMemberObj->getGroupMember([
                            'user_id' => $member_id,
                            'group_id' => $id,
                            'detail' => true
                        ]);
                        $update_member = array(); 
                        if($is_group_member){
                            $update_member['update_id'] = $is_group_member->id;
                            if ($is_group_member->member_last_message_id != NULL) {
                                $update_member['member_last_message_id'] =  'NULL';
                                $update_member['is_delete'] = 'False';
                            }
                        }
                        $update_member['member_id'] = $member_id; 
                        $update_member['group_id'] = $id;
                        if (isset($requested_data['type'])) {
                            $update_member['type'] = $requested_data['type'];
                        }
                        if (isset($requested_data['is_admin'])) {
                            $update_member['is_admin'] = $requested_data['is_admin'];
                        }
                        $group_member = $this->GroupMemberObj->saveUpdateGroupMember($update_member);

                        if ($group_member && $group_member->is_admin == 'True') {
                            $get_last_message = $this->MessageObj->getMessage([
                                'group_id' => $id,
                                'detail' => true,
                                'orderBy_name' =>'messages.id',
                                'orderBy_value' =>'DESC'
                            ]);
                            event (new \App\Events\GroupMakeAdminEvent(json_encode($get_last_message), $member_id));
                        }

                        if (!$is_group_member || (isset($update_member['is_delete']) && $update_member['is_delete'] == 'False')) {
                            $get_last_message = $this->MessageObj->getMessage([
                                'group_id' => $id,
                                'detail' => true,
                                'orderBy_name' =>'messages.id',
                                'orderBy_value' =>'DESC'
                            ]);
                            event (new \App\Events\GroupNewMemberEvent(json_encode($get_last_message), $member_id));
                        }

                        // $update_group_member = $this->GroupMemberObj->getGroupMember([
                        //     'user_id' => $member_id,
                        //     'group_id' => $id
                        // ])->ToArray(); 
                        // // if (!array_column($update_group_member,'user_id')) {
        
                        //     $update_member = array(); 
                        //     $update_member['member_id'] = $member_id; 
                        //     $update_member['group_id'] = $id;
                        //     if (isset($requested_data['type'])) {
                        //         $update_member['type'] = $requested_data['type'];
                        //     }
                        //     $this->GroupMemberObj->saveUpdateGroupMember($update_member);
                        // }
                    }
                }
            }

            $data = $this->GroupObj->getGroup([
                'id' => $id,
                'detail' =>true
            ]);
            
            return $this->sendResponse($data, 'Group Updated successfully');
        }
        else{
            return $this->sendError("error" , "You are not admin, so you did not update any information");
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

     public function destroy_group_member(Request $request, $id){
        $request_data =array();
        $request_data = $request->all();
       
        $rules = array(
            'member_id' => 'exists:group_members,user_id',
        );

        $validator = \Validator::make($request_data, $rules);

        // process the login
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        else {

            // $posted_data = array();
            // $posted_data['user_id'] = array();
            // $posted_data['group_id'] = array();
            // $posted_data['is_admin'] = array();
            // $posted_data['check_member_last_id_null'] = array();
            // $posted_data['detail'] = array();
            $message = $this->MessageObj->getMessage([
                'group_id' => $id,
                'orderBy_name'=>'messages.id',
                'orderBy_value'=>'DESC',
                'detail' => true,
                'for_me' => true
            ]);

            $check_user_is_admin = $this->GroupMemberObj->getGroupMember([
                'user_id' => \Auth::user()->id,
                'group_id' => $id,
                'is_admin' => 'True',
                'check_member_last_id_null' => true,
                'detail' =>true
            ]); 

            //if ($check_user_is_admin) {
                $check_admin = $this->GroupMemberObj->getGroupMember([
                    'user_id_in' => $request_data['member_id'],
                    'group_id' => $id,
                    'is_admin' => 'True',
                    'check_member_last_id_null' => true,
                ]);
    
                if (isset($check_admin) && count($check_admin)>0)   {
                    
                    $check_other_admin = $this->GroupMemberObj->getGroupMember([
                        'user_id_not' => \Auth::user()->id,
                        'group_id' => $id,
                        'is_admin' => 'True',
                        'check_member_last_id_null' => true,
                    ]);
    
                    
                    if (isset($check_other_admin) && count($check_other_admin)>0) {
                        
                        // $message = $this->MessageObj->getMessage([
                        //     'group_id' => $id,
                        //     'orderBy_name'=>'messages.id',
                        //     'orderBy_value'=>'DESC',
                        //     'detail' => true,
                        //     'for_me' => true
                        // ]); 
                        foreach ($request_data['member_id'] as $key => $member_id) {
            
                            $group_member_record= $this->GroupMemberObj->getGroupMember([
                                'user_id' => $member_id,
                                'group_id' => $id,
                                'detail'=>true
                            ]);
                          
                            // $get_columns_id = $group_member_record->ToArray(); 
                            // $get_columns_id = array_column($get_columns_id, 'id');
                            // $this->GroupMemberObj->deleteGroupMember(0,['id' => $get_columns_id]);
                            $update_member = $this->GroupMemberObj->saveUpdateGroupMember([
                                'update_id'=> $group_member_record->id,
                                'member_last_message_id'=> $message->id,
                                'is_delete'=> 'True',
                                'is_admin'=> 'False'
                            ]);   
                            
                            $get_last_message = $this->MessageObj->getMessage([
                                'group_id' => $id,
                                'detail' => true,
                                'orderBy_name' =>'messages.id',
                                'orderBy_value' =>'DESC'
                            ]);
                           
                            $member_archived_status =  $this->GroupMemberObj->getGroupMember([
                                'group_id' => $id,
                                'user_id' => $member_id,
                                'type' => 'Archived',
                                'orderBy_name' => 'group_members.id',
                                'orderBy_value' => 'desc',
                                'detail' =>true
                            ]);

                           
                            $get_last_message->groupData->is_delete = 'True';
                            $get_last_message->groupData->is_archived = false;

                            if($member_archived_status){
                                $get_last_message->groupData->is_archived = true;
                            }

                            event (new \App\Events\GroupMemberLeaveEvent(json_encode($get_last_message), $member_id));
                            
                        }
                    }
                    else{
    
                        // $message = $this->MessageObj->getMessage([
                        //     'group_id' => $id,
                        //     'orderBy_name'=>'messages.id',
                        //     'orderBy_value'=>'DESC',
                        //     'detail' => true,
                        //     'for_me' => true
                        // ]); 
                        foreach ($request_data['member_id'] as $key => $member_id) {
            
                            $group_member_record= $this->GroupMemberObj->getGroupMember([
                                'user_id' => $member_id,
                                'group_id' => $id,
                                'detail'=>true
                            ]);
                            $update_member = $this->GroupMemberObj->saveUpdateGroupMember([
                                'update_id'=> $group_member_record->id,
                                'member_last_message_id'=> $message->id,
                                'is_delete'=> 'True',
                                'is_admin'=> 'False'
                            ]);
                            $get_last_message = $this->MessageObj->getMessage([
                                'group_id' => $id,
                                'detail' => true,
                                'orderBy_name' =>'messages.id',
                                'orderBy_value' =>'DESC'
                            ]);

                            $member_archived_status =  $this->GroupMemberObj->getGroupMember([
                                'group_id' => $id,
                                'user_id' => $member_id,
                                'type' => 'Archived',
                                'orderBy_name' => 'group_members.id',
                                'orderBy_value' => 'desc',
                                'detail' =>true
                            ]);

                           
                            $get_last_message->groupData->is_delete = 'True';
                            $get_last_message->groupData->is_archived = false;

                            if($member_archived_status){
                                $get_last_message->groupData->is_archived = true;
                            }


                            // echo '<pre>3'; print_r($get_last_message); echo '</pre>'; exit;
                            event (new \App\Events\GroupMemberLeaveEvent(json_encode($get_last_message), $member_id));
                            // event (new \App\Events\GroupMemberLeaveEvent(json_encode($update_member), $member_id));
                        }
    
    
                        $get_latest_member = $this->GroupMemberObj->getGroupMember([
                            'user_id_not' => \Auth::user()->id,
                            'group_id' => $id,
                            'is_admin' => 'False',
                            'check_member_last_id_null' => true,
                            'orderBy_name' => 'group_members.id',
                            'orderBy_value' => 'DESC',
                            'detail' => true,
                        ]);
                        $this->GroupMemberObj->saveUpdateGroupMember([
                            'update_id'=> $get_latest_member->id,
                            'is_admin'=> 'True'
                        ]);
                        event (new \App\Events\GroupMakeAdminEvent(json_encode($get_latest_member), $get_latest_member->user_id));
                    }
                }
                else{
    
                    $check_is_user_admin = $this->GroupMemberObj->getGroupMember([
                        'user_id' => \Auth::user()->id,
                        'group_id' => $id,
                        'is_admin' => True,
                        'check_member_last_id_null' => true,
                    ]);
                   
                    // if ($check_is_user_admin && count($check_is_user_admin)>0) {

                        // $message = $this->MessageObj->getMessage([
                        //     'group_id' => $id,
                        //     'orderBy_name'=>'messages.id',
                        //     'orderBy_value'=>'DESC',
                        //     'detail' => true,
                        //     'for_me' => true
                        // ]); 
                        foreach ($request_data['member_id'] as $key => $member_id) {
            
                            $group_member_record= $this->GroupMemberObj->getGroupMember([
                                'user_id' => $member_id,
                                'group_id' => $id,
                                'detail'=>true
                            ]);
                          
                            // $get_columns_id = $group_member_record->ToArray(); 
                            // $get_columns_id = array_column($get_columns_id, 'id');
                            // $this->GroupMemberObj->deleteGroupMember(0,['id' => $get_columns_id]);
                            $update_member = $this->GroupMemberObj->saveUpdateGroupMember([
                                'update_id'=> $group_member_record->id,
                                'member_last_message_id'=> $message->id,
                                'is_delete'=> 'True'
                            ]);
                            $get_last_message = $this->MessageObj->getMessage([
                                'group_id' => $id,
                                'detail' => true,
                                'orderBy_name' =>'messages.id',
                                'orderBy_value' =>'DESC'
                            ]);

                            $member_archived_status =  $this->GroupMemberObj->getGroupMember([
                                'group_id' => $id,
                                'user_id' => $member_id,
                                'type' => 'Archived',
                                'orderBy_name' => 'group_members.id',
                                'orderBy_value' => 'desc',
                                'detail' =>true
                            ]);

                           
                            $get_last_message->groupData->is_delete = 'True';
                            $get_last_message->groupData->is_archived = false;

                            if($member_archived_status){
                                $get_last_message->groupData->is_archived = true;
                            }

                            
                            // echo '<pre>1'; print_r($get_last_message->ToArray()); echo '</pre>'; exit;
                            event (new \App\Events\GroupMemberLeaveEvent(json_encode($get_last_message), $member_id));
                            // event (new \App\Events\GroupMemberLeaveEvent(json_encode($update_member), $member_id));
                        }
                    // }
                    // else{
                    //     return $this->sendError("error" , "You are not admin, so you did not update any information");
                    // }
                   
                 }
           

            
          //  }
            // else{
            //     return $this->sendError("error" , "You are not admin, so you did not update any information");
            // }

            
            return $this->sendResponse('Success', 'Group member deleted successfully');
            // else{
            //     $message = $this->MessageObj->getMessage([
            //         'group_id' => $id,
            //         'orderBy_name'=>'messages.id',
            //         'orderBy_value'=>'DESC',
            //         'detail' => true,
            //         'for_me' => true
            //     ]); 
            //     foreach ($request_data['member_id'] as $key => $member_id) {
    
            //         $group_member_record= $this->GroupMemberObj->getGroupMember([
            //             'user_id' => $member_id,
            //             'group_id' => $id,
            //             'detail'=>true
            //         ]);
                  
            //         // $get_columns_id = $group_member_record->ToArray(); 
            //         // $get_columns_id = array_column($get_columns_id, 'id');
            //         // $this->GroupMemberObj->deleteGroupMember(0,['id' => $get_columns_id]);
            //         $this->GroupMemberObj->saveUpdateGroupMember([
            //             'update_id'=> $group_member_record->id,
            //             'member_last_message_id'=> $message->id,
            //             'is_delete'=>True
            //         ]);
            //     }
            //     return $this->sendResponse('Success', 'Group member deleted successfully');
            // }
            
        }
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
     
    public function destroy($id)
    {
       
    }
}