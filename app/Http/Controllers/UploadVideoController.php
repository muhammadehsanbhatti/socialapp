<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadVideoController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:upload-social-video-list|upload-social-video-edit|upload-social-video-delete', ['only' => ['index']]);
        $this->middleware('permission:upload-social-video-create', ['only' => ['create','store']]);
        $this->middleware('permission:upload-social-video-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        // $data['all_roles'] = $this->RoleObj->getRoles(['roles_not_in' => [1]]);
        // $data['assigned_roles'] = \Auth::user()->getRoleNames();

        $posted_data = $request->all();
        $posted_data['paginate'] = 10;

        $data['roles'] = \Auth::user()['roles'][0]['id'];
        if (\Auth::user()['roles'][0]['id'] == '3') {
            $posted_data['user_id'] = \Auth::user()->id;
        }
        $data['social_video_record'] = $this->UploadVideoObj->getUploadVideo($posted_data);

        $data['html'] = view('upload_video.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('upload_video.list', compact('data'));
    }

    public function verify_social_video(Request $request, $id)
    {
        // Assuming you have a model named Video
        $uploaded_video_detail = $this->UploadVideoObj->getUploadVideo([
            'id' => $id,
            'detail' => true
        ]);


        if ($uploaded_video_detail) {
            $this->UploadVideoObj->saveUpdateUploadVideo([
                'update_id' => $id,
                'vedio_status' => $request->vedio_status,
            ]);
            $response['status'] = true;
            $response['new_status'] = $uploaded_video_detail->vedio_status;

        }
        $response['message'] = 'Video status updated successfully';
        return response()->json($response, 200);
    }


    public function add_adsterra(Request $request, $id)
    {
        // Assuming you have a model named Video
        $uploaded_video_detail = $this->UploadVideoObj->getUploadVideo([
            'id' => $id,
            'detail' => true
        ]);
        if ($uploaded_video_detail) {
            $this->UploadVideoObj->saveUpdateUploadVideo([
                'update_id' => $id,
                'adsterra_code' => $request->adsterra_code,
            ]);
            $response['status'] = true;
            $response['new_status'] = $uploaded_video_detail->adsterra_code;

        }
        $response['message'] = 'Adsterra code updated successfully';

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('upload_video.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posted_data = $request->all();
        $rules = array(
            'title' => 'required',
            'upload_file' => 'file|required',
        );
        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {

            try{
                if ($request->file('upload_file')) {
                    $extension = $request->file('upload_file')->getClientOriginalExtension();
                    if ($extension == 'mp4') {
                        $file_name = $request->file('upload_file')->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                        $fileSize = $request->file('upload_file')->getSize();
                        $filePath = $request->file('upload_file')->storeAs('social_video', $file_name, 'public');
                        $posted_data['upload_file'] = 'storage/social_video/' . $file_name;

                       $pitch_asset_data =  $this->UploadVideoObj->saveUpdateUploadVideo([
                            'user_id' => \Auth::user()->id? \Auth::user()->id : NULL,
                            'title' => $posted_data['title'],
                            'description' => $posted_data['description'],
                            'vedio_status' => 'Pending',
                            'path' => $posted_data['upload_file'],
                            'name' => $request->file('upload_file')->getClientOriginalName(),
                            'size' => $fileSize,
                            'extension' => $extension,
                            'status' => 'Pitch Asset'
                        ]);
                    }
                    else{
                        $error_message['error'] = 'Uploaded file Only allowled  mp4 format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }

                \Session::flash('message', 'You uploaded social video Successfully!');
                }

            } catch (Exception $e) {
                \Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            // return redirect()->back()->withInput();
            return redirect('/upload_social_video');
        }
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
        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;

        $data  = $this->UploadVideoObj->getUploadVideo($posted_data);
        return view('upload_video.add',compact('data'));
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
        $posted_data = $request->all();
        $posted_data['update_id'] = $id;
        $rules = array(
            'title' => 'required',
            'upload_file' => 'file',
        );
        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {

            try{
                if ($request->file('upload_file')) {
                    $base_url = public_path();
                    $extension = $request->file('upload_file')->getClientOriginalExtension();
                    if ($extension == 'mp4') {

                        $auth_user_detail  = $this->UploadVideoObj->getUploadVideo($posted_data);

                        if (!empty($auth_user_detail[0]->path)) {
                            $oldVideoPath = $base_url.'/'.$auth_user_detail[0]->path;
                            if (file_exists($oldVideoPath)) {
                                unlink($oldVideoPath);
                            }
                        }

                        $file_name = $request->file('upload_file')->getClientOriginalName() . '_' . time() . '_'  . rand(1000000, 9999999) . '.' . $extension;
                        $fileSize = $request->file('upload_file')->getSize();
                        $filePath = $request->file('upload_file')->storeAs('social_video', $file_name, 'public');
                        $posted_data['upload_file'] = 'storage/social_video/' . $file_name;

                       $pitch_asset_data =  $this->UploadVideoObj->saveUpdateUploadVideo([
                            'update_id' => $id,
                            'user_id' => \Auth::user()->id? \Auth::user()->id : NULL,
                            'title' => $posted_data['title'],
                            'description' => $posted_data['description'],
                            'vedio_status' => 'Pending',
                            'path' => $posted_data['upload_file'],
                            'name' => $request->file('upload_file')->getClientOriginalName(),
                            'size' => $fileSize,
                            'extension' => $extension,
                            'status' => 'Pitch Asset'
                        ]);
                    }
                    else{
                        $error_message['error'] = 'Uploaded file Only allowled  mp4 format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }

                \Session::flash('message', 'You uploaded social video updated Successfully!');
                }

            } catch (Exception $e) {
                \Session::flash('error_message', $e->getMessage());
            }
            return redirect('/upload_social_video');
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
        //
    }
}
