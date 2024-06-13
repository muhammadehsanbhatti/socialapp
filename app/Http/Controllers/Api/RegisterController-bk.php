<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Validator;
use DB;
use App\Models\User;
use App\Models\GeneralTitle;
use App\Models\UserEducationalInformation;
use Carbon\Carbon;

class RegisterController extends BaseController
{


    function dummy_enteries($title_ary, $status)
    {
        $existingtitle = GeneralTitle::pluck('title')->toArray();

        $enter_records_array = [];
        if (is_array($title_ary)) {

            foreach ($title_ary as $value) {
                if (!in_array($value, $existingtitle)) {
    
                    // Degreen discipline fields
                    // $enter_records_array[] = [
                    //     'title' => $value,
                    //     'title_status' => $status,
                    // ];
                    $this->GeneralObj->saveUpdateGeneralTitle([
                        'title' => $value,
                        'title_status' => $status,
                    ]);
                }
            }
        }
        else{
            if (!in_array($title_ary, $existingtitle)) {
    
                // Degreen discipline fields
                // $enter_records_array[] = [
                //     'title' => $title_ary,
                //     'title_status' => $status,
                // ];
                $this->GeneralObj->saveUpdateGeneralTitle([
                    'title' => $title_ary,
                    'title_status' => $status,
                ]);
            }
        }
        
        // $this->GeneralObj->saveUpdateGeneralTitle($enter_records_array);
        // GeneralTitle::insert($enter_records_array);

        return count($enter_records_array);
    }


    public function enter_records()
    {
       // University names
        $posted_data = [
            "Harvard University",
            "Stanford University",
            "Massachusetts Institute of Technology (MIT)",
            "California Institute of Technology (Caltech)",
            "University of Oxford",
            "University of Cambridge",
            "Princeton University",
            "University of Chicago",
            "University of California, Berkeley",
            "ETH Zurich - Swiss Federal Institute of Technology",
            "University of Pennsylvania",
            "Columbia University",
            "University of California, Los Angeles (UCLA)",
            "University of Michigan, Ann Arbor",
            "Johns Hopkins University",
            "University of Toronto",
            "University of Tokyo",
            "University of Melbourne",
            "University of Hong Kong",
            "University College London (UCL)",
            "Australian National University",
            "London School of Economics and Political Science (LSE)",
            "University of California, San Diego (UCSD)",
            "New York University (NYU)",
            "University of Washington",
            "University of Edinburgh",
            "University of California, San Francisco (UCSF)",
            "University of British Columbia",
            "University of Illinois at Urbana-Champaign",
            "National University of Singapore (NUS)",
            "University of Texas at Austin",
            "King's College London",
            "University of Sydney",
            "Duke University",
            "University of Wisconsin-Madison",
            "Peking University",
            "University of Manchester",
            "University of Copenhagen",
            "McGill University",
            "Kyoto University",
            "University of Amsterdam",
            "University of Zurich",
            "University of California, Santa Barbara (UCSB)",
            "Carnegie Mellon University",
            "University of Warwick",
            "University of North Carolina at Chapel Hill",
            "University of Glasgow",
            "Hong Kong University of Science and Technology (HKUST)",
            "University of Bristol",
            "Boston University",
            "Seoul National University",
            "University of California, Davis",
            "University of Manchester",
            "University of Munich (LMU)",
            "University of Southern California (USC)",
            "University of Sydney",
            "Brown University",
            "University of Melbourne",
            "Tsinghua University",
            "University of Bristol",
            "University of Helsinki",
            "University of Oslo",
            "University of Auckland",
            "University of Nottingham",
            "University of Western Australia",
            "University of Birmingham",
            "University of Sao Paulo",
            "Rice University",
            "University of Southampton",
            "University of Alberta",
            "University of Geneva",
            "University of Barcelona",
            "National Taiwan University (NTU)",
            "École Polytechnique Fédérale de Lausanne (EPFL)",
            "Leiden University",
            "University of St Andrews",
            "University of Oslo",
            "University of Zurich",
            "University of Vienna",
            "University of Maryland, College Park",
            "University of Glasgow",
            "University of Adelaide",
            "University of Pittsburgh",
            "University of São Paulo",
            "University of Groningen",
            "Free University of Berlin",
            "University of Colorado Boulder",
            "University of Lausanne",
            "University of Freiburg",
            "Lund University",
            "University of Arizona",
            "University of Montreal",
            "Hebrew University of Jerusalem",
            "University of Cape Town",
            "University of Basel",
            "Uppsala University",
            "University of New South Wales (UNSW Sydney)",
            "Dartmouth College",
            "University of Bonn",
            "University of Rochester",
            "University of Sussex",
            "University of Virginia",
            "University of Bern",
            "University of Helsinki",
            "University of Exeter",
            "University of California, Irvine",
            "University of Gothenburg",
            "University of York",
            "University of Würzburg",
            "University of Liverpool",
            "University of Oslo",
            "University of Twente",
            "University of Münster",
            "University of Texas at Dallas",
            "University of Innsbruck",
            "University of Göttingen",
            "University of Southampton",
            "University of Manitoba",
            "University of Athens",
            "University of Sussex",
            "University of Auckland",
            "University of Waterloo",
            "University of Cape Town",
            "University of Pisa",
            "University of Otago",
        ]; 
        $returnAry['universities_name'] = $this->dummy_enteries($posted_data, 7);

        // Degree discipline 
        $posted_data = [
            "Computer Science and Engineering",
            "Business Administration and Management",
            "Medicine and Healthcare",
            "Electrical and Electronics Engineering",
            "Mechanical Engineering",
            "Data Science and Analytics",
            "Finance and Accounting",
            "Environmental Science and Sustainability",
            "Psychology and Behavioral Sciences",
            "Civil Engineering",
            "Biotechnology and Biomedical Sciences",
            "Computer Engineering",
            "Chemical Engineering",
            "Environmental Engineering",
            "Architecture and Urban Planning",
            "Economics and Econometrics",
            "Marketing and Advertising",
            "International Relations and Political Science",
            "English Language and Literature",
            "Education and Teaching",
            "Media and Communication Studies",
            "Fine Arts and Design",
            "Linguistics and Language Studies",
            "Mathematics and Statistics",
            "Sociology and Anthropology",
            "Aerospace Engineering",
            "Industrial Engineering",
            "Nursing and Healthcare Management",
            "Pharmaceutical Sciences",
            "Public Health",
            "Law and Legal Studies",
            "History and Archaeology",
            "Human Resource Management",
            "Social Work and Welfare",
            "Chemistry and Chemical Sciences",
            "Renewable Energy and Sustainable Technologies",
            "Biomedical Engineering",
            "Pharmacy and Pharmacology",
            "Artificial Intelligence and Machine Learning",
            "Anthropology and Archaeology",
            "Political Science and International Relations",
            "Journalism and Mass Communication",
            "Nutrition and Dietetics",
            "Linguistics and Language Studies",
            "Geography and Geology",
            "Philosophy and Ethics",
            "Theatre and Performing Arts",
            "Materials Science and Engineering",
            "Hospitality and Tourism Management",
            "Public Administration and Policy",
            "Agricultural Sciences and Agribusiness",
            "Environmental Studies and Conservation",
            "Music and Musicology",
            "Psychology and Counseling",
            "Supply Chain Management and Logistics",
            "Public Relations and Corporate Communications",
            "Human Rights and Social Justice",
            "Gender Studies and Women's Studies",
            "Neuroscience",
            "Marine Biology and Oceanography",
            "Health Sciences and Administration",
            "Robotics and Automation Engineering",
            "Forensic Science and Criminal Justice",
            "Film and Television Production",
            "Sports Science and Exercise Physiology",
            "Cybersecurity and Information Assurance",
            "Urban and Regional Planning",
            "Graphic Design and Visual Communication",
            "Cultural Studies and Anthropology",
            "Development Studies and International Development",
            "Linguistics and Applied Linguistics",
            "Public Policy and Governance",
            "Veterinary Medicine and Animal Sciences",
            "Biomedical Informatics",
            "Industrial Design and Product Development",
            "Religious Studies and Theology",
            "Physics and Astronomy",
            "Health Informatics",
            "Gerontology and Aging Studies",
            "Disaster Management and Humanitarian Assistance",
            "Game Design and Development",
            "Comparative Literature and Literary Studies",
            "Geographical Information Systems (GIS)",
            "Early Childhood Education",
            "Cognitive Science",
            "Tourism and Event Management",
            "Art History and Criticism",
            "Automotive Engineering",
            "Industrial-Organizational Psychology",
            "Aerospace and Aeronautical Engineering",
            "Communication Disorders and Speech-Language Pathology",
            "Renewable Energy Engineering",
            "Fashion Design and Merchandising",
            "Cultural Anthropology",
            "Human Computer Interaction (HCI)",
            "Earth Sciences and Geophysics",
            "Geriatric Medicine",
            "Indigenous Studies",
            "Food Science and Technology",
            "Real Estate and Property Management",
        ]; 
        $returnAry['degree_name'] = $this->dummy_enteries($posted_data, 8);
        return $this->sendResponse($returnAry, 'Inserted new Records.');
    }


