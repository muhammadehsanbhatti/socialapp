<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteVideo extends Model
{
    use HasFactory;

    public static function getFavouriteVideo($posted_data = array())
    {
        $query = FavouriteVideo::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('favorite_vedios.id', $posted_data['id']);
        }
        if (isset($posted_data['upload_vedio_id'])) {
            $query = $query->where('favorite_vedios.upload_vedio_id', $posted_data['upload_vedio_id']);
        }
        if (isset($posted_data['fav_count'])) {
            $query = $query->where('favorite_vedios.fav_count', $posted_data['fav_count']);
        }
        if (isset($posted_data['share_count'])) {
            $query = $query->where('favorite_vedios.share_count', $posted_data['share_count']);
        }
        if (isset($posted_data['download_count'])) {
            $query = $query->where('favorite_vedios.download_count', $posted_data['download_count']);
        }

        $query->select('favorite_vedios.*');

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

    public function saveUpdateFavouriteVideo($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = FavouriteVideo::find($posted_data['update_id']);
        } else {
            $data = new FavouriteVideo;
        }

        if (isset($posted_data['upload_vedio_id'])) {
            $data->upload_vedio_id = $posted_data['upload_vedio_id'];
        }
        if (isset($posted_data['fav_count'])) {
            $data->fav_count = $posted_data['fav_count'];
        }
        if (isset($posted_data['share_count'])) {
            $data->share_count = $posted_data['share_count'];
        }
        if (isset($posted_data['download_count'])) {
            $data->download_count = $posted_data['download_count'];
        }
        $data->save();
        $data = FavouriteVideo::getFavouriteVideo([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteFavouriteVideo($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = FavouriteVideo::find($id);
        }else{
            $data = FavouriteVideo::latest();
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
