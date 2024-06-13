<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitchShare extends Model
{
    use HasFactory;
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'share_to_user');
    }
    public function groupDetail()
    {
        return $this->belongsTo(User::class, 'share_to_group');
    }


    public static function getPitchShare($posted_data = array())
    {
        $query = PitchShare::latest()
        ;
        if(isset($posted_data['with_user_detail'])){
            $query = $query->with('userDetail');
        }
        if(isset($posted_data['with_group_detail'])){
            $query = $query->with('groupDetail');
        }

        if (isset($posted_data['id'])) {
            $query = $query->where('pitch_shares.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('pitch_shares.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('pitch_shares.pitch_id', $posted_data['pitch_id']);
        }
        if (isset($posted_data['share_to_user'])) {
            $query = $query->where('pitch_shares.share_to_user', $posted_data['share_to_user']);
        }
        if (isset($posted_data['share_to_group'])) {
            $query = $query->where('pitch_shares.share_to_group', $posted_data['share_to_group']);
        }
        if (isset($posted_data['share_to_social'])) {
            $query = $query->where('pitch_shares.share_to_social', $posted_data['share_to_social']);
        }

        $query->select('pitch_shares.*');
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

    public function saveUpdatePitchShare($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PitchShare::find($posted_data['update_id']);
        } else {
            $data = new PitchShare;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }
        if (isset($posted_data['share_to_user'])) {
            $data->share_to_user = $posted_data['share_to_user'];
        }
        if (isset($posted_data['share_to_group'])) {
            $data->share_to_group = $posted_data['share_to_group'];
        }
        if (isset($posted_data['share_to_social'])) {
            $data->share_to_social = $posted_data['share_to_social'];
        }

        $data->save();
        $data = PitchShare::getPitchShare([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletePitchShare($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PitchShare::find($id);
        }else{
            $data = PitchShare::latest();
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
