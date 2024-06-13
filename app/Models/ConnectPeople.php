<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectPeople extends Model
{
    use HasFactory;

    public static function getConnectPeople($posted_data = array())
    {
        $query = ConnectPeople::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('connect_people.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('connect_people.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_id_not'])) {
            $query = $query->where('connect_people.connect_user_id', '!=' , $posted_data['user_id_not']);
        }
        if (isset($posted_data['auth_id_no'])) {
            $query = $query->where('connect_people.user_id', '!=' , $posted_data['auth_id_no']);
        }
        if (isset($posted_data['connect_user_id'])) {
            $query = $query->where('connect_people.connect_user_id', $posted_data['connect_user_id']);
        }
        if (isset($posted_data['connect_type'])) {
            $query = $query->where('connect_people.connect_type', $posted_data['connect_type']);
        }
        if (isset($posted_data['connect_types'])) {
            $query = $query->whereIn('connect_people.connect_type', $posted_data['connect_types']);
        }
        if (isset($posted_data['connect_people_note'])) {
            $query = $query->where('connect_people.connect_people_note', $posted_data['connect_people_note']);
        }

        if (isset($posted_data['connect_user_ids'])) {
            $query = $query->whereIn('connect_people.connect_user_id', $posted_data['connect_user_ids']);
        }
        if (isset($posted_data['auth_connect_id'])) {
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('connect_people.user_id', $posted_data['auth_connect_id'])
                        ->orwhere('connect_people.connect_user_id', $posted_data['auth_connect_id']);
                });
        }
        if (isset($posted_data['status_check'])) {
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('connect_people.status', 'Accept')
                        ->orwhere('connect_people.status', 'Pending');
                });
        }

        if (isset($posted_data['other_connect_id'])) {
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('connect_people.user_id', $posted_data['other_connect_id'])
                        ->orwhere('connect_people.connect_user_id', $posted_data['other_connect_id']);
                });
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('connect_people.status', $posted_data['status']);
        }
        if (isset($posted_data['status_not'])) {
            $query = $query->where('connect_people.status', '!=' ,  $posted_data['status_not']);
        }
        if(isset($posted_data['age_from']) && isset($posted_data['age_to'])){

            $posted_data['age_from']--;
            $posted_data['age_to']--;
            $posted_data['age_from'] = date('Y', strtotime('-'.$posted_data['age_from'].' years'));
            $posted_data['age_to'] = date('Y', strtotime('-'.$posted_data['age_to'].' years'));
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('users.dob', '<=', $posted_data['age_from'])
                        ->where('users.dob', '>=', $posted_data['age_to']);
                });

        }
	    // if (isset($posted_data['gender'])) {
        //     $query = $query->whereIn('users.gender', $posted_data['gender']);
        // }
        if (isset($posted_data['gender'])) {
            $gender = $posted_data['gender'];
            $query = $query->where(function ($query) use ($genders) {
                foreach ($genders as $gender) {
                    $query->orWhere('users.gender', 'like', "%$gender%");
                }
            });
        }
        if (isset($posted_data['location'])) {
            $phoneNumbers = $posted_data['location'];
            $query = $query->where(function ($query) use ($phoneNumbers) {
                foreach ($phoneNumbers as $phoneNumber) {
                    $query->orWhere('users.phone_number', 'like', "%$phoneNumber%");
                }
            });
        }

        // if (isset($posted_data['get_record'])) {
        //     $query = $query->where('connect_people.user_id', \Auth::user()->id)->orWhere('connect_people.connect_user_id', \Auth::user()->id);
        // }

        if (isset($posted_data['comma_separated_ids'])) {
            $query = $query->pluck('connect_people.id');;
        }


        $query->join('users', 'users.id', '=', 'connect_people.connect_user_id');
        $query->select('connect_people.*','users.phone_number','users.dob','users.gender');
        $query->select('connect_people.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'DESC');
        }
        if (isset($posted_data['groupBy']) && $posted_data['groupBy']) {
            $query->groupBy($posted_data['groupBy']);
        }
        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else {
                $result = $query->get();
            }
        }

        if(isset($posted_data['printsql'])){
            $result = $query->toSql();
            echo '<pre>';
            print_r($result);
            print_r($posted_data);
            exit;
        }
        return $result;
    }

    public function saveUpdateConnectPeople($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ConnectPeople::find($posted_data['update_id']);
        } else {
            $data = new ConnectPeople;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['connect_user_id'])) {
            $data->connect_user_id = $posted_data['connect_user_id'];
        }
        if (isset($posted_data['connect_type'])) {
            $data->connect_type = $posted_data['connect_type'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        if (isset($posted_data['connect_people_note'])) {
            $data->connect_people_note = $posted_data['connect_people_note'];
        }

        $data->save();

        $data = ConnectPeople::getConnectPeople([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }

    public function deleteConnectPeople($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = ConnectPeople::find($id);
        }else{
            $data = ConnectPeople::latest();
        }

        // if(isset($where_posted_data) && count($where_posted_data)>0){
        //     if (isset($where_posted_data['name'])) {
        //         $is_deleted = true;
        //         $data = $data->where('name', $where_posted_data['name']);
        //     }
        // }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
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

    public function bookmark_user(){
        $book_mark_user_ids = array();
        $request_data['user_id_from'] = ConnectionBookMark::getConnectionBookMark([
            'user_id_from' => \Auth::user()->id
        ])->ToArray();

        $book_mark_user_ids = array_column($request_data['user_id_from'], 'user_id_to');

        return $book_mark_user_ids;
    }

    public function connect_people_list($requested_data=array(),$pitch_filter=false){

        $posted_data = array();
        $request_data = $requested_data;
        // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
        $return_ary = array();

        // $recommended_ids = $this->recommended_users();
        $posted_data['except_auth_id'] = \Auth::user()->id;

        $posted_data['orderBy_name'] = 'users.first_name';
        $posted_data['first_not_null'] = true;
        $posted_data['orderBy_value'] = 'ASC';
        $posted_data['groupBy'] = 'users.id';

        $get_users_ids = User::getUser($posted_data)->ToArray();
        $recommended_ids = array_column($get_users_ids, 'id');

        $count = 0;

            $per_page = isset($requested_data['per_page']) ? $requested_data['per_page']:10;
            // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
            $return_ary['not_matched_connect_peoples'] = array();

            $request_data['orderBy_name'] = 'users.first_name';
            $request_data['first_not_null'] = true;
            $request_data['orderBy_value'] = 'ASC';
            $request_data['groupBy'] = 'users.id';
            $request_data['paginate'] = $per_page;

            if(isset($request_data['check_matched'])){
                $request_data = $requested_data['all']();
                if (isset($request_data['phone_numbers'])) {
                    $cleanedPhoneNumbers = $this->change_phone_number_fromat($request_data['phone_numbers']);
                    $posted_data['phone_numbers_like'] = $cleanedPhoneNumbers;
                }
                $posted_data['except_auth_id'] = \Auth::user()->id;
                $get_user_data = User::getUser($posted_data)->ToArray();

                $matched_phone_number = array_column($get_user_data, 'phone_number');
                $match_user_ids = array_column($get_user_data, 'id');
                $request_data['groupBy'] = 'users.id';
                $request_data['users_in'] = $match_user_ids;
                $matched_connect_people_count = count($matched_phone_number);

                $return_ary['not_matched_connect_peoples'] = $request_data['phone_numbers'];
                for ($i=0; $i < count($cleanedPhoneNumbers); $i++) {
                    $input = preg_quote($cleanedPhoneNumbers[$i], '~');
                    $data = $matched_phone_number;

                    $result = preg_grep('~' . $input . '~', $data);
                    $result = array_values($result);

                    if(isset($result[0])){
                        unset($return_ary['not_matched_connect_peoples'][$i]);
                    }
                }
                $return_ary['not_matched_connect_peoples'] = array_values($return_ary['not_matched_connect_peoples']);
                $count = $matched_connect_people_count;
            }

            // connect_type filter
            if(isset($request_data['connect_type'])){

                $connect_peoples = $this->getConnectPeople([
                    'user_id' => \Auth::user()->id,
                    'connect_types' => $request_data['connect_type']
                ])->ToArray();

                $latestConnectUserId = array_column($connect_peoples, 'user_id');
                $latestConnectConnectId = array_column($connect_peoples, 'connect_user_id');
                $request_data['connect_type_user_ids'] = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
            }

            // Status check
            if (isset($request_data['status'])) {

                $connect_people_list = $this->getConnectPeople([
                    'auth_connect_id' => \Auth::user()->id,
                    'status_check' => true,
                ])->ToArray();

                $latestConnectUserId = array_column($connect_people_list, 'user_id');
                $latestConnectConnectId = array_column($connect_people_list, 'connect_user_id');
                $user_status_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));

                $request_data['conect_people_status'] = $request_data['status'];
                if (isset($request_data['status']) && ($request_data['status'] == 'Pending' || $request_data['status'] == 'not_connected' || $request_data['status'] == 'Reject')) {
                    if( $request_data['status'] == 'not_connected'){
                        $request_data['status_is_null'] = true;
                    }
                    $request_data['check_connect_id'] = \Auth::user()->id;
                    // $request_data['status'] = $request_data['status'];


                    if( $request_data['status'] == 'Reject'){
                        $request_data['status'] = 'Reject';
                        // $request_data['connect_ids'] = \Auth::user()->id;
                    }
                    else{
                        $request_data['status'] = 'Pending';
                    }
                    // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;

                    $request_data['connect_people_connect_user_id_left_join'] = false;
                    $request_data['connect_people_user_id_left_join'] = true;
                }

                // if ($request_data['status'] == 'pass') {
                //     $refuse_connection = $this->UserRefuseConnectionObj->getUserRefuseConnection([
                //         'user_id_from' =>\Auth::user()->id
                //     ])->ToArray();
                //     $request_data['users_in']= array_column($refuse_connection, 'user_id_to');
                //     // $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                // }

                // if (isset($request_data['status']) && $request_data['status'] == 'Accept' || $request_data['status'] == 'connected' || $request_data['status'] == 'not_connected') {
                if (isset($request_data['status']) && $request_data['status'] == 'Accept') {

                    $connect_people_list = $this->getConnectPeople([
                        'auth_connect_id' => \Auth::user()->id,
                        'status' => 'Accept',
                    ])->ToArray();

                    $latestConnectUserId = array_column($connect_people_list, 'user_id');
                    $latestConnectConnectId = array_column($connect_people_list, 'connect_user_id');
                    $array = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
                    User::saveUpdateUser([
                        'update_id' => \Auth::user()->id,
                        'new_connection_count' => 0,
                    ]);


                    if($request_data['status'] == 'not_connected'){
                        $request_data['users_not_in'] = $array;
                    }else{
                        $request_data['users_in'] = $array;
                    }
                    $request_data['except_auth_id'] = \Auth::user()->id;
                    $request_data['connect_people_connect_user_id_left_join'] = false;
                    $request_data['connect_people_user_id_left_join'] = true;
                    // $request_data['printsql'] =true;
                    unset($request_data['status']);
                }


                // if (isset($request_data['status']) && $request_data['status'] == 'Recommended') {
                //     if (isset($recommended_ids) && count($recommended_ids) > 0) {
                //         $request_data['users_in'] = $recommended_ids;
                //     }
                // }
            }

            $book_mark_user_ids =  $this->bookmark_user();

            if (isset($request_data['status']) && $request_data['status'] == 'Bookmark') {
                unset( $request_data['first_not_null']);
                $request_data['user_id_to'] = $book_mark_user_ids;
            }

            if (isset($request_data['status']) && $request_data['status'] == 'Recommended' && isset($recommended_ids)){


                $accept_connect_people_ids = $this->getConnectPeople([
                    'auth_connect_id' => \Auth::user()->id,
                    'status' => 'Accept',
                ])->ToArray();

                $pending_connect_people_ids = $this->getConnectPeople([
                    'connect_user_id' => \Auth::user()->id,
                    'status' => 'Pending',
                ])->ToArray();

                $refuse_connection = UserRefuseConnection::getUserRefuseConnection([
                    'user_id_from' =>\Auth::user()->id
                ])->ToArray();
                $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                // $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');

                $not_connected_ids = array_column($pending_connect_people_ids, 'user_id');
                $latestConnectUserId = array_column($accept_connect_people_ids, 'user_id');
                $latestConnectConnectId = array_column($accept_connect_people_ids, 'connect_user_id');
                $not_accepted_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
                $refuse_connection_ids = array_unique (array_merge ($not_accepted_ids, $refuse_connection_ids));
                $user_status_ids = array_unique (array_merge ($refuse_connection_ids, $not_connected_ids));
                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;

                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;
                // $connect_people_list = $this->ConnectPeopleObj->getConnectPeople([
                //     'user_id' => \Auth::user()->id,
                //     'status' => 'Pending',
                // ])->ToArray();

                // $pending_ids = array_column($connect_people_list, 'connect_user_id');

                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;
                // $pendings_user = array_unique (array_merge ($user_status_ids, $pending_ids));
                // echo '<pre>'; print_r($pendings_user); echo '</pre>'; exit;

                // $request_data['or_users_in'] = $pendings_user;

                $request_data['or_users_in'] = $user_status_ids;
                // $request_data['status_is_pending'] = 'Pending';

                // $request_data['users_in'] = $recommended_ids;
                // $request_data['users_not_in'] = $array;
                // $request_data['except_auth_id'] = \Auth::user()->id;
                // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
            }
             // Refuse connection check
            if (isset($request_data['pass_connection']) && $request_data['pass_connection'] == 'True') {
                $refuse_connection = UserRefuseConnection::getUserRefuseConnection([
                    'user_id_from' =>\Auth::user()->id
                ])->ToArray();
                $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                $common_ids = array_intersect($refuse_connection_ids, $recommended_ids);

                $common_ids = array_values($common_ids);

                if (isset($refuse_connection_ids) && count($refuse_connection_ids) > 0 ) {
                    // unset($request_data['or_users_in']);
                    // unset($request_data['users_in']);
                    if (isset($common_ids) && count($common_ids) >0) {
                        $request_data['users_not_in'] = $common_ids;
                    }
                }
                else{

                    // echo '<pre>'; print_r($refuse_connection_ids); echo '</pre>'; exit;
                }
            }

            if (isset($request_data['status']) && $request_data['status'] == 'all') {
                unset( $request_data['status']);
            }

            $user_is_blocked = MessageStatus::getMessageStatus([
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
                $request_data['users_not_in'] = array(0 =>\Auth::user()->id);
            }
            if($pitch_filter){
                unset($request_data['paginate']);
                // $user_ids = $connect_peoples->pluck('id')->implode(',');
                // $return_ary['comma_separated_ids'] = $user_ids;
                // $request_data['comma_separated_ids'] = true;
            }
            // $request_data['comma_separated_ids'] = true;
            // echo '<pre>'; print_r($request_data ); echo '</pre>'; exit;
            $connect_peoples = User::getUser($request_data);

            if(isset($connect_peoples) && !$pitch_filter){
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
                    $connect_peoples_value->connect_people = $this->getConnectPeople($connectPeopleList);

                    $connect_peoples_value->is_blocked = false;
                    $user_is_blocked = MessageStatus::getMessageStatus([
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
            $count = User::getUser($request_data);
            $return_ary['matched_connect_peoples'] = $connect_peoples;
            if (isset($connect_peoples) && count($connect_peoples) > 0) {
                $return_ary['matched_connect_peoples'] = $connect_peoples->pluck('id')->implode(',');
            }
            // echo '<pre>'; print_r($return_ary['matched_connect_peoples']); echo '</pre>'; exit;
            return  $return_ary;
        // return $this->sendResponse($return_ary, 'User connect list', $count);
    }
}
