<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function groupMember()
    {
        return $this->hasMany(GroupMember::class, 'group_id')->with('userData');
    }
    // public function groupMessage()
    // {
    //     return $this->hasMany(Message::class, 'group_id');
    // }
    public static function getGroup($posted_data = array())
    {
        $query = Group::latest()
                    ->with('groupMember')
                    // ->with('groupMessage')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('groups.id', $posted_data['id']);
        }
        if (isset($posted_data['ids'])) {
            $query = $query->whereIn('groups.id', $posted_data['ids']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('groups.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('groups.name', $posted_data['name']);
        }
        if (isset($posted_data['group_id'])) {
            $query = $query->where('groups.id', $posted_data['group_id']);
        }
        if (isset($posted_data['image'])) {
            $query = $query->where('groups.image', $posted_data['image']);
        }
       
        $query->select('groups.*');
        
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

    public static function saveUpdateGroup($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Group::find($posted_data['update_id']);
        } else {
            $data = new Group;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }
        if (isset($posted_data['image'])) {
            $data->image = $posted_data['image'];
        }
        
        $data->save();
        $data = Group::getGroup([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGroup($id=0)
    {
        $data = Group::find($id);
        return $data->delete();
    }
}
