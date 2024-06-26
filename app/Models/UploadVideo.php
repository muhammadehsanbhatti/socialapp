<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadVideo extends Model
{
    use HasFactory;
    public static function getUploadVideo($posted_data = array())
    {
        $query = UploadVideo::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('upload_videos.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('upload_videos.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['path'])) {
            $query = $query->where('upload_videos.path', $posted_data['path']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('upload_videos.name', $posted_data['name']);
        }
        if (isset($posted_data['size'])) {
            $query = $query->where('upload_videos.size', $posted_data['size']);
        }
        if (isset($posted_data['extension'])) {
            $query = $query->where('upload_videos.extension', $posted_data['extension']);
        }
        if (isset($posted_data['vedio_status'])) {
            $query = $query->where('upload_videos.vedio_status', $posted_data['vedio_status']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('upload_videos.title', $posted_data['title']);
        }
        if (isset($posted_data['description'])) {
            $query = $query->where('upload_videos.description', $posted_data['description']);
        }
        if (isset($posted_data['adsterra_code'])) {
            $query = $query->where('upload_videos.adsterra_code', $posted_data['adsterra_code']);
        }


        $query->select('upload_videos.*');

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

    public function saveUpdateUploadVideo($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UploadVideo::find($posted_data['update_id']);
        } else {
            $data = new UploadVideo;
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
        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        if (isset($posted_data['description'])) {
            $data->description = $posted_data['description'];
        }

        if (isset($posted_data['adsterra_code'])) {
            if ($posted_data['adsterra_code'] == 'NULL') {
                $data->adsterra_code = NULL;
            }
            else{
                $data->adsterra_code = $posted_data['adsterra_code'];
            }
        }

        $data->save();
        $data = UploadVideo::getUploadVideo([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUploadVideo($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UploadVideo::find($id);
        }else{
            $data = UploadVideo::latest();
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
