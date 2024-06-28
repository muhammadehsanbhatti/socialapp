<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Hash;
use App\Exports\ExportData;
use Illuminate\Support\Arr;
use App\Models\User;

class UserController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:user-list|user-edit|user-delete|user-status', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit|user-status|edit-profile', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user-change-password', ['only' => ['changePassword']]);

    }

    public function testing() {
        $perPage = 50;

        $user_data = $this->UserObj->getUser([
            'user_data' => true,
            'paginate' => $perPage,
            'page' => 1,

        ]);

        foreach ($user_data as $key => $user_detail) {
            $user_detail = $this->UserObj->saveUpdateUser([
                'update_id' => $user_detail->id,
                'user_name' => strtolower($user_detail->first_name) . '' . strtolower($user_detail->last_name) . '_' . mt_rand(1, 999)
            ]);
            echo '<pre>'; print_r($user_detail->id); echo '</pre>';
        }

        echo '<pre>'; print_r('testing'); '</pre>'; exit;
    }

    public function appChat()
    {
        return view('app-chat');
    }

    public function welcome(Request $request)
    {
        $page = $request->query('page', 1); // Get the current page from query string, default to 1
        $perPage = 5;

        $data = $this->UploadVideoObj->getUploadVideo([
            'vedio_status' => 'Approved',
            'paginate' => $perPage,
            'page' => $page
        ]);

        if ($request->ajax()) {
            return view('upload_video.videos', compact('data'))->render();
        }

        return view('default', compact('data'));
        // return view('welcome');
    }

    public function login()
    {
        return view('auth_v1.login');
    }

    public function register()
    {
        return view('auth_v1.register');
    }

    public function change_pass()
    {
        return view('auth_v1.reset-password');
    }

    public function forgotPassword()
    {
        return view('auth_v1.forgot-password');
    }

    public function logout()
    {
        if(\Auth::check()){

            $posted_data['device_id'] = \Session::getId();
            $posted_data['user_id'] = \Auth::user()->id;
            $posted_data['detail'] = true;
            $get_notification_tokens = $this->FcnTokenObj->getFcmTokens($posted_data);
            if($get_notification_tokens){
                $this->FcnTokenObj->deleteFCM_Token($get_notification_tokens->id);
            }
            \Auth::logout();

        }
        return redirect('/sp-login');
    }


    public function dashboard(Request $request)
    {
        $data = array();
        $data['counts'] = array();

        $data['counts']['roles'] = $this->RoleObj->getRoles([
            'count' => true
        ]);

        $data['counts']['permissions'] = $this->PermissionObj->getPermissions([
            'count' => true
        ]);

        $data['counts']['users'] = $this->UserObj->getUser([
            'count' => true
        ]);

        $data['counts']['menus'] = $this->MenuObj->getMenus([
            'count' => true
        ]);

        $data['counts']['sub_menus'] = $this->SubMenuObj->getSubMenus([
            'count' => true
        ]);

        $data['counts']['email_messages'] = $this->EmailTemplateObj->getEmailTemplates([
            'count' => true
        ]);

        $data['counts']['short_codes'] = $this->EmailShortCodeObj->getEmailShortCode([
            'count' => true
        ]);


        $data['counts']['approve_social_video_super_admin'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'Approved',
        ]);

        $data['counts']['approve_social_video_user'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'Approved',
            'user_id' => \Auth::user()->id,
        ]);

        $data['counts']['pending_social_video_super_admin'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'Pending',
        ]);

        $data['counts']['pending_social_video_user'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'Pending',
            'user_id' => \Auth::user()->id,
        ]);

        $data['counts']['not_approve_social_video_super_admin'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'NotApprove',
        ]);

        $data['counts']['not_approve_social_video_user'] = $this->UploadVideoObj->getUploadVideo([
            'count' => true,
            'vedio_status' => 'NotApprove',
            'user_id' => \Auth::user()->id,
        ]);




        // echo '<pre>';print_r($data);'</pre>';exit;

        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = $this->RoleObj->getRoles($posted_data);

        return view('dashboard', compact('data'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        $data['all_roles'] = $this->RoleObj->getRoles(['roles_not_in' => [1]]);
        $data['assigned_roles'] = \Auth::user()->getRoleNames();

        $posted_data = $request->all();
        $posted_data['paginate'] = 10;
        $posted_data['users_not_in'] = [1];
        // $posted_data['printsql'] = true;
        $data['users'] = $this->UserObj->getUser($posted_data);

        $data['html'] = view('user.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('user.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function changePassword(Request $request)
     {
         $requested_data = $request->all();
         $rules = array(
             'old_password'      => 'required',
             'new_password'      => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@^&*()_+]).*$/',
             'confirm_password'  => 'required|required_with:new_password|same:new_password'
         );

         $messages = array(
             'new_password.regex' => 'Password must be atleast eight characters long and must use atleast one letter, number and special character.',
         );
         $validator = \Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        }else {

            if (!\Hash::check($request->old_password, \Auth::user()->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Old password does not match.'])->withInput();
            }

             if ($requested_data['old_password'] == $requested_data['new_password']) {
                return redirect()->back()->withErrors(['old_password' => 'New and old password must be different'])->withInput();
             }

             $this->UserObj->saveUpdateUser([
                 'update_id' => \Auth::user()->id,
                 'password' => $requested_data['new_password']
             ]);

             saveEmailLog([
                 'user_id' => \Auth::user()->id,
                 'email_template_id' => 2,
                 'new_password' => $requested_data['new_password']
             ]);

             if(\Auth::check()){

                $posted_data['device_id'] = \Session::getId();
                $posted_data['user_id'] = \Auth::user()->id;
                $posted_data['detail'] = true;
                $get_notification_tokens = $this->FcnTokenObj->getFcmTokens($posted_data);
                if($get_notification_tokens){
                    $this->FcnTokenObj->deleteFCM_Token($get_notification_tokens->id);
                }
                \Auth::logout();
            }
            \Session::flash('message', 'Your password changed successfully!');
            return redirect('/sp-login');
         }
     }


    public function create()
    {
        $data = array();
        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = $this->RoleObj->getRoles($posted_data);

        return view('user.add',compact('data'));
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
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:12',
            'confirm_password' => 'required|required_with:password|same:password',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'last_name' => 'required',
            'first_name' => 'required',
            'user_role' => 'required',
            // 'profile_image' => 'required',
        );

        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {

            try{
                $base_url = public_path();
                if($request->file('profile_image')) {
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                        $imageData = array();
                        // $imageData['fileName'] = time().'_'.$request->profile_image->getClientOriginalName();
                        $imageData['fileName'] = time().'_'.rand(1000000,9999999).'.'.$extension;
                        $imageData['uploadfileObj'] = $request->file('profile_image');
                        $imageData['fileObj'] = \Image::make($request->file('profile_image')->getRealPath());
                        $imageData['folderName'] = 'profile_image';

                        $uploadAssetRes = uploadAssets($imageData, $original = false, $optimized = true, $thumbnail = false);
                        $posted_data['profile_image'] = $uploadAssetRes;
                        if(!$uploadAssetRes){
                            return back()->withErrors([
                                'profile_image' => 'Something wrong with your image, please try again later!',
                            ])->withInput();
                        }
                    }else{
                        return back()->withErrors([
                            'profile_image' => 'The Profile image format is not correct you can only upload (jpg, jpeg, png).',
                        ])->withInput();
                    }
                }

                $latest_user = $this->UserObj->saveUpdateUser($posted_data);

                $latest_user->assignRole($posted_data['user_role']);

                \Session::flash('message', 'User Register Successfully!');

            } catch (Exception $e) {
                \Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            // return redirect()->back()->withInput();
            return redirect('/user');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function theme_layout(Request $request) {

        $posted_data = $request->all();
        $posted_data['update_id'] = \Auth::user()->id;


        if(\Auth::user()->theme_mode == 'Light'){
            $posted_data['theme_mode'] = 'Dark';
        }
        else{
            $posted_data['theme_mode'] = 'Light';
        }
    //    echo '<pre>';print_r($posted_data);echo '</pre>';exit;
    $this->UserObj->saveUpdateUser($posted_data);
        // echo '<pre>';print_r($response);echo '</pre>';exit;
        return response()->json(['message' => 'Data submitted', 'record' => $posted_data]);

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
        $data = $this->UserObj->getUser($posted_data);

        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = $this->RoleObj->getRoles($posted_data);
        $data['user_role'] = count($data->getRoleNames()) > 0 ? $data->getRoleNames()[0] : '';

        return view('user.add',compact('data'));
    }

    public function editProfile()
    {
        $id = \Auth::user()->id;

        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;
        $data = $this->UserObj->getUser($posted_data);

        $posted_data = array();
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'Asc';
        $data['roles'] = $this->RoleObj->getRoles($posted_data);

        // echo '<pre>';print_r($data);echo '</pre>';exit;
        return view('user.add',compact('data'));
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
        $updated_data = $request->all();
        $updated_data['update_id'] = $id;
        $rules = array(
            'update_id' => 'required|exists:users,id',
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:12',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'password' => 'nullable|min:6',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'last_name' => 'required',
            'first_name' => 'required',
            // 'user_role' => 'required'
        );

        $messages = array(
            'phone_number.min' => 'The :attribute format is not correct (123-456-7890).'
        );

        $validator = \Validator::make($updated_data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            try{
                $user_detail = $this->UserObj->getUser(['id' => $id, 'detail' => true]);

                if ($user_detail && isset($updated_data['user_role'])) {
                    $pre_role = count($user_detail->getRoleNames()) > 0 ? $user_detail->getRoleNames()[0] : '';
                    $user_detail->removeRole($pre_role);
                    $user_detail->assignRole($updated_data['user_role']);
                }

                $base_url = public_path();
                if($request->file('profile_image')) {
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
                        $imageData = array();
                        // $imageData['fileName'] = time().'_'.$request->profile_image->getClientOriginalName();
                        $imageData['fileName'] = time().'_'.rand(1000000,9999999).'.'.$extension;
                        $imageData['uploadfileObj'] = $request->file('profile_image');
                        $imageData['fileObj'] = \Image::make($request->file('profile_image')->getRealPath());
                        $imageData['folderName'] = 'profile_image';

                        $uploadAssetRes = uploadAssets($imageData, $original = false, $optimized = true, $thumbnail = false);
                        $updated_data['profile_image'] = $uploadAssetRes;
                        if(!$uploadAssetRes){
                            return back()->withErrors([
                                'profile_image' => 'Something wrong with your image, please try again later!',
                            ])->withInput();
                        }
                        $imageData = array();
                        $imageData['imagePath'] = $user_detail->profile_image;
                        unlinkUploadedAssets($imageData);

                    }else{
                        return back()->withErrors([
                            'profile_image' => 'The Profile image format is not correct you can only upload (jpg, jpeg, png).',
                        ])->withInput();
                    }
                }

                $this->UserObj->saveUpdateUser($updated_data);
                \Session::flash('message', 'User Update Successfully!');
                return redirect()->back();

            } catch (Exception $e) {
                \Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function blockUnblockUser(Request $request)
    {
        $posted_data = $request->all();

        $rules = array(
            'update_id' => 'required|exists:users,id',
            'user_status' => 'required'
        );

        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->UserObj->saveUpdateUser($posted_data);

        \Session::flash('message', 'User status updated successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        unlinkUploadedAssets([
            'imagePath' => $user->profile_image
        ]);
        $user->delete();
        \Session::flash('message', 'User deleted successfully!');
        return redirect('/user');

    }

    public function accountLogin(Request $request)
    {
        $request_data = $request->all();
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // if(!isset($request_data['g-recaptcha-response']) || empty($request_data['g-recaptcha-response'])){
        //     return back()->withErrors([
        //         'password' => 'Please check recaptcha and try again.',
        //     ]);
        // }

        // $credentials['role'] = 1;

        if (\Auth::attempt($credentials)) {
            return redirect('/dashboard');
        }else{
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    public function accountRegister(Request $request)
    {
        $posted_data = $request->all();
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|required_with:password|same:password',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            try{
                $latest_user = $this->UserObj->saveUpdateUser($posted_data);

                $latest_user->assignRole('User');

                \Session::flash('message', 'User Register Successfully!');

            } catch (Exception $e) {
                \Session::flash('error_message', $e->getMessage());
                // dd("Error: ". $e->getMessage());
            }
            return redirect('/sp-login');
        }
    }

    public function forgot_password(Request $request)
    {
        $request_data = $request->all();
        $rules = array(
            'email' => 'required|email|exists:users'
        );

        $messages = array(
            'email.exists' => 'We do not recognize this email address. Please try again.',
        );

        $validator = \Validator::make($request_data, $rules, $messages);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors());
        } else {


            $otp = substr(md5(uniqid(rand(), true)), 5, 5);
            $userDetail = $this->UserObj->getUser([
                'email' => $request_data['email'],
                'detail' => true
            ]);

            if($userDetail){
                $response = $this->UserObj->saveUpdateUser([
                    'update_id' => $userDetail->id,
                    'email_verification_code' => $otp,
                ]);
                if($response){
                    saveEmailLog([
                        'user_id' => $response->id,
                        'email_template_id' => 6, //OTP Verification
                        'otp_code' =>$otp
                    ]);
                    return $this->sendResponse($otp, 'Your password has been reset. Please check your email.');
                }
            }

                return $this->sendResponse([], 'Your password has been updated.');

        }

            \Session::flash('message', 'Your password has been changed successfully please check you email!');
            return redirect('/sp-login');

    }

    public function authorizeUser($posted_data)
    {
        // $email = isset($posted_data['email']) ? $posted_data['email'] : '';
        $password = isset($posted_data['password']) ? $posted_data['password'] : '';

        if (\Auth::attempt(['password' => $password])) {
            $user = \Auth::user();
            $response =  $user;

            if (isset($posted_data['mode']) && $posted_data['mode'] == 'only_validate') {
                return $response;
            }

            $response['token'] =  $user->createToken('MyApp')->accessToken;
            return $response;
        } else {
            return false;
        }
    }

    public function sendNotification() {

        // echo '<pre>';print_r(Session::getId());'</pre>';exit;
        // $token = "fHRRYnyQyrA:APA91bFGF5j4A76XXsC4xb2canvjRPlJqlcL_yKBmgQrOu9egO3Qk9v86Lh5eSE6EQ13DC6qdE4AoxgdFsIYZvv3PtCeNdbtj6zXazZuJKGI6Doxcriw-Zdpd9QnigCD_mDCgz_BA5N7";

        $token = "fBvzndxKvpE:APA91bEc2l-37uRiTcYRulz3vVL63KtqmtwP5Tlm4E8hWvKUVAvfRMHjqb_ony4nHDNxuxmDjbmoPzDcmog2cX5zwL-vCf_CA0bdw8en7mVzdpCOUZeQb8Ne9HVr45LGLu3Nulees_V2";
        $from  = "AAAA1x62L-A:APA91bHPEZuPTTVn8tWhggUur4h2_k92s4cRWIu5L9lkRgS2pHtYJKMgCIkg4UcIMui1lWcXRGStyKxjIgrlH7KXefS0CkSS8tlrR0yDWiNRUkeYsNuivIgnV2rgep6QCmQL75-QpBTd";
        $msg = array
            (
                'body'  => "Testing",
                'title' => "Hi, From Raj1",
                'receiver' => 'erw',
                'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
                'sound' => 'Default'/*Default sound*/
            );
        $fields = array
                (
                    'to'        => $token,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $from,
                    'Content-Type: application/json'
                );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        dd($result);
        curl_close( $ch );
        // echo '<pre>';print_r('Notification send successfully');'</pre>';exit;
    }


    public function export_data(Request $request)
    {
        $requested_data = $request->all();

        $data = array();
        if (isset($requested_data['product_id']) || $requested_data['product_id'] == '' )
            $data['product_id'] = $requested_data['product_id'];

        // $export_data = new ExportData();
        // $export_data->set_module = "items";
        // $export_data->set_product_id = $data['product_id'];

        return (new ExportData)
            ->set_module("items")
            ->set_product_id($data['product_id'])
            ->download('items.xlsx');
    }
}