    // List of general titles 
    public function general_titles(Request $request){

        $posted_data = array();
        $posted_data['title_status'] = $request->title_status;
        if (isset($request->title)) {
            $posted_data['title'] = $request->title;
        }
        $posted_data['status'] = 'Published';
        if (isset($request->title_status) && $request->title_status == 4) {
            $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                'user_id' => \Auth::user()->id,
                'intrested_vertical' => 'Industry',
                'groupBy' => 'user_industy_vertical_items.general_title_id',
                
            ]);
            $general_title_id = $user_industry_vertical_items->ToArray();
            $general_title_id = array_column($general_title_id, 'general_title_id');
            $posted_data['general_title_id_in'] = $general_title_id;
            $data = $this->IndustryVerticalItemObj->getIndustryVerticalItem($posted_data);
        }
        else{
            $data = $this->GeneralObj->getGeneralTitle($posted_data);
        }
        return $this->sendResponse($data, 'List of general titles');
    }
    // Add of general titles 
    public function create_general_title(Request $request){
        $requestd_data = array();
        $requestd_data = $request->all();
        $rules = array(
            'title' => 'required|unique:general_titles,title|regex:/^[a-zA-Z ]+$/u',
            'title_status' => 'required|integer|between:1,6',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError(["error" => $validator->errors()->first()]);
        }
        
        if (isset($requestd_data['title_status']) && $requestd_data['title_status'] == 4) {
            $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                'user_id' => \Auth::user()->id,
                'intrested_vertical' => 'Industry',
                'groupBy' => 'user_industy_vertical_items.general_title_id',
                
            ]);
            $general_title_id = $user_industry_vertical_items->ToArray();
            $general_title_id = array_column($general_title_id, 'general_title_id');

            if(isset($general_title_id) && count($general_title_id)>0){
                foreach ($general_title_id as $key => $id) {
                    // echo '<pre>'; print_r($id); echo '</pre>'; exit;
                    $general_title = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem([
                        'title' => $requestd_data['title'],
                        'general_title_id' => $id
                    ]);
                }
            }
        }else{
            $general_title = $this->GeneralObj->saveUpdateGeneralTitle([
                'title' => $requestd_data['title'],
                'title_status' => $requestd_data['title_status']
            ]);
        }

        return $this->sendResponse($general_title, 'General title added successfully');
    }

     // list of goals
     public function goals(Request $request){

        $posted_data = array();
        $posted_data['goal_number'] = $request->goal_number;
        $goals = $this->GoalObj->getGoal($posted_data);
        return $this->sendResponse($goals, 'List of goals');
    }

    // List of institute name
    public function educational_info(Request $request){

        $education_information = $this->GeneralObj->getGeneralTitle([
            'title' => $request['institute_name'],
            'title_status' => 7,
            'groupBy' => 'title',
        ])->pluck('title');
        // $education_information = $this->UserEducationalInfoObj::where('university_school_name', 'like', '%' . $request['institute_name'] . '%')->groupBy('university_school_name')->pluck('university_school_name');
        return $this->sendResponse($education_information, 'List of institue name');
    }

    // List of Degrees name
    public function degree_info(Request $request){

        $education_information = $this->GeneralObj->getGeneralTitle([
            'title' => $request['degree_name'],
            'title_status' => 8,
            'groupBy' => 'title',
        ])->pluck('title');
        
        // $education_information = $this->UserEducationalInfoObj::where('degree_discipline', 'like', '%' . $request['degree_name'] . '%')->groupBy('degree_discipline')->pluck('degree_discipline');
        return $this->sendResponse($education_information, 'List of Degree name');
    }

    public function contact_user_list(Request $request){
        $posted_data = array();
        $request_data = $request->all();
        $posted_data['except_auth_id'] = \Auth::user()->id;
        if (isset($request_data['name'])) {
            $posted_data['name'] = $request_data['name'];
        }
        if (isset($request_data['phone_numbers'])) {
            $posted_data['phone_numbers_in'] = $request_data['phone_numbers'];
        }
        $posted_data['except_auth_id'] = \Auth::user()->id;
        $return_ary['matched_connect_peoples'] = $this->UserObj->getUser($posted_data);
        
        $matched_phone_number = $return_ary['matched_connect_peoples']->ToArray();
        $matched_phone_number = array_column($matched_phone_number, 'phone_number');

        $return_ary['not_matched_connect_peoples'] = array();
        if(isset($request_data['phone_numbers'])){
        $a1 = $matched_phone_number;
        $a2 = $request_data['phone_numbers'];
        $return_ary['not_matched_connect_peoples'] = array_merge(array_diff($a1, $a2),array_diff($a2,$a1));
        }
        //echo '<pre>';
        //print_r($matched_phone_number);
        //print_r($request_data['phone_numbers']);
        //print_r($return_ary['not_matched_connect_peoples']);
        //echo '</pre>';
                
        //        $not_match_record = $this->UserObj->getUser([
        //           'phone_numbers_not_in'=>$matched_phone_number
        //        ]);
                
        //       $not_matched_phone_number = $not_match_record->ToArray();
        //        $return_ary['not_matched_connect_peoples'] = array_column($not_matched_phone_number, 'phone_number');
        return $this->sendResponse($return_ary, 'List of users');
    }

    // list of connect people And Filters
    public function connect_people_list(Request $request){
        $posted_data = array();
        $request_data = $request->all();
        $posted_data['paginate'] = 10;
        $posted_data['user_id'] = \Auth::user()->id;
        $posted_data['status'] = 'Accept';

        // status filter
        if(isset($request_data['status'])){
            $posted_data['status'] = $request_data['status'];
        }

        // connect_type filter
        if(isset($request_data['connect_type'])){
            $posted_data['connect_type'] = $request_data['connect_type'];
        }

        if($request_data){
            $posted_data = array_merge($posted_data,$request_data);
        }
        
        $connect_peoples = $this->ConnectPeopleObj->getConnectPeople($posted_data);
        
        $latestConnectUserId = $connect_peoples->ToArray();
        $latestConnectUserId = array_column($latestConnectUserId['data'], 'connect_user_id');

        // Professional role filter
        if(isset($request_data['professional_role'])){
            $professional_role_type_items = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                'general_title_ids' => $request_data['professional_role'],
                'user_ids' => $latestConnectUserId
            ]);
            
            $latestConnectUserId = $professional_role_type_items->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
        }

        // Industry experties filter
        if(isset($request_data['industry_experties'])){
            $industries_experties = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                'general_title_ids' => $request_data['industry_experties'],
                'user_ids' => $latestConnectUserId
            ]);
            
            $latestConnectUserId = $industries_experties->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
        }

        // Career status position filter
        if(isset($request_data['career_status_position'])){
            $career_status_position = $this->UserCareerStatusObj->getUserCareerStatusPosition([
                'general_title_ids' => $request_data['career_status_position'],
                'user_ids' => $latestConnectUserId
            ]);
            $latestConnectUserId = $career_status_position->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'user_id');

        }
        
        // Age range filter filter
        if(isset($request_data['age_from']) && isset($request_data['age_to'])){
            $user_record = $this->UserObj->getUser([
                'user_ids' => $latestConnectUserId,
                'age_from'=> $request_data['age_from'],
                'age_to'=> $request_data['age_to'],
            ]);
            
            $latestConnectUserId = $user_record->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'id');
        }

        // Location filter
        if(isset($request_data['location'])){
            $user_location = $this->UserObj->getUser([
                'location' => $request_data['location'],
                'user_ids' => $latestConnectUserId,
            ]);
            
            $latestConnectUserId = $user_location->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'id');
        }
        // Gender filter
        if(isset($request_data['gender'])){
            $user_record = $this->UserObj->getUser([
                'gender' => $request_data['gender'],
                'user_ids' => $latestConnectUserId
            ]);

            $latestConnectUserId = $user_record->ToArray();
            $latestConnectUserId = array_column($latestConnectUserId, 'id');
        }

        $connect_peoples = $this->UserObj->getUser([
            'user_ids' => $latestConnectUserId
        ]);

        return $this->sendResponse($connect_peoples, 'User connect list');
    
    }


    // Post connects peoples
    public function connects_people(Request $request){
        if (\Auth::check()) {

            $rules = array(
                'connect_user_id' => 'required|exists:users,id'
            );
    
            $validator = \Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return $this->sendError(["error" => $validator->errors()->first()]);
            }

            $requestd_data = array();
            $requestd_data['user_id'] = \Auth::user()->id;
            $requestd_data['connect_user_id'] =  $request->connect_user_id;
            $requestd_data['status'] = 'Pending';
            $requestd_data['connect_type'] =  'phonebook';

            $this->ConnectPeopleObj->saveUpdateConnectPeople($requestd_data);
            return $this->sendResponse('Success', 'User connected');
        }
        else{
            return $this->sendError('Something went wrong');
        }
    }

    // Update connects peoples
    public function update_connects_people(Request $request, $id){
        
        $update_data = $request->all();
        $update_data['update_id'] = $id;

        $rules = array(
            'update_id' => 'exists:connect_people,id'
        );

        $validator = \Validator::make($update_data, $rules);
        if ($validator->fails()) {
            return $this->sendError(["error" => $validator->errors()->first()]);
        }

        $connect_people = $this->ConnectPeopleObj->getConnectPeople([
            'id' => $id,
            'detail' => true
        ]);
        if ($connect_people) {
            if ($update_data['status'] == 'Accept') {
                
                $this->ConnectPeopleObj->saveUpdateConnectPeople([
                    'user_id' => $connect_people->connect_user_id,
                    'connect_user_id' => \Auth::user()->id,
                    'status' => 'Accept',
                    'connect_type' => 'phonebook'
                ]);  
            }
        }
        
        $this->ConnectPeopleObj->saveUpdateConnectPeople($update_data);
        return $this->sendResponse('Success', 'User connection updated');
    }


    public function login_user(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'phone_number'  => 'required|exists:users,phone_number|regex:/^\d{3}-\d{3}-\d{4}$/'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        
        $user_data['phone_number'] = $posted_data['phone_number'];
        $user_data['detail'] = true;
        $user_data = $this->UserObj->getUser($user_data);
        \Auth::login($user_data);
        $user_data['token'] =  $user_data->createToken('MyApp')->accessToken;

        // $user =  $user_data->createToken('MyApp')->accessToken;

       return $this->sendResponse($user_data, 'User Login Successfully');
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register_user(Request $request)
    {
        $requested_data = $request->all();

        if(isset($requested_data['user_id'])){
            $user_detail_record = $this->UserObj->getUser([
                'id' => $requested_data['user_id'],
                'detail'=>true
            ]);
        }
        if (isset($requested_data['step'])) {
            $posted_data = array();
            $posted_data['step'] = $requested_data['step'];

            //In step 1 send phone number
            if ($requested_data['step'] == 1) {
                $rules = array(
                    'phone_number' => 'required|unique:users,phone_number',
                );
                $validator = \Validator::make($requested_data, $rules);

                if ($validator->fails()) {
                    $rules = array(
                        'phone_number' => 'required',
                    );

                    $validator = \Validator::make($requested_data, $rules);

                    if ($validator->fails()) {
                        return $this->sendError($validator->errors()->first(), $validator->messages());
                        // return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
                    } 

                    $user_data = $this->UserObj->getUser([
                        'phone_number' => $requested_data['phone_number'],
                        'detail' => true
                    ]);
                    \Auth::login($user_data);
                    $user_data['token'] =  $user_data->createToken('MyApp')->accessToken;
                   return $this->sendResponse($user_data, 'User Login Successfully');
                }
                $posted_data['phone_number'] = $requested_data['phone_number'];
            }

            //In step 2 send first name, last name and other name
            else if ($requested_data['step'] == 2) {

                $rules = array(
                   
                    'user_id' => 'required|exists:users,id',
                    // 'email' => 'required|email:rfc,dns|unique:users,email,'.$requested_data['user_id'],
                    // 'first_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
                    // 'last_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/'
                     'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                     'last_name' => 'required||regex:/^[a-zA-Z ]+$/u'
                );
                $validator = \Validator::make($requested_data, $rules, [
                     
                    'first_name.regex' => 'Only letters are allowed',
                    'last_name.regex' => 'Only letters are allowed',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];
                if (isset($requested_data['email'])) {
                    $posted_data['email'] = $requested_data['email'];
                }
               
                $posted_data['first_name'] = $requested_data['first_name'];
                $posted_data['last_name'] = $requested_data['last_name'];
                if (isset($requested_data['other_name'])) {
                    $posted_data['other_name'] = $requested_data['other_name'];
                }
            }

            //In step 3 send date of birth and gender
            else if ($requested_data['step'] == 3) {
                $rules = array(
                    'user_id' => 'required|exists:users,id',
                    'dob' => 'required',
                    'gender' => 'required|in:1,2,3'
                );
                $validator = \Validator::make($requested_data, $rules);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];
                $posted_data['dob'] = $requested_data['dob'];
                $posted_data['gender'] = $requested_data['gender'];
            }

            // Company job title and career seniority
            else if ($requested_data['step'] == 4) {
                $rules = array(
                    'user_id'  => 'required|exists:users,id',
                    'company_career_info_id' => 'required|exists:general_titles,id',
                    //'company_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
                    //'job_title' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
                    'job_title' => 'required|regex:/^[a-zA-Z()\/\-\ ]*$/u',
		    'company_name' => 'required|regex:/^[a-zA-Z()\/\-\ ]*$/u',
                );
                $validator = \Validator::make($requested_data, $rules, [
'company_name.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
'job_title.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
                    'company_name.regex' => 'Company name format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
                    'job_title.regex' => 'Job title format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];
                $user_career_record = $this->UserCareerStatusObj->getUserCareerStatusPosition([
                    'user_id' => $requested_data['user_id'],
                ]);

                if($user_career_record){
                    $this->UserCareerStatusObj->deleteUserCareerStatusPosition(0,['user_id' => $requested_data['user_id']]);
                }
                
                $this->UserCareerStatusObj->saveUpdateUserCareerStatusPosition([
                    'user_id' => $requested_data['user_id'],
                    'general_title_id' => $requested_data['company_career_info_id'],
                    'company_name' => $requested_data['company_name'],
                    'job_title' => $requested_data['job_title'],
                ]);
            }

            // Select Professional Role information
            else if ($requested_data['step'] == 5) {

                $rules = array(
                    'user_id' => 'required|exists:users,id',
                    'prof_role_type_id' => 'required|exists:prof_role_types,id',
                    'prof_info_id' => 'required|exists:general_titles,id',
                    "prof_role_type_item_id"  => "exists:pro_role_type_items,id",
                );
                $validator = \Validator::make($requested_data, $rules, [
                    'prof_role_type_item_id.*.exists' => 'The selected professional role type item id is invalid.',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                }
                $posted_data['update_id'] = $requested_data['user_id'];

                $user_professional_role_record = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                    'user_id' => $requested_data['user_id'],
                ]);

                if($user_professional_role_record){
                    $this->UserProRolteItemObj->deleteUserProRoleTypeItem(0,['user_id' => $requested_data['user_id']]);
                }

                if (isset($requested_data['prof_role_type_item_id'])) {
                    for ($i = 0; $i < count($requested_data['prof_role_type_item_id']); $i++) {
                        $update_data = array();
                        $update_data['prof_role_type_id'] = $requested_data['prof_role_type_id'];
                        $update_data['general_title_id'] = $requested_data['prof_info_id'];
                        $update_data['user_id'] = $requested_data['user_id'];
                        $update_data['prof_role_type_item_id'] = $requested_data['prof_role_type_item_id'][$i];
                        $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);
                    }
                }
            }

            // Choose Goals
            else if ($requested_data['step'] == 6) {
                $rules = array(
                    'user_id' => 'required|exists:users,id',
                    "goal_item_id" => "required|exists:goal_items,id",
                );

                $validator = \Validator::make($requested_data, $rules,[
                    'goal_item_id.*.exists' => 'The selected goal item id is invalid.',
                    'goal_item_id.required' => 'The selected goal item id is invalid.',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 
                $posted_data['update_id'] = $requested_data['user_id'];

                $user_goals_record = $this->UserGoalItemObj->getUserGoalItem([
                    'user_id' => $requested_data['user_id'],
                ]);

                if($user_goals_record){
                    $this->UserGoalItemObj->deleteUserGoalItem(0,['user_id' => $requested_data['user_id']]);
                }

                if (isset($requested_data['goal_item_id'])) {
                    foreach ($requested_data['goal_item_id'] as $key => $goal_item_value) {
                        $posted_goal_items = array();
                        $posted_goal_items['user_id'] = $requested_data['user_id'];
                        $posted_goal_items['goal_item_id'] = $goal_item_value;
                        $this->UserGoalItemObj->saveUpdateUserGoalItem($posted_goal_items);
                    }
                }

                // $this->UserGoalItemObj->saveUpdateUserGoalItem([
                //     'user_id' =>  $requested_data['user_id'],
                //     'goal_item_id' => $requested_data['goal_item_id'],
                // ]);
            }

            // Educational Information
            else if ($requested_data['step'] == 7) {

                $rules = array(
                    
                    'user_id' => 'required|exists:users,id',
                    'education_info_id' => 'required|exists:general_titles,id',
                    'university_school_name' => 'required|regex:/^[a-zA-Z()\/\-\ ]*$/u',
                    'degree_discipline' => 'required|regex:/^[a-zA-Z()\/\-\ ]*$/u',
                    // 'university_school_name' => 'required|regex:/^[a-zA-Z ()-]+$/u',
                    // 'degree_discipline' => 'required|regex:/^[a-zA-Z ()-]+$/u'
                    // 'university_school_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                    // 'degree_discipline' => 'required||regex:/^[a-zA-Z ]*(?:[\/\-\(\)][a-zA-Z ]*)+$/u'
                );
                $validator = \Validator::make($requested_data, $rules, [
                    'university_school_name.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
		            'degree_discipline.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
                   // 'university_school_name.regex' => 'University school name format not correct kindly Only leters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
                   // 'degree_discipline.regex' => 'Degree discipline format not correct kindly Only leters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                }
                $posted_data['update_id'] = $requested_data['user_id'];

                $user_educational_record = $this->UserEducationalInfoObj->getUserEducationalInformation([
                    'user_id' => $requested_data['user_id'],
                ]);

                $this->dummy_enteries($requested_data['university_school_name'],7);
                $this->dummy_enteries($requested_data['degree_discipline'],8);
                if($user_educational_record){
                    $this->UserEducationalInfoObj->deleteUserEducationalInformation(0,['user_id' => $requested_data['user_id']]);
                }

                $this->UserEducationalInfoObj->saveUpdateUserEducationalInformation([
                    'user_id' =>  $requested_data['user_id'],
                    'general_title_id' => $requested_data['education_info_id'],
                    'university_school_name' => $requested_data['university_school_name'],
                    'degree_discipline' => $requested_data['degree_discipline']
                ]);
            }

            // User Industry Vertical
            else if ($requested_data['step'] == 8) {

                $rules = array(
                    'user_id' => 'required|exists:users,id',
                    'industry_id' => 'required|exists:general_titles,id',
                    "industry_vertical_item_id"  => "exists:industry_vertical_items,id",
                );
                $validator = \Validator::make($requested_data, $rules, [
                    'industry_vertical_item_id.*.exists' => 'The selected industry vertical item id is invalid.',
                    'industry_id.required' => 'The industry id is required.',
                    'industry_id.*.exists' => 'The selected industry id is invalid.',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                }
                $posted_data['update_id'] = $requested_data['user_id'];

                $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                    'user_id' => $requested_data['user_id'],
                    'intrested_vertical' => 'Industry',
                ]);
                
                if($user_detail_record['same_as_industry'] == 'No'){
                    if($user_industry_vertical_record){
                        $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Industry']);
                    }
                }
                else if($user_detail_record['same_as_industry'] == 'Yes'){
                    $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id']]);
                }


                if (isset($requested_data['industry_vertical_item_id'])) {
                    foreach ($requested_data['industry_vertical_item_id'] as $key => $industry_vertical_item_value) {

                        $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                            'id' => $industry_vertical_item_value,
                            'detail' => true
                        ]);
                        if ($industry_list) {
                            $industry_data = array();
                            $industry_data['user_id'] = $requested_data['user_id'];
                            $industry_data['intrested_vertical'] = 'Industry';
                            $industry_data['general_title_id'] = $industry_list['general_title_id'];
                            $industry_data['industry_vertical_item_id'] = $industry_vertical_item_value;
                            $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
                        }
                    }
                }
                if (isset($requested_data['industry_id'])) {
                    foreach ($requested_data['industry_id'] as $key => $industry_vertical_item_value) {

                        $industry_list = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                            'user_id' => $requested_data['user_id'],
                            'general_title_id' => $industry_vertical_item_value,
                            'detail' => true
                        ]);
                        
                        // $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                        //     'general_title_id' => $industry_vertical_item_value,
                        //     'detail' => true
                        // ]);
                        if (!$industry_list) {
                            $industry_data = array();
                            $industry_data['user_id'] = $requested_data['user_id'];
                            $industry_data['general_title_id'] = $industry_vertical_item_value;
                            $industry_data['intrested_vertical'] = 'Industry';

                            $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
                        }
                    }
                }
            }

            // User Interested Vertical
            else if ($requested_data['step'] == 9) {

                $rules = array(
                    'user_id' => 'required|exists:users,id',
                );
                $validator = \Validator::make($requested_data, $rules);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];
                

                if (isset($requested_data['same_industry_vertical']) && $requested_data['same_industry_vertical']== 1) {

                    $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                        'user_id' => $requested_data['user_id'],
                        'intrested_vertical' => 'Interest',
                    ]);
                    
                    if($user_industry_vertical_record){
                        $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Interest']);
                    }
                    
                    $posted_data['same_as_industry'] = 'Yes';

                    $user_industry_lis = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                        'user_id' => $requested_data['user_id']
                    ]);

                    if ($user_industry_lis) {
                        foreach ($user_industry_lis as $key => $user_industry_value) {
                            $industry_data = array();
                            $industry_data['user_id'] = $user_industry_value['user_id'];
                            $industry_data['general_title_id'] = $user_industry_value['general_title_id'];
                            $industry_data['industry_vertical_item_id'] = $user_industry_value['industry_vertical_item_id'];
                            $industry_data['intrested_vertical'] = 'Interest';
                            $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
                        }
                    }
                } else {

                    $rules = array(
                        'intrest_id' => 'required|exists:general_titles,id',
                        "interested_vertical_item_id"  => "exists:industry_vertical_items,id",
                    );
                    $validator = \Validator::make($requested_data, $rules, [
                        'interested_vertical_item_id.*.exists' => 'The selected intrested vertical item id is invalid.',
                        'intrest_id.required' => 'The Intrest id is required.',
                        'intrest_id.*.exists' => 'The selected Intrest id is invalid.',
                    ]);
                    if ($validator->fails()) {
                        return $this->sendError($validator->errors()->first(), $validator->messages());
                    }

                    $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                        'user_id' => $requested_data['user_id'],
                        'intrested_vertical' => 'Interest',
                    ]);
                    if($user_industry_vertical_record){
                        $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Interest']);
                    }
                    
                    $posted_data['same_as_industry'] = 'No';

                    if (isset($requested_data['interested_vertical_item_id'])) {

                    
                        foreach ($requested_data['interested_vertical_item_id'] as $key => $industry_vertical_item_value) {

                            $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                                'id' => $industry_vertical_item_value,
                                'detail' => true
                            ]);
                            if ($industry_list) {
                                $interest_data = array();
                                $interest_data['user_id'] = $requested_data['user_id'];
                                $interest_data['general_title_id'] = $industry_list['general_title_id'];
                                $interest_data['industry_vertical_item_id'] = $industry_vertical_item_value;
                                $interest_data['intrested_vertical'] = 'Interest';
                                $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
                            }
                        }
                    }
                    if (isset($requested_data['intrest_id'])) {

                        foreach ($requested_data['intrest_id'] as $key => $industry_vertical_item_value) {

                            $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                                'general_title_id' => $industry_vertical_item_value,
                                'detail' => true
                            ]);
                            if (!$industry_list) {
                                $interest_data = array();
                                $interest_data['user_id'] = $requested_data['user_id'];
                                $interest_data['general_title_id'] = $industry_vertical_item_value;
                                $interest_data['intrested_vertical'] = 'Interest';

                                $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
                            }
                        }
                    }
                }
            }
            // Specialty And Skills
            else if ($requested_data['step'] == 10) {

                if(isset($requested_data['specialty_skill_id'])){
                    $requested_data['industry_vertical_item_id'] = $requested_data['specialty_skill_id'];
                }
                $rules = array(
                    'user_id' => 'required|exists:users,id',
		    'industry_vertical_item_id' => 'required',
                    //'industry_vertical_item_id' => 'required|exists:industry_vertical_items,id'
                    // 'industry_vertical_item_id' => 'required|exists:user_industy_vertical_items,industry_vertical_item_id'
                );
                $validator = \Validator::make($requested_data, $rules,[
                    'industry_vertical_item_id.exists' => 'The selected specialty skill is invalid.',
                    'industry_vertical_item_id.required' => 'The specialty skill is required.',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];

                $user_specialty_record = $this->UserSpecialtyObj->getUserSpecialty([
                    'user_id' => $requested_data['user_id'],
                ]);
                // $user_industry_vertical_item_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                //     'user_id' => $requested_data['user_id'],
                // ]);
                

                if($user_specialty_record){
                    $this->UserSpecialtyObj->deleteUserSpecialty(0,['user_id' => $requested_data['user_id']]);
                }

                if (isset($requested_data['industry_vertical_item_id'])) {
                    for ($i = 0; $i < count($requested_data['industry_vertical_item_id']); $i++) {
                        $update_data = array();
                        $update_data['user_id'] = $requested_data['user_id'];
                        $update_data['industry_vertical_item_id'] = $requested_data['industry_vertical_item_id'][$i];
			$check = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
					'id' => $requested_data['industry_vertical_item_id'][$i],	
					'detail' =>	true		
					]);
			if($check){
                	        $this->UserSpecialtyObj->saveUpdateUserSpecialty($update_data);
		            }
        }
                }
            }


            // upload user Profile Image
            else if ($requested_data['step'] == 11) {

                $rules = array(
                    'user_id' => 'required|exists:users,id',
                    'profile_image' => 'required|mimes:jpeg,png,jpg|max:2048',
                );
                $validator = \Validator::make($requested_data, $rules);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), $validator->messages());
                } 

                $posted_data['update_id'] = $requested_data['user_id'];

                if ($request->file('profile_image')) {
                    $extension = $request->profile_image->getClientOriginalExtension();
                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {

                        // if (!empty(\Auth::user()->profile_image) && \Auth::user()->role != 1) {
                        //     $url = $base_url.'/'.\Auth::user()->profile_image;
                        //     if (file_exists($url)) {
                        //         unlink($url);
                        //     }
                        // }

                        // $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                        $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                        $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                        $posted_data['profile_image'] = 'storage/profile_image/' . $file_name;
                    } else {
                        $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }
            }

            if (count($posted_data) > 0) {

                // $validator = \Validator::make($requested_data, $rules);
                // if ($validator->fails()) {
                //     return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
                // } else {
                    $user = $this->UserObj->saveUpdateUser($posted_data);
                    if ($requested_data['step'] == 1) {
                        $user = $this->UserObj->getUser([
                            'phone_number' => $user['phone_number'],
                            'detail' => true
                        ]);
                        \Auth::login($user);
                        $user['token'] =  $user->createToken('MyApp')->accessToken;
                    }
                    return $this->sendResponse($user, 'User information added successfully.');
                // }
            } else {
                $error_message['error'] = 'Please submit user data.';
                return $this->sendError($error_message['error'], $error_message);
            }
        }
        $error_message['error'] = 'Please add step value.';
        return $this->sendError($error_message['error'], $error_message);
    }

    public function register_user_backup(Request $request)
    {
        /*
        id, first_name, last_name, full_name, email, password, user_type, dob, location, country, city, state, latitude, longitude, profile_image, phone_number, user_status, register_from, last_seen, email_verified_at, time_spent, theme_mode, remember_token, created_at, updated_at
        */

        $requested_data = $request->all();
        $rules = array(
            'full_name'         => 'required',
            'email'             => $requested_data['register_from'] != 1 ? 'required|email' : 'required|email|unique:users',
            'user_type'         => 'required|in:1,2,3,4',
            'dob'               => 'nullable|date_format:Y-m-d',
            'location'          => 'nullable|min:4',
            'country'           => 'nullable|min:4',
            'city'              => 'nullable|min:4',
            'state'             => 'nullable|min:4',
            'latitude'          => 'nullable|min:4',
            'longitude'         => 'nullable|min:4',
            'profile_image'     => 'nullable',
            'phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'user_status'       => 'nullable|in:1,2',
            'register_from'     => 'required|in:1,2,3,4',
            'password'          => $requested_data['register_from'] == 'app' ?
                [
                    'required', Password::min(8)
                    // ->numbers()
                    // ->letters()
                    // ->mixedCase()
                    // ->symbols()
                    // ->uncompromised()
                ] : 'nullable',
            'confirm_password'  => 'required_with:password|same:password',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        } else {
            // $register_data = array();
            // $documents_arr = array();

            $name_arr = explode(' ', $requested_data['full_name']);
            $global_data = array();
            $global_data['first_name'] = isset($name_arr[0]) ? ucfirst($name_arr[0]) : NULL;
            $global_data['last_name'] = isset($name_arr[1]) ? ucfirst($name_arr[1]) : NULL;

            if (!filter_var($requested_data['email'], FILTER_VALIDATE_EMAIL)) {
                $error_message['error'] = 'Please eneter a valid email address.';
                return $this->sendError($error_message['error'], $error_message);
            }

            if ($requested_data['register_from'] != 1) {

                $posted_data = array();
                $posted_data['email'] = $requested_data['email'];
                $posted_data['detail'] = true;
                $user_details = User::getUser($posted_data);

                if (isset($user_details['id'])) {
                    $user = User::where('email', $requested_data['email'])->first();
                    User::saveUpdateUser(['update_id' => $user_details['id'], 'register_from' => $requested_data['register_from']]);
                    if (Auth::loginUsingId($user->id)) {
                        $user = Auth::user();
                        $response =  $user;
                        $response['token'] =  $user->createToken('MyApp')->accessToken;
                    } else {
                        $response = false;
                    }

                    if ($response)
                        return $this->sendResponse($response, 'User login successfully.');
                    else {
                        $error_message['error'] = 'This email has already been registered.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                } else {
                    $global_data['password'] = '12345678@w';
                    $global_data['email_verified_at'] = date('Y-m-d h:i:s');
                }
            }

            $user_data = array();
            $user_data['first_name'] = $global_data['first_name'];
            $user_data['last_name'] = $global_data['last_name'];
            $user_data['full_name'] = $requested_data['full_name'];
            $user_data['email'] = $requested_data['email'];

            if (isset($global_data['password']) && $global_data['password'])
                $user_data['password'] = $global_data['password'];
            else
                $user_data['password'] = $requested_data['password'];

            $user_data['user_type'] = $requested_data['user_type'];
            $user_data['dob'] = $requested_data['dob'];
            $user_data['location'] = isset($requested_data['location']) ? $requested_data['location'] : NULL;
            $user_data['country'] = isset($requested_data['country']) ? $requested_data['country'] : NULL;
            $user_data['city'] = isset($requested_data['city']) ? $requested_data['city'] : NULL;
            $user_data['state'] = isset($requested_data['state']) ? $requested_data['state'] : NULL;
            $user_data['latitude'] = isset($requested_data['latitude']) ? $requested_data['latitude'] : NULL;
            $user_data['longitude'] = isset($requested_data['longitude']) ? $requested_data['longitude'] : NULL;
            $user_data['profile_image'] = isset($requested_data['profile_image']) ? $requested_data['profile_image'] : NULL;
            $user_data['phone_number'] = isset($requested_data['phone_number']) ? $requested_data['phone_number'] : NULL;
            $user_data['user_status'] = isset($requested_data['user_status']) ? $requested_data['user_status'] : NULL;
            $user_data['register_from'] = isset($requested_data['register_from']) ? $requested_data['register_from'] : NULL;
            $user_data['last_seen'] = isset($requested_data['last_seen']) ? $requested_data['last_seen'] : NULL;

            if (isset($global_data['email_verified_at']) && $global_data['email_verified_at'])
                $user_data['email_verified_at'] = $global_data['email_verified_at'];

            if (isset($global_data['email_verified_at']) && $global_data['email_verified_at'])
                $user_data['remember_token'] = NULL;
            else
                $user_data['remember_token'] = generateRandomNumbers(4);

            $user_id = User::saveUpdateUser($user_data);

            if ($user_id) {
                $response = $this->authorizeUser([
                    'email' => $user_data['email'],
                    'password' => isset($user_data['password']) ? $user_data['password'] : '12345678@w'
                ]);

                // $notification_text = "A new user has been register into the app.";

                // $notification_params = array();
                // $notification_params['sender'] = $user_id->id;
                // $notification_params['receiver'] = 1;
                // $notification_params['slugs'] = "new-user";
                // $notification_params['notification_text'] = $notification_text;
                // $notification_params['metadata'] = "user_id=$user_id";

                // $notif_response = Notification::saveUpdateNotification([
                //     'sender' => $notification_params['sender'],
                //     'receiver' => $notification_params['receiver'],
                //     'slugs' => $notification_params['slugs'],
                //     'notification_text' => $notification_params['notification_text'],
                //     'metadata' => $notification_params['metadata']
                // ]);

                // $firebase_devices = FCM_Token::getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
                // $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');

                // if ($notif_response) {

                //     $notification = FCM_Token::sendFCM_Notification([
                //         'title' => $notification_params['slugs'],
                //         'body' => $notification_params['notification_text'],
                //         'metadata' => $notification_params['metadata'],
                //         'registration_ids' => $notification_params['registration_ids'],
                //         'details' => []
                //     ]);
                // }

                // $admin_data = User::getUser(['id' => 1, 'without_with' => true, 'detail' => true]);

                // // this email will send to the admin to notify about newly registered user
                // $email_content = EmailTemplate::getEmailMessage(['id' => 2, 'detail' => true]);

                // $email_data = decodeShortCodesTemplate([
                //     'subject' => $email_content->subject,
                //     'body' => $email_content->body,
                //     'email_message_id' => 2,
                //     'sender_id' => $user_id->id,
                //     'receiver_id' => $admin_data->id,
                // ]);

                // // here sender is the customer and receiver is the supplier
                // EmailLogs::saveUpdateEmailLogs([
                //     'email_msg_id' => 2,
                //     'sender_id' => $user_id->id,
                //     'receiver_id' => $admin_data->id,
                //     'email' => $admin_data->email,
                //     'subject' => $email_data['email_subject'],
                //     'email_message' => $email_data['email_body'],
                //     'send_email_after' => 1, // 1 = Daily Email
                // ]);


                // // this email will send to the user who has successfully registered with social apps
                // $email_content = EmailTemplate::getEmailMessage(['id' => 5, 'detail' => true]);

                // $email_data = decodeShortCodesTemplate([
                //     'subject' => $email_content->subject,
                //     'body' => $email_content->body,
                //     'email_message_id' => 5,
                //     'user_id' => $user_id->id,
                // ]);

                // // here sender is the customer and receiver is the supplier
                // EmailLogs::saveUpdateEmailLogs([
                //     'email_msg_id' => 5,
                //     'sender_id' => $admin_data->id,
                //     'receiver_id' => $user_id->id,
                //     'email' => $user_id->email,
                //     'subject' => $email_data['email_subject'],
                //     'email_message' => $email_data['email_body'],
                //     'send_email_after' => 1, // 1 = Daily Email
                // ]);

                $data = [
                    'subject' => 'Welcome to the App',
                    'email_mode' => 'welcome_mail',
                    'name' => $requested_data['full_name'],
                    'email' => $requested_data['email'],
                    'token' => '',
                ];

                Mail::send('emails.generic_template', ['email_data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])
                        ->subject($data['subject']);
                });

                if (!(isset($global_data['email_verified_at']) && $global_data['email_verified_at'])) {
                    $data = [
                        'subject' => 'Account Verification',
                        'email_mode' => 'send_otp',
                        'name' => $requested_data['full_name'],
                        'email' => $requested_data['email'],
                        'otp_token' => $user_data['remember_token'],
                        'token' => '',
                    ];

                    Mail::send('emails.generic_template', ['email_data' => $data], function ($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });
                }

                if ($response)
                    return $this->sendResponse($response, 'User successfully registered. OTP also sent to your email.');
                else {
                    $error_message['error'] = 'The user credentials are not valid.';
                    return $this->sendError($error_message['error'], $error_message);
                }
            }



            // if( $requested_data['role'] != 2 && $requested_data['role'] != 3 && $requested_data['role'] != 4 ){
            //     $error_message['error'] = 'You entered the invalid role.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            if (isset($request->company_documents)) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                foreach ($request->company_documents as $mediaFiles) {
                    $extension = strtolower($mediaFiles->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if (!$check) {
                        $error_message['error'] = 'Invalid file format you can only add jpg, jpeg, png and pdf file format.';
                        return $this->sendError($error_message['error'], $error_message);
                    }
                }
            }

            $user_detail = User::saveUpdateUser($requested_data);
            $user_id = $user_detail->id;

            $login_response = $this->authorizeUser([
                'email' => $requested_data['email'],
                'password' => isset($requested_data['password']) ? $requested_data['password'] : '12345678@w'
            ]);

            $message = ($user_id) > 0 ? 'User is successfully registered.' : 'Something went wrong during registration.';
            if ($user_id) {

                if ($requested_data['role'] == 3 || $requested_data['role'] == 2) {
                    $address_arr['user_id'] = $user_id;
                    $address_arr['title'] = $requested_data['title'];
                    $address_arr['address'] = $requested_data['address'];
                    $address_arr['country'] = $requested_data['country'];
                    $address_arr['city'] = isset($requested_data['city']) ? $requested_data['city'] : NULL;
                    $address_arr['state'] = isset($requested_data['state']) ? $requested_data['state'] : NULL;
                    $address_arr['code'] = isset($requested_data['code']) ? $requested_data['code'] : NULL;
                    $address_arr['iso_code'] = isset($requested_data['iso_code']) ? $requested_data['iso_code'] : NULL;
                    $address_arr['postal_code'] = $requested_data['postal_code'];
                    $data = UserMultipleAddresse::saveUpdateUserMultipleAddresse($address_arr);
                }

                if (isset($request->company_documents)) {
                    $allowedfileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                    foreach ($request->company_documents as $mediaFiles) {

                        $extension = strtolower($mediaFiles->getClientOriginalExtension());

                        $check = in_array($extension, $allowedfileExtension);
                        if ($check) {

                            $response = upload_files_to_storage($request, $mediaFiles, 'other_assets');

                            if (isset($response['action']) && $response['action'] == true) {
                                $arr = [];
                                $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                                $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                            }

                            $asset_id = UserAssets::saveUpdateUserAssets([
                                'user_id'       => $user_id,
                                'asset_type'    => 1,
                                'filepath'      => $arr['file_path'],
                                'filename'      => $arr['file_name'],
                                'mimetypes'     => $mediaFiles->getClientMimeType(),
                                'asset_status'  => 0,
                                'asset_view'    => 0,
                            ]);

                            $arr['asset_id'] = $asset_id;
                            $documents_arr[] = $arr;
                        } else {
                            $error_message['error'] = 'Invalid file format you can only add jpg, jpeg, png and pdf file format.';
                            return $this->sendError($error_message['error'], $error_message);
                        }
                    }
                }
                $user_detail = User::getUser([
                    'id'       => $user_id,
                    'detail'       => true
                ]);

                $notification_text = "A new user has been register into the app.";

                $notification_params = array();
                $notification_params['sender'] = $user_id;
                $notification_params['receiver'] = 1;
                $notification_params['slugs'] = "new-user";
                $notification_params['notification_text'] = $notification_text;
                $notification_params['metadata'] = "user_id=$user_id";

                $response = Notification::saveUpdateNotification([
                    'sender' => $notification_params['sender'],
                    'receiver' => $notification_params['receiver'],
                    'slugs' => $notification_params['slugs'],
                    'notification_text' => $notification_params['notification_text'],
                    'metadata' => $notification_params['metadata']
                ]);

                $firebase_devices = FCM_Token::getFCM_Tokens(['user_id' => $notification_params['receiver']])->toArray();
                $notification_params['registration_ids'] = array_column($firebase_devices, 'device_token');

                if ($response) {

                    if (isset($model_response['user']))
                        unset($model_response['user']);
                    if (isset($model_response['post']))
                        unset($model_response['post']);

                    $notification = FCM_Token::sendFCM_Notification([
                        'title' => $notification_params['slugs'],
                        'body' => $notification_params['notification_text'],
                        'receiver_id' => $notification_params['receiver'],
                        'metadata' => $notification_params['metadata'],
                        'registration_ids' => $notification_params['registration_ids'],
                        'details' => $user_detail
                    ]);
                }

                /*
                $data = [
                    'subject' => 'Email Verification',
                    'name' => $request->get('full_name'),
                    'email' => $request->get('email'),
                    'token' => $token,
                ];
                */

                $admin_data['id'] = 1;
                $admin_data['detail'] = true;
                $response = User::getUser($admin_data);

                // this email will sent to the newly registered user via mobile app
                $email_content = EmailTemplate::getEmailMessage(['id' => 6, 'detail' => true]);

                $email_data = decodeShortCodesTemplate([
                    'subject' => $email_content->subject,
                    'body' => $email_content->body,
                    'email_message_id' => 6,
                    'user_id' => $user_id,
                    'email_verification_url' => $token,
                ]);

                EmailLogs::saveUpdateEmailLogs([
                    'email_msg_id' => 6,
                    'sender_id' => $response->id,
                    'receiver_id' => $user_id,
                    'email' => $request->get('email'),
                    'subject' => $email_data['email_subject'],
                    'email_message' => $email_data['email_body'],
                    'send_email_after' => 1, // 1 = Daily Email
                ]);

                /*
                Mail::send('emails.welcome_email', ['email_data' => $data], function($message) use ($data) {
                    $message->to($data['email'])
                            ->subject($data['subject']);
                });
                */

                if ($response) {

                    /*
                    $data = [
                        'subject' => 'New User Registered',
                        'name' => $response->name,
                        'email' => $response->email,
                        'text_line' => "A new user ".$request->get('full_name')." has been registered on ".config('app.name'),
                    ];
                    */

                    // this email will sent to the admin on new user registeration
                    $email_content = EmailTemplate::getEmailMessage(['id' => 2, 'detail' => true]);

                    $email_data = decodeShortCodesTemplate([
                        'subject' => $email_content->subject,
                        'body' => $email_content->body,
                        'email_message_id' => 2,
                        'sender_id' => $user_id,
                        'receiver_id' => $response->id,
                    ]);

                    EmailLogs::saveUpdateEmailLogs([
                        'email_msg_id' => 2,
                        'sender_id' => $user_id,
                        'receiver_id' => $response->id,
                        'email' => $response->email,
                        'subject' => $email_data['email_subject'],
                        'email_message' => $email_data['email_body'],
                        'send_email_after' => 1, // 1 = Daily Email
                    ]);

                    /*
                    Mail::send('emails.general_email', ['email_data' => $data], function($message) use ($data) {
                        $message->to($data['email'])
                                ->subject($data['subject']);
                    });
                    */
                }

                $user_detail['token'] = isset($login_response['token']) ? $login_response['token'] : '';
                return $this->sendResponse($user_detail, $message);
            } else {
                $error_message['error'] = $message;
                return $this->sendError($error_message['error'], $error_message);
            }
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login_user_old(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'phone_number'      => 'required|exists:users,phone_number',
            // 'password'          => $posted_data['source'] != 1 ? 'nullable' : 'required',
            // 'source'            => 'required|integer|in:1,2,3,4',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        if ($posted_data['source'] != 1) {
            $user_data['phone_number'] = $posted_data['phone_number'];
            $user_data['detail'] = true;
            $user_data = $this->UserObj->getUser($user_data);

            if (isset($user_data->id) && isset($user_data->register_from) && $user_data->register_from != 1) {
                $response = $this->authorizeUser([
                    'phone_number' => $posted_data['phone_number'],
                    'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                ]);

                if ($response) {
                    return $this->sendResponse($response, 'User login successfully.');
                } else {
                    $error_message['error'] = 'Unauthorised';
                    return $this->sendError($error_message['error'], $error_message);
                }
            } else {

                // $user_data = array();
                // $user_data['phone_number'] = $posted_data['phone_number'];
                // $user_data['role'] = 2;
                // $user_data['account_status'] = 1;
                // $user_data['password'] = '12345678@w';

                // if ( isset($posted_data['facebook_id']) && !isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 2; //facebook;
                // if ( !isset($posted_data['facebook_id']) && isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 3; //google;

                // $user_detail = $this->UserObj->saveUpdateUser($user_data);
                // $user_id = $user_detail->id;
                // if ($user_id) {
                //     $response = $this->authorizeUser([
                //         'phone_number' => $posted_data['phone_number'],
                //         'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                //     ]);

                //     if ($response){
                //         return $this->sendResponse($response, 'User login successfully.');
                //     }
                //     else{
                //         $error_message['error'] = 'Unauthorised';
                //         return $this->sendError($error_message['error'], $error_message);
                //     }
                // }
            }
        } else if ($posted_data['source'] == 1) {

            $response = $this->authorizeUser([
                'phone_number' => isset($posted_data['phone_number']) ? $posted_data['phone_number'] : '12345678901',
                'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
            ]);

            if ($response) {
                return $this->sendResponse($response, 'User login successfully.');
            } else {
                $error_message['error'] = 'Please enter correct phone_number and password.';
                return $this->sendError($error_message['error'], $error_message);
            }
        } else {

            // if( (!isset($posted_data['phone_number']) || empty($posted_data['phone_number'])) ){
            //     $error_message['error'] = 'The phone_number address is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // if( (!isset($posted_data['password']) || empty($posted_data['password'])) ){
            //     $error_message['error'] = 'The password is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // $error_message['error'] = 'Please post the valid credentials for login.';
            // return $this->sendError($error_message['error'], $error_message);
        }
    }

    public function login_user_backup(Request $request)
    {
        $user_data = array();
        $posted_data = $request->all();

        $rules = array(
            'email'             => 'required|email',
            'password'          => $posted_data['source'] != 1 ? 'nullable' : 'required',
            'source'            => 'required|in:1,2,3,4',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        if ($posted_data['source'] != 1) {
            $user_data['email'] = $posted_data['email'];
            $user_data['detail'] = true;
            $user_data = $this->UserObj->getUser($user_data);

            if (isset($user_data->id) && isset($user_data->register_from) && $user_data->register_from != 1) {
                $response = $this->authorizeUser([
                    'email' => $posted_data['email'],
                    'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                ]);

                if ($response) {
                    return $this->sendResponse($response, 'User login successfully.');
                } else {
                    $error_message['error'] = 'Unauthorised';
                    return $this->sendError($error_message['error'], $error_message);
                }
            } else {

                // $user_data = array();
                // $user_data['email'] = $posted_data['email'];
                // $user_data['role'] = 2;
                // $user_data['account_status'] = 1;
                // $user_data['password'] = '12345678@w';

                // if ( isset($posted_data['facebook_id']) && !isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 2; //facebook;
                // if ( !isset($posted_data['facebook_id']) && isset($posted_data['gmail_id']) )
                //     $user_data['register_from'] = 3; //google;

                // $user_detail = $this->UserObj->saveUpdateUser($user_data);
                // $user_id = $user_detail->id;
                // if ($user_id) {
                //     $response = $this->authorizeUser([
                //         'email' => $posted_data['email'],
                //         'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
                //     ]);

                //     if ($response){
                //         return $this->sendResponse($response, 'User login successfully.');
                //     }
                //     else{
                //         $error_message['error'] = 'Unauthorised';
                //         return $this->sendError($error_message['error'], $error_message);
                //     }
                // }
            }
        } else if ($posted_data['source'] == 1) {

            $response = $this->authorizeUser([
                'email' => isset($posted_data['email']) ? $posted_data['email'] : 'xyz@admin.com',
                'password' => isset($posted_data['password']) ? $posted_data['password'] : '12345678@w'
            ]);

            if ($response) {
                return $this->sendResponse($response, 'User login successfully.');
            } else {
                $error_message['error'] = 'Please enter correct email and password.';
                return $this->sendError($error_message['error'], $error_message);
            }
        } else {

            // if( (!isset($posted_data['email']) || empty($posted_data['email'])) ){
            //     $error_message['error'] = 'The email address is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // if( (!isset($posted_data['password']) || empty($posted_data['password'])) ){
            //     $error_message['error'] = 'The password is required.';
            //     return $this->sendError($error_message['error'], $error_message);  
            // }

            // $error_message['error'] = 'Please post the valid credentials for login.';
            // return $this->sendError($error_message['error'], $error_message);
        }
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeUser($posted_data)
    {
        $email = isset($posted_data['email']) ? $posted_data['email'] : '';
        $password = isset($posted_data['password']) ? $posted_data['password'] : '';

        if (\Auth::attempt(['email' => $email, 'password' => $password])) {
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

    public function verifyUserEmail($token)
    {

        $where_query = array(['remember_token', '=', isset($token) ? $token : 0]);
        $verifyUser = User::where($where_query)->first();

        $email_data = [
            'name' => isset($verifyUser->name) ? $verifyUser->name : 'Dear User',
            'text_line' => 'This verfication code is invalid. Please contact to the customer support',
        ];

        if ($verifyUser) {
            if ($verifyUser->email_verified_at == NULL) {

                $model_response = User::saveUpdateUser([
                    'update_id' => $verifyUser->id,
                    'remember_token' => NULL,
                    'email_verified_at' => date('Y-m-d h:i:s')
                ]);

                if (!empty($model_response)) {
                    $email_data = [
                        'name' => $verifyUser->name,
                        'text_line' => 'Congratulations! You email is successfully verified. Welcome to ' . config('app.name'),
                    ];
                }
            } else {
                $email_data = [
                    'name' => $verifyUser->name,
                    'text_line' => 'Your email is already verified. Welcome to ' . config('app.name'),
                ];
            }
        }
        return view('emails.general_email', compact('email_data'));
    }

    public function forgotPassword(Request $request)
    {
        $rules = array(
            'email' => 'required||email:rfc,dns|email',
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        } else {

            $users = User::where('email', '=', $request->input('email'))->first();
            if ($users === null) {

                $error_message['error'] = 'We do not recognize this email address. Please try again.';
                return $this->sendError($error_message['error'], $error_message);
            } else {
                $random_hash = substr(md5(uniqid(rand(), true)), 10, 10);
                $email = $request->get('email');
                $password = Hash::make($random_hash);

                \DB::update('update users set password = ? where email = ?', [$password, $email]);

                $data = [
                    'new_password' => $random_hash,
                    'subject' => 'Reset Password',
                    'email' => $email
                ];

                $admin['id'] = 1;
                $admin['detail'] = true;
                $admin_data = $this->UserObj->getUser($admin);

                if ($admin_data) {

                    // this email will sent to the user who have requested to forget password
                    $email_content = EmailTemplate::getEmailMessage(['id' => 7, 'detail' => true]);

                    $email_data = decodeShortCodesTemplate([
                        'subject' => $email_content->subject,
                        'body' => $email_content->body,
                        'email_message_id' => 7,
                        'user_id' => $users->id,
                        'new_password' => $random_hash,
                    ]);

                    EmailLogs::saveUpdateEmailLogs([
                        'email_msg_id' => 7,
                        'sender_id' => $admin_data->id,
                        'receiver_id' => $users->id,
                        'email' => $users->email,
                        'subject' => $email_data['email_subject'],
                        'email_message' => $email_data['email_body'],
                        'send_email_after' => 1, // 1 = Daily Email
                    ]);
                }


                /*
                Mail::send('emails.reset_password', $data, function($message) use ($data) {
                    $message->to($data['email'])
                    ->subject($data['subject']);
                });
                */

                return $this->sendResponse($data, 'Your password has been reset. Please check your email.');
            }
        }
    }

    public function changePassword(Request $request)
    {
        $params = $request->all();
        $rules = array(
            'email'             => 'required|email:rfc,dns|email',
            'old_password'      => 'required',
            // 'new_password'      => 'required|min:4',
            'new_password'      => [
                'required', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'confirm_password'  => 'required|required_with:new_password|same:new_password'
        );
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }

        $response = $this->authorizeUser([
            'email' => $params['email'],
            'password' => $params['old_password'],
            'mode' => 'only_validate',
        ]);

        if ($params['old_password'] == $params['new_password']) {
            $error_message['error'] = 'New and old password must be different.';
            return $this->sendError($error_message['error'], $error_message);
        }

        if (!$response) {
            $error_message['error'] = 'Your old password is incorrect.';
            return $this->sendError($error_message['error'], $error_message);
        } else {
            $new_password = $params['confirm_password'];
            $email = $request->get('email');
            $password = Hash::make($new_password);

            \DB::update('update users set password = ? where email = ?', [$password, $email]);

            // $data = [
            //     'new_password' => $new_password,
            //     'subject' => 'Reset Password',
            //     'email' => $email
            // ];

            $admin['id'] = 1;
            $admin['detail'] = true;
            $admin_data = $this->UserObj->getUser($admin);

            $user_data = User::where('email', '=', $request->get('email'))->first();
            if ($admin_data) {

                // this email will sent to the user who have requested to forget password
                $email_content = EmailTemplate::getEmailMessage(['id' => 8, 'detail' => true]);

                $email_data = decodeShortCodesTemplate([
                    'subject' => $email_content->subject,
                    'body' => $email_content->body,
                    'email_message_id' => 8,
                    'user_id' => $user_data->id,
                ]);

                EmailLogs::saveUpdateEmailLogs([
                    'email_msg_id' => 8,
                    'sender_id' => $admin_data->id,
                    'receiver_id' => $user_data->id,
                    'email' => $user_data->email,
                    'subject' => $email_data['email_subject'],
                    'email_message' => $email_data['email_body'],
                    'send_email_after' => 1, // 1 = Daily Email
                ]);
            }


            // Mail::send('emails.reset_password', $data, function($message) use ($data) {
            //     $message->to($data['email'])
            //     ->subject($data['subject']);
            // });

            return $this->sendResponse([], 'Your password has been updated.');
        }
    }

    public function logoutUser(Request $request)
    {
        if (!empty(\Auth::user())) {
            $user = \Auth::user()->token();
            $user->revoke();
        }
        return $this->sendResponse([], 'User is successfully logout.');
    }

    public function get_profile(Request $request)
    {
        if (!empty(\Auth::user())) {

            $posted_data = array();
            $posted_data['id'] = \Auth::user()->id;
            $posted_data['detail'] = true;
            $user = User::getUser($posted_data);
            return $this->sendResponse($user, 'User profile is successfully loaded.');
        } else {
            $error_message['error'] = 'Please login to get profile data.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }
}
