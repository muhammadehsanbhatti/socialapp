<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;
    // protected $encryptable = ['message'];

    public function setMessageAttribute($value) {
        if ($value) {
            $this->attributes['message'] = Crypt::encrypt($value);
        }
        else{
            $this->attributes['message'] = NULL;
        }
    }

    public function getMessageAttribute($value)
    {
        try {
            return $this->attributes['message'] = Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return  $this->attributes['message'] = NULL;
        }
        // if ($value) {
        //     return $this->attributes['message'] = Crypt::decrypt($value);
        // }
        // else{
        //     return  $this->attributes['message'] = NULL;
        // }

    }
    function receiverData()
    {
        return $this->belongsTo(User::class, 'receiver_id','id')->with('companyCareerInfo');
    }
    function pitchData()
    {
        return $this->belongsTo(Pitch::class, 'pitch_id','id')->with('documentAsset');
    }
    function senderData()
    {
        return $this->belongsTo(User::class, 'sender_id','id')->with('companyCareerInfo');
    }
    public function messageAsset()
    {
        return $this->hasOne(MessageAsset::class, 'message_id');
    }
    public function groupData()
    {

        return $this->belongsTo(Group::class, 'group_id')->select('*', \DB::raw('(SELECT MAX(is_delete) FROM group_members WHERE group_members.group_id = groups.id AND group_members.user_id = ' . \Auth::user()->id . ') as is_delete'));


        // return $this->belongsTo(Group::class, 'group_id');

        // return $this->belongsTo(Group::class, 'group_id')->with('groupMember')->where('groupMember.id', \Auth::user()->id);
        // return $this->belongsTo(Group::class, 'group_id')->where('group_members')->select(['id', 'name']);
    }
    public function messageReplyId()
    {
        return $this->belongsTo(Message::class, 'message_reply_id')->with('messageAsset');
    }

    // public function connectPeopleData()
    // {
    //     return $this->belongsTo(ConnectPeople::class, 'connect_user_id');
    // }
    public function messageAssetReplyId()
    {
        return $this->belongsTo(MessageAsset::class, 'message_asset_reply_id');
    }
    public function readMessageStatus()
    {
        return $this->hasOne(ReadMessage::class, 'message_id');
        // return $this->hasOne(ReadMessage::class, 'message_id')->select(['read_message_status']);
    }

    public static function getMessage($posted_data = array())
    {
        $query = Message::latest()
                        ->with('receiverData')
                        ->with('senderData')
                        // ->with('groupData')
                        ->with('messageAsset')
                        ->with('messageReplyId')
                        ->with('readMessageStatus')
                        ->with('messageAssetReplyId')
                        ->with('pitchData')
                        // ->with('connectPeopleData')
        ;

        if (isset($posted_data['auth_user_id'])) {
            $posted_data['auth_user_id'] = $posted_data['auth_user_id'];
        }else{
            $query = $query->with('groupData');
            $posted_data['auth_user_id'] = \Auth::user()->id;
        }

        if (isset($posted_data['id'])) {
            $query = $query->where('messages.id', $posted_data['id']);
        }
        // if (isset($posted_data['message_id_in'])) {
        //     $query = $query->whereIn('messages.id', $posted_data['message_id_in']);
        // }
        if (isset($posted_data['message_id'])) {
            $query = $query->whereIn('messages.id', $posted_data['message_id']);
        }
        if (isset($posted_data['user_message_id'])) {
            $query = $query->where('messages.id','>=', $posted_data['user_message_id']);
        }
        if (isset($posted_data['message'])) {
            $query = $query->where('messages.message', $posted_data['message']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('messages.pitch_id', $posted_data['pitch_id']);
        }
        if (isset($posted_data['type'])) {
            $query = $query->where('messages.type', $posted_data['type']);
        }
        if (isset($posted_data['type_not'])) {
            $query = $query->where('messages.type', '!=', $posted_data['type_not']);
        }
        if (isset($posted_data['message_status'])) {
            $query = $query->where('messages.message_status','=',$posted_data['message_status']);
        }
        if (isset($posted_data['for_me_and_null'])) {

            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->whereNull('messages.message_delete')
                        ->orwhere('messages.message_delete', '=', 'For Me');
                });
            });

        }
        if (isset($posted_data['message_reply_id'])) {
            $query = $query->where('messages.message_reply_id',$posted_data['message_reply_id']);
        }
        if (isset($posted_data['message_asset_reply_id'])) {
            $query = $query->where('messages.message_asset_reply_id',$posted_data['message_asset_reply_id']);
        }
        if (isset($posted_data['is_forwarded'])) {
            $query = $query->where('messages.is_forwarded',$posted_data['is_forwarded']);
        }
        if (isset($posted_data['where_null_message_status'])) {
            $query = $query->whereNull('messages.message_status');
        }
        // if (isset($posted_data['sender_id'])) {
        //     echo '<pre>'; print_r("Dfsd"); echo '</pre>'; exit;
        //     $query = $query->where('messages.sender_id',$posted_data['sender_id']);
        //     $query = $query->where('messages.sender_id','!=',$posted_data['sender_id']);
        // }
        if (isset($posted_data['group_id'])) {
            $query = $query->where('messages.group_id', $posted_data['group_id']);
        }
        if (isset($posted_data['share_user_id'])) {
            $query = $query->where('messages.share_user_id', $posted_data['share_user_id']);
        }
        if (isset($posted_data['group_id_in'])) {
            $query = $query->whereIn('messages.group_id', $posted_data['group_id_in']);
        }
        if (isset($posted_data['connect_along_message'])) {
            $query = $query->where('messages.connect_along_message', $posted_data['connect_along_message']);
        }
        if (isset($posted_data['message_delete'])) {
            $query = $query->where('messages.message_delete', $posted_data['message_delete']);
        }
        if (isset($posted_data['last_seen_message_id'])) {
            $query = $query->where('messages.id','<=', $posted_data['last_seen_message_id']);
        }
        if (isset($posted_data['member_start_message_id'])) {
            $query = $query->where('messages.id','>=', $posted_data['member_start_message_id']);
        }
        if (isset($posted_data['conversation_message_ids_ary'])) {
            $query = $query->whereNotIn('messages.id', $posted_data['conversation_message_ids_ary']);
        }
        if (isset($posted_data['for_me'])) {
            if (isset($posted_data['sender_id'])) {

                $query = $query->Where(function ($query) use ($posted_data) {
                    $query = $query->orWhere(function ($query) use ($posted_data) {
                        $query->where('messages.sender_id', '!=', $posted_data['sender_id'])
                            ->where('messages.message_delete', '=', 'For Me');
                    });
                    $query = $query->orwhereNull('messages.message_delete');
                });
                // $query = $query->orWhere(function ($query) use ($posted_data) {
                //     $query = $query->orWhere(function ($query) use ($posted_data) {
                //         $query = $query->orwhereNull('messages.message_delete');
                //     });
                // });
            }
            else if (isset($posted_data['group_id'])) {

                $query = $query->Where(function ($query) use ($posted_data) {
                    $query = $query->orWhere(function ($query) use ($posted_data) {
                        $query->where('messages.sender_id', '!=', $posted_data['auth_user_id'])
                            ->where('messages.message_delete', '=', 'For Me');
                    });
                    $query = $query->orwhereNull('messages.message_delete');
                });
            }else{
                $query = $query->whereNull('messages.message_delete');
            }
        }
        if (isset($posted_data['sender_id_not'])) {
            $query = $query->where('messages.sender_id','!=',$posted_data['sender_id_not']);
        }
        if (isset($posted_data['where_ids']) || isset($posted_data['group_member_id_in'])) {

            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->where('messages.sender_id', '!=', $posted_data['auth_user_id'])
                        ->where('messages.message_delete', '=', 'For Me');
                });
                $query = $query->orwhereNull('messages.message_delete');
            });

            $query->where(function ($query) use ($posted_data) {
                $query->where('sender_id', $posted_data['auth_user_id'])
                    ->orWhere(function ($query) use ($posted_data){
                        $query->where('receiver_id', $posted_data['auth_user_id'])
                            ->orWhereIn('group_id', $posted_data['group_member_id_in'])
                            ;
                    });
            })
            ->whereNull('message_status')
            // ->whereNull('message_delete')
            ->whereNull('deleted_at');
        }

        if (isset($posted_data['where_sender_ids'])) {
            $query = $query->where('messages.sender_id',$posted_data['auth_user_id']);
            $query = $query->where('messages.receiver_id',$posted_data['where_sender_ids']);
        }
        if (isset($posted_data['where_receiver_ids'])) {
            $query = $query->orwhere('messages.receiver_id',$posted_data['auth_user_id']);
            $query = $query->where('messages.sender_id',$posted_data['where_receiver_ids']);
        }

        if(isset($posted_data['read_sender_id']) && isset($posted_data['read_receiver_id'])){

            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->where('messages.sender_id', '=', $posted_data['read_receiver_id'])
                        ->where('messages.receiver_id', '=', $posted_data['read_sender_id']);
                });
            });

        }
        if(isset($posted_data['unread_group_count'])){

            $query = $query->where('messages.sender_id', '!=', $posted_data['user_id']);

            // $query = $query->Where(function ($query) use ($posted_data) {
            //     $query = $query->where(function ($query) use ($posted_data) {
            //         $query->where('read_messages.read_message_status', '!=', 'Read')
            //                 ->orwhereNull('read_messages.read_message_status')
            //                 ->orwhere('read_messages.user_id', '=', $posted_data['user_id']);
            //     });

            //     // $query = $query->where('read_messages.user_id', '=', $posted_data['user_id']);
            // });
        }
        if(isset($posted_data['exect_sender_id']) && (isset($posted_data['exect_receiver_id']))){
            $query = $query->where('messages.sender_id', $posted_data['exect_sender_id']);
            $query = $query->where('messages.receiver_id', $posted_data['exect_receiver_id']);
        }
        if(isset($posted_data['unread_count'])){
            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->where(function ($query) use ($posted_data) {
                    $query->where('read_messages.read_message_status', '!=', 'Read')
                            ->orwhereNull('read_messages.read_message_status');
                });
                $query = $query->where(function ($query) use ($posted_data) {
                    $query->where('messages.sender_id', '=', $posted_data['receiver_id'])
                        ->where('messages.receiver_id', '=', $posted_data['sender_id']);
                });


            });
        }
        else if(isset($posted_data['sender_id']) && isset($posted_data['receiver_id'])){
            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->where('messages.sender_id', '=', $posted_data['sender_id'])
                        ->where('messages.receiver_id', '=', $posted_data['receiver_id']);
                });
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->where('messages.sender_id', '=', $posted_data['receiver_id'])
                        ->where('messages.receiver_id', '=', $posted_data['sender_id']);
                });
            });

        }else if(isset($posted_data['sender_id'])){
            $query = $query->where('messages.sender_id', $posted_data['sender_id']);

        }else if(isset($posted_data['receiver_id'])){
            $query = $query->where('messages.receiver_id', $posted_data['receiver_id']);
        }
        if (isset($posted_data['latest_data']) && isset($posted_data['user_id'])) {

            // $message_type = "message_statuses.type != 'Archived'";
            // if (isset($posted_data['type']) == 'Archived') {
            //     $message_type = "message_statuses.type = 'Archived'";
            // }
            // $message_type = "(`receiver_id` NOT IN (SELECT block_user_id_to FROM `message_statuses` WHERE `block_user_id_from` = {$posted_data['user_id']} AND type = 'Archived') AND `sender_id` NOT IN (SELECT block_user_id_to FROM `message_statuses` WHERE `block_user_id_from` = {$posted_data['user_id']} AND type = 'Archived') )";
            // if (isset($posted_data['type']) == 'Archived') {
            //     $message_type = "message_statuses.type = 'Archived'";
            // }


            $archiveMessageStatusRes = MessageStatus::getMessageStatus([
                'block_user_id_from' => $posted_data['user_id'],
                'type' => 'Archived',
            ]);
            $archiveMessageStatusRes_ids = $archiveMessageStatusRes->ToArray();
            $archiveMessageStatusRes_ids = array_column($archiveMessageStatusRes_ids, 'block_user_id_to');
            if(is_array($archiveMessageStatusRes_ids) && count($archiveMessageStatusRes_ids)>0){
                $archiveMessageStatusRes_ids = implode(',', $archiveMessageStatusRes_ids);
            }else{
                $archiveMessageStatusRes_ids = '32323213232';
            }

            $message_type = "((`messages`.`receiver_id` NOT IN ({$archiveMessageStatusRes_ids}) AND `messages`.`sender_id` = {$posted_data['user_id']}) OR (`messages`.`sender_id` NOT IN ({$archiveMessageStatusRes_ids}) AND `messages`.`receiver_id` = {$posted_data['user_id']}))";
            if (isset($posted_data['type']) == 'Archived') {
                $message_type = "((`messages`.`receiver_id` IN ({$archiveMessageStatusRes_ids}) AND `messages`.`sender_id` = {$posted_data['user_id']}) OR (`messages`.`sender_id` IN ({$archiveMessageStatusRes_ids}) AND `messages`.`receiver_id` = {$posted_data['user_id']}))";
            }
            $conversation_message_ids = '';
            if (isset($posted_data['conversation_message_ids']) && !empty($posted_data['conversation_message_ids'])) {
                $conversation_message_ids = "AND `messages`.`id` NOT IN ({$posted_data['conversation_message_ids']})";
            }

            $check_member_status =  "AND `group_members`.`member_last_message_id` IS NULL";
            $check_group_member_status = GroupMember::getGroupMember([
                'user_id' => $posted_data['auth_user_id'],
                'check_member_last_id_null' => true,
                'detail' => true,
            ]);

            if(!empty($check_group_member_status)){
                $check_member_status = "AND `group_members`.`member_last_message_id` IS NOT NULL AND `messages`.`id` <= " .$check_group_member_status->member_last_message_id;
            }
            $pagination_query = "";

            // $pagination_query = "LIMIT 0, 5";
            if (isset($posted_data['per_page']) && isset($posted_data['page'])) {


                $start_at = ($posted_data['page']*$posted_data['per_page']) - $posted_data['per_page'];
                // $pagination = ($posted_data['page'] * $posted_data['per_page']) - $posted_data['per_page'] ;
                // $pagination_query =  $posted_data['page'] == 1 ?  $pagination_query :"LIMIT {$posted_data['page']} ,{$pagination}";
                $pagination_query =  "LIMIT ".$start_at.", ".$posted_data['per_page'];

            }

            $search= '';
            if (isset($posted_data['search']) && !empty($posted_data['search'])) {
                $str = $posted_data['search'];
                $search1 = $str;
                $search2 = $str;
                if ($str == trim($str) && strpos($str, ' ') !== false) {
                    $str_ary = explode(' ', $str);
                    $search1 = $str_ary[0];
                    $search2 = $str_ary[1];
                }

                $search = "AND (`users`.`first_name` LIKE '%{$search1}%' OR `users`.`last_name` LIKE '%{$search2}%' OR `groups`.`name` LIKE '%{$str}%')";
                // $search = "AND (`messages`.`message` LIKE '%{$posted_data['search']}%')";
            }

            $select_query = "`messages`.* , `users`.`first_name`,`users`.`last_name`  , `groups`.`name`";
            if (isset($posted_data['count'])) {
                $select_query = "COUNT(*) AS total_count";
            }

            $query1 = "SELECT {$select_query}

                FROM `messages`
                LEFT JOIN users ON users.id = messages.receiver_id
                LEFT JOIN `groups` ON `groups`.id = messages.group_id
                -- LEFT JOIN message_assets ON message_assets.message_id = messages.id
                WHERE (`receiver_id` = {$posted_data['user_id']} OR `sender_id` = {$posted_data['user_id']})
                AND ((`sender_id` = {$posted_data['user_id']} AND `messages`.`message_delete` IS NULL) || (`receiver_id` = {$posted_data['user_id']} AND (`messages`.`message_delete` IS NULL || `messages`.`message_delete` = 'For Me')))
                AND `messages`.`deleted_at` IS NULL
                AND TYPE != 'Group'
                AND `messages`.`id` IN (
                    SELECT MAX(`messages`.`id`) AS max_id
                    FROM `messages`
                    LEFT JOIN users ON users.id = messages.receiver_id
                    -- LEFT JOIN pitches ON pitches.id = messages.pitch_id
                    /* LEFT JOIN message_statuses ON message_statuses.block_user_id_to = messages.receiver_id */

                    WHERE {$message_type} {$search}  AND
                        (`receiver_id` = {$posted_data['user_id']} OR `sender_id` = {$posted_data['user_id']})
                        AND ((`sender_id` = {$posted_data['user_id']} AND `messages`.`message_delete` IS NULL) || (`receiver_id` = {$posted_data['user_id']} AND (`messages`.`message_delete` IS NULL || `messages`.`message_delete` = 'For Me')))
                        AND ((`sender_id` = {$posted_data['user_id']} AND `message_status` IS NULL) || (`sender_id` != {$posted_data['user_id']} AND `message_status` IS NULL))
                        AND `messages`.`deleted_at` IS NULL
                        AND `group_id` IS NULL
                    {$conversation_message_ids}
                    GROUP BY IF(`receiver_id` = {$posted_data['user_id']}, `sender_id`, `receiver_id`)
                )
                OR `messages`.`id` IN (
                    SELECT MAX(messages.`id`) AS max_id
                    FROM `messages`
                    INNER JOIN `groups` ON `groups`.id = messages.group_id
                    INNER JOIN group_members ON group_members.group_id = `groups`.id
                    LEFT JOIN users ON users.id = group_members.user_id

                    WHERE  ".(isset($posted_data['type']) ? " group_members.type = 'Archived'" : "group_members.type = 'Normal'")." AND group_members.user_id = {$posted_data['user_id']}
                        AND ((
                            /*`sender_id` = {$posted_data['user_id']} AND */
                            `messages`.`message_delete` IS NULL) || (`messages`.`sender_id` != {$posted_data['user_id']} AND `messages`.`message_delete` = 'For Me'))
                            -- `messages`.`message_delete` IS NULL) || (`receiver_id` = {$posted_data['user_id']} AND (`messages`.`message_delete` IS NULL || `messages`.`message_delete` = 'For Me')))
                        AND messages.`deleted_at` IS NULL
                        {$conversation_message_ids}
                        {$search}
                        -- AND ((`messages`.`sender_id`, '!=',{$posted_data['user_id']} AND  `messages`.`message_delete`, '=', 'For Me') OR  IS NULL  `messages`.`message_delete`)
                        GROUP BY messages.`group_id`
                )
                ORDER BY `messages`.`id` DESC
                {$pagination_query} ";

                // echo '<pre>'; print_r($query1); echo '</pre>'; exit;
                $records = DB::select($query1);
            return $records;
        }

        // if (isset($posted_data['unread_count']) || isset($posted_data['unread_group_count'])) {
        if (isset($posted_data['unread_count'])) {
            $query->leftjoin('read_messages', 'read_messages.message_id', '=', 'messages.id');
        }

        $query->select('messages.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
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

    public static function saveUpdateMessage($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Message::find($posted_data['update_id']);
        } else {
            $data = new Message;
        }

        if (isset($posted_data['sender_id'])) {
            $data->sender_id = $posted_data['sender_id'];
        }
        if (isset($posted_data['receiver_id'])) {
            $data->receiver_id = $posted_data['receiver_id'];
        }
        if (isset($posted_data['group_id'])) {
            $data->group_id = $posted_data['group_id'];
        }
        if (isset($posted_data['share_user_id'])) {
            $data->share_user_id = $posted_data['share_user_id'];
        }
        if (isset($posted_data['message'])) {
            $data->message = $posted_data['message'];
        }
        if (isset($posted_data['type'])) {
            $data->type = $posted_data['type'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }
        if (isset($posted_data['message_status'])) {
            $data->message_status = $posted_data['message_status'];
        }
        if (isset($posted_data['connect_along_message'])) {
            $data->connect_along_message = $posted_data['connect_along_message'];
        }
        if (isset($posted_data['message_delete'])) {
            $data->message_delete = $posted_data['message_delete'];
        }
        if (isset($posted_data['message_reply_id'])) {
            $data->message_reply_id = $posted_data['message_reply_id'];
        }
        if (isset($posted_data['message_asset_reply_id'])) {
            $data->message_asset_reply_id = $posted_data['message_asset_reply_id'];
        }
        if (isset($posted_data['is_forwarded'])) {
            $data->is_forwarded = $posted_data['is_forwarded'];
        }


        $data->save();
        $data = Message::getMessage([
            'detail' => true,
            'id' => $data->id,
        ]);
        // if(isset($posted_data['receiver_id'])){
        //     send_notification([
        //         'user_id' => \Auth::user()->id,
        //         'receiver_id' => $posted_data['receiver_id'],
        //         'notification_message_id' => 1,
        //         'metadata' => $data
        //     ]);
        // }
        return $data;
    }
    public static function deleteMessage($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Message::find($id);
        }else{
            $data = Message::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['receiver_id'])) {
                $is_deleted = true;
                $data = $data->where('receiver_id', $where_posted_data['receiver_id']);
                $data = $data->where('type', 'Single');
            }
        }
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
