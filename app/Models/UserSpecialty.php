<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpecialty extends Model
{
    use HasFactory;
    public function generalTitle()
    {
        return $this->belongsTo(IndustryVerticalItem::class, 'industry_vertical_item_id');
    }

    public static function getUserSpecialty($posted_data = array())
    {
        $query = UserSpecialty::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_specialties.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_specialties.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_specialties.user_id', $posted_data['user_ids']);
        }
        if (isset($posted_data['industry_vertical_item_ids'])) {
            $query = $query->whereIn('user_specialties.industry_vertical_item_id', $posted_data['industry_vertical_item_ids']);
        }
        if (isset($posted_data['industry_vertical_item_id'])) {
            $query = $query->where('user_specialties.industry_vertical_item_id', $posted_data['industry_vertical_item_id']);
        }
       
        $query->select('user_specialties.*');
        
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

    public static function saveUpdateUserSpecialty($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserSpecialty::find($posted_data['update_id']);
        } else {
            $data = new UserSpecialty;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['industry_vertical_item_id'])) {
            $data->industry_vertical_item_id = $posted_data['industry_vertical_item_id'];
        }
        $data->save();
        $data = UserSpecialty::getUserSpecialty([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserSpecialty($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserSpecialty::find($id);
        }else{
            $data = UserSpecialty::latest();
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
