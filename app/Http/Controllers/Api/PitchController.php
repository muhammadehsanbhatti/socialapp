<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Something\Else;
use Aws\S3\Exception\S3Exception;
use App\Services\AwsBucketService;
use Illuminate\Support\Facades\Storage;
class PitchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $connected_ids ='';
        $posted_data = $request->all();
        $requested_data = $request->all();
        $requested_data['paginate'] = 7;
        $requested_data['orderBy_name'] = 'pitches.id';
        $requested_data['orderBy_value'] = 'DESC';

        if (isset($posted_data['pitch_search']) ) {
            $requested_data['pitch_search'] = $request->pitch_search;
        }
        if (isset($posted_data['user_id']) ) {
            $requested_data['user_id'] = $request->user_id;
        }

        $connect_people = $this->ConnectPeopleObj->connect_people_list($requested_data,true);

        if(isset($connect_people['matched_connect_peoples']) && !isset($posted_data['user_id'])){
            $ids_array = explode(',', $connect_people['matched_connect_peoples']);
            $requested_data['user_id_in'] = $ids_array;
        }
        // if (isset($posted_data['search_filter']) ) {
        //     if (isset($posted_data['search'])) {
        //         if ($posted_data['search_filter'] == 'Pitch') {
        //             // if ($posted_data['search_filter'] == 'Pitch' || $posted_data['search_filter'] == 'Tags') {
        //             $requested_data['search'] = $request->search;
        //         }
        //         if ($posted_data['search_filter'] == 'All') {
        //             // if ($posted_data['search_filter'] == 'Pitch' || $posted_data['search_filter'] == 'Tags') {
        //             $requested_data['search'] = $request->search;
        //         }
        //     }
        // }

        if (isset($posted_data['id']) ) {
            // $requested_data['detail'] = true;
            unset($requested_data['user_id_in']);
            // $requested_data['id'] = $request->id;
        }
        if (isset($posted_data['connection_status']) && $posted_data['connection_status'] == "MyConnection") {
            // if (isset($posted_data['connection_status']) && ($posted_data['connection_status'] == "MyConnection"  || $posted_data['connection_status'] == "Everyone")) {

            $connect_peoples = $this->ConnectPeopleObj->getConnectPeople([
                'auth_connect_id' => \Auth::user()->id,
                'status' => 'Accept'
            ])->ToArray();
            $latestConnectUserId = array_column($connect_peoples, 'user_id');
            $latestConnectConnectId = array_column($connect_peoples, 'connect_user_id');
            $connected_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
            $connected_ids = array_diff($connected_ids, [\Auth::user()->id]);
            unset($requested_data['connection_status']);
            $requested_data['user_id_in'] = $connected_ids;
        }

        if (isset($posted_data['pitch_tag_id']) ) {
            $pitch_tags = $this->PitchTagObj->getPitchTag([
                'tag_id' => $posted_data['pitch_tag_id']
            ])->ToArray();
            $get_pitch_ids = array_unique(array_column($pitch_tags, 'pitch_id'));
            $requested_data['ids_in'] = $get_pitch_ids;
        }
        if (isset($posted_data['country_code']) ) {
            // $cleanedPhoneNumbers = $this->change_phone_number_fromat($requested_data['country_code']);
            // $user_posted_data['country_code'] = $requested_data['country_code'];
            // $user_posted_data['get_ids'] = true;
            // $user_posted_data['printsql'] = true;

            $get_user_data = $this->UserObj->getUser([
                'country_code' => $posted_data['country_code'],
                'get_ids' => true

            ]);

            $requested_data['user_id_in'] = $get_user_data;
        }

        $check_already_pass = $this->UserRefuseConnectionObj->getUserRefuseConnection([
            // 'user_id_from' =>  \Auth::user()->id,
            'comma_separated_ids' => true,
            // 'printsql' => true
        ]);
        // echo '<pre>'; print_r($check_already_pass); echo '</pre>'; exit;
        // ])->pluck('user_id_to')->implode(',');
        // $userIds = $check_already_pass->pluck('user_id_from')->implode(',');
        // echo '<pre>'; print_r($check_already_pass); echo '</pre>'; exit;
        // $userIds = $check_already_pass->map(function ($record) {
        //     return $record->user_id_from . ',' . $record->user_id_to;
        // })->implode(',');


        if (isset($check_already_pass) && $check_already_pass && !isset($posted_data['user_id'])) {
            $userIdsArray = explode(',', $check_already_pass);
            // $userIdsArray = explode(',', $userIds);
            // $authUserId = \Auth::user()->id;
            // $userIdsArray = array_diff($userIdsArray, [$authUserId]);
            $requested_data['auth_id_not_in'] = $userIdsArray;
       }


        if (isset($posted_data['connection_status']) && $posted_data['connection_status'] == "Everyone") {
            $connect_peoples = $this->ConnectPeopleObj->getConnectPeople([
                'auth_connect_id' => \Auth::user()->id,
                'status' => 'Accept'
            ])->ToArray();
            if (isset($connect_peoples) && count($connect_peoples) > 0) {
                $latestConnectUserId = array_column($connect_peoples, 'user_id');
                $latestConnectConnectId = array_column($connect_peoples, 'connect_user_id');
                $connected_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
                $connected_ids = array_diff($connected_ids, [\Auth::user()->id]);
                unset($requested_data['connection_status']);
                $requested_data['user_id_in'] = $connected_ids;
            }


            $requested_data['check_everyone_status'] = true;

            // $get_user_ids_not = $this->PitchObj->getPitch([
            //     // 'auth_id_not_in' => $connected_ids,
            //     'connection_status' => 'Everyone'
            // ])->ToArray();
            // $latestConnectUserId = array_column($get_user_ids_not, 'user_id');
            // // echo '<pre>connected_ids11'; print_r($connected_ids); echo '</pre>';
            // if (empty($connected_ids) && !isset($connected_ids)) {
            //     $connected_ids = $latestConnectUserId;
            // } else {
            //     $connected_ids = array_unique(array_merge($connected_ids, $latestConnectUserId));
            // }

            // // $pitch_ids = array_column($get_user_ids_not, 'id');

            // // if ((isset($connected_ids) && count($connected_ids)  > 0) || (isset($latestConnectUserId) && count($latestConnectUserId)  > 0)) {
            // // if ((isset($connected_ids) && count($connected_ids)  > 0) || (isset($pitch_ids) && count($pitch_ids)  > 0)) {
            // // if (isset($pitch_ids) && count($pitch_ids)  > 0) {
            // //     unset($connected_ids);
            // //     $requested_data['ids_in'] = $pitch_ids;
            // // }
            // // $requested_data['printsql'] = true;
            // // if (count($connected_ids) > 0 && count($pitch_ids) > 0) {
            // //     $requested_data['ids_in'] = $pitch_ids;
            // // }

            // // $connected_ids = array_unique (array_merge ($connected_ids, $latestConnectUserId));
            // $requested_data['user_id_in'] = $connected_ids;
        }
        // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
        // $requested_data['printsql'] = true;
        $get_pitches = $this->PitchObj->getPitch($requested_data);
        return $this->sendResponse($get_pitches, 'List of pitches successfully.');
    }

    public function pitch_assets_store(Request $request){
        $posted_data = array();
        $request_data = $request->all();

        $rules = array(
            'upload_file.*' => 'file|max:2048',
        );
        $customMessages = array(
            'upload_file.*.max' => 'The :attribute must not be greater than 2048 kilobytes.',
        );
        $validator = \Validator::make($request_data, $rules, $customMessages);
        // $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;
        if (isset($request_data['upload_file'])) {
            foreach ($request_data['upload_file'] as $file_key => $file_value) {

                if ($request->file('upload_file')) {
                    $extension = $file_value->getClientOriginalExtension();
                    // if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'mp4' || $extension == 'pdf') {
                        $file_name = $file_value->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                        $fileSize = $file_value->getSize();
                        $filePath = $file_value->storeAs('pitch_assets', $file_name, 'public');
                        $posted_data['upload_file'] = 'storage/pitch_assets/' . $file_name;

                       $pitch_asset_data[] =  $this->DocumentAssetObj->saveUpdateDocumentAsset([
                            // 'pitch_id' => $data->id,
                            'path' => $posted_data['upload_file'],
                            'name' => $file_value->getClientOriginalName(),
                            'size' => $fileSize,
                            'extension' => $extension,
                            'status' => 'Pitch Asset'
                        ]);
                    // }
                    // else{
                    //     $error_message['error'] = 'Uploaded file Only allowled jpg, jpeg, png, mp4 or pdf format.';
                    //     return $this->sendError($error_message['error'], $error_message);
                    // }
                }
            }

            return $this->sendResponse($pitch_asset_data, 'Pitch assets uploaded successfully.');

        }
    }
    public function delete_pitch_asset(Request $request){
        // $posted_data = array();
        $request_data = $request->all();
        $rules = array(
            'asset_id.*' => 'exists:document_assets,id',
        );
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;

        foreach ($request_data['asset_id'] as $asset_key => $asset_value) {



            $pitch_asset_detail = $this->DocumentAssetObj->getDocumentAsset([
                'id' => $asset_value,
            ]);
            if ($pitch_asset_detail) {
                $url = public_path().'/'.$pitch_asset_detail[$asset_key]['path'];
                if (file_exists($url)) {
                    unlink($url);
                }
                $this->DocumentAssetObj->deleteDocumentAsset(0,['id' => $pitch_asset_detail[$asset_key]['id']]);
                return $this->sendResponse("Success", 'Pitch assets deleted successfully.');
            }
            else{
                return $this->sendError("Error", "Something went wrong");
            }
        }
    }

    public function pitches_record(Request $request){

        $posted_data = $request->all();
        $requested_data = $request->all();

        $pitches_per_page_record = isset($request->pitches_per_page_record) ? $request->pitches_per_page_record:15;
        $requested_data['paginate'] = $pitches_per_page_record;
        $requested_data['orderBy_name'] = 'pitches.id';
        $requested_data['orderBy_value'] = 'DESC';

        $check_already_pass = $this->UserRefuseConnectionObj->getUserRefuseConnection([
            'user_id_from' =>  \Auth::user()->id,
        ]);
        $userIds = $check_already_pass->map(function ($record) {
            return $record->user_id_from . ',' . $record->user_id_to;
        })->implode(',');
        $userIdsArray = explode(',', $userIds);
        $authUserId = \Auth::user()->id;
        $userIdsArray = array_diff($userIdsArray, [$authUserId]);
        $requested_data['auth_id_not_in'] = $userIdsArray;
        $data['pitches_data'] = $this->PitchObj->getPitch($requested_data);



        // ConnectPeople Record
        $posted_data['except_auth_id'] = \Auth::user()->id;
        $posted_data['orderBy_name'] = 'users.first_name';
        $posted_data['first_not_null'] = true;
        $posted_data['orderBy_value'] = 'ASC';
        $posted_data['groupBy'] = 'users.id';

        $get_users_ids = $this->UserObj->getUser($posted_data)->ToArray();
        $recommended_ids = array_column($get_users_ids, 'id');

        $return_ary['not_matched_connect_peoples'] = array();
        $connection_per_page_record = isset($request->connection_per_page_record) ? $request->connection_per_page_record:15;
        $request_data['orderBy_name'] = 'users.first_name';
        $request_data['first_not_null'] = true;
        $request_data['orderBy_value'] = 'ASC';
        $request_data['groupBy'] = 'users.id';
        $request_data['paginate'] = $connection_per_page_record;

        $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
            'block_id_from' => \Auth::user()->id,
            'type' => 'Block',
        ])->ToArray();

        $user_id_from = array_column($user_is_blocked, 'block_user_id_from');
        $user_id_to = array_column($user_is_blocked, 'block_user_id_to');
        $not_accepted_ids = array_unique (array_merge ($user_id_to, $user_id_from));
        if ($not_accepted_ids) {
            $request_data['users_not_in'] = $not_accepted_ids;
        }
        else{
            $request_data['users_not_in'] = array(0 => \Auth::user()->id);
        }
        $connect_peoples = $this->UserObj->getUser($request_data);

        if(isset($connect_peoples)){

            foreach ($connect_peoples as $key => $connect_peoples_value) {
                $connect_peoples_value->is_recommended = false;
                if(isset($recommended_ids) && in_array($connect_peoples_value->id, $recommended_ids)){
                    $connect_peoples_value->is_recommended = true;
                }
                $connect_peoples_value->is_bookmark = false;
                if(isset($book_mark_user_ids) && in_array($connect_peoples_value->id, $book_mark_user_ids)){
                    $connect_peoples_value->is_bookmark = true;
                }

                $connectPeopleList = array();
                $connectPeopleList['auth_connect_id'] = \Auth::user()->id;
                $connectPeopleList['other_connect_id'] = $connect_peoples_value->id;
                $connectPeopleList['detail'] = true;
                $connect_peoples_value->connect_people = $this->ConnectPeopleObj->getConnectPeople($connectPeopleList);

                $connect_peoples_value->is_blocked = false;
                $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
                    'block_user_id_from' => \Auth::user()->id,
                    'block_user_id_to' => $connect_peoples_value->id,
                    'type' => 'Block',
                    'detail' => true
                ]);
                if($user_is_blocked){
                    $connect_peoples_value->is_blocked = true;
                }
            }
        }
        unset($request_data['paginate']);
        $request_data['count'] = true;
        $count = $this->UserObj->getUser($request_data);

        // $return_ary['matched_connect_peoples'] = $connect_peoples;

        $data['matched_connect_peoples'] = $connect_peoples;


        // General tags
        $tag_per_page = isset($request->tag_per_page) ? $request->tag_per_page:15;
        $general_tags_data['paginate'] = $tag_per_page;
        $get_general_tags = $this->GeneralTagObj->getGeneralTag($general_tags_data);
        $data['records'] = $get_general_tags;

        return $this->sendResponse($data, 'List of all pitches record successfully.');
    }

     // Change phone number format funct
     function change_phone_number_fromat($posted_data =array()){
        $cleanedPhoneNumbers = array_map(function($phoneNumber) {
            $cleanedNumber = preg_replace('/\s|-/', '', $phoneNumber);
            $last10Digits = substr($cleanedNumber, -10);
            return $last10Digits;
        }, $posted_data);
        return $cleanedPhoneNumbers;
    }

    public function user_seen_pitch(Request $request){

        $request_data = $request->all();

        $rules = array(
            'pitch_id' => 'required|exists:pitches,id',
        );
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }
        $request_data['user_id'] =  \Auth::user()->id;

        $user_seen_record = $this->UserSeenPitchObj->getUserSeenPitch([
            'pitch_id' => $request->pitch_id,
            'user_id' => \Auth::user()->id,
            'detail' => true
        ]);
        if ($user_seen_record) {
            $request_data['update_id'] =  $user_seen_record->id;
        }

        $data = $this->UserSeenPitchObj->saveUpdateUserSeenPitch($request_data);
        return $this->sendResponse($data, 'You seen pitches successfully.');
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
    // public function store(Request $request,  AwsBucketService $awsBucketService)
    {
        $posted_data = array();
        $request_data = $request->all();
        $connect_search = $request->all();

        $rules = array(
            // 'title' => 'required||regex:/^[A-Za-z ]*[A-Za-z ][A-Za-z0-9()\/\-\ ]*$/',
            // 'description' => 'required||regex:/^[A-Za-z ]*[A-Za-z ][A-Za-z0-9()\/\-\ ]*$/',
            'connection_status' => 'required|in:Everyone,MyConnection,Filter',
            'status' => 'required|in:Published,Draft',
            'asset_id.*' => 'exists:document_assets,id',
        );
        // $customMessages = array(
        //     'upload_file.*.max' => 'The :attribute must not be greater than 2048 kilobytes.',
        // );
        $validator = \Validator::make($request_data, $rules);
        // $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;

        if ((isset($request_data['connection_status']) && $request_data['connection_status'] == 'Filter')) {
            unset($connect_search['upload_file']);
            $request_data['connection_search'] = json_encode($connect_search);
        }
        $data =$this->PitchObj->saveUpdatePitch($request_data);
        if (isset($request_data['pitch_tag'])) {
            foreach ($request_data['pitch_tag'] as $pitch_tag_key => $pitch_tag_value) {
                $get_general_tag = $this->GeneralTagObj->getGeneralTag([
                    'title' => $pitch_tag_value,
                    'detail' =>true
                ]);
                if (!isset($get_general_tag)) {
                    $get_general_tag =$this->GeneralTagObj->saveUpdateGeneralTag([
                        'title' => $pitch_tag_value
                    ]);
                }
                $this->PitchTagObj->saveUpdatePitchTag([
                    'tag_id' => $get_general_tag->id,
                    'pitch_id' => $data->id,
                ]);
            }
        }

        // if (isset($request_data['asset_id'])) {
        //     foreach ($request_data['asset_id'] as $asset_key => $pitch_asset_value) {
        //         $this->DocumentAssetObj->saveUpdateDocumentAsset([
        //             'update_id' => $pitch_asset_value,
        //             'pitch_id' => $data->id,
        //         ]);
        //     }
        // }
        if (isset($request_data['upload_file'])) {

            foreach ($request_data['upload_file'] as $file_key => $file_value) {

                if ($request->file('upload_file')) {
                    $extension = $file_value->getClientOriginalExtension();
                    // if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'mp4' || $extension == 'pdf') {


                        //Bucket upload funciton------------------------------------
                        // $file_name = pathinfo($file_value->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;

                        // // $file = $request_data['upload_file'];
                        // // $nameName = time() . $file->getClientOriginalName();
                        // // $awsBucketService = new AwsBucketService();
                        // $file_detail = [
                        //    "name"=> "images",
                        //    "file_value"=> $file_value,
                        //    "file_name"=> $file_name,
                        // ];


                        // $imageData = $awsBucketService->store_file($file_detail);
                        // $fileSize = $file_value->getSize();
                        // // $filePath = $file_value->storeAs('pitch_assets', $file_name, 'public');
                        // $posted_data['upload_file'] = 'storage/pitch_assets/' . $file_name;

                        //Bucket upload funciton ends------------------------------------

                        $file_name = pathinfo($file_value->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                        $fileSize = $file_value->getSize();
                        $filePath = $file_value->storeAs('pitch_assets', $file_name, 'public');
                        $posted_data['upload_file'] = 'storage/pitch_assets/' . $file_name;

                        $this->DocumentAssetObj->saveUpdateDocumentAsset([
                            'pitch_id' => $data->id,
                            'path' => isset($imageData) ? $imageData : $posted_data['upload_file'],
                            // 'path' => $posted_data['upload_file'],
                            'name' => $file_value->getClientOriginalName(),
                            'size' => $fileSize,
                            'extension' => $extension,
                            'status' => 'Pitch Asset',
                            'pitch_asset_status' => isset($imageData) ? 'S3_bucket' : 'Server',
                        ]);
                    // }
                    // else{
                    //     $error_message['error'] = 'Uploaded file Only allowled jpg, jpeg, png, mp4 or pdf format.';
                    //     return $this->sendError($error_message['error'], $error_message);
                    // }
                }
            }
            // $file = $request->file('upload_file');
            // $name = time() . $file->getClientOriginalName();
            // $filePath = 'images/' . $name;
            // $filePathS = \Storage::disk('s3')->put($filePath, file_get_contents($file));


        }
        if (isset($data)) {
            // event (new \App\Events\GroupMakeAdminEvent(json_encode($get_last_message), $member_id));
            $get_connect_people= $this->ConnectPeopleObj->getConnectPeople([
                'auth_connect_id' => \Auth::user()->id,
                'status' =>'Accept',
            ])->ToArray();
            $latestConnectUserId = array_column($get_connect_people, 'user_id');
            $latestConnectConnectId = array_column($get_connect_people, 'connect_user_id');
            $connected_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
            $connected_ids = array_diff($connected_ids, [\Auth::user()->id]);

            foreach ($connected_ids as $key => $connect_people_id) {

                send_notification([
                    'user_id' => \Auth::user()->id,
                    'receiver_id' => $connect_people_id,
                    'notification_message_id' => 6,
                    'new_pitch_event' => 'pitch_request',
                    'metadata' => $data
                ], 'pitch_notification_check');
            }



            $data = $this->PitchObj->getPitch([
                'id' => $data->id,
                'detail' => true
            ]);
        }

        return $this->sendResponse($data, 'Pitch created successfully.');
    }

    // Pitch share post
    public function pitch_share(Request $request)
    {

        $request_data =array();
        $returnData = array();
        $request_data = $request->all();
        $rules = array(
            'pitch_id' => 'required|exists:pitches,id',
            'share_to_user' => 'exists:users,id',
            'share_to_group' => 'exists:groups,id',
            'share_to_social' => 'in:Social,ConnectedUser',
        );

        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        // $request_data['user_id'] =  \Auth::user()->id;

        if ($request_data['share_to_social'] != 'Social') {
            // $group_count = isset($request_data['share_to_group']) && count($request_data['share_to_group']) > 0 ? count($request_data['share_to_group']) :0;
            // $user_count = isset($request_data['share_to_user']) && count($request_data['share_to_user']) > 0 ? count($request_data['share_to_user']) :0;
            if (isset($request_data['share_to_group'])) {

                foreach ($request_data['share_to_group'] as $get_group_key => $posted_group_id) {
                    $group_posted_data['sender_id'] =  \Auth::user()->id;
                    $group_posted_data['group_id'] =  $posted_group_id;
                    $group_posted_data['message'] = $request_data['message'];
                    $group_posted_data['type'] =  'Group';
                    $group_posted_data['pitch_id'] =  $request_data['pitch_id'];

                    $group_data = $this->MessageObj->saveUpdateMessage($group_posted_data);
                    $this->PitchShareObj->saveUpdatePitchShare([
                        'user_id' => \Auth::user()->id,
                        'pitch_id' => $request_data['pitch_id'],
                        'share_to_group' => $posted_group_id,
                        'share_to_social' => 'ConnectedUser',
                    ]);

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

                    // $returnData[] = $data;
                }
            }
            if (isset($request_data['share_to_user'])) {


                foreach ($request_data['share_to_user'] as $get_receiver_key => $posted_receiver_id) {
                    $posted_data['sender_id'] =  \Auth::user()->id;
                    $posted_data['receiver_id'] =  $posted_receiver_id;
                    $posted_data['message'] =  $request_data['message'];
                    $posted_data['type'] =  'Single';
                    $posted_data['pitch_id'] =  $request_data['pitch_id'];
                    // $posted_data['is_forwarded'] =  'True';
                    $single_user_data = $this->MessageObj->saveUpdateMessage($posted_data);
                    $this->PitchShareObj->saveUpdatePitchShare([
                        'user_id' => \Auth::user()->id,
                        'pitch_id' => $request_data['pitch_id'],
                        'share_to_user' => $posted_receiver_id,
                        'share_to_social' => 'ConnectedUser',
                    ]);
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

        } else{
            $returnData[] =$this->PitchShareObj->saveUpdatePitchShare([
                'user_id' => \Auth::user()->id,
                'pitch_id' => $request_data['pitch_id'],
                'share_to_social' => 'Social',
            ]);
        }

        $pitch_detail = $this->PitchObj->getPitch([
            'id' => $request_data['pitch_id'],
            'detail' => true
        ]);
        $this->PitchObj->saveUpdatePitch([
            'update_id' => $request_data['pitch_id'],
            'shares_count' => count($returnData) + $pitch_detail->shares_count,
        ]);
        return $this->sendResponse($returnData, 'Pitch share successfully.');
    }

    // Pitch reply post
    public function pitch_reply(Request $request)
    {
        $request_data = $request->all();
        $rules = array(
            'pitch_id' => 'required|exists:pitches,id',
            'reply_message' => 'required',
            'pitch_reply_id' => 'exists:pitche_replies,id',
        );
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;

        $pitch_detail = $this->PitchObj->getPitch([
            'id' => $request_data['pitch_id'],
            'detail' => true
        ]);

        $this->PitchObj->saveUpdatePitch([
            'update_id' => $request_data['pitch_id'],
            'replies_count' => ($pitch_detail->replies_count) + 1
        ]);
        $data =$this->PitcheReplyObj->saveUpdatePitcheReply($request_data);
        return $this->sendResponse($data, 'Pitch reply added successfully.');
    }


    // Pitch Contribution
    public function pitch_contribution(Request $request)
    {
        $request_data = $request->all();
        $rules = array(
            'pitch_id' => 'required|exists:pitches,id',
            'status' => 'required|in:Report,Pass',
        );
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;
        $pitch_contribution_data =$this->PitchContributionObj->saveUpdatePitchContribution($request_data);
        return $this->sendResponse($pitch_contribution_data, 'Pitch reply added successfully.');
    }

    // Pitch reply get
    public function get_pitch_reply(Request $request)
    {
        $get_pitches_replies = $this->PitcheReplyObj->getPitcheReply([
            'pitch_id' => $request->pitch_id
        ]);
        return $this->sendResponse($get_pitches_replies, 'Pitches Replies');
    }

    // Pitch shares get
    public function pitch_shares_detail(Request $request)
    {
        $get_pitches_shares = $this->PitchShareObj->getPitchShare([
            'pitch_id' => $request->pitch_id,
            'with_user_detail' => true,
            'with_group_detail' => true,
            'paginate' => 15,
            'orderBy_name' => 'pitch_shares.id',
            'orderBy_value' => 'DESC',
        ]);
        return $this->sendResponse($get_pitches_shares, 'Pitches shares record');
    }

    // get general tags
    public function get_general_tag(Request $request)
    {
        $request_data = $request->all();
        $get_general_tags = $this->GeneralTagObj->getGeneralTag($request_data);
        return $this->sendResponse($get_general_tags, 'General Tags');
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
        $posted_data = array();
        $request_data = $request->all();
        $connect_search = $request->all();

        $rules = array(
            'connection_status' => 'required|in:Everyone,MyConnection,Filter',
            'status' => 'required|in:Published,Draft',
            'asset_id.*' => 'exists:document_assets,id',
        );
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $request_data['user_id'] =  \Auth::user()->id;
        $request_data['update_id'] =  $id;

        if ((isset($request_data['connection_status']) && $request_data['connection_status'] == 'Filter')) {
            unset($connect_search['upload_file']);
            $request_data['connection_search'] = json_encode($connect_search);
        }
        $data =$this->PitchObj->saveUpdatePitch($request_data);

        if (isset($request_data['pitch_tag'])) {
            $this->PitchTagObj->deletePitchTag(0,['pitch_id' => $data->id]);

            foreach ($request_data['pitch_tag'] as $pitch_tag_key => $pitch_tag_value) {
                $get_general_tag = $this->GeneralTagObj->getGeneralTag([
                    'title' => $pitch_tag_value,
                    'detail' =>true
                ]);
                if (!isset($get_general_tag)) {
                    $get_general_tag =$this->GeneralTagObj->saveUpdateGeneralTag([
                        'title' => $pitch_tag_value
                    ]);
                }
                $this->PitchTagObj->saveUpdatePitchTag([
                    'tag_id' => $get_general_tag->id,
                    'pitch_id' => $data->id,
                ]);
            }
        }

        if (isset($request_data['delete_asset_id'])) {
            $rules = array(
                'delete_asset_id.*' => 'exists:document_assets,id',
            );
            $validator = \Validator::make($request_data, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->messages());
            }

            $pitch_asset = $this->DocumentAssetObj->getDocumentAsset([
                'document_assets_ids_in' => $request_data['delete_asset_id'],
            ]);
            if (isset($pitch_asset)  && count($pitch_asset) > 0) {
                foreach ($pitch_asset as $key => $pitch_asset_detail) {
                    $url = public_path().'/'.$pitch_asset_detail['path'];
                    if (file_exists($url)) {
                        unlink($url);
                    }
                    $this->DocumentAssetObj->deleteDocumentAsset(0,['id' => $pitch_asset_detail['id']]);
                }
            }
        }

        if (isset($request_data['upload_file'])) {
            foreach ($request_data['upload_file'] as $file_key => $file_value) {

                if ($request->file('upload_file')) {
                    $extension = $file_value->getClientOriginalExtension();

                    $file_name = pathinfo($file_value->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                    $fileSize = $file_value->getSize();
                    $filePath = $file_value->storeAs('pitch_assets', $file_name, 'public');
                    $posted_data['upload_file'] = 'storage/pitch_assets/' . $file_name;

                    $this->DocumentAssetObj->saveUpdateDocumentAsset([
                        'pitch_id' => $data->id,
                        'path' => isset($imageData) ? $imageData : $posted_data['upload_file'],
                        // 'path' => $posted_data['upload_file'],
                        'name' => $file_value->getClientOriginalName(),
                        'size' => $fileSize,
                        'extension' => $extension,
                        'status' => 'Pitch Asset',
                        'pitch_asset_status' => isset($imageData) ? 'S3_bucket' : 'Server',
                    ]);
                }
            }
        }
        if (isset($data)) {
            $data = $this->PitchObj->getPitch([
                'id' => $data->id,
                'detail' => true
            ]);
        }

        return $this->sendResponse($data, 'Pitch created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $request_data = $request->all();

        $validator = \Validator::make($request_data, [
            'id' => 'exists:pitches'.$id,
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);
        }

        $data = $this->PitchObj->deletePitch($id);
        return $this->sendResponse($data, 'Pitch removed successfully');
    }
}
