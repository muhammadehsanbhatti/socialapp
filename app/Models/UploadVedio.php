<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadVedio extends Model
{
    use HasFactory;
    

    public static function getUploadVedio($posted_data = array())
    {
        $query = UploadVedio::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('upload_vedios.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('upload_vedios.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['path'])) {
            $query = $query->where('upload_vedios.path', $posted_data['path']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('upload_vedios.name', $posted_data['name']);
        }
        if (isset($posted_data['size'])) {
            $query = $query->where('upload_vedios.size', $posted_data['size']);
        }
        if (isset($posted_data['extension'])) {
            $query = $query->where('upload_vedios.extension', $posted_data['extension']);
        }
        if (isset($posted_data['vedio_status'])) {
            $query = $query->where('upload_vedios.vedio_status', $posted_data['vedio_status']);
        }

        $query->select('upload_vedios.*');

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

    public function saveUpdateUploadVedio($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UploadVedio::find($posted_data['update_id']);
        } else {
            $data = new UploadVedio;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['path'])) {
            $data->path = $posted_data['path'];
        }
        if (isset($posted_data['name'])) {
            $data->name = $posted_data['name'];
        }
        if (isset($posted_data['size'])) {
            $data->size = $posted_data['size'];
        }
        if (isset($posted_data['extension'])) {
            $data->extension = $posted_data['extension'];
        }
        if (isset($posted_data['vedio_status'])) {
            $data->vedio_status = $posted_data['vedio_status'];
        }
        $data->save();
        $data = UploadVedio::getUploadVedio([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUploadVedio($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UploadVedio::find($id);
        }else{
            $data = UploadVedio::latest();
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
