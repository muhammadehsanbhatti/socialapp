<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAsset extends Model
{
    use HasFactory;


    public static function getDocumentAsset($posted_data = array())
    {
        $query = DocumentAsset::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('document_assets.id', $posted_data['id']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('document_assets.pitch_id', $posted_data['pitch_id']);
        }
        if (isset($posted_data['document_assets_ids_in'])) {
            $query = $query->whereIn('document_assets.id', $posted_data['document_assets_ids_in']);
        }
        if (isset($posted_data['path'])) {
            $query = $query->where('document_assets.path', $posted_data['path']);
        }
        if (isset($posted_data['name'])) {
            $query = $query->where('document_assets.name', $posted_data['name']);
        }
        if (isset($posted_data['size'])) {
            $query = $query->where('document_assets.size', $posted_data['size']);
        }
        if (isset($posted_data['extension'])) {
            $query = $query->where('document_assets.extension', $posted_data['extension']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('document_assets.status', $posted_data['status']);
        }
        if (isset($posted_data['pitch_asset_status'])) {
            $query = $query->where('document_assets.pitch_asset_status', $posted_data['pitch_asset_status']);
        }

        $query->select('document_assets.*');

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

    public function saveUpdateDocumentAsset($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = DocumentAsset::find($posted_data['update_id']);
        } else {
            $data = new DocumentAsset;
        }

        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
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
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        if (isset($posted_data['pitch_asset_status'])) {
            $data->pitch_asset_status = $posted_data['pitch_asset_status'];
        }
        $data->save();
        $data = DocumentAsset::getDocumentAsset([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteDocumentAsset($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = DocumentAsset::find($id);
        }else{
            $data = DocumentAsset::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){

            if (isset($where_posted_data['pitch_id'])) {
                $is_deleted = true;
                $data = $data->where('pitch_id', $where_posted_data['pitch_id']);
            }
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
