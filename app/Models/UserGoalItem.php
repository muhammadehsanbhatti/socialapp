<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGoalItem extends Model
{
    use HasFactory;
    public function goalItem()
    {
        return $this->belongsTo(GoalItem::class, 'goal_item_id')->with('goal');
    }

    public static function getUserGoalItem($posted_data = array())
    {
        $query = UserGoalItem::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_goal_items.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_goal_items.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_goal_items.user_id', $posted_data['user_ids']);
        }
        if (isset($posted_data['goal_item_id'])) {
            $query = $query->where('user_goal_items.goal_item_id', $posted_data['goal_item_id']);
        }  
        if (isset($posted_data['goal_item_ids'])) {
            $query = $query->whereIn('user_goal_items.goal_item_id', $posted_data['goal_item_ids']);
        } 
       
        $query->select('user_goal_items.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('user_goal_items.id', 'ASC');
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

    public static function saveUpdateUserGoalItem($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserGoalItem::find($posted_data['update_id']);
        } else {
            $data = new UserGoalItem;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['goal_item_id'])) {
            $data->goal_item_id = $posted_data['goal_item_id'];
        }
        
        $data->save();
        $data = UserGoalItem::getUserGoalItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserGoalItem($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserGoalItem::find($id);
        }else{
            $data = UserGoalItem::latest();
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
