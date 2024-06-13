<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAsset extends Model
{
    use HasFactory;
    // public function messageReplyAsset()
    // {
    //     return $this->belongsTo(Message::class, 'message_id');
    // }
    
    public static function getMessageAsset($posted_data = array())
    {
        $query = MessageAsset::latest()
                        // ->with('messageReplyAsset')
                        // ->with('senderData')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('message_assets.id', $posted_data['id']);
        }
        if (isset($posted_data['message_assets_id'])) {
            $query = $query->whereIn('message_assets.id', $posted_data['message_assets_id']);
        }
        if (isset($posted_data['message_id'])) {
            $query = $query->where('message_assets.message_id', $posted_data['message_id']);
        }
        if (isset($posted_data['path'])) {
            $query = $query->where('message_assets.path', $posted_data['path']);
        }
        if (isset($posted_data['extension'])) {
            $query = $query->where('message_assets.extension',$posted_data['extension']);
        }
        $query->select('message_assets.*');
        
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

    public static function saveUpdateMessageAsset($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = MessageAsset::find($posted_data['update_id']);
        } else {
            $data = new MessageAsset;
        }

        if (isset($posted_data['message_id'])) {
            $data->message_id = $posted_data['message_id'];
        }
        if (isset($posted_data['path'])) {
            $data->path = $posted_data['path'];
        }
        if (isset($posted_data['extension'])) {
            $data->extension = $posted_data['extension'];
        }

        $data->save();
        $data = MessageAsset::getMessageAsset([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteMessageAsset($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = MessageAsset::find($id);
        }else{
            $data = MessageAsset::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['message_id'])) {
                $is_deleted = true;
                $data = $data->where('message_id', $where_posted_data['message_id']);
                // $data = $data->where('type', 'Single');
            }
        }
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}