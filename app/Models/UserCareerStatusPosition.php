<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCareerStatusPosition extends Model
{
    use HasFactory;
    public function generalTitle()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }

    public static function getUserCareerStatusPosition($posted_data = array())
    {
        $query = UserCareerStatusPosition::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_career_status_positions.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_career_status_positions.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_id_not'])) {
            $query = $query->where('user_career_status_positions.user_id', '!=', $posted_data['user_id_not']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('user_career_status_positions.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['general_title_ids'])) {
            $query = $query->whereIn('user_career_status_positions.general_title_id', $posted_data['general_title_ids']);
        }
        if (isset($posted_data['company_name'])) {
            $query = $query->where('user_career_status_positions.company_name', $posted_data['company_name']);
        }
        if (isset($posted_data['job_title'])) {
            $query = $query->where('user_career_status_positions.job_title', $posted_data['job_title']);
        }
       
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_career_status_positions.user_id', $posted_data['user_ids']);
        }     
        
        // $query->leftjoin('user_career_status_positions', 'user_career_status_positions.user_id', '=', 'group_members.user_id');
        $query->select('user_career_status_positions.*');
        
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

    public static function saveUpdateUserCareerStatusPosition($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserCareerStatusPosition::find($posted_data['update_id']);
        } else {
            $data = new UserCareerStatusPosition;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['company_name'])) {
            $data->company_name = $posted_data['company_name'];
        }
        if (isset($posted_data['job_title'])) {
            $data->job_title = $posted_data['job_title'];
        }
        
        $data->save();
        $data = UserCareerStatusPosition::getUserCareerStatusPosition([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserCareerStatusPosition($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserCareerStatusPosition::find($id);
        }else{
            $data = UserCareerStatusPosition::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            
            if (isset($where_posted_data['user_id'])) {
                $is_deleted = true;
                $data = $data->where('user_id', $where_posted_data['user_id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }

    }
}
