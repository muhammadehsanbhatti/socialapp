<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConnectionBookMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_bookmark_connections = $this->ConnectionBookMarkObj->getConnectionBookMark([
            'user_id_from' => \Auth::user()->id
        ]);
        return $this->sendResponse($user_bookmark_connections, 'Bookmark list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requested_data = $request->all();
        $rules = array(
            'user_id_to' => 'required|exists:users,id',
            // 'status' => 'in:Pending,Accept,Reject,Recommended',
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $requested_data['user_id_from'] =  \Auth::user()->id;
        $requested_data['detail'] =  true;
        $bookmarkConnectionDetail = $this->ConnectionBookMarkObj->getConnectionBookMark($requested_data);
        if($bookmarkConnectionDetail){
            $requested_data['update_id'] = $bookmarkConnectionDetail->id;
        }

        $bookmark_connection = $this->ConnectionBookMarkObj->saveUpdateConnectionBookMark($requested_data);
        return $this->sendResponse($bookmark_connection, 'Bookmark user successfully');

    }

    //Pass connection
    public function refuse_connection(Request $request){
        $requested_data = $request->all();
        $rules = array(
            'user_id_to' => 'required|exists:users,id',
            'status' => 'required|in:Pass,',
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $check_already_pass = $this->UserRefuseConnectionObj->getUserRefuseConnection([
            'user_id_to' => $request->user_id_to,
            'user_id_from' =>  \Auth::user()->id,
            'detail' =>true
        ]);
        if ($check_already_pass) {
            return $this->sendError("error" , "You already pass this user");
        }
        $requested_data['user_id_from'] =  \Auth::user()->id;
        $this->UserRefuseConnectionObj->saveUpdateUserRefuseConnection($requested_data);
        return $this->sendResponse("Success", 'User Pass successfully');
    }

    // Remove refuse connection
    public function delete_refuse_connection(Request $request,$id){

        $refuse_connection_detail= $this->UserRefuseConnectionObj->getUserRefuseConnection([
            'user_id_to' =>$id,
            'user_id_from' => \Auth::user()->id,
            'detail' =>true
        ]);
        if (isset($refuse_connection_detail)) {
            $this->UserRefuseConnectionObj->deleteUserRefuseConnection($refuse_connection_detail->id);
            return $this->sendResponse('Success', 'Remove from refuse connection list');
        }
        return $this->sendError("error", "Something went wrong");
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookmark_connection = $this->ConnectionBookMarkObj->deleteConnectionBookMark($id);
        return $this->sendResponse($bookmark_connection, 'Remove user from bookmark successfully');
      
    }
}
