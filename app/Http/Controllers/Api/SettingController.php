<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function terms_privacy(Request $request)
    {
        $rules = array(
            'type' => 'required',
        );
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        else
        {
            $get_privacy = $this->TermsAndPrivacyPolicyObj->getTermsAndPrivacyPolicy([
                'type' => $request->type,
                'detail' =>true
            ]);
            return $this->sendResponse($get_privacy, 'Success');
        }
    }

    public function block_users()
    {
        $block_users_list = $this->MessageStatusObj->getMessageStatus([
            'block_user_id_from' => \Auth::user()->id,
            'type' => 'Block'
        ]);
        return $this->sendResponse($block_users_list, 'List of block users');
    }
    public function terminate_account(Request $request)
    {
        $request_data = $request->all();
        $validator = \Validator::make($request_data, [
            'phone_number' => 'required|exists:users,phone_number',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);
        }
        $check_data = $this->UserObj->getUser([
            'id' => \Auth::user()->id,
            'phone_number' => $request_data['phone_number'],
            'detail' =>true
        ]);

        if($check_data) {
            $response =$this->UserObj->deleteUser($check_data->id);
            return $this->sendResponse('Success', 'Your account terminated successfully.');
        }
        else {
            return $this->sendError("error", "Something went wrong");
        }
    }

    public function get_seed_store()
    {
        $package_detail = $this->SeedStoreObj->getSeedStore();
        return $this->sendResponse($package_detail, 'Package deatail.');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posted_data = array();
        $request_data = $request->all();
        $connect_search = $request->all();

        $rules = array();
        if ((isset($request_data['pitch_notification']) && $request_data['pitch_notification'] == 'Check') || (isset($request_data['message_notification']) && $request_data['message_notification'] == 'Check') ) {
            $rules = array(

                'pitch_sound' => 'in:Sound,None,Vibration',
                'pitch_flash_notification' => 'in:Yes,No',
                'message_sound' => 'in:Sound,None,Vibration',
                'message_flash_notification' => 'in:Yes,No',
            );

        }
        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }

        $check_data = $this->SettingObj->getSetting([
            'user_id' => \Auth::user()->id,
            'detail' =>true
        ]);

        $posted_data['user_id'] =  \Auth::user()->id;

        if (isset($request_data['pitch_notification'])) {
            $posted_data['pitch_notification'] = $request_data['pitch_notification'];
            $posted_data['pitch_sound'] = $request_data['pitch_sound'];
            $posted_data['pitch_flash_notification'] = $request_data['pitch_flash_notification'];
        }
        if (isset($request_data['message_notification'])) {
            $posted_data['message_notification'] = $request_data['message_notification'];
            $posted_data['message_sound'] = $request_data['message_sound'];
            $posted_data['message_flash_notification'] = $request_data['message_flash_notification'];
        }
        if ((isset($request_data['connections']) && $request_data['connections'] == 'Manual')) {
            if (isset($request_data['profile_visibility'])) {
                unset($connect_search['profile_visibility']);
            }
            unset($connect_search['connections']);
            $posted_data['connection_search'] = json_encode($connect_search);
        }

        if ((isset($request_data['profile_visibility']) && $request_data['profile_visibility'] == 'Privacy Filter')) {
            if (isset($request_data['connections'])) {
                unset($connect_search['connections']);
            }
            unset($connect_search['profile_visibility']);
            $posted_data['privacy_filter'] = json_encode($connect_search);
        }

        if (isset($request_data['connections'])) {
            $posted_data['connections'] = $request_data['connections'];
        }
        if (isset($request_data['profile_visibility'])) {
            $posted_data['profile_visibility'] = $request_data['profile_visibility'];
        }



        if ($check_data) {
            $posted_data['update_id'] = $check_data->id;
        }
        $data =$this->SettingObj->saveUpdateSetting($posted_data);

        return $this->sendResponse($data, 'Account setting updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        $request_data['update_id'] = $id;
        $validator = \Validator::make($request_data, [
            'old_phone_number' => isset($request->email) ?  'nullable':'required|exists:users,phone_number',
            'phone_number' => isset($request->email) ? 'nullable': 'required|unique:users,phone_number',
            'email' => 'email:rfc,dns|unique:users,email,'.\Auth::user()->id
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), ["error"=>$validator->errors()->first()]);
        }
        $check_user = $this->UserObj->getUser([
            'id' => \Auth::user()->id,
            'detail' =>true
        ]);
        if ($check_user) {
            if (isset($request_data['phone_number'])) {
                $request_data['phone_number'] = $request->phone_number;
            }
            if (isset($request_data['email'])) {
                $request_data['email'] = $request->email;
            }

            $data =$this->UserObj->saveUpdateUser($request_data);
            return $this->sendResponse($data, 'Your account updated successfully.');
        }
        else{
            return $this->sendError("Something went wrong", ["error"=>"Something went wrong"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
