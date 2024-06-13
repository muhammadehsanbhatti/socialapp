<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProRoleTypeItem extends Model
{
    use HasFactory;
    public function professionalRoleTypeItem()
    {
        return $this->belongsTo(ProRoleTypeItem::class, 'prof_role_type_item_id');
        // return $this->belongsTo(ProRoleTypeItem::class, 'prof_role_type_item_id')->with('proRoleType');
    }

    public function generalTitle()
    {
        return $this->belongsTo(GeneralTitle::class, 'general_title_id');
    }
    public function proRoleType()
    {
        // if (\URL::current() == asset(url('api/connect_people_list'))) {
        //     return $this->belongsTo(ProfRoleType::class, 'prof_role_type_id')
        //     ->with('profRoleTypeItem');
        // }
        // else{
            return $this->belongsTo(ProfRoleType::class, 'prof_role_type_id')
            // ->with('profRoleTypeItem')
            // ->with('selectedProfRoleTypeItem')
            ;
        // }


        // return $this->belongsTo(ProfRoleType::class, 'prof_role_type_id')
        // // ->with('profRoleTypeItem')
        // ->with('selectedProfRoleTypeItem');
    }


    public function selectedprofRoleTypeItem()
    {
        // if(!\Auth::check()){
        //     return $this->hasMany(ProRoleTypeItem::class,'pro_role_type_id')->orderBy('id', 'ASC');
        // }else{
            // return $this->hasMany(UserProRoleTypeItem::class,'prof_role_type_id')->with('professionalRoleTypeItem')->where('user_id',\Auth::user()->id)->orderBy('id', 'ASC');
            return $this->hasMany(UserProRoleTypeItem::class, 'user_id', 'user_id')

            // ->where(function ($query) {
            //     $query->where('user_pro_role_type_items.user_id', $this->user_id);
            // })
            ->with('professionalRoleTypeItem')
            // ->join('users','users.id','user_pro_role_type_items.user_id')
            // ->select('user_pro_role_type_items.*')
            ;
            // ->where('user_id',\Auth::user()->id)->orderBy('id', 'ASC');
        // }
    }

    public static function getUserProRoleTypeItem($posted_data = array())
    {
        $query = UserProRoleTypeItem::latest()
                                        ->with('professionalRoleTypeItem')
                                        ->with('generalTitle')
                                        ->with('proRoleType')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_pro_role_type_items.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('user_pro_role_type_items.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('user_pro_role_type_items.user_id', $posted_data['user_ids']);
        }
        if (isset($posted_data['general_title_ids'])) {
            $query = $query->whereIn('user_pro_role_type_items.general_title_id', $posted_data['general_title_ids']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('user_pro_role_type_items.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['prof_role_type_id'])) {
            $query = $query->where('user_pro_role_type_items.prof_role_type_id', $posted_data['prof_role_type_id']);
        }
        if (isset($posted_data['prof_role_type_item_id'])) {
            $query = $query->where('user_pro_role_type_items.prof_role_type_item_id', $posted_data['prof_role_type_item_id']);
        }
        if (isset($posted_data['prof_role_type_item_ids'])) {
            $query = $query->whereIn('user_pro_role_type_items.prof_role_type_item_id', $posted_data['prof_role_type_item_ids']);
        }

        $query->select('user_pro_role_type_items.*');

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

    public static function saveUpdateUserProRoleTypeItem($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserProRoleTypeItem::find($posted_data['update_id']);
        } else {
            $data = new UserProRoleTypeItem;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['prof_role_type_id'])) {
            $data->prof_role_type_id = $posted_data['prof_role_type_id'];
        }
        if (isset($posted_data['prof_role_type_item_id'])) {
            $data->prof_role_type_item_id = $posted_data['prof_role_type_item_id'];
        }
        $data->save();
        $data = UserProRoleTypeItem::getUserProRoleTypeItem([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserProRoleTypeItem($id = 0, $where_posted_data = array())
    {

         $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserProRoleTypeItem::find($id);
        }else{
            $data = UserProRoleTypeItem::latest();
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
