<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMessageReport extends Model
{
    use HasFactory;

    public static function getUserMessageReport($posted_data = array())
    {
        $query = UserMessageReport::latest()
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('user_message_reports.id', $posted_data['id']);
        }
        if (isset($posted_data['message_status_id'])) {
            $query = $query->where('user_message_reports.message_status_id', $posted_data['message_status_id']);
        }
        if (isset($posted_data['general_title_id'])) {
            $query = $query->where('user_message_reports.general_title_id', $posted_data['general_title_id']);
        }
        if (isset($posted_data['report_message'])) {
            $query = $query->where('user_message_reports.report_message', $posted_data['report_message']);
        }
       
        $query->select('user_message_reports.*');
        
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

    public static function saveUpdateUserMessageReport($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = UserMessageReport::find($posted_data['update_id']);
        } else {
            $data = new UserMessageReport;
        }

        if (isset($posted_data['message_status_id'])) {
            $data->message_status_id = $posted_data['message_status_id'];
        }
        if (isset($posted_data['general_title_id'])) {
            $data->general_title_id = $posted_data['general_title_id'];
        }
        if (isset($posted_data['report_message'])) {
            $data->report_message = $posted_data['report_message'];
        }
        
        $data->save();
        $data = UserMessageReport::getUserMessageReport([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteUserMessageReport($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = UserMessageReport::find($id);
        }else{
            $data = UserMessageReport::latest();
        }
        
        if(isset($where_posted_data) && count($where_posted_data)>0){
            
            if (isset($where_posted_data['user_id'])) {
                $is_deleted = true;
                $data = $data->where('user_id', $where_posted_data['user_id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}