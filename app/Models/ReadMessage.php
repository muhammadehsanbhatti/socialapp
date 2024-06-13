<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadMessage extends Model
{
    use HasFactory;
    public function messageData(){
        return $this->belongsTo(Message::class, 'message_id')->select('id','sender_id','receiver_id','group_id','type','message_status','message_delete','message_reply_id','message_asset_reply_id','is_forwarded','deleted_at','created_at','updated_at');
    }
    public static function getReadMessage($posted_data = array())
    {
        $query = ReadMessage::latest()
                    ->with('messageData')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('read_messages.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('read_messages.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['message_id'])) {
            $query = $query->where('read_messages.message_id', $posted_data['message_id']);
        }
        if (isset($posted_data['read_message_status'])) {
            $query = $query->where('read_messages.read_message_status', $posted_data['read_message_status']);
        }
        if (isset($posted_data['delivered_at'])) {
            $query = $query->where('read_messages.delivered_at', $posted_data['delivered_at']);
        }
        if (isset($posted_data['read_at'])) {
            $query = $query->where('read_messages.read_at', $posted_data['read_at']);
        }
        if (isset($posted_data['message_ids'])) {
            $query = $query->whereIn('read_messages.message_id', $posted_data['message_ids']);
        }
        if (isset($posted_data['not_message_ids'])) {
            $query = $query->whereNotIn('read_messages.message_id', $posted_data['not_message_ids']);
        }
        if (isset($posted_data['conversation_message_ids_ary'])) {
            $query = $query->whereNotIn('read_messages.message_id', $posted_data['conversation_message_ids_ary']);
        }
       
        $query->select('read_messages.*');
        
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

    public static function saveUpdateReadMessage($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ReadMessage::find($posted_data['update_id']);
        } else {
            $data = new ReadMessage;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['message_id'])) {
            $data->message_id = $posted_data['message_id'];
        }
        if (isset($posted_data['read_message_status'])) {
            $data->read_message_status = $posted_data['read_message_status'];
        }
        if (isset($posted_data['delivered_at'])) {
            $data->delivered_at = $posted_data['delivered_at'];
        }
        if (isset($posted_data['read_at'])) {
            $data->read_at = $posted_data['read_at'];
        }
        
        $data->save();
        $data = ReadMessage::getReadMessage([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteReadMessage($id=0)
    {
        $data = ReadMessage::find($id);
        return $data->delete();
    }
}
