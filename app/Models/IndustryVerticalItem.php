<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryVerticalItem extends Model
{
    use HasFactory;

    public function industryVerticalItem()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }

    public static function getIndustryVerticalItem($posted_data = array())
    {
        $query = IndustryVerticalItem::latest()
                                        // ->with('industryVerticalItem');
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('industry_vertical_items.id', $posted_data['id']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('industry_vertical_items.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['general_title_id_in'])) {
            $query = $query->whereIn('industry_vertical_items.general_title_id', $posted_data['general_title_id_in']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('industry_vertical_items.title', 'like', $posted_data['title'].'%');
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('industry_vertical_items.status', $posted_data['status']);
        }
        if (isset($posted_data['category_status'])) {
            $query = $query->where('industry_vertical_items.category_status', $posted_data['category_status']);
        }
        if (isset($posted_data['category_status_in'])) {
            $query = $query->whereIn('industry_vertical_items.category_status', $posted_data['category_status_in']);
        }
        
       
        $query->select('industry_vertical_items.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('industry_vertical_items.id', 'ASC');
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

    public static function saveUpdateIndustryVerticalItem($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = IndustryVerticalItem::find($posted_data['update_id']);
        } else {
            $data = new IndustryVerticalItem;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['user_status'])) {
                $is_updated = true;
                $data = $data->where('user_status', $where_posted_data['user_status']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }
        
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        if (isset($posted_data['category_status'])) {
            $data->category_status = $posted_data['category_status'];
        }
        
        $data->save();
        $data = IndustryVerticalItem::getIndustryVerticalItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteIndustryVerticalItem($id = 0, $where_posted_data = array())
    {

         $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = IndustryVerticalItem::find($id);
        }else{
            $data = IndustryVerticalItem::latest();
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
