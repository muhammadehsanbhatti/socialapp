<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndustryVerticalItemController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
        parent::__construct();
        $this->middleware('permission:industry-vertical-item-list|industry-vertical-item-update|industry-vertical-item-delete', ['only' => ['index']]);
        $this->middleware('permission:industry-vertical-item-create', ['only' => ['create','store']]);
        $this->middleware('permission:industry-vertical-item-update', ['only' => ['edit','update']]);
        $this->middleware('permission:industry-vertical-item-delete', ['only' => ['destroy']]);
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
        $posted_data['title_status'] = 5;
        $data['get_industry_verticals'] = $this->GeneralObj->getGeneralTitle();
        $data['records'] = $this->GeneralObj->getGeneralTitle($posted_data);

        $data['html'] = view('industry_vertical_item.ajax_records', compact('data'));

        if($request->ajax()){
            return $data['html'];
        }

        return view('industry_vertical_item.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('industry_vertical_item.add');
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
            'industry_title' => 'required',
        );

        $validator = \Validator::make($posted_data, $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 
        else{
            // $posted_data['title'] = $posted_data['industry_title'];
            // echo '<pre>'; print_r($posted_data); echo '</pre>';

            foreach ($posted_data['industry_title'] as $industry_title_key => $industry_title_value) {
                $get_titles = $this->GeneralObj->getGeneralTitle([
                    'title' => $industry_title_value,
                    'title_status' => $posted_data['industry_title_status']
                ])->ToArray(); 
                // echo '<pre>'; print_r($get_titles); echo '</pre>'; 

                if (array_column($get_titles, 'title')) {
                    foreach ($posted_data['industry_item'][$industry_title_key] as $vertical_item_key => $vertical_item_value) {
                        $get_vertical_items = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                            'general_title_id' => $get_titles[0]['id'],
                            'title' => $vertical_item_value,
                        ])->ToArray();
                        echo '<pre>'; print_r($get_vertical_items); echo '</pre>';
                        if (!array_column($get_vertical_items, 'title')){
                            echo '<pre>'; print_r("array_colum not exists"); echo '</pre>'; exit;

                            $prof_role_type_data = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
                                'general_title_id' => $get_titles[0]['id'],
                                'title' => $vertical_item_value,
                                'category_status' => 'Industry',
                            ]);
                        }
                        if (array_column($get_vertical_items, 'title')){
                            if (in_array('category_status',)) {
                                echo '<pre>'; print_r("in_array"); echo '</pre>'; exit;
                                $prof_role_type_data = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
                                    'general_title_id' => $get_titles[0]['id'],
                                    'title' => $vertical_item_value,
                                    'category_status' => 'Both',
                                ]);
                            }
                           
                        }
                    }
                }
                if (!array_column($get_titles,'title')) {
                    $general_title_data =  $this->GeneralObj->saveUpdateGeneralTitle([
                        'title' => $industry_title_value,
                        'title_status' => $posted_data['industry_title_status'],
                    ]);
                    if(isset($posted_data['industry_item'][$industry_title_key])){
                        foreach ($posted_data['industry_item'][$industry_title_key] as $vertical_item_key => $vertical_item_value) {
                          
                            // if (!array_column($get_vertical_items,'title') && !array_column($get_vertical_items,'category_status')) {
                                $prof_role_type_data = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
                                    'general_title_id' => $general_title_data->id,
                                    'title' => $vertical_item_value,
                                    'category_status' => 'Industry',
                                ]);
                            // } 
                        }
                    }
                }
                // if (array_column($get_titles,'title')) {
                //     if(isset($posted_data['industry_item'][$industry_title_key])){
                //         foreach ($posted_data['industry_item'][$industry_title_key] as $vertical_item_key => $vertical_item_value) {
                //             // echo '<pre>'; print_r($get_titles[$vertical_item_key]); echo '</pre>'; exit;
                //             $get_vertical_items = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                //                 'general_title_id' => $get_titles[$industry_title_key]['id'],
                //                 'title' => $vertical_item_value,
                //                 // 'category_status' => 'Industry',
                //             ])->ToArray();
                //             echo '<pre>'; print_r($get_vertical_items); echo '</pre>'; exit;
                //             if (!array_column($get_vertical_items,'title') && !array_column($get_vertical_items,'category_status')) {
                //                 $prof_role_type_data = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
                //                     'general_title_id' => $general_title_data->id,
                //                     'title' => $vertical_item_value,
                //                     'category_status' => 'Industry',
                //                 ]);
                //             } 
                //         }
                //     }
                // }
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
        //
    }
}
