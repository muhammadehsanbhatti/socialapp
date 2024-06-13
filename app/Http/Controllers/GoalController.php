<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoalController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:goal-list|goal-update|goal-delete', ['only' => ['index']]);
        $this->middleware('permission:goal-create', ['only' => ['create','store']]);
        $this->middleware('permission:goal-update', ['only' => ['edit','update']]);
        $this->middleware('permission:goal-delete', ['only' => ['destroy']]);
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
        $posted_data['orderBy_name'] = 'id';
        $posted_data['orderBy_value'] = 'DESC';
        $data['get_goals'] = $this->GoalObj->getGoal();
        $data['records'] = $this->GoalObj->getGoal($posted_data);

        $data['html'] = view('goal.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('goal.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('goal.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $rules = array(
            'icon' => 'required',
            'title' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules, [
            'icon.regex' => 'Icon Only allowled jpg, jpeg or png image format.',
        ]);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $posted_data = $request->all();
            // echo '<pre>'; print_r($posted_data); echo '</pre>'; exit;
            foreach ($posted_data['icon'] as $icon_index => $value) {

                if ($request->file('icon')) {
                    $extension = $value->getClientOriginalExtension();
                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                        $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                        $filePath = $value->storeAs('goal_icon', $file_name, 'public');
                        $posted_data['icon'] = 'storage/goal_icon/' . $file_name;
                    } else {
                        $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }

                $goal_deatail = $this->GoalObj->getGoal([
                    'orderBy_name'=> 'goals.id',
                    'orderBy_value'=> 'DESC',
                    'detail' =>true,
                ]);
                
                $posted_data['goal_number'] = isset($goal_deatail->goal_number)? $goal_deatail->goal_number + 1 : 1;

                
                $goal_id =  $this->GoalObj->saveUpdateGoal($posted_data);
                
                if(isset($posted_data['title'][$icon_index])){
                    foreach ($posted_data['title'][$icon_index] as $goal_title_key => $goal_title_value) {
                        $posted_data['goal_id'] = $goal_id->id;
                        $posted_data['goal_item_title'] = $goal_title_value;
                        $this->GoalItemObj->saveUpdateGoalItem($posted_data);
                    }
                }
            }
            // return $this->sendResponse($data, 'Goal added successfully!');
            \Session::flash('message', 'Goal added successfully!');
            return redirect('/goal');
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
        $data = $this->GoalObj->getGoal($update_goals);

        if($data){
            $posted_data = array();
            $posted_data['goal_id'] = $data->id;
            $data['goal_items'] = $this->GoalItemObj->getGoalItem($posted_data);
        }
        return view('goal.add', compact('data'));    
        // \Session::flash('error_message', 'Goal not found');
        // return redirect('/goals');
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
        $rules = array(
            // 'icon' => 'required',
            'title' => 'required',
        );
        $validator = \Validator::make($request->all(), $rules, [
            'icon.regex' => 'Icon Only allowled jpg, jpeg or png image format.',
        ]);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {

            $posted_data['update_id'] = $id;

            $goal_deatail = $this->GoalObj->getGoal([
                'id' => $id,
                'detail' =>true
            ]);
            if ($request->file('icon')) {
                if (isset($goal_deatail['icon'])) {
                    $url = public_path().'/'.$goal_deatail['icon'];
                    if (file_exists($url)) {
                        unlink($url);
                    }
                }
                
                $extension = $posted_data['icon']->getClientOriginalExtension();
                if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                    $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                    $filePath = $posted_data['icon']->storeAs('goal_icon', $file_name, 'public');
                    $posted_data['icon'] = 'storage/goal_icon/' . $file_name;
                } else {
                    $error_message['error'] = 'Icon Only allowled jpg, jpeg or png image format.';
                    return $this->sendError($error_message['error'], $error_message);
                }
            }

            $goal_detail =  $this->GoalObj->saveUpdateGoal($posted_data);
            if(isset($posted_data['goal_items_id'])){
                for ($i=0; $i < count($posted_data['goal_items_id']); $i++) {
                    $update_goal_items = array();
                    $update_goal_items['update_id'] = $posted_data['goal_items_id'][$i];
                    $update_goal_items['goal_id'] = $goal_detail['id'];
                    $update_goal_items['goal_item_title'] = $posted_data['goal_item_title'][$i];
                   
                    $this->GoalItemObj->saveUpdateGoalItem($update_goal_items);
                    $index_value = $i; 
                }
            }
            if (isset($posted_data['title'])) {
                foreach ($posted_data['title'] as $goal_title_key => $goal_title_value) {
                // for ($i=0; $i < count($posted_data['title']); $i++) {
                    $requested_data =array();
                    $requested_data['goal_id'] = $goal_detail->id;
                    $requested_data['goal_item_title'] = $goal_title_value;
                    $this->GoalItemObj->saveUpdateGoalItem($requested_data);
                }
            }

            \Session::flash('message', 'Goal updated successfully!');
            return redirect('/goal');
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
    
    public function destroy_goal_item($id)
    {
        $response = $this->GoalItemObj->deletGoalItem($id);
        return $this->sendResponse($response, 'Goal item deleted successfully');
    }
}