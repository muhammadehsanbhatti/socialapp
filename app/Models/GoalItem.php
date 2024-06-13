<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalItem extends Model
{
    use HasFactory;

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
    
    public static function getGoalItem($posted_data = array())
    {
        $query = GoalItem::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('goal_items.id', $posted_data['id']);
        }
        if (isset($posted_data['goal_id'])) {
            $query = $query->where('goal_items.goal_id', $posted_data['goal_id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('goal_items.title', $posted_data['title']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('goal_items.status', $posted_data['status']);
        }
       
        $query->select('goal_items.*');
        
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

    public static function saveUpdateGoalItem($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = GoalItem::find($posted_data['update_id']);
        } else {
            $data = new GoalItem;
        }

        if (isset($posted_data['goal_id'])) {
            $data->goal_id = $posted_data['goal_id'];
        }
        if (isset($posted_data['goal_item_title'])) {
            $data->title = $posted_data['goal_item_title'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        
        $data->save();
        $data = GoalItem::getGoalItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletGoalItem($id=0)
    {
        $data = GoalItem::find($id);
        return $data->delete();
    }
    
}
