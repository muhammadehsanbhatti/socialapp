<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfessionalRoleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
        parent::__construct();
        $this->middleware('permission:professional-role-type-list|professional-role-type-update|professional-role-type-delete', ['only' => ['index']]);
        $this->middleware('permission:professional-role-type-create', ['only' => ['create','store']]);
        $this->middleware('permission:professional-role-type-update', ['only' => ['edit','update']]);
        $this->middleware('permission:professional-role-type-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $posted_data = $request->all();
        $posted_data['paginate'] = 10;
        $posted_data['orderBy_name'] = 'id';
        $posted_data['orderBy_value'] = 'DESC';
        $data['get_prof_roles'] = $this->ProRoleTypeObj->getProfRoleType();
        $data['records'] = $this->ProRoleTypeObj->getProfRoleType($posted_data);

        $data['html'] = view('pro_role_type.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('pro_role_type.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pro_role_type.add');
    }
    public function add_items($id)
    {
        $posted_data['id'] = $id;
        $data['get_prof_roles'] = $this->ProRoleTypeObj->getProfRoleType($posted_data);
        return view('pro_role_type.add_pro_role_items',compact('data'));
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
            'prof_title' => 'required',
            'role_type'=>'required'
        );

        $validator = \Validator::make($posted_data, $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else{
            $posted_data['title'] = $posted_data['prof_title'];
            $general_title_data =  $this->GeneralObj->saveUpdateGeneralTitle([
                'title' => $posted_data['prof_title'],
                'title_status' => $posted_data['prof_title_status'],
            ]);
            
            foreach ($posted_data['role_type'] as $prof_role_key => $prof_role_value) {
                $prof_role_type_data = $this->ProRoleTypeObj->saveUpdateProfRoleType([
                    'general_title_id' => $general_title_data->id,
                    'professional_role_title' => $prof_role_value
                ]);

                if (isset($prof_role_type_data)  && isset($posted_data['role_item'][$prof_role_key])) {
                    foreach ($posted_data['role_item'][$prof_role_key] as $prof_role_key => $prof_role_value) {
                        $rerquestd_data['pro_role_type_id'] = $prof_role_type_data['id'];
                        $rerquestd_data['title'] = $prof_role_value; 
                        $this->ProRoleTypeItemObj->saveUpdateProRoleTypeItem($rerquestd_data);
                    }
                }
            }
            return response()->json(["success" => 'Professional roles added successfully']);
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
        $update_goals = array();
        $update_goals['id'] = $id;
        $update_goals['detail'] = true;
        $data = $this->ProRoleTypeObj->getProfRoleType($update_goals); 
        return view('pro_role_type.add', compact('data'));  
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
            // 'prof_title' => 'required',
            'role_type'=>'required'
        );

        $validator = \Validator::make($posted_data, $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
     
        else{
            $prof_role_type_data = $this->ProRoleTypeObj->saveUpdateProfRoleType([
                'update_id' => $id,
                'professional_role_title' => $posted_data['role_type']
            ]);
            $prof_role_type_item = $this->ProRoleTypeItemObj->getProRoleTypeItem([
                'pro_role_type_id' => $prof_role_type_data->id,
            ])->ToArray();
            if (isset($prof_role_type_data)  && isset($posted_data['update_role_item'])) {
                foreach ($posted_data['update_role_item'] as $prof_role_key => $prof_role_value) {
                   
                    $rerquestd_data['update_id'] = $prof_role_type_item[$prof_role_key]['id'];
                    $rerquestd_data['title'] = $prof_role_value; 
                    $this->ProRoleTypeItemObj->saveUpdateProRoleTypeItem($rerquestd_data);
                }
            }
            if (isset($prof_role_type_data)  && isset($posted_data['role_item'][1])) {
                foreach ($posted_data['role_item'][1] as $role_key => $role_value) {
                    $new_rerquestd_data['pro_role_type_id'] = $prof_role_type_data->id;
                    $new_rerquestd_data['title'] = $role_value; 
                    $this->ProRoleTypeItemObj->saveUpdateProRoleTypeItem($new_rerquestd_data);
                }
            }
            return response()->json(["success" => 'Professional roles updated successfully']);
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
        $response = $this->ProRoleTypeObj->deletProfRoleType($id);
        \Session::flash('message', 'Professional role deleted successfully');
        return redirect('/professional_role_type');
    }

    public function destroy_prof_rote_type_item($id){
        $response = $this->ProRoleTypeItemObj->deletProRoleTypeItem($id);
        return response()->json(["success" => 'Professional role type item deleted successfully']);
    }
}
