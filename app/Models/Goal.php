<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goal extends Model
{
    use HasFactory;
    protected $appends = ['icon_url'];

    public function getIconUrlAttribute()
    {
        return $this->attributes['icon_url'] = asset($this->icon);
    }

    public function goalItem()
    {
        return $this->hasMany(GoalItem::class);
    }

    public static function getGoal($posted_data = array())
    {
        $query = Goal::latest()
        ->with('goalItem')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('goals.id', $posted_data['id']);
        }
        if (isset($posted_data['icon'])) {
            $query = $query->where('goals.icon', $posted_data['icon']);
        }
        if (isset($posted_data['goal_number'])) {
            $query = $query->where('goals.goal_number', $posted_data['goal_number']);
        }
       
        $query->select('goals.*');
        
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

    public function saveUpdateGoal($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Goal::find($posted_data['update_id']);
        } else {
            $data = new Goal;
        }

        if (isset($posted_data['icon'])) {
            $data->icon = $posted_data['icon'];
        }
        if (isset($posted_data['goal_number'])) {
            $data->goal_number = $posted_data['goal_number'];
        }
        
        $data->save();
        $data = Goal::getGoal([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGoal($id=0)
    {
        $data = Goal::find($id);
        return $data->delete();
    }
    
}
