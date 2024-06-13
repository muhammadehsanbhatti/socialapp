<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitchContribution extends Model
{
    use HasFactory;


    public static function getPitchContribution($posted_data = array())
    {
        $query = PitchContribution::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('pitch_contributions.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('pitch_contributions.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('pitch_contributions.pitch_id', $posted_data['pitch_id']);
        }
        if (isset($posted_data['message'])) {
            $query = $query->where('pitch_contributions.message', $posted_data['message']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('pitch_contributions.status', $posted_data['status']);
        }

        $query->select('pitch_contributions.*');

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

    public function saveUpdatePitchContribution($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PitchContribution::find($posted_data['update_id']);
        } else {
            $data = new PitchContribution;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }
        if (isset($posted_data['message'])) {
            $data->message = $posted_data['message'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        $data->save();
        $data = PitchContribution::getPitchContribution([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletePitchContribution($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PitchContribution::find($id);
        }else{
            $data = PitchContribution::latest();
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
