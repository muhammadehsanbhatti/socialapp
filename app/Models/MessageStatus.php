<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    use HasFactory;
    public function blockedUserDetail()
    {
        return $this->belongsTo(User::class, 'block_user_id_to')->with('companyCareerInfo')->with('userProfRoleTypeItem');
    }

    public static function getMessageStatus($posted_data = array())
    {
        $query = MessageStatus::latest()
                    ->with('blockedUserDetail')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('message_statuses.id', $posted_data['id']);
        }
        if (isset($posted_data['block_user_id_from'])) {
            $query = $query->where('message_statuses.block_user_id_from', $posted_data['block_user_id_from']);
        }
        if(isset($posted_data['block_id_from'])){

            $query = $query->Where(function ($query) use ($posted_data) {
                $query = $query->orWhere(function ($query) use ($posted_data) {
                    $query->where('message_statuses.block_user_id_from', '=', $posted_data['block_id_from'])
                        ->orwhere('message_statuses.block_user_id_to', '=', $posted_data['block_id_from'])
                        // ->where('message_statuses.type', '=', 'Block')
                        ;
                });
            });

        }
        if (isset($posted_data['block_user_id_to'])) {
            $query = $query->where('message_statuses.block_user_id_to', $posted_data['block_user_id_to']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('message_statuses.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['report_message'])) {
            $query = $query->where('message_statuses.report_message', $posted_data['report_message']);
        }
        if (isset($posted_data['type'])) {
            $query = $query->where('message_statuses.type', $posted_data['type']);
        }
        if (isset($posted_data['block_user_id_to_in'])) {
            $query = $query->whereIn('message_statuses.block_user_id_to', $posted_data['block_user_id_to_in']);
        }

        $query->select('message_statuses.*');
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
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

    public static function saveUpdateMessageStatus($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = MessageStatus::find($posted_data['update_id']);
        } else {
            $data = new MessageStatus;
        }

        if (isset($posted_data['block_user_id_from'])) {
            $data->block_user_id_from = $posted_data['block_user_id_from'];
        }
        if (isset($posted_data['block_user_id_to'])) {
            $data->block_user_id_to = $posted_data['block_user_id_to'];
        }
        if (isset($posted_data['type'])) {
            $data->type = $posted_data['type'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['report_message'])) {
            $data->report_message = $posted_data['report_message'];
        }
        $data->save();
        $data = MessageStatus::getMessageStatus([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteMessageStatus($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = MessageStatus::find($id);
        }else{
            $data = MessageStatus::latest();
        }
        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['block_user_id_from'])) {
                $is_deleted = true;
                $data = $data->where('block_user_id_from', $where_posted_data['block_user_id_from']);
                $data = $data->where('block_user_id_to', $where_posted_data['block_user_id_to']);
                $data = $data->where('type', $where_posted_data['type']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
