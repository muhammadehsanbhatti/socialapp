<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PitchTag extends Model
{
    use HasFactory;

    public function tagDetail()
    {
        return $this->belongsTo(GeneralTag::class, 'tag_id');
    }

    public static function getPitchTag($posted_data = array())
    {
        $query = PitchTag::latest()
                    ->with('tagDetail')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('pitch_tags.id', $posted_data['id']);
        }
        if (isset($posted_data['tag_id'])) {
            $query = $query->where('pitch_tags.tag_id', $posted_data['tag_id']);
        }
        if (isset($posted_data['pitch_id_not_in'])) {
            $query = $query->whereNotIn('pitch_tags.pitch_id', $posted_data['pitch_id_not_in']);
        }
        if (isset($posted_data['pitch_id'])) {
            $query = $query->where('pitch_tags.pitch_id', $posted_data['pitch_id']);
        }
        $query->select('pitch_tags.*');
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

    public function saveUpdatePitchTag($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = PitchTag::find($posted_data['update_id']);
        } else {
            $data = new PitchTag;
        }

        if (isset($posted_data['tag_id'])) {
            $data->tag_id = $posted_data['tag_id'];
        }
        if (isset($posted_data['pitch_id'])) {
            $data->pitch_id = $posted_data['pitch_id'];
        }
        $data->save();
        $data = PitchTag::getPitchTag([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletePitchTag($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = PitchTag::find($id);
        }else{
            $data = PitchTag::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){

            if (isset($where_posted_data['pitch_id'])) {
                $is_deleted = true;
                $data = $data->where('pitch_id', $where_posted_data['pitch_id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
