<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralTag extends Model
{
    use HasFactory;
    protected $appends = ['pitches_count'];

    public function getPitchesCountAttribute()
    {
        $pitch_count = 0;
        $pitchTagObj = new \App\Models\PitchTag;
        $pitchObj = new \App\Models\Pitch;
        $user_refuseObj = new \App\Models\UserRefuseConnection;
        $message_statusObj = new \App\Models\MessageStatus;

        $user_refuse_connection = $user_refuseObj->getUserRefuseConnection([
            'user_id_from' => \Auth::user()->id
        ])->pluck('user_id_to')->toArray();

        $user_message_status = $message_statusObj->getMessageStatus([
            'type' => 'Block',
            'block_user_id_from' => \Auth::user()->id,
        ])->pluck('block_user_id_to')->toArray();

        $get_user_ids = array_unique(array_merge($user_refuse_connection, $user_message_status));

        $pitches_data = $pitchObj->getPitch([
            'user_id_in' => $get_user_ids
        ])->pluck('id');

        $pitch_count = $pitchTagObj->getPitchTag([
            'tag_id' => $this->id,
            'pitch_id_not_in' => $pitches_data,
            'count' =>true
        ]);
        return $pitch_count;
    }

    public static function getGeneralTag($posted_data = array())
    {
        $query = GeneralTag::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('general_tags.id', $posted_data['id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('general_tags.title', 'like', '%' . $posted_data['title'] . '%');
        }
        $query->select('general_tags.*');

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

    public function saveUpdateGeneralTag($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = GeneralTag::find($posted_data['update_id']);
        } else {
            $data = new GeneralTag;
        }

        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        $data->save();
        $data = GeneralTag::getGeneralTag([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGeneralTag($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = GeneralTag::find($id);
        }else{
            $data = GeneralTag::latest();
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
