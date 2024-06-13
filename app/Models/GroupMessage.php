<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    public static function getGroupMessage($posted_data = array())
    {
        $query = GroupMessage::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('group_messages.id', $posted_data['id']);
        }
        if (isset($posted_data['group_id'])) {
            $query = $query->where('group_messages.group_id', $posted_data['group_id']);
        }
        if (isset($posted_data['message_id'])) {
            $query = $query->where('group_messages.message_id', $posted_data['message_id']);
        }
       
        $query->select('group_messages.*');
        
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

    public static function saveUpdateGroupMessage($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = GroupMessage::find($posted_data['update_id']);
        } else {
            $data = new GroupMessage;
        }

        if (isset($posted_data['group_id'])) {
            $data->group_id = $posted_data['group_id'];
        }
        if (isset($posted_data['message_id'])) {
            $data->message_id = $posted_data['message_id'];
        }
        
        $data->save();
        $data = GroupMessage::getGroupMessage([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGroupMessage($id=0)
    {
        $data = GroupMessage::find($id);
        return $data->delete();
    }
}
