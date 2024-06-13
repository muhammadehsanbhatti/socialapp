<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProRoleTypeItem extends Model
{
    use HasFactory;
    public function proRoleType()
    {
        return $this->belongsTo(ProfRoleType::class, 'pro_role_type_id');
    }
    

    public static function getProRoleTypeItem($posted_data = array())
    {
        $query = ProRoleTypeItem::latest()
        ->with('proRoleType')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('pro_role_type_items.id', $posted_data['id']);
        }
        if (isset($posted_data['pro_role_type_id'])) {
            $query = $query->where('pro_role_type_items.pro_role_type_id', $posted_data['pro_role_type_id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('pro_role_type_items.title', $posted_data['title']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('pro_role_type_items.status', $posted_data['status']);
        }
       
        $query->select('pro_role_type_items.*');
        
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

    public static function saveUpdateProRoleTypeItem($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ProRoleTypeItem::find($posted_data['update_id']);
        } else {
            $data = new ProRoleTypeItem;
        }

       
        if (isset($posted_data['pro_role_type_id'])) {
            $data->pro_role_type_id = $posted_data['pro_role_type_id'];
        }
        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        
        $data->save();
        $data = ProRoleTypeItem::getProRoleTypeItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletProRoleTypeItem($id=0)
    {
        $data = ProRoleTypeItem::find($id);
        return $data->delete();
    }
}
