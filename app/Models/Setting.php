<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;



    public static function getSetting($posted_data = array())
    {
        $query = Setting::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('settings.id', $posted_data['id']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('settings.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_id_in'])) {
            $query = $query->whereIn('settings.user_id', $posted_data['user_id_in']);
        }
        if (isset($posted_data['profile_visibility'])) {
            $query = $query->where('settings.profile_visibility', $posted_data['profile_visibility']);
        }
        if (isset($posted_data['privacy_filter'])) {
            $query = $query->where('settings.privacy_filter', $posted_data['privacy_filter']);
        }
        if (isset($posted_data['connections'])) {
            $query = $query->where('settings.connections', $posted_data['connections']);
        }
        if (isset($posted_data['connection_search'])) {
            $query = $query->where('settings.connection_search', $posted_data['connection_search']);
        }
        if (isset($posted_data['pitch_notification'])) {
            $query = $query->where('settings.pitch_notification', $posted_data['pitch_notification']);
        }
        if (isset($posted_data['pitch_sound'])) {
            $query = $query->where('settings.pitch_sound', $posted_data['pitch_sound']);
        }
        if (isset($posted_data['pitch_flash_notification'])) {
            $query = $query->where('settings.pitch_flash_notification', $posted_data['pitch_flash_notification']);
        }
        if (isset($posted_data['message_notification'])) {
            $query = $query->where('settings.message_notification', $posted_data['message_notification']);
        }
        if (isset($posted_data['message_sound'])) {
            $query = $query->where('settings.message_sound', $posted_data['message_sound']);
        }
        if (isset($posted_data['message_flash_notification'])) {
            $query = $query->where('settings.message_flash_notification', $posted_data['message_flash_notification']);
        }

        $query->select('settings.*');

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



    public function saveUpdateSetting($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Setting::find($posted_data['update_id']);
        } else {
            $data = new Setting;
        }


        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['profile_visibility'])) {
            $data->profile_visibility = $posted_data['profile_visibility'];
        }
        if (isset($posted_data['privacy_filter'])) {
            $data->privacy_filter = $posted_data['privacy_filter'];
        }
        if (isset($posted_data['connections'])) {
            $data->connections = $posted_data['connections'];
        }
        if (isset($posted_data['connection_search'])) {
            $data->connection_search = $posted_data['connection_search'];
        }
        if (isset($posted_data['pitch_notification'])) {
            $data->pitch_notification = $posted_data['pitch_notification'];
        }
        if (isset($posted_data['pitch_sound'])) {
            $data->pitch_sound = $posted_data['pitch_sound'];
        }
        if (isset($posted_data['pitch_flash_notification'])) {
            $data->pitch_flash_notification = $posted_data['pitch_flash_notification'];
        }
        if (isset($posted_data['message_notification'])) {
            $data->message_notification = $posted_data['message_notification'];
        }
        if (isset($posted_data['message_sound'])) {
            $data->message_sound = $posted_data['message_sound'];
        }
        if (isset($posted_data['message_flash_notification'])) {
            $data->message_flash_notification = $posted_data['message_flash_notification'];
        }

        $data->save();

        $data = Setting::getSetting([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }

    public function deleteSetting($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Setting::find($id);
        }else{
            $data = Setting::latest();
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
