<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionBookMark extends Model
{
    use HasFactory;

    public static function getConnectionBookMark($posted_data = array())
    {
        $query = ConnectionBookMark::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('connection_book_marks.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id_from'])) {
            $query = $query->where('connection_book_marks.user_id_from', $posted_data['user_id_from']);
        }
        if (isset($posted_data['user_id_to'])) {
            $query = $query->where('connection_book_marks.user_id_to', $posted_data['user_id_to']);
        }
        $query->select('connection_book_marks.*');
        
        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'DESC');
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

    public function saveUpdateConnectionBookMark($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = ConnectionBookMark::find($posted_data['update_id']);
        } else {
            $data = new ConnectionBookMark;
        }

        if (isset($posted_data['user_id_from'])) {
            $data->user_id_from = $posted_data['user_id_from'];
        }
        if (isset($posted_data['user_id_to'])) {
            $data->user_id_to = $posted_data['user_id_to'];
        }

        $data->save();
        
        $data = ConnectionBookMark::getConnectionBookMark([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteConnectionBookMark($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = ConnectionBookMark::find($id);
        }else{
            $data = ConnectionBookMark::latest();
        }

        // if(isset($where_posted_data) && count($where_posted_data)>0){
        //     if (isset($where_posted_data['name'])) {
        //         $is_deleted = true;
        //         $data = $data->where('name', $where_posted_data['name']);
        //     }
        // }
        
        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
