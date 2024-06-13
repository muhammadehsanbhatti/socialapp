<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIndustyVerticalItem extends Model
{
    use HasFactory;
    public function industryVerticalItem()
    {
        return $this->belongsTo(IndustryVerticalItem::class, 'industry_vertical_item_id');
    }
    public function generalTitle()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }

    public static function getUserIndustyVerticalItem($posted_data = array())
    {
        $query = UserIndustyVerticalItem::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_industy_vertical_items.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_industy_vertical_items.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['industry_vertical_item_id'])) {
            $query = $query->where('user_industy_vertical_items.industry_vertical_item_id', $posted_data['industry_vertical_item_id']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('user_industy_vertical_items.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['general_title_ids'])) {
            $query = $query->whereIn('user_industy_vertical_items.general_title_id', $posted_data['general_title_ids']);
        }
        if (isset($posted_data['intrested_vertical'])) {
            $query = $query->where('user_industy_vertical_items.intrested_vertical', $posted_data['intrested_vertical']);
        }
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_industy_vertical_items.user_id', $posted_data['user_ids']);
        }


        $query->select('user_industy_vertical_items.*');
        
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

    public static function saveUpdateUserIndustyVerticalItem($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserIndustyVerticalItem::find($posted_data['update_id']);
        } else {
            $data = new UserIndustyVerticalItem;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['industry_vertical_item_id'])) {
            $data->industry_vertical_item_id = $posted_data['industry_vertical_item_id'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['intrested_vertical'])) {
            $data->intrested_vertical = $posted_data['intrested_vertical'];
        }
        
        $data->save();
        $data = UserIndustyVerticalItem::getUserIndustyVerticalItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserIndustyVerticalItem($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserIndustyVerticalItem::find($id);
        }else{
            $data = UserIndustyVerticalItem::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            
            if (isset($where_posted_data['user_id'])) {
                $is_deleted = true;
                $data = $data->where('user_id', $where_posted_data['user_id']);
            }
            if(isset($where_posted_data['user_id']) && isset($where_posted_data['intrested_vertical'])) {
                $is_deleted = true;
                $data = $data->where('user_id', $where_posted_data['user_id']);
                $data = $data->where('intrested_vertical', $where_posted_data['intrested_vertical']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
