<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;
    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id')->with('companyCareerInfo');
    }

    public static function getGroupMember($posted_data = array())
    {
        $query = GroupMember::latest()
                        // ->with('userData')
                        // ->with('userCareerStatusPosition')
        ;
        if (!isset($posted_data['userData'])) {
            $query = $query->with('userData');
        }

        if (isset($posted_data['id'])) {
            $query = $query->where('group_members.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('group_members.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['group_id'])) {
            $query = $query->where('group_members.group_id', $posted_data['group_id']);
        }    
        if (isset($posted_data['user_id_not'])) {
            $query = $query->where('group_members.user_id','!=', $posted_data['user_id_not']);
        }
        if (isset($posted_data['user_id_in'])) {
            $query = $query->whereIn('group_members.user_id', $posted_data['user_id_in']);
        }
        if (isset($posted_data['user_id_not_in'])) {
            $query = $query->whereNotIn('group_members.user_id', $posted_data['user_id_not_in']);
        }
        if (isset($posted_data['type'])) {
            $query = $query->where('group_members.type', $posted_data['type']);
        }
        if (isset($posted_data['check_member_last_id'])) {
            $query = $query->whereNotNull('group_members.member_last_message_id');
        } 
        if (isset($posted_data['check_member_last_id_null'])) {
            $query = $query->whereNull('group_members.member_last_message_id');
        }
        if (isset($posted_data['check_member_status'])) {
            $query = $query->whereNull('group_members.member_status');
            $query = $query->where('group_members.is_delete','False');
        }
        if (isset($posted_data['member_status'])) {
            $query = $query->where('group_members.member_status', $posted_data['member_status']);
        }
        if (isset($posted_data['not_blocked'])) {
            $query = $query->whereNull('group_members.member_status');
        }
        if (isset($posted_data['member_last_message_id'])) {
            $query = $query->where('group_members.member_last_message_id', $posted_data['member_last_message_id']);
        }
        if (isset($posted_data['is_delete'])) {
            $query = $query->where('group_members.is_delete', $posted_data['is_delete']);
        }
        
        if (isset($posted_data['is_admin'])) {
            $query = $query->where('group_members.is_admin', $posted_data['is_admin']);
        }
       
        $query->select('group_members.*');
        
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

    public function saveUpdateGroupMember($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = GroupMember::find($posted_data['update_id']);
        } else {
            $data = new GroupMember;
        }

        if (isset($posted_data['member_id'])) {
            $data->user_id = $posted_data['member_id'];
        }
        if (isset($posted_data['group_id'])) {
            $data->group_id = $posted_data['group_id'];
        }
        if (isset($posted_data['type'])) {
            $data->type = $posted_data['type'];
        }
        if (isset($posted_data['member_status'])) {
            $data->member_status = $posted_data['member_status'];
        }
        if (isset($posted_data['member_last_message_id'])) {
            if ($posted_data['member_last_message_id'] == 'NULL') {
                $data->member_last_message_id = NULL;
            }
            else{
                $data->member_last_message_id = $posted_data['member_last_message_id'];
            }
        }
        if (isset($posted_data['is_delete'])) {
            $data->is_delete = $posted_data['is_delete'];
        }
        if (isset($posted_data['is_admin'])) {
            $data->is_admin = $posted_data['is_admin'];
        }
        $data->save();
        $data = GroupMember::getGroupMember([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGroupMember($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = GroupMember::find($id);
        }else{
            $data = GroupMember::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            
            if (isset($where_posted_data['id'])) {
                $is_deleted = true;
                $data = $data->where('id', $where_posted_data['id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
