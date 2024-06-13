<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducationalInformation extends Model
{
    use HasFactory;
    public function generalTitle()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }

    public static function getUserEducationalInformation($posted_data = array())
    {
        $query = UserEducationalInformation::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_educational_information.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_educational_information.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_educational_information.user_id', $posted_data['user_ids']);
        } 
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('user_educational_information.general_title_id', $posted_data['general_title_id']);
        } 
        if (isset($posted_data['general_title_ids'])) {
            $query = $query->whereIn('user_educational_information.general_title_id', $posted_data['general_title_ids']);
        } 
        if (isset($posted_data['university_school_name'])) {
            $query = $query->where('user_educational_information.university_school_name', $posted_data['university_school_name']);
        }
        if (isset($posted_data['degree_discipline'])) {
            $query = $query->where('user_educational_information.degree_discipline', $posted_data['degree_discipline']);
        }
       
        if(isset($posted_data['columns'])){
            $query->select($posted_data['columns']);
            // $query->select($posted_data['columns']);
        }
        $query->select('user_educational_information.*');
        
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

    public static function saveUpdateUserEducationalInformation($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserEducationalInformation::find($posted_data['update_id']);
        } else {
            $data = new UserEducationalInformation;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['university_school_name'])) {
            $data->university_school_name = $posted_data['university_school_name'];
        }
        if (isset($posted_data['degree_discipline'])) {
            $data->degree_discipline = $posted_data['degree_discipline'];
        }
        
        $data->save();
        $data = UserEducationalInformation::getUserEducationalInformation([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserEducationalInformation($id = 0, $where_posted_data = array())
    {

        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserEducationalInformation::find($id);
        }else{
            $data = UserEducationalInformation::latest();
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
