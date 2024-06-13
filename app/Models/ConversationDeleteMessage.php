<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationDeleteMessage extends Model
{
    use HasFactory;

    public static function getConversationDeleteMessage($posted_data = array())
    {
        $query = ConversationDeleteMessage::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('conversation_delete_messages.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id_from'])) {
            $query = $query->where('conversation_delete_messages.user_id_from', $posted_data['user_id_from']);
        }
        if (isset($posted_data['message_id'])) {
            $query = $query->where('conversation_delete_messages.message_id', $posted_data['message_id']);
        }
        if (isset($posted_data['message_id_in'])) {
            $query = $query->whereIn('conversation_delete_messages.message_id', $posted_data['message_id_in']);
        }
       
        $query->select('conversation_delete_messages.*');
        
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

    public function saveUpdateConversationDeleteMessage($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ConversationDeleteMessage::find($posted_data['update_id']);
        } else {
            $data = new ConversationDeleteMessage;
        }

        if (isset($posted_data['user_id_from'])) {
            $data->user_id_from = $posted_data['user_id_from'];
        }
        if (isset($posted_data['message_id'])) {
            $data->message_id = $posted_data['message_id'];
        }
        
        $data->save();
        $data = ConversationDeleteMessage::getConversationDeleteMessage([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteConversationDeleteMessage($id=0)
    {
        $data = ConversationDeleteMessage::find($id);
        return $data->delete();
    }
}
