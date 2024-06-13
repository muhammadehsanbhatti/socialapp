<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitcheReply extends Model
{
    use HasFactory;
    
    public function pitchReplyDetail()
    {
        return $this->belongsTo(PitcheReply::class, 'pitch_reply_id');
    }
    public static function getPitcheReply($posted_data = array())
    {
        $query = PitcheReply::latest()
                    ->with('pitchReplyDetail')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('pitche_replies.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('pitche_replies.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('pitche_replies.pitch_id', $posted_data['pitch_id']);
        }
        if (isset($posted_data['pitch_reply_id'])) {
            $query = $query->where('pitche_replies.pitch_reply_id', $posted_data['pitch_reply_id']);
        }
        if (isset($posted_data['reply_message'])) {
            $query = $query->where('pitche_replies.reply_message', $posted_data['reply_message']);
        }
       
        $query->select('pitche_replies.*');
        
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

    public function saveUpdatePitcheReply($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PitcheReply::find($posted_data['update_id']);
        } else {
            $data = new PitcheReply;
        }
        
        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }
        if (isset($posted_data['pitch_reply_id'])) {
            $data->pitch_reply_id = $posted_data['pitch_reply_id'];
        }
        if (isset($posted_data['reply_message'])) {
            $data->reply_message = $posted_data['reply_message'];
        }
        $data->save();
        $data = PitcheReply::getPitcheReply([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletePitcheReply($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PitcheReply::find($id);
        }else{
            $data = PitcheReply::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            
            if (isset($where_posted_data['id'])) {
                $is_deleted = true;
                $data = $data->where('id', $where_posted_data['id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}