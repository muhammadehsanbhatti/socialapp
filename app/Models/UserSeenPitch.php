<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSeenPitch extends Model
{
    use HasFactory;
    public static function getUserSeenPitch($posted_data = array())
    {
        $query = UserSeenPitch::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_seen_pitches.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_seen_pitches.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('user_seen_pitches.pitch_id', $posted_data['pitch_id']);
        }

        $query->select('user_seen_pitches.*');
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

    public function saveUpdateUserSeenPitch($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserSeenPitch::find($posted_data['update_id']);
        } else {
            $data = new UserSeenPitch;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }

        $data->save();
        $data = UserSeenPitch::getUserSeenPitch([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserSeenPitch($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserSeenPitch::find($id);
        }else{
            $data = UserSeenPitch::latest();
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
