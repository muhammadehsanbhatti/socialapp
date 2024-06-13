<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndPrivacyPolicy extends Model
{
    use HasFactory;

    

    public static function getTermsAndPrivacyPolicy($posted_data = array())
    {
        $query = TermsAndPrivacyPolicy::latest();

        if (isset($posted_data['id'])) {
            $query = $query->where('terms_and_privacy_policies.id', $posted_data['id']);
        }
        if (isset($posted_data['type'])) {
            $query = $query->where('terms_and_privacy_policies.type', $posted_data['type']);
        }
        if (isset($posted_data['description'])) {
            $query = $query->where('terms_and_privacy_policies.description', $posted_data['description']);
        }

        $query->select('terms_and_privacy_policies.*');
        
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



    public function saveUpdateTermsAndPrivacyPolicy($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = TermsAndPrivacyPolicy::find($posted_data['update_id']);
        } else {
            $data = new TermsAndPrivacyPolicy;
        }

      
        if (isset($posted_data['type'])) {
            $data->type = $posted_data['type'];
        }
        if (isset($posted_data['description'])) {
            $data->description = $posted_data['description'];
        }

        $data->save();
        
        $data = TermsAndPrivacyPolicy::getTermsAndPrivacyPolicy([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }
    
    public function deleteTermsAndPrivacyPolicy($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = TermsAndPrivacyPolicy::find($id);
        }else{
            $data = TermsAndPrivacyPolicy::latest();
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
