<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CsvImportRequest;
use App\Imports\CsvImport;
use App\Models\CsvImportData;
use Illuminate\Support\Facades\Schema;

class ImportExcelFileController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:import-excel', ['only' => ['index','store','csv_import_data']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $posted_data = array();
        $database_name = DB::connection()->getDatabaseName();

        $table_name['table_name']= DB::select("SELECT table_name as table_name FROM information_schema.tables WHERE table_schema = '{$database_name}'");
        // echo'<pre>'; print_r($table_name); echo'</pre>';exit;
        return view('import_excel.add', compact('table_name'));
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
        $rules = array();
        $posted_data = array();
        $rules = array(
            'csv_file' => 'required',
            'table_name' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

       
        if ($validator->fails()) {
            return $this->sendError('Please fill all the required fields.', ["error" => $validator->errors()->first()]);
        } else {
            
            $data = array();
            $data['headings']= (new HeadingRowImport)->toArray($request->file('csv_file'))[0];
            $data['csv_data'] = Excel::toArray(new CsvImport, $request->file('csv_file'))[0];
            $data['column_name'] = Schema::getColumnListing($request->table_name);
            $data['html'] =  view('import_excel.ajax_records', compact('data'))->render();

            if (count($data['csv_data']) > 0) {
                $data['csv_data_file'] = new CsvImportData;
                // $data['csv_data_file']->csv_filename = $request->file('csv_file')->getClientOriginalName();
                // $data['csv_data_file']->csv_header = 1;
                $data['csv_data_file']->table_name =  $request->table_name;
                $data['csv_data_file']->csv_data = json_encode($data['csv_data']);
                $latest_res = $data['csv_data_file']->save();

                $data['csv_data'] = $latest_res;

                return response()->json(['success'=>'Import Csv File', 'data' => $data]);
            }
        }
    }

    public function csv_import_data(Request $request)
    {
        $data = CsvImportData::find($request->csv_import_data_id);

        $csv_data = json_decode($data->csv_data, true);
        $dbfield = $request->get('dbfield');
        $table_name = $data->table_name;
        // echo '<pre>'; print_r($table_name); echo '</pre>'; 
        // echo '<pre>'; print_r($csv_data); echo '</pre>'; exit;
        foreach ($csv_data as $key =>$value) {
            $tmp_ary = array();
            $tmp_ary['created_at'] = now();
            $tmp_ary['updated_at'] = now();
            $index = 0;
            $selectQuery = DB::table($table_name);
            foreach ($value as $key2 => $value2) {
                if(isset($dbfield[$index]) && !empty($dbfield[$index])){
                    
                    $is_insert_record = true;
                    if($table_name == 'industry_vertical_items' && $dbfield[$index] == 'general_title_id'){
                        $checkGeneralTitle = $this->GeneralObj->getGeneralTitle([
                            'title_match' => $value2,
                            'title_status' => 5,
                            'detail' => true
                        ]);
                        if($checkGeneralTitle){
                            $value2 = $checkGeneralTitle->id;
                            // $selectQuery->where('category_status', 'Industry');
                            // $selectQuery->where('category_status', 'Specialty');
                        }else{
                            $is_insert_record = false;
                        }
                    }

                    if($is_insert_record){
                        $tmp_ary[$dbfield[$index]] = $value2;
                        $selectQuery->where($dbfield[$index], $value2);
                    }
                }
                $index++;
            }
            $selectResult = $selectQuery->first();
            if(!$selectResult){
                if($table_name == 'industry_vertical_items'){
                    $tmp_ary['category_status'] = 'Specialty';
                }
                DB::table($table_name)->insert([$tmp_ary]);
            }
            else{
                if($table_name == 'industry_vertical_items'){

                    $category_status = $selectResult->category_status;
                    if($selectResult->category_status == 'Industry'){
                        $category_status = 'Both';
                    }

                    DB::table($table_name)->where('id', $selectResult->id)->update(["category_status" => $category_status]);
                }
            }
        }
        return response()->json(['success'=>'Csv File imported Successfully']);
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