<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeedStore extends Model
{
    use HasFactory;
    

    public static function getSeedStore($posted_data = array())
    {
        $query = SeedStore::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('seed_stores.id', $posted_data['id']);
        }
        if (isset($posted_data['package_name'])) {
            $query = $query->where('seed_stores.package_name', $posted_data['package_name']);
        }
        if (isset($posted_data['price'])) {
            $query = $query->where('seed_stores.price', $posted_data['price']);
        }
        if (isset($posted_data['seeds_count'])) {
            $query = $query->where('seed_stores.seeds_count',$posted_data['seeds_count']);
        }

        $query->select('seed_stores.*');
        
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



    public function saveUpdateSeedStore($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = SeedStore::find($posted_data['update_id']);
        } else {
            $data = new SeedStore;
        }

        if (isset($posted_data['package_name'])) {
            $data->package_name = $posted_data['package_name'];
        }
        if (isset($posted_data['price'])) {
            $data->price = $posted_data['price'];
        }
        if (isset($posted_data['seeds_count'])) {
            $data->seeds_count = $posted_data['seeds_count'];
        }

        $data->save();
        
        $data = SeedStore::getSeedStore([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteSeedStore($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = SeedStore::find($id);
        }else{
            $data = SeedStore::latest();
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
