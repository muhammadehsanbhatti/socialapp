<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserRefuseConnection extends Model
{
    use HasFactory;

    public static function getUserRefuseConnection($posted_data = array())
    {
        $query = UserRefuseConnection::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_refuse_connections.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id_from'])) {
            $query = $query->where('user_refuse_connections.user_id_from', $posted_data['user_id_from']);
        }
        if (isset($posted_data['user_id_to'])) {
            $query = $query->where('user_refuse_connections.user_id_to', $posted_data['user_id_to']);
        }
        if (isset($posted_data['auth_connect_id'])) {
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('user_refuse_connections.user_id_from', $posted_data['auth_connect_id'])
                        ->orwhere('user_refuse_connections.user_id_to', $posted_data['auth_connect_id']);
                });
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('user_refuse_connections.status', $posted_data['status']);
        }
        if (isset($posted_data['comma_separated_ids'])) {
            $result = $query->where('user_refuse_connections.user_id_from', \Auth::user()->id)
                        ->pluck('user_id_to')
                        ->implode(',');
        return $result;
        //     // $query = $query->whereRaw('CONCAT(",", `user_refuse_connections`.`user_id_to`, ",") REGEXP ",(' . \Auth::user()->id . '),"');
        //     // $query = $query->where('user_refuse_connections.user_id_from',  \Auth::user()->id);
        //     // return $result;
        }

        $query->select('user_refuse_connections.*');

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

    public static function saveUpdateUserRefuseConnection($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserRefuseConnection::find($posted_data['update_id']);
        } else {
            $data = new UserRefuseConnection;
        }

        if (isset($posted_data['user_id_from'])) {
            $data->user_id_from = $posted_data['user_id_from'];
        }
        if (isset($posted_data['user_id_to'])) {
            $data->user_id_to = $posted_data['user_id_to'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }

        $data->save();
        $data = UserRefuseConnection::getUserRefuseConnection([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserRefuseConnection($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserRefuseConnection::find($id);
        }else{
            $data = UserRefuseConnection::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){

            if (isset($where_posted_data['user_id_from'])) {
                $is_deleted = true;
                $data = $data->where('user_id_from', $where_posted_data['user_id_from']);
            }
        }
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
