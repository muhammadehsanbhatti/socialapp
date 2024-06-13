<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfRoleType extends Model
{
    use HasFactory;
    public function profRoleTypeItem()
    {
        // if(!\Auth::check()){
            return $this->hasMany(ProRoleTypeItem::class,'pro_role_type_id')->orderBy('id', 'ASC');
        // }else{
        //     return $this->hasMany(UserProRoleTypeItem::class,'prof_role_type_id')->with('professionalRoleTypeItem')->where('user_id',\Auth::user()->id)->orderBy('id', 'ASC');
        // }
    }
    public function selectedprofRoleTypeItem()
    {
        // if(!\Auth::check()){
        //     return $this->hasMany(ProRoleTypeItem::class,'pro_role_type_id')->orderBy('id', 'ASC');
        // }else{
            // return $this->hasMany(UserProRoleTypeItem::class,'prof_role_type_id')->with('professionalRoleTypeItem')->where('user_id',\Auth::user()->id)->orderBy('id', 'ASC');
            return $this->hasMany(UserProRoleTypeItem::class, 'prof_role_type_id')

            // ->where(function ($query) {
            //     $query->where('user_pro_role_type_items.user_id', $this->user_id);
            // })
            // ->with('professionalRoleTypeItem')
            // ->join('users','users.id','user_pro_role_type_items.user_id')
            // ->select('user_pro_role_type_items.*')
            ;
            // ->where('user_id',\Auth::user()->id)->orderBy('id', 'ASC');
        // }
    }
    public function generalTitle()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }
    public static function getProfRoleType($posted_data = array())
    {
        $query = ProfRoleType::latest()
        ->with('profRoleTypeItem')
        ->with('generalTitle')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('prof_role_types.id', $posted_data['id']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('prof_role_types.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('prof_role_types.title', $posted_data['title']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('prof_role_types.status', $posted_data['status']);
        }

        $query->select('prof_role_types.*');

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

    public static function saveUpdateProfRoleType($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ProfRoleType::find($posted_data['update_id']);
        } else {
            $data = new ProfRoleType;
        }

        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['professional_role_title'])) {
            $data->title = $posted_data['professional_role_title'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }

        $data->save();
        $data = ProfRoleType::getProfRoleType([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletProfRoleType($id=0)
    {
        $data = ProfRoleType::find($id);
        return $data->delete();
    }
}
