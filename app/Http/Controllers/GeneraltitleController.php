<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneraltitleController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:general-title-list|general-title-view|general-title-delete', ['only' => ['index']]);
        $this->middleware('permission:general-title-create', ['only' => ['create','store']]);
        $this->middleware('permission:general-title-view', ['only' => ['edit','update']]);
        $this->middleware('permission:general-title-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $posted_data = $request->all();
        $posted_data['paginate'] = 10;
        $posted_data['general_title_response'] = true;
        $data['general_title'] = $this->GeneralObj->getGeneralTitle();
        $data['records'] = $this->GeneralObj->getGeneralTitle($posted_data);

        $data['html'] = view('general_title.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('general_title.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $data = array();
        // $posted_data = array();
        // $posted_data['orderBy_name'] = 'title';
        // $posted_data['orderBy_value'] = 'Asc';
        // $data['general_title'] = $this->GeneralObj->getGeneralTitle($posted_data);

        // return view('general_title.add',compact('data'));
        return view('general_title.add');
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
            'title' => 'required||regex:/^[a-zA-Z ]+$/u|unique:general_titles,title',
            'title_status' => 'required',
        );

        $validator = \Validator::make($posted_data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else {

            $this->GeneralObj->saveUpdateGeneralTitle($posted_data);
            \Session::flash('message', 'General title added successfully!');
            return redirect('/general_title');
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

        $data = $this->GeneralObj->getGeneralTitle($posted_data);

        return view('general_title.add',compact('data'));
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
        $updated_data = array();
        $updated_data = $request->all();

        $validator = \Validator::make($updated_data, [
            'title' => 'required||regex:/^[a-zA-Z ]+$/u|unique:general_titles,title,'.$updated_data['title'],
            'title_status' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($id == 0) {
            \Session::flash('error_message', 'Something went wrong. Please post correct general title id.');
            return redirect('/general_title');
        }


        $updated_data['update_id'] = $id;

        $this->GeneralObj->saveUpdateGeneralTitle($updated_data);

        \Session::flash('message', 'GeneralTitle updated successfully!');
        return redirect('/general_title');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->GeneralObj->deleteGeneralTitle($id);
        \Session::flash('message', 'GeneralTitle deleted successfully!');
        return redirect('/general_title');
    }
}
