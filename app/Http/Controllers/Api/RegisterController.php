<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password;
use Illuminate\Pagination\LengthAwarePaginator;
use Validator;
use DB;
use App\Models\User;
use App\Models\GeneralTitle;
use App\Models\ProfRoleType;

use App\Models\UserEducationalInformation;
use Carbon\Carbon;

class RegisterController extends Controller
{


    function dummy_enteries($title_ary, $status)
    {
        $existingtitle = GeneralTitle::pluck('title')->toArray();

        $enter_records_array = [];
        if (is_array($title_ary)) {

            foreach ($title_ary as $value) {
                // if (!in_array($value, $existingtitle)) {

                    // Degreen discipline fields
                    $enter_records_array[] = [
                        'general_title_id' => 968,
                        'title' => $value,
                        'status' => 'Published',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // $this->GeneralObj->saveUpdateGeneralTitle([
                    //     'title' => $value,
                    //     'title_status' => $status,
                    // ]);
                // }
            }
        }
        else{
            if (!in_array($title_ary, $existingtitle)) {

                // Degreen discipline fields
                $enter_records_array[] = [
                    'general_title_id' => 968,
                    'title' => $title_ary,
                    'status' => 'Published',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // $this->GeneralObj->saveUpdateGeneralTitle([
                //     'title' => $title_ary,
                //     'title_status' => $status,
                // ]);
            }
        }

        // $this->GeneralObj->saveUpdateGeneralTitle($enter_records_array);
        ProfRoleType::insert($enter_records_array);

        return count($enter_records_array);
    }


    public function enter_records()
    {

       // Professional Roles names
        // $posted_data = [
        //     "Investor",
        //     "Tech startup / entrepreneur",
        //     "SME / entrepreneur",
        //     "Large corporate",
        //     "Independent financial advisor / intermediary",
        //     "Financial institution",
        //     "Professional service Provider",
        //     "Academic / in-school",
        //     "Government",
        //     "Financially independent",
        // ];
        // $returnAry['professional_role'] = $this->dummy_enteries($posted_data, 2);

        // Professional Role Type
        $posted_data = [
            "Accounting",
            "Advertising",
            "Coaching",
            "Consulting",
            "Corporate / secretarial services",
            "Data / information services",
            "Headhunting",
            "IT / web & app development",
            "Legal / compliance",
            "Sales & marketing",
            "Public relations",
            "Valuation / risk management",
            "Others",
        ];
        $returnAry['professional_role'] = $this->dummy_enteries($posted_data, 2);

        // Degree discipline
        // $posted_data = [
        //     "Computer Science and Engineering",
        //     "Business Administration and Management",
        //     "Medicine and Healthcare",
        //     "Electrical and Electronics Engineering",
        //     "Mechanical Engineering",
        //     "Data Science and Analytics",
        //     "Finance and Accounting",
        //     "Environmental Science and Sustainability",
        //     "Psychology and Behavioral Sciences",
        //     "Civil Engineering",
        //     "Biotechnology and Biomedical Sciences",
        //     "Computer Engineering",
        //     "Chemical Engineering",
        //     "Environmental Engineering",
        //     "Architecture and Urban Planning",
        //     "Economics and Econometrics",
        //     "Marketing and Advertising",
        //     "International Relations and Political Science",
        //     "English Language and Literature",
        //     "Education and Teaching",
        //     "Media and Communication Studies",
        //     "Fine Arts and Design",
        //     "Linguistics and Language Studies",
        //     "Mathematics and Statistics",
        //     "Sociology and Anthropology",
        //     "Aerospace Engineering",
        //     "Industrial Engineering",
        //     "Nursing and Healthcare Management",
        //     "Pharmaceutical Sciences",
        //     "Public Health",
        //     "Law and Legal Studies",
        //     "History and Archaeology",
        //     "Human Resource Management",
        //     "Social Work and Welfare",
        //     "Chemistry and Chemical Sciences",
        //     "Renewable Energy and Sustainable Technologies",
        //     "Biomedical Engineering",
        //     "Pharmacy and Pharmacology",
        //     "Artificial Intelligence and Machine Learning",
        //     "Anthropology and Archaeology",
        //     "Political Science and International Relations",
        //     "Journalism and Mass Communication",
        //     "Nutrition and Dietetics",
        //     "Linguistics and Language Studies",
        //     "Geography and Geology",
        //     "Philosophy and Ethics",
        //     "Theatre and Performing Arts",
        //     "Materials Science and Engineering",
        //     "Hospitality and Tourism Management",
        //     "Public Administration and Policy",
        //     "Agricultural Sciences and Agribusiness",
        //     "Environmental Studies and Conservation",
        //     "Music and Musicology",
        //     "Psychology and Counseling",
        //     "Supply Chain Management and Logistics",
        //     "Public Relations and Corporate Communications",
        //     "Human Rights and Social Justice",
        //     "Gender Studies and Women's Studies",
        //     "Neuroscience",
        //     "Marine Biology and Oceanography",
        //     "Health Sciences and Administration",
        //     "Robotics and Automation Engineering",
        //     "Forensic Science and Criminal Justice",
        //     "Film and Television Production",
        //     "Sports Science and Exercise Physiology",
        //     "Cybersecurity and Information Assurance",
        //     "Urban and Regional Planning",
        //     "Graphic Design and Visual Communication",
        //     "Cultural Studies and Anthropology",
        //     "Development Studies and International Development",
        //     "Linguistics and Applied Linguistics",
        //     "Public Policy and Governance",
        //     "Veterinary Medicine and Animal Sciences",
        //     "Biomedical Informatics",
        //     "Industrial Design and Product Development",
        //     "Religious Studies and Theology",
        //     "Physics and Astronomy",
        //     "Health Informatics",
        //     "Gerontology and Aging Studies",
        //     "Disaster Management and Humanitarian Assistance",
        //     "Game Design and Development",
        //     "Comparative Literature and Literary Studies",
        //     "Geographical Information Systems (GIS)",
        //     "Early Childhood Education",
        //     "Cognitive Science",
        //     "Tourism and Event Management",
        //     "Art History and Criticism",
        //     "Automotive Engineering",
        //     "Industrial-Organizational Psychology",
        //     "Aerospace and Aeronautical Engineering",
        //     "Communication Disorders and Speech-Language Pathology",
        //     "Renewable Energy Engineering",
        //     "Fashion Design and Merchandising",
        //     "Cultural Anthropology",
        //     "Human Computer Interaction (HCI)",
        //     "Earth Sciences and Geophysics",
        //     "Geriatric Medicine",
        //     "Indigenous Studies",
        //     "Food Science and Technology",
        //     "Real Estate and Property Management",
        // ];
        // $returnAry['degree_name'] = $this->dummy_enteries($posted_data, 8);
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
        $posted_data['orderBy_name'] = 'general_titles.id';
        $posted_data['orderBy_value'] = 'ASC';

        if (isset($request->title_status) && $request->title_status == 5) {
            $posted_data['orderBy_name'] = 'general_titles.title';
            $posted_data['orderBy_value'] = 'ASC';
        }
        if (isset($request->title_status) && $request->title_status == 4) {
            // $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
            //     'user_id' => \Auth::user()->id,
            //     'intrested_vertical' => 'Industry',
            //     'groupBy' => 'user_industy_vertical_items.general_title_id',

            // ]);
            // $general_title_id = $user_industry_vertical_items->ToArray();
            // $general_title_id = array_column($general_title_id, 'general_title_id');

            $IndustryVerticalItemFltr = array();
            // $IndustryVerticalItemFltr['general_title_id_in'] = $general_title_id;
            if (isset($request->industry_id)) {
                $comma_separated_industry_id = explode(',',$request->industry_id);
                $IndustryVerticalItemFltr['general_title_id_in'] = $comma_separated_industry_id;
            }

            $IndustryVerticalItemFltr['category_status_in'] = ['Specialty','Both'];
            $IndustryVerticalItemFltr['status'] = 'Published';
            $IndustryVerticalItemFltr['groupBy'] = 'industry_vertical_items.title';
            $IndustryVerticalItemFltr['orderBy_name'] = 'industry_vertical_items.title';
            $IndustryVerticalItemFltr['orderBy_value'] = 'ASC';
            if (isset($request->title)) {
                $IndustryVerticalItemFltr['title'] = $request->title;
            }

            $data = $this->IndustryVerticalItemObj->getIndustryVerticalItem($IndustryVerticalItemFltr);
        }
        else{
            $data = $this->GeneralObj->getGeneralTitle($posted_data);
        }
        return $this->sendResponse($data, 'List of general titles');
    }
    // Add of general titles
    public function create_general_title(Request $request){
        $return_data = array();
        $is_inserted = false;
        $requestd_data = array();
        $requestd_data = $request->all();
        $rules = array(
            // 'title' => 'required|unique:general_titles,title|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
            'title' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
            // 'title' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
            'title_status' => 'required|integer|between:1,6',
        );

        if (isset($requestd_data['title_status']) && $requestd_data['title_status'] == 4) {
            $rules = array(
                'industry_id' => 'required|exists:general_titles,id',
            );
            $validator = \Validator::make($request->all(), $rules, [
                'industry_id.*.exists' => 'The selected industry id is invalid.',
            ]);
        }
        $validator = \Validator::make($request->all(), $rules, [
            // 'title.regex' => 'Must start with alphabets only letters, numbers and can only one of each special character ( - / ) are allowed',
            'title.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )',
        ]);

        if ($validator->fails()) {
            return $this->sendError(["error" => $validator->errors()->first()]);
        }
        if (isset($requestd_data['title_status']) && $requestd_data['title_status'] == 4) {
            // $user_industry_vertical_items = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
            //     'user_id' => \Auth::user()->id,
            //     'intrested_vertical' => 'Industry',
            //     'groupBy' => 'user_industy_vertical_items.general_title_id',
            // ]);
            // $general_title_id = $user_industry_vertical_items->ToArray();
            // $general_title_id = array_column($general_title_id, 'general_title_id');
            $general_title_id = $requestd_data['industry_id'];

            if(isset($general_title_id) && count($general_title_id)>0){
                foreach ($general_title_id as $key => $id) {
                    $insert_data = array();
                    $check_data = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                        // 'category_status' => 'Specialty',
                        'title' => $requestd_data['title'],
                        'general_title_id' => $id,
                        'detail' => true
                    ]);
                    if($check_data){
                        $insert_data['update_id'] = $check_data->id;
                    }

                    if($check_data && $check_data->category_status == 'Industry'){
                        $insert_data['category_status'] = 'Both';
                    }else{
                        $insert_data['category_status'] = 'Specialty';
                    }
                    $insert_data['title'] = $requestd_data['title'];
                    $insert_data['general_title_id'] = $id;
                    $general_title = $this->IndustryVerticalItemObj->saveUpdateIndustryVerticalItem($insert_data);
                    if(is_array($return_data)){
                        $return_data = $general_title;
                        $is_inserted = true;
                    }
                }
            }
        }else{
            $general_title = $this->GeneralObj->saveUpdateGeneralTitle([
                'title' => $requestd_data['title'],
                'title_status' => $requestd_data['title_status']
            ]);
            $return_data = $general_title;
            $is_inserted = true;
        }

        if($is_inserted){
            return $this->sendResponse($return_data, 'General title added successfully');
        }else{
            return $this->sendError('Please check your industry verticals there is no industry verticals found');
        }
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
        $posted_data['orderBy_name'] = 'users.id';
        $posted_data['orderBy_value'] = 'DESC';
        $posted_data['groupBy'] = 'users.id';

        if (isset($request_data['name'])) {
            $posted_data['name'] = $request_data['name'];
        }
        if (isset($request_data['phone_numbers'])) {
            $posted_data['phone_numbers_in'] = $request_data['phone_numbers'];
        }
        // $posted_data['except_auth_id'] = \Auth::user()->id;
        $return_ary['matched_connect_peoples'] = $this->UserObj->getUser($posted_data);

        $matched_phone_number = $return_ary['matched_connect_peoples']->ToArray();
        $matched_phone_number = array_column($matched_phone_number, 'phone_number');
        $matched_connect_people_count = count($matched_phone_number);

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
        return $this->sendResponse($return_ary, 'List of users',$matched_connect_people_count);
    }

    // Check user
    public function check_connect_people(Request $request){
        $return_data = $this->UserObj->getUser([
            'phone_number' => $request->phone_number,
            'detail' => true
        ]);
        if (isset($return_data)) {
            return $this->sendResponse($return_data, 'User record');
        }
        return $this->sendResponse("Success", 'This user not on jugu app');
    }


    public function connect_list(Request $request)
    {

        $request_data = array();
        $conversation_data = array();
        $request_data['latest_data'] = true;
        $request_data['user_id'] =  \Auth::user()->id;

        $message = $this->MessageObj->getMessage($request_data);
        unset($request_data['per_page']);
        unset($request_data['page']);
        $request_data['type'] =  'Archived';
        $request_data['count'] =  true;
        $total_archived_messages = $this->MessageObj->getMessage($request_data);


        $request_data['count'] =  true;
        unset($request_data['type']);
        $total_unarchived_messages = $this->MessageObj->getMessage($request_data);

        if(isset($message) && count($message)){
            foreach ($message as $key => $value) {
                $value->message =  isset($value->message) ?Crypt::decrypt($value->message):'';

                $receiverData =  $this->MessageObj->find($value->id)->receiverData;
                $senderData =  $this->MessageObj->find($value->id)->senderData;
                $groupData =  $this->MessageObj->find($value->id)->groupData;
                $messageAssets =  $this->MessageObj->find($value->id)->messageAsset;
                $messageReplyDetail =  $this->MessageObj->find($value->id)->messageReplyId;


                if(\Auth::user()->id == $senderData->id){
                    $otherUserData =  $receiverData;
                }else{
                    $otherUserData =  $senderData;
                }

                $value->receiver_data = $receiverData;
                $value->sender_data = $senderData;

                $tmp_data = array();
                if (isset($otherUserData)) {
                    $tmp_data = $otherUserData;
                    $user_id[] = $otherUserData['id'];
                }
                if (isset($groupData)) {
                    $tmp_data   = $groupData;
                    $tmp_data['is_group'] = true;
                }
                $conversation_data[]   = $tmp_data;

                // $value->groupData = $groupData;
                $value->messageAssets = $messageAssets;
                $value->messageReplyDetail = $messageReplyDetail;
            }
        }
        $return_people =array();
        $return_people = $this->UserObj->getUser([
            'except_auth_id' => \Auth::user()->id,
            'users_not_in' => $user_id
        ])->ToArray();
        $return_ary = array_merge($conversation_data, $return_people);
        // return $this->sendResponse($return_ary, 'User connect list');


        return $this->sendResponse($return_ary, 'User connect list');

        // $retun_ary = array();
        // $retun_ary['total_archived_messages'] = isset($total_archived_messages[0]->total_count)? $total_archived_messages[0]->total_count:0;
        // $retun_ary['total_unarchived_messages'] = isset($total_unarchived_messages[0]->total_count)? $total_unarchived_messages[0]->total_count:0;
        // $retun_ary['message'] = $message;

        // $retun_ary['message'] = array_unique(array_merge ($retun_ary, $return_ary));



        // $posted_data = array();
        // $request_data = $request->all();
        // $posted_data['except_auth_id'] = \Auth::user()->id;
        // if (isset($request_data['name'])) {
        //     $posted_data['name'] = $request_data['name'];
        // }
        // if (isset($request_data['phone_numbers'])) {
        //     $posted_data['phone_numbers_in'] = $request_data['phone_numbers'];
        // }
        // $posted_data['except_auth_id'] = \Auth::user()->id;
        // $return_ary['matched_connect_peoples'] = $this->UserObj->getUser($posted_data);

        // $matched_phone_number = $return_ary['matched_connect_peoples']->ToArray();
        // $matched_phone_number = array_column($matched_phone_number, 'phone_number');

        // $return_ary['not_matched_connect_peoples'] = array();
        // if(isset($request_data['phone_numbers'])){
        // $a1 = $matched_phone_number;
        // $a2 = $request_data['phone_numbers'];
        // $return_ary['not_matched_connect_peoples'] = array_merge(array_diff($a1, $a2),array_diff($a2,$a1));
        // }
        // return $this->sendResponse($retun_ary, 'User connect list');

    }
    public function arrayPaginator($array, $request)
    {
        // echo '<pre>'; print_r($request); echo '</pre>';
        $page = 1;
        $perPage = isset($request->per_page)? $request->per_page:5;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    public function recommended_users(){
        $recommended_ids = array();
        // $user_career_status_average_calculation = 70;
        // $user_goal_average_calculation = 50;
        // $user_education_information_calculation = 50;
        // $user_speciality_calculation = 50;
        // $user_industry_vertical_item_calculation = 50;
        // $user_prof_role_type_item_calculation = 20;

        $user_career_status_average_calculation = 0;
        $user_goal_average_calculation = 0;
        $user_education_information_calculation = 0;
        $user_speciality_calculation = 0;
        $user_industry_vertical_item_calculation = 0;
        $user_prof_role_type_item_calculation = 0;


        $get_user_data = $this->UserObj->getUser([
            'id' =>\Auth::user()->id,
            'detail' =>true,
        ]);

        $get_user_info = $this->UserCareerStatusObj->getUserCareerStatusPosition([
            'user_id' => \Auth::user()->id,
            'detail' => true
        ]);

        // $request_data['id'] = '';
        // Company Career Information
        if (isset($get_user_data['companyCareerInfo'])) {
            // echo '<pre>'; print_r($get_user_data['companyCareerInfo']->general_title_id); echo '</pre>'; exit;
            // foreach ($get_user_data['companyCareerInfo'] as $career_user_key => $career_user_value) {
            //     $getcareer_general_title[] = $career_user_value->general_title_id;
            // }
            // echo '<pre>'; print_r($get_user_data['companyCareerInfo']->ToArray()); echo '</pre>'; exit;
            $get_career_status_users = $this->UserCareerStatusObj->getUserCareerStatusPosition([
                'user_id_not' => \Auth::user()->id,
                'general_title_id' => $get_user_data['companyCareerInfo']->general_title_id,
            ])->ToArray();

            if (isset($get_career_status_users) && count($get_career_status_users) > 0) {
                foreach ($get_career_status_users as $get_career_status_key => $get_career_status_value) {


                    if($get_career_status_value['general_title_id'] == $get_user_data['companyCareerInfo']->general_title_id){
                        $get_ids[] = $get_career_status_value['user_id'];
                    }
                }
                if(isset($get_ids)){
                    $recommended_ids = $get_ids;
                }else{
                    $recommended_ids = array();
                }
            }

        }
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';
        // UserGoal
        if (isset($get_user_data['userGoal']) && count($get_user_data['userGoal']) >0 &&  isset($get_ids) && count($get_ids) > 0) {

            foreach ($get_user_data['userGoal'] as $user_goal_key => $user_goal_value) {
                $user_goal_items[] = $user_goal_value->goal_item_id;
            }
            // echo '<pre>'; print_r(implode(',',$get_ids)); echo '</pre>';
            $get_user_goals = $this->UserGoalItemObj->getUserGoalItem([
                'user_ids' => $get_ids,
                'goal_item_ids' => $user_goal_items,
                'groupBy' => 'user_goal_items.user_id'
            ])->ToArray();

            if (isset($get_user_goals) && count($get_user_goals) > 0) {
                foreach ($get_user_goals as $get_user_goal_key => $get_user_goals_value) {
                    $get_total_user_goals = $this->UserGoalItemObj->getUserGoalItem([
                        'user_id' => $get_user_goals_value['user_id'],
                        'count' =>true
                    ]);

                    $get_user_goal_ids = $this->UserGoalItemObj->getUserGoalItem([
                        'user_id' => $get_user_goals_value['user_id'],
                        'goal_item_ids' => $user_goal_items,
                    ])->ToArray();
                    $match_count = count($get_user_goal_ids);


                    $calculate_percentage = ($match_count / $get_total_user_goals)*100;
                    // echo '<pre>user_id: '; print_r($get_user_goals_value['user_id']); echo '</pre>';
                    // echo '<pre>match_count: '; print_r($match_count); echo '</pre>';
                    // echo '<pre>get_total_user_goals: '; print_r($get_total_user_goals); echo '</pre>';
                    // echo '<pre>'; print_r($calculate_percentage); echo '</pre>'; exit;
                    if ($calculate_percentage >= $user_goal_average_calculation) {
                        foreach ($get_user_goal_ids as $key => $value) {
                            $goal_ids[] = $value['user_id'];
                        }
                        $goal_ids =  array_unique($goal_ids);
                    }
                }

                if(isset($goal_ids)){
                    $recommended_ids = $goal_ids;
                }else{
                    $recommended_ids = array();
                }
            }
        }

        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';
        // User educational information
        if (isset($get_user_data['userEducationalInformation']) && count($get_user_data['userEducationalInformation']) >0 &&  isset($goal_ids) && count($goal_ids) > 0) {
            foreach ($get_user_data['userEducationalInformation'] as $user_education_key => $user_education_value) {
                $educaton_general_title[] = $user_education_value->general_title_id;
            }
            $get_user_educations = $this->UserEducationalInfoObj->getUserEducationalInformation([
                'user_ids' => $goal_ids,
                'general_title_ids' => $educaton_general_title,
            ])->ToArray();
            if (isset($get_user_educations) && count($get_user_educations) > 0) {
                foreach ($get_user_educations as $get_user_educations_key => $get_user_educations_value) {
                //   echo '<pre>'; print_r($get_user_educationss_value['user_id']); echo '</pre>';
                    $get_total_user_educations = $this->UserEducationalInfoObj->getUserEducationalInformation([
                        'user_id' => $get_user_educations_value['user_id'],
                        'count' =>true
                    ]);

                    $get_user_education_ids = $this->UserEducationalInfoObj->getUserEducationalInformation([
                        'user_id' => $get_user_educations_value['user_id'],
                        'general_title_ids' => $educaton_general_title,
                    ])->ToArray();

                    $match_count = count($get_user_education_ids);

                    $calculate_percentage = ($match_count / $get_total_user_educations)*100;
                    if ($calculate_percentage >= $user_education_information_calculation) {
                        foreach ($get_user_education_ids as $key => $value) {
                            $education_ids[] = $value['user_id'];
                        }
                        $education_ids =  array_unique($education_ids);
                    }
                }
                if(isset($education_ids)){
                    $recommended_ids = $education_ids;
                }else{
                    $recommended_ids = array();
                }
            }
        }
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // User specility
        if (isset($get_user_data['userSpeciality']) && count($get_user_data['userSpeciality']) >0 &&  isset($education_ids) && count($education_ids) > 0) {

            foreach ($get_user_data['userSpeciality'] as $user_speciality_key => $user_speciality_value) {
                $industry_vertical_id[] = $user_speciality_value->industry_vertical_item_id;
            }


            $get_user_speciality = $this->UserSpecialtyObj->getUserSpecialty([
                'user_ids' => $education_ids,
                'industry_vertical_item_ids' => $industry_vertical_id,
            ])->ToArray();

            if (isset($get_user_speciality) && count($get_user_speciality) > 0) {

                foreach ($get_user_speciality as $get_user_speciality_key => $get_user_speciality_value) {
                    $get_total_user_educations = $this->UserSpecialtyObj->getUserSpecialty([
                        'user_id' => $get_user_speciality_value['user_id'],
                        'count' =>true
                    ]);

                    $get_user_speciality_ids = $this->UserSpecialtyObj->getUserSpecialty([
                        'user_id' => $get_user_speciality_value['user_id'],
                        'industry_vertical_item_ids' => $industry_vertical_id,
                    ])->ToArray();

                    $match_count = count($get_user_speciality_ids);

                    $calculate_percentage = ($match_count / $get_total_user_educations)*100;
                    if ($calculate_percentage >= $user_speciality_calculation) {
                        foreach ($get_user_speciality_ids as $key => $value) {
                            $specialty_id[] = $value['user_id'];
                        }
                        $specialty_id =  array_unique($specialty_id);
                    }
                }
                if(isset($specialty_id)){
                    $recommended_ids = $specialty_id;
                }else{
                    $recommended_ids = array();
                }
            }
        }
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // User Industry
        if (isset($get_user_data['userIndustry']) && count($get_user_data['userIndustry']) >0 &&  isset($specialty_id) && count($specialty_id) > 0) {
            foreach ($get_user_data['userIndustry'] as $user_industry_key => $user_industry_value) {
                $user_industry_id[] = $user_industry_value->general_title_id;
            }

            $get_user_industry_vertical = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                'user_ids' => $specialty_id,
                'general_title_ids' => $user_industry_id,
            ])->ToArray();


            if (isset($get_user_industry_vertical) && count($get_user_industry_vertical) > 0) {

                foreach ($get_user_industry_vertical as $get_user_industry_vertical_key => $get_user_industry_vertical_value) {
                    $get_total_user_industry_vertical = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                        'user_id' => $get_user_industry_vertical_value['user_id'],
                        'count' =>true
                    ]);

                    $get_user_indusry_ids = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                        'user_id' => $get_user_industry_vertical_value['user_id'],
                        'general_title_ids' => $user_industry_id,
                    ])->ToArray();

                    $match_count = count($get_user_indusry_ids);

                    $calculate_percentage = ($match_count / $get_total_user_industry_vertical)*100;
                    if ($calculate_percentage >= $user_industry_vertical_item_calculation) {
                        foreach ($get_user_indusry_ids as $key => $value) {
                            $industry_vertical_items_id[] = $value['user_id'];
                        }
                        $industry_vertical_items_id =  array_unique($industry_vertical_items_id);
                    }
                }
                if(isset($industry_vertical_items_id)){
                    $recommended_ids = $industry_vertical_items_id;
                }else{
                    $recommended_ids = array();
                }
            }
        }
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // User Professional Role Type
        if (isset($get_user_data['userProfRoleTypeItem']) &&  isset($industry_vertical_items_id) && count($industry_vertical_items_id) > 0) {
            // if (isset($get_user_data['userProfRoleTypeItem']) && count($get_user_data['userProfRoleTypeItem']) >0 &&  isset($industry_vertical_items_id) && count($industry_vertical_items_id) > 0) {
            foreach ($get_user_data['userProfRoleTypeItem'] as $user_professional_role_key => $user_professional_role_value) {
                $user_profes_role_type[] = $user_professional_role_value->prof_role_type_item_id;
            }
            $get_user_prof_role_type_item = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                'user_ids' => $industry_vertical_items_id,
                'prof_role_type_item_ids' => $user_profes_role_type,
            ])->ToArray();

            if (isset($get_user_prof_role_type_item) && count($get_user_prof_role_type_item) > 0) {

                foreach ($get_user_prof_role_type_item as $get_user_prof_role_type_item_key => $get_user_prof_role_type_item_value) {

                    $get_total_user_industry_vertical = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                        'user_id' => $get_user_prof_role_type_item_value['user_id'],
                        'count' =>true
                    ]);

                    $get_user_prof_role_type_ids = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                        'user_id' => $get_user_prof_role_type_item_value['user_id'],
                        'prof_role_type_item_ids' => $user_profes_role_type,
                    ])->ToArray();


                    $match_count = count($get_user_prof_role_type_ids);

                    $calculate_percentage = ($match_count / $get_total_user_industry_vertical)*100;
                    if ($calculate_percentage >= $user_prof_role_type_item_calculation) {
                        foreach ($get_user_prof_role_type_ids as $key => $value) {
                            $user_prof_role_items[] = $value['user_id'];
                        }
                        $status_recommended =  array_unique($user_prof_role_items);
                    }
                }
                if(isset($user_prof_role_items)){
                    $recommended_ids = $user_prof_role_items;
                }else{
                    $recommended_ids = array();
                }
            }

        }
        return $recommended_ids;
    }

    public function bookmark_user(){
        $book_mark_user_ids = array();
        $request_data['user_id_from'] = $this->ConnectionBookMarkObj->getConnectionBookMark([
            'user_id_from' => \Auth::user()->id
        ])->ToArray();

        $book_mark_user_ids = array_column($request_data['user_id_from'], 'user_id_to');

        return $book_mark_user_ids;
    }
    // list of connect people And Filters
    public function connect_people_list(Request $request){
        // $get_user_data = $this->UserObj->getUser([
        //     'id' =>\Auth::user()->id,
        //     'detail' =>true
        // ]);
        // echo '<pre>'; print_r($get_user_data); echo '</pre>'; exit;

        // if (isset($request_data['status']) && $request_data['status'] == 'Recommended') {
        // $get_user_data = $this->UserObj->getUser([
        //     'id' =>\Auth::user()->id,
        //     'detail' =>true,
        // ]);

        // $get_user_info = $this->UserCareerStatusObj->getUserCareerStatusPosition([
        //     'user_id' => \Auth::user()->id,
        //     'detail' => true
        // ]);

        // // $request_data['id'] = '';
        // // Company Career Information
        // if (isset($get_user_data['companyCareerInfo'])) {
        //     // echo '<pre>'; print_r($get_user_data['companyCareerInfo']->general_title_id); echo '</pre>'; exit;
        //     // foreach ($get_user_data['companyCareerInfo'] as $career_user_key => $career_user_value) {
        //     //     $getcareer_general_title[] = $career_user_value->general_title_id;
        //     // }
        //     // echo '<pre>'; print_r($get_user_data['companyCareerInfo']->ToArray()); echo '</pre>'; exit;
        //     $get_career_status_users = $this->UserCareerStatusObj->getUserCareerStatusPosition([
        //         'user_id_not' => \Auth::user()->id,
        //         'general_title_id' => $get_user_data['companyCareerInfo']->general_title_id,
        //     ])->ToArray();

        //     if (isset($get_career_status_users) && count($get_career_status_users) > 0) {
        //         foreach ($get_career_status_users as $get_career_status_key => $get_career_status_value) {


        //             if($get_career_status_value['general_title_id'] == $get_user_data['companyCareerInfo']->general_title_id){
        //                 $get_ids[] = $get_career_status_value['user_id'];
        //             }

        //             // $get_total_count = $this->UserCareerStatusObj->getUserCareerStatusPosition([
        //             //     'user_id' => $get_career_status_value['user_id'],
        //             //     'count' =>true
        //             // ]);

        //             // $get_user_id = $this->UserCareerStatusObj->getUserCareerStatusPosition([
        //             //     'user_id' => $get_career_status_value['user_id'],
        //             //     'general_title_id' => $get_user_data['companyCareerInfo']->general_title_id,
        //             // ])->ToArray();

        //             // $match_count = count($get_user_id);


        //             // $calculate_percentage = ($match_count / $get_total_count)*100;
        //             // if ($calculate_percentage >= $user_career_status_average_calculation) {
        //             //     $get_ids[] = array_column($get_user_id, 'user_id');
        //             // }
        //         }
        //         if(isset($get_ids)){
        //             $recommended_ids = $get_ids;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }

        // }
        // // echo '<pre>'; print_r($recommended_ids); echo '</pre>';
        // // UserGoal
        // if (isset($get_user_data['userGoal']) && count($get_user_data['userGoal']) >0 &&  isset($get_ids) && count($get_ids) > 0) {

        //     foreach ($get_user_data['userGoal'] as $user_goal_key => $user_goal_value) {
        //         $user_goal_items[] = $user_goal_value->goal_item_id;
        //     }
        //     // echo '<pre>'; print_r(implode(',',$get_ids)); echo '</pre>';
        //     $get_user_goals = $this->UserGoalItemObj->getUserGoalItem([
        //         'user_ids' => $get_ids,
        //         'goal_item_ids' => $user_goal_items,
        //         'groupBy' => 'user_goal_items.user_id'
        //     ])->ToArray();

        //     if (isset($get_user_goals) && count($get_user_goals) > 0) {
        //         foreach ($get_user_goals as $get_user_goal_key => $get_user_goals_value) {
        //             $get_total_user_goals = $this->UserGoalItemObj->getUserGoalItem([
        //                 'user_id' => $get_user_goals_value['user_id'],
        //                 'count' =>true
        //             ]);


        //             $get_user_goal_ids = $this->UserGoalItemObj->getUserGoalItem([
        //                 'user_id' => $get_user_goals_value['user_id'],
        //                 'goal_item_ids' => $user_goal_items,
        //             ])->ToArray();
        //             $match_count = count($get_user_goal_ids);


        //             $calculate_percentage = ($match_count / $get_total_user_goals)*100;
        //             // echo '<pre>user_id: '; print_r($get_user_goals_value['user_id']); echo '</pre>';
        //             // echo '<pre>match_count: '; print_r($match_count); echo '</pre>';
        //             // echo '<pre>get_total_user_goals: '; print_r($get_total_user_goals); echo '</pre>';
        //             // echo '<pre>'; print_r($calculate_percentage); echo '</pre>'; exit;
        //             if ($calculate_percentage >= $user_goal_average_calculation) {
        //                 foreach ($get_user_goal_ids as $key => $value) {
        //                     $goal_ids[] = $value['user_id'];
        //                 }
        //                 $goal_ids =  array_unique($goal_ids);
        //             }
        //         }

        //         if(isset($goal_ids)){
        //             $recommended_ids = $goal_ids;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }
        // }
        // // echo '<pre>'; print_r($recommended_ids); echo '</pre>';
        // // User educational information
        // if (isset($get_user_data['userEducationalInformation']) && count($get_user_data['userEducationalInformation']) >0 &&  isset($goal_ids) && count($goal_ids) > 0) {
        //     foreach ($get_user_data['userEducationalInformation'] as $user_education_key => $user_education_value) {
        //         $educaton_general_title[] = $user_education_value->general_title_id;
        //     }
        //     $get_user_educations = $this->UserEducationalInfoObj->getUserEducationalInformation([
        //         'user_ids' => $goal_ids,
        //         'general_title_ids' => $educaton_general_title,
        //     ])->ToArray();
        //     if (isset($get_user_educations) && count($get_user_educations) > 0) {
        //         foreach ($get_user_educations as $get_user_educations_key => $get_user_educations_value) {
        //         //   echo '<pre>'; print_r($get_user_educationss_value['user_id']); echo '</pre>';
        //             $get_total_user_educations = $this->UserEducationalInfoObj->getUserEducationalInformation([
        //                 'user_id' => $get_user_educations_value['user_id'],
        //                 'count' =>true
        //             ]);

        //             $get_user_education_ids = $this->UserEducationalInfoObj->getUserEducationalInformation([
        //                 'user_id' => $get_user_educations_value['user_id'],
        //                 'general_title_ids' => $educaton_general_title,
        //             ])->ToArray();

        //             $match_count = count($get_user_education_ids);

        //             $calculate_percentage = ($match_count / $get_total_user_educations)*100;
        //             if ($calculate_percentage >= $user_education_information_calculation) {
        //                 foreach ($get_user_education_ids as $key => $value) {
        //                     $education_ids[] = $value['user_id'];
        //                 }
        //                 $education_ids =  array_unique($education_ids);
        //             }
        //         }
        //         if(isset($education_ids)){
        //             $recommended_ids = $education_ids;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }
        // }
        // // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // // User specility
        // if (isset($get_user_data['userSpeciality']) && count($get_user_data['userSpeciality']) >0 &&  isset($education_ids) && count($education_ids) > 0) {

        //     foreach ($get_user_data['userSpeciality'] as $user_speciality_key => $user_speciality_value) {
        //         $industry_vertical_id[] = $user_speciality_value->industry_vertical_item_id;
        //     }


        //     $get_user_speciality = $this->UserSpecialtyObj->getUserSpecialty([
        //         'user_ids' => $education_ids,
        //         'industry_vertical_item_ids' => $industry_vertical_id,
        //     ])->ToArray();

        //     if (isset($get_user_speciality) && count($get_user_speciality) > 0) {

        //         foreach ($get_user_speciality as $get_user_speciality_key => $get_user_speciality_value) {
        //             $get_total_user_educations = $this->UserSpecialtyObj->getUserSpecialty([
        //                 'user_id' => $get_user_speciality_value['user_id'],
        //                 'count' =>true
        //             ]);

        //             $get_user_speciality_ids = $this->UserSpecialtyObj->getUserSpecialty([
        //                 'user_id' => $get_user_speciality_value['user_id'],
        //                 'industry_vertical_item_ids' => $industry_vertical_id,
        //             ])->ToArray();

        //             $match_count = count($get_user_speciality_ids);

        //             $calculate_percentage = ($match_count / $get_total_user_educations)*100;
        //             if ($calculate_percentage >= $user_speciality_calculation) {
        //                 foreach ($get_user_speciality_ids as $key => $value) {
        //                     $specialty_id[] = $value['user_id'];
        //                 }
        //                 $specialty_id =  array_unique($specialty_id);
        //             }
        //         }
        //         if(isset($specialty_id)){
        //             $recommended_ids = $specialty_id;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }
        // }
        // // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // // User Industry
        // if (isset($get_user_data['userIndustry']) && count($get_user_data['userIndustry']) >0 &&  isset($specialty_id) && count($specialty_id) > 0) {
        //     foreach ($get_user_data['userIndustry'] as $user_industry_key => $user_industry_value) {
        //         $user_industry_id[] = $user_industry_value->general_title_id;
        //     }

        //     $get_user_industry_vertical = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
        //         'user_ids' => $specialty_id,
        //         'general_title_ids' => $user_industry_id,
        //     ])->ToArray();


        //     if (isset($get_user_industry_vertical) && count($get_user_industry_vertical) > 0) {

        //         foreach ($get_user_industry_vertical as $get_user_industry_vertical_key => $get_user_industry_vertical_value) {
        //             $get_total_user_industry_vertical = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
        //                 'user_id' => $get_user_industry_vertical_value['user_id'],
        //                 'count' =>true
        //             ]);

        //             $get_user_indusry_ids = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
        //                 'user_id' => $get_user_industry_vertical_value['user_id'],
        //                 'general_title_ids' => $user_industry_id,
        //             ])->ToArray();

        //             $match_count = count($get_user_indusry_ids);

        //             $calculate_percentage = ($match_count / $get_total_user_industry_vertical)*100;
        //             if ($calculate_percentage >= $user_industry_vertical_item_calculation) {
        //                 foreach ($get_user_indusry_ids as $key => $value) {
        //                     $industry_vertical_items_id[] = $value['user_id'];
        //                 }
        //                 $industry_vertical_items_id =  array_unique($industry_vertical_items_id);
        //             }
        //         }
        //         if(isset($industry_vertical_items_id)){
        //             $recommended_ids = $industry_vertical_items_id;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }
        // }
        // // echo '<pre>'; print_r($recommended_ids); echo '</pre>';

        // // User Professional Role Type
        // if (isset($get_user_data['userProfRoleTypeItem']) && count($get_user_data['userProfRoleTypeItem']) >0 &&  isset($industry_vertical_items_id) && count($industry_vertical_items_id) > 0) {
        //     foreach ($get_user_data['userProfRoleTypeItem'] as $user_professional_role_key => $user_professional_role_value) {
        //         $user_profes_role_type[] = $user_professional_role_value->prof_role_type_item_id;
        //     }
        //     $get_user_prof_role_type_item = $this->UserProRolteItemObj->getUserProRoleTypeItem([
        //         'user_ids' => $industry_vertical_items_id,
        //         'prof_role_type_item_ids' => $user_profes_role_type,
        //     ])->ToArray();

        //     if (isset($get_user_prof_role_type_item) && count($get_user_prof_role_type_item) > 0) {

        //         foreach ($get_user_prof_role_type_item as $get_user_prof_role_type_item_key => $get_user_prof_role_type_item_value) {

        //             $get_total_user_industry_vertical = $this->UserProRolteItemObj->getUserProRoleTypeItem([
        //                 'user_id' => $get_user_prof_role_type_item_value['user_id'],
        //                 'count' =>true
        //             ]);

        //             $get_user_prof_role_type_ids = $this->UserProRolteItemObj->getUserProRoleTypeItem([
        //                 'user_id' => $get_user_prof_role_type_item_value['user_id'],
        //                 'prof_role_type_item_ids' => $user_profes_role_type,
        //             ])->ToArray();


        //             $match_count = count($get_user_prof_role_type_ids);

        //             $calculate_percentage = ($match_count / $get_total_user_industry_vertical)*100;
        //             if ($calculate_percentage >= $user_prof_role_type_item_calculation) {
        //                 foreach ($get_user_prof_role_type_ids as $key => $value) {
        //                     $user_prof_role_items[] = $value['user_id'];
        //                 }
        //                 $status_recommended =  array_unique($user_prof_role_items);
        //             }
        //         }
        //         if(isset($user_prof_role_items)){
        //             $recommended_ids = $user_prof_role_items;
        //         }else{
        //             $recommended_ids = array();
        //         }
        //     }

        // }
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>';
        // }

        //  echo '<pre>'; print_r($get_ids); echo '</pre>'; exit;

        // if (isset($request_data['type'])) {
        //     $return_data = array();
        //     $return_data['user_id'] = \Auth::user()->id;

        //     if ($request_data['type'] == 'Connected') {
        //         $return_data['status'] = 'Accept';
        //     }

        //     if ($request_data['type'] == 'Not Connected') {
        //         $return_data['status'] = 'Pending';
        //     }

        //     $get_user_data  = $this->ConnectPeopleObj->getConnectPeople($return_data);
        // }

        $posted_data = array();
        $request_data = $request->all();
        // echo '<pre>'; print_r($request_data['phone_numbers']); echo '</pre>'; exit;
        $return_ary = array();

        // $recommended_ids = $this->recommended_users();
        $posted_data['except_auth_id'] = \Auth::user()->id;

        $posted_data['orderBy_name'] = 'users.first_name';
        $posted_data['first_not_null'] = true;
        $posted_data['orderBy_value'] = 'ASC';
        $posted_data['groupBy'] = 'users.id';

        $get_users_ids = $this->UserObj->getUser($posted_data)->ToArray();
        $recommended_ids = array_column($get_users_ids, 'id');
        // echo '<pre>'; print_r($recommended_ids); echo '</pre>'; exit;




        $user_id_with_connection_filter = $this->SettingObj->getSetting([
            'user_id' => Auth::user()->id,
            'connections' => 'Manual'
        ])->ToArray();

        $profile_connection_decord_record= [];
        if (isset($user_id_with_connection_filter)  && count($user_id_with_connection_filter) > 0) {
            $profile_connection_filter = array_column($user_id_with_connection_filter, 'connection_search');
            foreach ($get_users_ids as $key => $user_detail) {
                $profile_connection_decord_record[] = json_decode($profile_connection_filter[0]);
                // echo '<pre>'; print_r($profile_connection_filter[0]); echo '</pre>'; exit;
                if (isset($profile_connection_decord_record[$key]->gender)) {
                    $request_data['gender'] = $profile_connection_decord_record[$key]->gender;
                }
                if (isset($profile_connection_decord_record[$key]->age_from) && isset($profile_connection_decord_record[$key]->age_to)) {
                    $request_data['age_from'] = $profile_connection_decord_record[$key]->age_from;
                    $request_data['age_to'] = $profile_connection_decord_record[$key]->age_to;
                }
                if (isset($profile_connection_decord_record[$key]->location)) {
                    $request_data['location'] = $profile_connection_decord_record[$key]->location;
                }
                if (isset($profile_connection_decord_record[$key]->professional_role)) {
                    $request_data['professional_role'] = $profile_connection_decord_record[$key]->professional_role;
                }
                if (isset($profile_connection_decord_record[$key]->career_status_position)) {
                    $request_data['career_status_position'] = $profile_connection_decord_record[$key]->career_status_position;
                }
                if (isset($profile_connection_decord_record[$key]->goal_item)) {
                    $request_data['goal_item_id'] = $profile_connection_decord_record[$key]->goal_item;
                }
            }

        }
// echo '<pre>'; print_r($request_data); echo '</pre>'; exit;




        // echo '<pre>'; print_r($profile_connection_filter); echo '</pre>'; exit;

//         $user_id_with_connection_filter = $this->SettingObj->getSetting([
//             'user_id_in' => $recommended_ids,
//             'connections' => 'Manual'
//         ])->ToArray();
//
//
//
//         // echo '<pre>'; print_r($recommended_ids); echo '</pre>'; exit;
//         // echo '<pre>'; print_r($user_id_with_connection_filter); echo '</pre>'; exit;
//         if (isset($user_id_with_connection_filter) && count($user_id_with_connection_filter) > 0) {
//             $profile_connection_filter = array_column($user_id_with_connection_filter, 'connection_search');
//
//             $profile_connection_decord_record= [];
//             foreach ($profile_connection_filter as $key => $profile_connection) {
//
//                 $profile_connection_decord_record[] = json_decode($profile_connection);
//
//                 if ($profile_connection_decord_record[$key]->gender) {
//                     $request_data['gender'] = $profile_connection_decord_record[$key]->gender;
//                 }
//                 if (isset($profile_connection_decord_record[$key]->age_from)  && isset($profile_connection_decord_record[$key]->age_to)) {
//                     $request_data['age_from'] = $profile_connection_decord_record[$key]->age_from;
//                     $request_data['age_to'] = $profile_connection_decord_record[$key]->age_to;
//                 }
//                 // if (isset($profile_connection_decord_record[$key]->location)) {
//                 //     $request_data['location'] = $profile_connection_decord_record[$key]->location;
//                 // }
//                 // if (isset($profile_connection_decord_record[$key]->professional_role)) {
//                 //     $request_data['professional_role'] = $profile_connection_decord_record[$key]->professional_role;
//                 // }
//                 // if (isset($profile_connection_decord_record[$key]->career_status_position)) {
//                 //     $request_data['career_status_position'] = $profile_connection_decord_record[$key]->career_status_position;
//                 // }
//             }
//         }




//         $user_id_with_profile_none = $this->SettingObj->getSetting([
//             'user_id_in' => $recommended_ids,
//             'profile_visibility' => 'None'
//         ])->ToArray();
//
//         if (isset($user_id_with_profile_none) && count($user_id_with_profile_none) > 0) {
//             $profile_visibility_none_ids = array_column($user_id_with_profile_none, 'user_id');
//             $request_data['profile_not_in_ids'] = $profile_visibility_none_ids;
//         }
//
//         $user_id_with_privacy_filter = $this->SettingObj->getSetting([
//             'user_id_in' => $recommended_ids,
//             'profile_visibility' => 'Privacy Filter'
//         ])->ToArray();
//
//         if (isset($user_id_with_privacy_filter) && count($user_id_with_privacy_filter) > 0) {
//             $profile_visibility_filter = array_column($user_id_with_privacy_filter, 'privacy_filter');
//
//             $profile_visibility_decord_record= [];
//             foreach ($profile_visibility_filter as $key => $profile_visibility) {
//                 $profile_visibility_decord_record[] = json_decode($profile_visibility);
//
//                 if ($profile_visibility_decord_record[$key]->gender) {
//                     $request_data['gender'] = $profile_visibility_decord_record[$key]->gender;
//                 }
//                 if (isset($profile_visibility_decord_record[$key]->age_from)  && isset($profile_visibility_decord_record[$key]->age_to)) {
//                     $request_data['age_from'] = $profile_visibility_decord_record[$key]->age_from;
//                     $request_data['age_to'] = $profile_visibility_decord_record[$key]->age_to;
//                 }
//                 if (isset($profile_visibility_decord_record[$key]->location)) {
//                     $request_data['location'] = $profile_visibility_decord_record[$key]->location;
//                 }
//                 if (isset($profile_visibility_decord_record[$key]->professional_role)) {
//                     $request_data['professional_role'] = $profile_visibility_decord_record[$key]->professional_role;
//                 }
//                 if (isset($profile_visibility_decord_record[$key]->career_status_position)) {
//                     $request_data['career_status_position'] = $profile_visibility_decord_record[$key]->career_status_position;
//                 }
//             }
//         }




//




        $count = 0;
        // if(isset($request_data['check_matched'])){

        //     $posted_data = array();
        //     $request_data = $request->all();
        //     $posted_data['except_auth_id'] = \Auth::user()->id;
        //     if (isset($request_data['name'])) {
        //         $posted_data['name'] = $request_data['name'];
        //     }
        //     if (isset($request_data['phone_numbers'])) {
        //         $posted_data['phone_numbers_in'] = $request_data['phone_numbers'];
        //     }
        //     $posted_data['except_auth_id'] = \Auth::user()->id;
        //     $return_ary['matched_connect_peoples'] = $this->UserObj->getUser($posted_data);

        //     $matched_phone_number = $return_ary['matched_connect_peoples']->ToArray();
        //     $matched_phone_number = array_column($matched_phone_number, 'phone_number');
        //     $matched_connect_people_count = count($matched_phone_number);

        //     $return_ary['not_matched_connect_peoples'] = array();
        //     if(isset($request_data['phone_numbers'])){
        //         $a1 = $matched_phone_number;
        //         $a2 = $request_data['phone_numbers'];
        //         $return_ary['not_matched_connect_peoples'] = array_merge(array_diff($a1, $a2),array_diff($a2,$a1));
        //     }
        //     $count = $matched_connect_people_count;


        // }else{
            $per_page = isset($request->per_page) ? $request->per_page:10;

            $return_ary['not_matched_connect_peoples'] = array();

            $request_data['orderBy_name'] = 'users.first_name';
            $request_data['first_not_null'] = true;
            $request_data['orderBy_value'] = 'ASC';
            $request_data['groupBy'] = 'users.id';
            $request_data['paginate'] = $per_page;

            if(isset($request_data['check_matched'])){

                $request_data = $request->all();
                if (isset($request_data['phone_numbers'])) {
                    $cleanedPhoneNumbers = $this->change_phone_number_fromat($request_data['phone_numbers']);
                    $posted_data['phone_numbers_like'] = $cleanedPhoneNumbers;
                }
                $posted_data['except_auth_id'] = \Auth::user()->id;
                $get_user_data = $this->UserObj->getUser($posted_data)->ToArray();
                // echo '<pre>'; print_r($get_user_data); echo '</pre>'; exit;

                $matched_phone_number = array_column($get_user_data, 'phone_number');
                $match_user_ids = array_column($get_user_data, 'id');
                $request_data['groupBy'] = 'users.id';
                $request_data['users_in'] = $match_user_ids;
                $matched_connect_people_count = count($matched_phone_number);



                $return_ary['not_matched_connect_peoples'] = $request_data['phone_numbers'];
                // echo '<pre>request_data_phone_numbers: '; print_r($request_data['phone_numbers']); echo '</pre>';
                // echo '<pre>matched_phone_number: '; print_r($matched_phone_number); echo '</pre>';
                // echo '<pre>cleanedPhoneNumbers: '; print_r($cleanedPhoneNumbers); echo '</pre>';



                for ($i=0; $i < count($cleanedPhoneNumbers); $i++) {

                    $input = preg_quote($cleanedPhoneNumbers[$i], '~'); // don't forget to quote input string!
                    $data = $matched_phone_number;
                    // $data = $this->change_phone_number_fromat($matched_phone_number);

                    $result = preg_grep('~' . $input . '~', $data);
                    $result = array_values($result);
                    // print_r('--------------------------------------');
                    // echo '<pre>'; print_r($cleanedPhoneNumbers[$i]); echo '</pre>';
                    // echo '<pre>'; print_r($data); echo '</pre>';
                    // echo '<pre>'; print_r($result); echo '</pre>';
                    // print_r('--------------------------------------');


                    if(isset($result[0])){
                        unset($return_ary['not_matched_connect_peoples'][$i]);
                    }
                }
                // exit;
                // echo '<pre>'; print_r($request_data['phone_numbers']); echo '</pre>';
                // echo '<pre>'; print_r($matched_phone_number); echo '</pre>';
                // echo '<pre>'; print_r($return_ary['not_matched_connect_peoples']); echo '</pre>';



                // exit;
                $return_ary['not_matched_connect_peoples'] = array_values($return_ary['not_matched_connect_peoples']);
                $count = $matched_connect_people_count;

            }

            // connect_type filter
            if(isset($request_data['connect_type'])){

                $connect_peoples = $this->ConnectPeopleObj->getConnectPeople([
                    'user_id' => \Auth::user()->id,
                    'connect_types' => $request_data['connect_type']
                ])->ToArray();

                $latestConnectUserId = array_column($connect_peoples, 'user_id');
                $latestConnectConnectId = array_column($connect_peoples, 'connect_user_id');
                $request_data['connect_type_user_ids'] = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
            }


            // Status check
            if (isset($request_data['status'])) {

                $connect_people_list = $this->ConnectPeopleObj->getConnectPeople([
                    'auth_connect_id' => \Auth::user()->id,
                    'status_check' => true,
                ])->ToArray();

                $latestConnectUserId = array_column($connect_people_list, 'user_id');
                $latestConnectConnectId = array_column($connect_people_list, 'connect_user_id');
                $user_status_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));

                $request_data['conect_people_status'] = $request_data['status'];
                if (isset($request_data['status']) && ($request_data['status'] == 'Pending' || $request_data['status'] == 'not_connected' || $request_data['status'] == 'Reject')) {
                    if( $request_data['status'] == 'not_connected'){
                        $request_data['status_is_null'] = true;
                    }
                    $request_data['check_connect_id'] = \Auth::user()->id;
                    // $request_data['status'] = $request_data['status'];


                    if( $request_data['status'] == 'Reject'){
                        $request_data['status'] = 'Reject';
                        // $request_data['connect_ids'] = \Auth::user()->id;
                    }
                    else{
                        $request_data['status'] = 'Pending';
                    }
                    // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;

                    $request_data['connect_people_connect_user_id_left_join'] = false;
                    $request_data['connect_people_user_id_left_join'] = true;
                }

                // if ($request_data['status'] == 'pass') {
                //     $refuse_connection = $this->UserRefuseConnectionObj->getUserRefuseConnection([
                //         'user_id_from' =>\Auth::user()->id
                //     ])->ToArray();
                //     $request_data['users_in']= array_column($refuse_connection, 'user_id_to');
                //     // $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                // }

                // if (isset($request_data['status']) && $request_data['status'] == 'Accept' || $request_data['status'] == 'connected' || $request_data['status'] == 'not_connected') {
                if (isset($request_data['status']) && $request_data['status'] == 'Accept') {

                    $connect_people_list = $this->ConnectPeopleObj->getConnectPeople([
                        'auth_connect_id' => \Auth::user()->id,
                        'status' => 'Accept',
                    ])->ToArray();

                    $latestConnectUserId = array_column($connect_people_list, 'user_id');
                    $latestConnectConnectId = array_column($connect_people_list, 'connect_user_id');
                    $array = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
                    $this->UserObj->saveUpdateUser([
                        'update_id' => \Auth::user()->id,
                        'new_connection_count' => 0,
                    ]);


                    if($request_data['status'] == 'not_connected'){
                        $request_data['users_not_in'] = $array;
                    }else{
                        $request_data['users_in'] = $array;
                    }
                    $request_data['except_auth_id'] = \Auth::user()->id;
                    $request_data['connect_people_connect_user_id_left_join'] = false;
                    $request_data['connect_people_user_id_left_join'] = true;
                    // $request_data['printsql'] =true;
                    unset($request_data['status']);
                }


                // if (isset($request_data['status']) && $request_data['status'] == 'Recommended') {
                //     if (isset($recommended_ids) && count($recommended_ids) > 0) {
                //         $request_data['users_in'] = $recommended_ids;
                //     }
                // }
            }
            // echo '<pre>'; print_r($array); echo '</pre>'; exit;

            $book_mark_user_ids =  $this->bookmark_user();

            if (isset($request_data['status']) && $request_data['status'] == 'Bookmark') {
                unset( $request_data['first_not_null']);
                $request_data['user_id_to'] = $book_mark_user_ids;
            }

            if (isset($request_data['status']) && $request_data['status'] == 'Recommended' && isset($recommended_ids)){


                $accept_connect_people_ids = $this->ConnectPeopleObj->getConnectPeople([
                    'auth_connect_id' => \Auth::user()->id,
                    'status' => 'Accept',
                ])->ToArray();

                $pending_connect_people_ids = $this->ConnectPeopleObj->getConnectPeople([
                    'connect_user_id' => \Auth::user()->id,
                    'status' => 'Pending',
                ])->ToArray();

                $refuse_connection = $this->UserRefuseConnectionObj->getUserRefuseConnection([
                    'user_id_from' =>\Auth::user()->id
                ])->ToArray();
                $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                // $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');

                $not_connected_ids = array_column($pending_connect_people_ids, 'user_id');
                $latestConnectUserId = array_column($accept_connect_people_ids, 'user_id');
                $latestConnectConnectId = array_column($accept_connect_people_ids, 'connect_user_id');
                $not_accepted_ids = array_unique (array_merge ($latestConnectConnectId, $latestConnectUserId));
                $refuse_connection_ids = array_unique (array_merge ($not_accepted_ids, $refuse_connection_ids));
                $user_status_ids = array_unique (array_merge ($refuse_connection_ids, $not_connected_ids));
                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;

                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;
                // $connect_people_list = $this->ConnectPeopleObj->getConnectPeople([
                //     'user_id' => \Auth::user()->id,
                //     'status' => 'Pending',
                // ])->ToArray();

                // $pending_ids = array_column($connect_people_list, 'connect_user_id');

                // echo '<pre>'; print_r($user_status_ids); echo '</pre>'; exit;
                // $pendings_user = array_unique (array_merge ($user_status_ids, $pending_ids));
                // echo '<pre>'; print_r($pendings_user); echo '</pre>'; exit;

                // $request_data['or_users_in'] = $pendings_user;

                $request_data['or_users_in'] = $user_status_ids;
                // $request_data['status_is_pending'] = 'Pending';

                // $request_data['users_in'] = $recommended_ids;
                // $request_data['users_not_in'] = $array;
                // $request_data['except_auth_id'] = \Auth::user()->id;
                // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
            }
             // Refuse connection check
            if (isset($request_data['pass_connection']) && $request_data['pass_connection'] == 'True') {
                $refuse_connection = $this->UserRefuseConnectionObj->getUserRefuseConnection([
                    'user_id_from' =>\Auth::user()->id
                ])->ToArray();
                $refuse_connection_ids = array_column($refuse_connection, 'user_id_to');
                $common_ids = array_intersect($refuse_connection_ids, $recommended_ids);

                $common_ids = array_values($common_ids);

                if (isset($refuse_connection_ids) && count($refuse_connection_ids) > 0 ) {
                    // unset($request_data['or_users_in']);
                    // unset($request_data['users_in']);
                    if (isset($common_ids) && count($common_ids) >0) {
                        $request_data['users_not_in'] = $common_ids;
                    }
                    // if (isset($common_ids) && count($common_ids) > 0) {
                    //     unset($request_data['or_users_in']);
                    //     $request_data['users_not_in'] = $common_ids;
                    // }
                }
                else{
                    // $request_data['users_not_in'] = $user_status_ids;
                    // echo '<pre>'; print_r($refuse_connection_ids); echo '</pre>'; exit;
                }
            }

            if (isset($request_data['status']) && $request_data['status'] == 'all') {
                unset( $request_data['status']);
            }
            // if (isset($request_data['status']) && $request_data['status'] == 'not_connected') {
            //     $request_data['status'] = 'Pending';
            // }
            // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
            $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
                'block_id_from' => \Auth::user()->id,
                'type' => 'Block',
            ])->ToArray();


            $user_id_from = array_column($user_is_blocked, 'block_user_id_from');
            $user_id_to = array_column($user_is_blocked, 'block_user_id_to');
            $not_accepted_ids = array_unique (array_merge ($user_id_to, $user_id_from));
            if ($not_accepted_ids) {
                $request_data['users_not_in'] = $not_accepted_ids;
            }
            else{
                $request_data['users_not_in'] = array(0 =>Auth::user()->id);
            }
            // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
            $connect_peoples = $this->UserObj->getUser($request_data);

            if(isset($connect_peoples)){

                foreach ($connect_peoples as $key => $connect_peoples_value) {
                    $connect_peoples_value->is_recommended = false;
                    if(isset($recommended_ids) && in_array($connect_peoples_value->id, $recommended_ids)){
                        $connect_peoples_value->is_recommended = true;
                    }
                    $connect_peoples_value->is_bookmark = false;
                    if(isset($book_mark_user_ids) && in_array($connect_peoples_value->id, $book_mark_user_ids)){
                        $connect_peoples_value->is_bookmark = true;
                    }

                    $connectPeopleList = array();
                    $connectPeopleList['auth_connect_id'] = Auth::user()->id;
                    $connectPeopleList['other_connect_id'] = $connect_peoples_value->id;
                    // if (isset($request_data['status'])) {
                    //     $connectPeopleList['status'] = $connect_peoples_value->status;
                    // }
                    $connectPeopleList['detail'] = true;
                    // $connectPeopleList['printsql'] = true;

                    // $connect_peoples_value->connect_people = $this->ConnectPeopleObj->getConnectPeople([
                    //     'auth_connect_id' => Auth::user()->id,
                    //     'auth_connect_id' => $connect_peoples_value->id,
                    //     'detail' => true,
                    //     // 'printsql' => true
                    // ]);
                    $connect_peoples_value->connect_people = $this->ConnectPeopleObj->getConnectPeople($connectPeopleList);

                    $connect_peoples_value->is_blocked = false;
                    $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
                        'block_user_id_from' => \Auth::user()->id,
                        'block_user_id_to' => $connect_peoples_value->id,
                        'type' => 'Block',
                        'detail' => true
                    ]);
                    if($user_is_blocked){
                        $connect_peoples_value->is_blocked = true;
                    }
                }
            }
            unset($request_data['paginate']);
            $request_data['count'] = true;
            // echo '<pre>'; print_r($request_data); echo '</pre>'; exit;
            $count = $this->UserObj->getUser($request_data);

            $return_ary['matched_connect_peoples'] = $connect_peoples;

        // }


        return $this->sendResponse($return_ary, 'User connect list', $count);

    }

    // Change phone number format funct
    function change_phone_number_fromat($posted_data =array()){
        $cleanedPhoneNumbers = array_map(function($phoneNumber) {
            $cleanedNumber = preg_replace('/\s|-/', '', $phoneNumber);
            $last10Digits = substr($cleanedNumber, -10);
            return $last10Digits;
        }, $posted_data);
        return $cleanedPhoneNumbers;
    }

    function user_validation(Request $request){
        $request_data = $request->all();
        $rules = array(
            'user_name' => 'required|exists:users,user_name',
        );

        $validator = \Validator::make($request_data, $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->messages());
        }
        $user_detail =$this->UserObj->getUser($request_data);
        if ($user_detail) {
            return $this->sendResponse($user_detail, 'User information');
        }
        else{
            return $this->sendError('error', "Something went wrong");
        }

    }

    // Delete Connection
    public function delete_connection(Request $request,$id){
        $connection_detail= $this->ConnectPeopleObj->getConnectPeople([
            'other_connect_id' => $id,
            'auth_connect_id' => \Auth::user()->id,
            // 'status' => 'Accept',
            'detail' =>true,
            // 'printsql' =>true
        ]);
        // exit;
        if (isset($connection_detail)) {

            $this->ConnectPeopleObj->deleteConnectPeople($connection_detail->id);

            // $connectionAuthId = \Auth::user()->id;
            // $connectOtherId = $id;
            // $connection_ids = array_merge([$connectOtherId], [$connectionAuthId]);

            // foreach ($connection_ids as $key => $connectionid) {
            //     $get_user_info = $this->UserObj->getUser([
            //         'id' => $connectionid,
            //         'detail' => true,
            //     ]);
            //     $this->UserObj->saveUpdateUser([
            //         'update_id' => $connectionid,
            //         'new_connection_count' => $get_user_info->new_connection_count > 0 ? $get_user_info->new_connection_count - 1: 0,
            //         'request_count' => $get_user_info->request_count > 0 ? $get_user_info->request_count - 1 :0,
            //     ]);
            // }

            // =================================================================
            // =================================================================
            $get_user_info_user_id= $this->UserObj->getUser([
                'id' => \Auth::user()->id,
                'detail' =>true
            ]);

            $new_connection_count = $get_user_info_user_id->new_connection_count > 0 ? $get_user_info_user_id->new_connection_count - 1: 0;
            $this->UserObj->saveUpdateUser([
                'update_id' => $get_user_info_user_id->id,
                'new_connection_count' => $new_connection_count,
            ]);
            $event_data = array();
            $event_data['count'] = $new_connection_count;
            $event_data['user_id'] = $id;
            $event_data['connection_removed'] = true;
            $event_data['connect_people'] = $connection_detail;
            event (new \App\Events\ConnectionRequestCountEvent($event_data, \Auth::user()->id));

            // =================================================================
            // =================================================================

            $get_user_info_connect_user_id= $this->UserObj->getUser([
                'id' => $id,
                'detail' =>true
            ]);
            $new_connection_count = $get_user_info_connect_user_id->new_connection_count > 0 ? $get_user_info_connect_user_id->new_connection_count - 1: 0;
            $this->UserObj->saveUpdateUser([
                'update_id' => $get_user_info_connect_user_id->id,
                'new_connection_count' => $new_connection_count,
                'request_count' => $get_user_info_connect_user_id->request_count > 0 ? $get_user_info_connect_user_id->request_count - 1 :0,
            ]);

            $event_data = array();
            $event_data['count'] = $new_connection_count;
            $event_data['user_id'] = \Auth::user()->id;
            $event_data['connection_removed'] = true;
            $event_data['connect_people'] = $connection_detail;
            event (new \App\Events\ConnectionRequestCountEvent($event_data, $id));


            // =================================================================
            // =================================================================


                // $user_auth_new_connection_count= $this->UserObj->getUser([
                //     'id' => \Auth::user()->id,
                //     'detail' =>true
                // ]);

                // $user_other_new_connection_count= $this->UserObj->getUser([
                //     'id' => $res->connect_user_id,
                //     'detail' =>true
                // ]);

                // event (new \App\Events\NewConnectionCountEvent($user_auth_new_connection_count->new_connection_count, \Auth::user()->id));
                // event (new \App\Events\NewConnectionCountEvent($user_other_new_connection_count->new_connection_count, $connectOtherId));




            // $get_connect_people_count= $this->ConnectPeopleObj->getConnectPeople([
            //     'other_connect_id' => $id,
            //     'auth_connect_id' => \Auth::user()->id,
            //     'count' =>true,
            // ]);

            $get_connect_people_count = $this->ConnectPeopleObj->getConnectPeople([
                'connect_user_id' => \Auth::user()->id,
                'status' =>'Pending',
                'count' =>true,
            ]);


            $connection_detail = $this->ConnectPeopleObj->getConnectPeople([
                'connect_user_id' => \Auth::user()->id,
                'status' =>'Pending'
            ]);


            $event_data = array();
            $event_data['count'] = $get_connect_people_count;
            $event_data['user_id'] = $id;
            $event_data['connection_removed'] = true;
            $event_data['connect_people'] = $connection_detail;
            event (new \App\Events\ConnectionRequestCountEvent($event_data, \Auth::user()->id));
            // event (new \App\Events\ConnectionRequestCountEvent($get_connect_people_count, \Auth::user()->id));
            return $this->sendResponse('Success', 'Connection removed');


            // $get_connect_people_count= $this->ConnectPeopleObj->getConnectPeople([
            //     'other_connect_id' => $id,
            //     'auth_connect_id' => \Auth::user()->id,
            //     // 'status' =>'Pending',
            //     'count' =>true,
            // ]);
            // echo '<pre>'; print_r($get_connect_people_count); echo '</pre>'; exit;
            // event (new \App\Events\ConnectionRequestCountEvent($get_connect_people_count, $id));


            // $res = $this->ConnectPeopleObj->deleteConnectPeople($connection_detail->id);
            // echo '<pre>'; print_r($res); echo '</pre>'; exit;





        }
        return $this->sendError("error", "Something went wrong");
    }

    // list of connect people And Filters
    public function without_connect_module_connect_people_list(Request $request){
        $request_data = $request->all();

        $return_ary = array();
        $count = 0;
        if(isset($request_data['check_matched'])){

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
            $matched_connect_people_count = count($matched_phone_number);

            $return_ary['not_matched_connect_peoples'] = array();
            if(isset($request_data['phone_numbers'])){
                $a1 = $matched_phone_number;
                $a2 = $request_data['phone_numbers'];
                $return_ary['not_matched_connect_peoples'] = array_merge(array_diff($a1, $a2),array_diff($a2,$a1));
            }
            $count = $matched_connect_people_count;


        }else{


            $per_page = isset($request->per_page) ? $request->per_page:10;

            // $request_data['user_id'] = \Auth::user()->id;
            // $request_data['status'] = 'Accept';
            // $connect_peoples = $this->ConnectPeopleObj->getConnectPeople($request_data);
            // $latestConnectUserId = $connect_peoples->ToArray();
            // $latestConnectUserId = array_column($latestConnectUserId, 'connect_user_id');

            $request_data['orderBy_name'] = 'users.first_name';
            $request_data['first_not_null'] = true;
            $request_data['orderBy_value'] = 'ASC';
            $request_data['groupBy'] = 'users.id';
            $request_data['paginate'] = $per_page;

            $connect_peoples = $this->UserObj->getUser($request_data);
            // $connect_peoples = $connect_peoples->ToArray();
            // $connect_peoples = array_column($connect_peoples['data'], 'id');

            if(isset($connect_peoples)){

                foreach ($connect_peoples as $key => $connect_peoples_value) {
                    $connect_peoples_value->is_blocked = false;
                    $user_is_blocked = $this->MessageStatusObj->getMessageStatus([
                        'block_user_id_from' => \Auth::user()->id,
                        'block_user_id_to' => $connect_peoples_value->id,
                        'type' => 'Block',
                        'detail' => true
                    ]);
                    if($user_is_blocked){
                        $connect_peoples_value->is_blocked = true;
                    }
                }
            }
            unset($request_data['paginate']);
            $request_data['count'] = true;
            // $request_data['printsql'] = true;
            $count = $this->UserObj->getUser($request_data);
            $return_ary['matched_connect_peoples'] = $connect_peoples;
            $return_ary['not_matched_connect_peoples'] = array();

            // $latestConnectUserId = $connect_peoples->ToArray();
            // $latestConnectUserId = array_column($latestConnectUserId['data'], 'id');
            // // echo '<pre>'; print_r($latestConnectUserId); echo '</pre>';

            // if(count($latestConnectUserId)>0){
            //     // Connect people filter
            //     if(isset($request_data['connect_type'])){
            //         $connect_type_type_items = $this->ConnectPeopleObj->getConnectPeople([
            //             'connect_types' => $request_data['connect_type'],
            //             'connect_user_ids' => $latestConnectUserId
            //         ]);
            //         $latestConnectUserId = $connect_type_type_items->ToArray();
            //         $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
            //     }

            //     // Goal item filter
            //     if(isset($request_data['goal_item_id'])){
            //         $goal_item_id_type_items = $this->UserGoalItemObj->getUserGoalItem([
            //             'goal_item_id' => $request_data['goal_item_id'],
            //             'user_ids' => $latestConnectUserId
            //         ]);
            //         // echo '<pre>'; print_r($goal_item_id_type_items); echo '</pre>'; exit;
            //         $latestConnectUserId = $goal_item_id_type_items->ToArray();
            //         $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
            //     }

            //     // Professional role filter
            //     if(isset($request_data['professional_role'])){
            //         $professional_role_type_items = $this->UserProRolteItemObj->getUserProRoleTypeItem([
            //             'general_title_ids' => $request_data['professional_role'],
            //             'user_ids' => $latestConnectUserId
            //         ]);
            //         $latestConnectUserId = $professional_role_type_items->ToArray();
            //         $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
            //     }

            //     // Industry experties filter
            //     if(isset($request_data['industry_experties'])){
            //         $industries_experties = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
            //             'general_title_ids' => $request_data['industry_experties'],
            //             'user_ids' => $latestConnectUserId
            //         ]);
            //         $latestConnectUserId = $industries_experties->ToArray();
            //         $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
            //     }

            //     // Career status position filter
            //     if(isset($request_data['career_status_position'])){
            //         $career_status_position = $this->UserCareerStatusObj->getUserCareerStatusPosition([
            //             'general_title_ids' => $request_data['career_status_position'],
            //             'user_ids' => $latestConnectUserId
            //         ]);
            //         $latestConnectUserId = $career_status_position->ToArray();
            //         $latestConnectUserId = array_column($latestConnectUserId, 'user_id');
            //     }
            //     // $request_data['page'] = 1;
            //     $connect_peoples = $this->UserObj->getUser([
            //         'user_ids' => $latestConnectUserId,
            //         'search' => @$request_data['search'],
            //         'groupBy' => 'users.id',
            //         // 'paginate' => $per_page,
            //     ]);
            //     $return_ary['matched_connect_peoples'] = $connect_peoples;
            //     $return_ary['not_matched_connect_peoples'] = array();
            //     $count = count($latestConnectUserId);
            // }else{
            //     $connect_peoples = $this->UserObj->getUser([
            //         'user_ids' => $latestConnectUserId,
            //         // 'paginate' => $per_page,
            //         'id' => 0
            //     ]);
            //     $return_ary['matched_connect_peoples'] = $connect_peoples;
            //     $return_ary['not_matched_connect_peoples'] = array();
            //     $count = 0;
            // }
        }

        return $this->sendResponse($return_ary, 'User connect list', $count);

    }

    // list of connect people And Filters
    public function connect_people_list_bk(Request $request){
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
        // echo '<pre>'; print_r($latestConnectUserId); echo '</pre>'; exit;
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
                // 'printsql'=> true
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
                'connect_user_id' => 'required|exists:users,id',
                // 'message' => 'required',
                // 'connect_along_message' => 'required|in:False,True',
            );

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError(["error" => $validator->errors()->first()]);
            }

            $requestd_data = array();
            $requestd_data['user_id'] = \Auth::user()->id;
            $requestd_data['connect_user_id'] =  $request->connect_user_id;
            $requestd_data['connect_type'] =  'connection';
            if ($request->connect_type) {
                $requestd_data['connect_type'] =  $request->connect_type;
            }
            $requestd_data['detail'] =  true;
            if($requestd_data['user_id'] != $requestd_data['connect_user_id']){
                $ConnectPeopleDetail = $this->ConnectPeopleObj->getConnectPeople($requestd_data);
                if($ConnectPeopleDetail){
                    $requestd_data['update_id'] =  $ConnectPeopleDetail->id;
                }
                $requestd_data['status'] = 'Pending';
                if(isset($request->connect_people_note)){
                    $requestd_data['connect_people_note'] =  $request->connect_people_note;
                }


                $returnData = $this->ConnectPeopleObj->saveUpdateConnectPeople($requestd_data);
                if (isset($returnData)) {
                    $get_user_info = $this->UserObj->getUser([
                        'id' => $returnData->connect_user_id,
                        'detail' => true
                    ]);
                    $this->UserObj->saveUpdateUser([
                        'update_id' => $get_user_info->id,
                        'request_count' => $get_user_info->request_count + 1,
                    ]);
                }

                // echo '<pre>'; print_r($returnData); echo '</pre>'; exit;
                $get_connect_people_count= $this->ConnectPeopleObj->getConnectPeople([
                    'connect_user_id' => $request->connect_user_id,
                    'status' =>'Pending',
                    'count' =>true,
                ]);

                send_notification([
                    'user_id' => \Auth::user()->id,
                    'receiver_id' => $request->connect_user_id,
                    'notification_message_id' => 3,
                    'event_name' => 'connection_request',
                    'metadata' => $returnData
                ]);
                $event_data = array();
                $event_data['count'] = $get_connect_people_count;
                $event_data['user_id'] = \Auth::user()->id;
                $event_data['connect_people'] = $returnData;
                event (new \App\Events\ConnectionRequestCountEvent($event_data, $request->connect_user_id));
                // event (new \App\Events\ConnectionRequestCountEvent($get_connect_people_count, $request->connect_user_id));
                return $this->sendResponse($returnData, 'User connected');
            }else{
                return $this->sendError("You can't connect with your own profile");
            }
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

        // $connect_people = $this->ConnectPeopleObj->getConnectPeople([
        //     'id' => $id,
        //     'detail' => true
        // ]);
        // if ($connect_people) {
        //     if ($update_data['status'] == 'Accept') {

        //         $this->ConnectPeopleObj->saveUpdateConnectPeople([
        //             'user_id' => $connect_people->connect_user_id,
        //             'connect_user_id' => \Auth::user()->id,
        //             'status' => 'Accept',
        //             'connect_type' => 'phonebook'
        //         ]);
        //     }
        // }

        $res = $this->ConnectPeopleObj->saveUpdateConnectPeople($update_data);
        if (isset($request->status) && $request->status == 'Accept') {

            // =================================================================
            // =================================================================
            $get_user_info_user_id= $this->UserObj->getUser([
                'id' => $res->user_id,
                'detail' =>true
            ]);
            $new_connection_count = $get_user_info_user_id->new_connection_count + 1;
            $this->UserObj->saveUpdateUser([
                'update_id' => $get_user_info_user_id->id,
                'new_connection_count' => $new_connection_count,
            ]);
            $event_data = array();
            $event_data['count'] = $new_connection_count;
            $event_data['user_id'] = $res->connect_user_id;
            $event_data['connect_people'] = $res;
            event (new \App\Events\NewConnectionCountEvent($event_data, $res->user_id));

            // =================================================================
            // =================================================================

            $get_user_info_connect_user_id= $this->UserObj->getUser([
                'id' => $res->connect_user_id,
                'detail' =>true
            ]);
            $new_connection_count = $get_user_info_connect_user_id->new_connection_count + 1;
            $this->UserObj->saveUpdateUser([
                'update_id' => $get_user_info_connect_user_id->id,
                'new_connection_count' => $new_connection_count,
                'request_count' => $get_user_info_connect_user_id->request_count - 1,
            ]);

            $event_data = array();
            $event_data['count'] = $new_connection_count;
            $event_data['user_id'] = $res->user_id;
             $event_data['connect_people'] = $res;
            event (new \App\Events\NewConnectionCountEvent($event_data, $res->connect_user_id));
            // =================================================================
            // =================================================================

        }


        // $get_member_ids = array_column($res, 'user_id');
        // echo '<pre>'; print_r($res['user_id']); echo '</pre>'; exit;
        // $user_id = $res->user_id;
        // $connect_user_id = $res->connect_user_id;

        // echo '<pre>'; print_r($get_member_ids); echo '</pre>'; exit;

        // if (isset($request->status) && $request->status == 'Accept') {
        //     $get_user_info = $this->UserObj->getUser([
        //         'id' => $res->connect_user_id,
        //     ]);
        //     $this->UserObj->saveUpdateUser([
        //         'update_id' => $res->connect_user_id,
        //         'request_count' => $get_user_info->request_count + 1,
        //     ]);
        // }

        $get_connect_people_count= $this->ConnectPeopleObj->getConnectPeople([
            'connect_user_id' => $res->connect_user_id,
            'status' =>'Pending',
            'count' =>true,
        ]);
        // echo '<pre>'; print_r($get_connect_people_count); echo '</pre>'; exit;
        send_notification([
            'user_id' => \Auth::user()->id,
            'receiver_id' => $res->user_id,
            'notification_message_id' => 4,
            'update_connection_event' => 'update_connection_request',
            'metadata' => $res
        ]);
        $event_data = array();
        $event_data['count'] = $get_connect_people_count;
        $event_data['user_id'] = $res->user_id;
        $event_data['connect_people'] = $res;
        event (new \App\Events\ConnectionRequestCountEvent($event_data, $res->connect_user_id));
        // event (new \App\Events\ConnectionRequestCountEvent($get_connect_people_count, $res->connect_user_id));

        // $ConnectPeopleDetail = $this->ConnectPeopleObj->getConnectPeople([
        //     'id' => $res->id,
        //     'detail'=> true
        // ]);
        // if ($ConnectPeopleDetail) {


            // event (new \App\Events\ConnectionRequestEvent($notification_params['metadata'], $posted_data['receiver_id']));
            // event (new \App\Events\ConnectionRequestEvent($ConnectPeopleDetail, $ConnectPeopleDetail->connect_user_id));
            // send_notification([
            //     'user_id' => \Auth::user()->id,
            //     'receiver_id' => $ConnectPeopleDetail->connect_user_id,
            //     'notification_message_id' => 1,
            //     'metadata' => $res
            // ]);
        // }
        return $this->sendResponse($res, 'User connection updated');
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
        // if (isset($requested_data['step'])) {
            $posted_data = array();
            // $posted_data['step'] = $requested_data['step'];

            //In step 1 send phone number
            // if ($requested_data['step'] == 1) {
            if (isset($requested_data['phone_number'])) {
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

                    $user_data = $this->UserObj->getUser([
                        'phone_number' => $requested_data['phone_number'],
                        'detail' => true
                    ]);
                    $user_data['token'] =  $user_data->createToken('MyApp')->accessToken;
                   return $this->sendResponse($user_data, 'User Login Successfully');
                }
                $posted_data['phone_number'] = $requested_data['phone_number'];
                $posted_data['step'] = 1;
            }

            //In step 2 send first name, last name and other name
            // else if ($requested_data['step'] == 2) {
            else {

            if (!isset($requested_data['step']) || (isset($requested_data['step']) && $requested_data['step'] != 11) ) {
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
                $posted_data['user_name'] = strtolower($requested_data['first_name']) . '' . strtolower($requested_data['last_name']) . '_' . mt_rand(1, 999);
                if (isset($requested_data['other_name'])) {
                    $posted_data['other_name'] = $requested_data['other_name'];
                }
                // }

                //In step 3 send date of birth and gender
                // else if ($requested_data['step'] == 3) {
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
                // }

                // Company job title and career seniority
                // else if ($requested_data['step'] == 4) {
                    $rules = array(
                        'user_id'  => 'required|exists:users,id',
                        'company_career_info_id' => 'required|exists:general_titles,id',
                        //'company_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
                        //'job_title' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
                        'job_title' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                        'company_name' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                        // 'company_name' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
                    );
                    $validator = \Validator::make($requested_data, $rules, [
                        'company_name.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )',
                        'job_title.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )'
                        //'company_name.regex' => 'Company name format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
                        //'job_title.regex' => 'Job title format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
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
                // }

                // Select Professional Role information
                // else if ($requested_data['step'] == 5) {

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

                    // if (isset($requested_data['prof_role_type_id'])) {
                    //     $update_data['prof_role_type_id'] = $requested_data['prof_role_type_id'];
                    //     $update_data['general_title_id'] = $requested_data['prof_info_id'];
                    //     $update_data['user_id'] = $requested_data['user_id'];
                    //     $update_data['prof_role_type_item_id'] = $requested_data['prof_role_type_item_id'];
                    //     $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);
                    // }




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
                // }

                // Choose Goals
                // else if ($requested_data['step'] == 6) {
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
                // }

                // Educational Information
                // else if ($requested_data['step'] == 7) {

                    $rules = array(

                        'user_id' => 'required|exists:users,id',
                        'education_info_id' => 'required|exists:general_titles,id',
                        'university_school_name' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                        'degree_discipline' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                        // 'university_school_name' => 'required|regex:/^[a-zA-Z ()-]+$/u',
                        //'degree_discipline' => 'required|regex:/^[a-zA-Z ()-]+$/u'
                        // 'university_school_name' => 'required||regex:/^[a-zA-Z ]+$/u',
                        // 'degree_discipline' => 'required||regex:/^[a-zA-Z ]*(?:[\/\-\(\)][a-zA-Z ]*)+$/u'
                    );
                    $validator = \Validator::make($requested_data, $rules, [
                        'university_school_name.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )',
                        'degree_discipline.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )'
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
                // }

                // User Industry Vertical
                // else if ($requested_data['step'] == 8) {

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
                // }

                // User Interested Vertical
                // else if ($requested_data['step'] == 9) {

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

                // }
                $posted_data['step'] = 9;
                // Specialty And Skills
                // else if ($requested_data['step'] == 10) {

                    if(isset($requested_data['specialty_skill_id'])){
                        $requested_data['industry_vertical_item_id'] = $requested_data['specialty_skill_id'];
                        $posted_data['step'] = 10;
                    }
                    $rules = array(
                        'user_id' => 'required|exists:users,id',
                        // 'industry_vertical_item_id' => 'required',
                        //'industry_vertical_item_id' => 'required|exists:industry_vertical_items,id'
                        // 'industry_vertical_item_id' => 'required|exists:user_industy_vertical_items,industry_vertical_item_id'
                    );
                    $validator = \Validator::make($requested_data, $rules);
                     // $validator = \Validator::make($requested_data, $rules,[
                    //     'industry_vertical_item_id.exists' => 'The selected specialty skill is invalid.',
                    //     'industry_vertical_item_id.required' => 'The specialty skill is required.',
                    // ]);
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
                // }

                }
                // upload user Profile Image
                // else if ($requested_data['step'] == 11) {

                    $rules = array(
                        'user_id' => 'required|exists:users,id',
                        'profile_image' => 'mimes:jpeg,png,jpg|max:2048',
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
                            $posted_data['step'] = 11;
                        } else {
                            $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                            return $this->sendError($error_message['error'], $error_message);
                        }
                    }
                // }
            }
            if (count($posted_data) > 0) {

                // $validator = \Validator::make($requested_data, $rules);
                // if ($validator->fails()) {
                //     return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
                // } else {
                    $user = $this->UserObj->saveUpdateUser($posted_data);
                    if (isset($requested_data['phone_number'])) {
                        $user = $this->UserObj->getUser([
                            'phone_number' => $user['phone_number'],
                            'detail' => true
                        ]);
                        \Auth::login($user);


                        $user = $this->UserObj->getUser([
                            'phone_number' => $user['phone_number'],
                            'detail' => true
                        ]);

                        $user['token'] =  $user->createToken('MyApp')->accessToken;
                    }
                    return $this->sendResponse($user, 'User information added successfully.');
                // }
            } else {
                $error_message['error'] = 'Please submit user data.';
                return $this->sendError($error_message['error'], $error_message);
            }
        // }
        // $error_message['error'] = 'Please add step value.';
        // return $this->sendError($error_message['error'], $error_message);
    }

    // public function register_user_bk(Request $request)
    // {
    //     $requested_data = $request->all();

    //     if(isset($requested_data['user_id'])){
    //         $user_detail_record = $this->UserObj->getUser([
    //             'id' => $requested_data['user_id'],
    //             'detail'=>true
    //         ]);
    //     }
    //     if (isset($requested_data['step'])) {
    //         $posted_data = array();
    //         $posted_data['step'] = $requested_data['step'];

    //         //In step 1 send phone number
    //         if ($requested_data['step'] == 1) {
    //             $rules = array(
    //                 'phone_number' => 'required|unique:users,phone_number',
    //             );
    //             $validator = \Validator::make($requested_data, $rules);

    //             if ($validator->fails()) {
    //                 $rules = array(
    //                     'phone_number' => 'required',
    //                 );

    //                 $validator = \Validator::make($requested_data, $rules);

    //                 if ($validator->fails()) {
    //                     return $this->sendError($validator->errors()->first(), $validator->messages());
    //                     // return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
    //                 }

    //                 $user_data = $this->UserObj->getUser([
    //                     'phone_number' => $requested_data['phone_number'],
    //                     'detail' => true
    //                 ]);
    //                 \Auth::login($user_data);
    //                 $user_data['token'] =  $user_data->createToken('MyApp')->accessToken;
    //                return $this->sendResponse($user_data, 'User Login Successfully');
    //             }
    //             $posted_data['phone_number'] = $requested_data['phone_number'];
    //         }

    //         //In step 2 send first name, last name and other name
    //         else if ($requested_data['step'] == 2) {

    //             $rules = array(

    //                 'user_id' => 'required|exists:users,id',
    //                 // 'email' => 'required|email:rfc,dns|unique:users,email,'.$requested_data['user_id'],
    //                 // 'first_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
    //                 // 'last_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/'
    //                  'first_name' => 'required||regex:/^[a-zA-Z ]+$/u',
    //                  'last_name' => 'required||regex:/^[a-zA-Z ]+$/u'
    //             );
    //             $validator = \Validator::make($requested_data, $rules, [

    //                 'first_name.regex' => 'Only letters are allowed',
    //                 'last_name.regex' => 'Only letters are allowed',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];
    //             if (isset($requested_data['email'])) {
    //                 $posted_data['email'] = $requested_data['email'];
    //             }

    //             $posted_data['first_name'] = $requested_data['first_name'];
    //             $posted_data['last_name'] = $requested_data['last_name'];
    //             if (isset($requested_data['other_name'])) {
    //                 $posted_data['other_name'] = $requested_data['other_name'];
    //             }
    //         }

    //         //In step 3 send date of birth and gender
    //         else if ($requested_data['step'] == 3) {
    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //                 'dob' => 'required',
    //                 'gender' => 'required|in:1,2,3'
    //             );
    //             $validator = \Validator::make($requested_data, $rules);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];
    //             $posted_data['dob'] = $requested_data['dob'];
    //             $posted_data['gender'] = $requested_data['gender'];
    //         }

    //         // Company job title and career seniority
    //         else if ($requested_data['step'] == 4) {
    //             $rules = array(
    //                 'user_id'  => 'required|exists:users,id',
    //                 'company_career_info_id' => 'required|exists:general_titles,id',
    //                 //'company_name' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
    //                 //'job_title' => 'required|regex:/^(?=.*[a-zA-Z0-9 ]{4})[a-zA-Z0-9 ]*(?:[\/\-\(\)][a-zA-Z0-9 ]*)?$/',
    //                 'job_title' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
    //                 'company_name' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
    //             );
    //             $validator = \Validator::make($requested_data, $rules, [
    //                 'company_name.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
    //                 'job_title.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
    //                 //'company_name.regex' => 'Company name format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
    //                 //'job_title.regex' => 'Job title format not correct kindly Only letters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];
    //             $user_career_record = $this->UserCareerStatusObj->getUserCareerStatusPosition([
    //                 'user_id' => $requested_data['user_id'],
    //             ]);

    //             if($user_career_record){
    //                 $this->UserCareerStatusObj->deleteUserCareerStatusPosition(0,['user_id' => $requested_data['user_id']]);
    //             }

    //             $this->UserCareerStatusObj->saveUpdateUserCareerStatusPosition([
    //                 'user_id' => $requested_data['user_id'],
    //                 'general_title_id' => $requested_data['company_career_info_id'],
    //                 'company_name' => $requested_data['company_name'],
    //                 'job_title' => $requested_data['job_title'],
    //             ]);
    //         }

    //         // Select Professional Role information
    //         else if ($requested_data['step'] == 5) {

    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //                 'prof_role_type_id' => 'required|exists:prof_role_types,id',
    //                 'prof_info_id' => 'required|exists:general_titles,id',
    //                 "prof_role_type_item_id"  => "exists:pro_role_type_items,id",
    //             );
    //             $validator = \Validator::make($requested_data, $rules, [
    //                 'prof_role_type_item_id.*.exists' => 'The selected professional role type item id is invalid.',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }
    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             $user_professional_role_record = $this->UserProRolteItemObj->getUserProRoleTypeItem([
    //                 'user_id' => $requested_data['user_id'],
    //             ]);

    //             if($user_professional_role_record){
    //                 $this->UserProRolteItemObj->deleteUserProRoleTypeItem(0,['user_id' => $requested_data['user_id']]);
    //             }

    //             if (isset($requested_data['prof_role_type_item_id'])) {
    //                 for ($i = 0; $i < count($requested_data['prof_role_type_item_id']); $i++) {
    //                     $update_data = array();
    //                     $update_data['prof_role_type_id'] = $requested_data['prof_role_type_id'];
    //                     $update_data['general_title_id'] = $requested_data['prof_info_id'];
    //                     $update_data['user_id'] = $requested_data['user_id'];
    //                     $update_data['prof_role_type_item_id'] = $requested_data['prof_role_type_item_id'][$i];
    //                     $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);
    //                 }
    //             }
    //         }

    //         // Choose Goals
    //         else if ($requested_data['step'] == 6) {
    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //                 "goal_item_id" => "required|exists:goal_items,id",
    //             );

    //             $validator = \Validator::make($requested_data, $rules,[
    //                 'goal_item_id.*.exists' => 'The selected goal item id is invalid.',
    //                 'goal_item_id.required' => 'The selected goal item id is invalid.',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }
    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             $user_goals_record = $this->UserGoalItemObj->getUserGoalItem([
    //                 'user_id' => $requested_data['user_id'],
    //             ]);

    //             if($user_goals_record){
    //                 $this->UserGoalItemObj->deleteUserGoalItem(0,['user_id' => $requested_data['user_id']]);
    //             }

    //             if (isset($requested_data['goal_item_id'])) {
    //                 foreach ($requested_data['goal_item_id'] as $key => $goal_item_value) {
    //                     $posted_goal_items = array();
    //                     $posted_goal_items['user_id'] = $requested_data['user_id'];
    //                     $posted_goal_items['goal_item_id'] = $goal_item_value;
    //                     $this->UserGoalItemObj->saveUpdateUserGoalItem($posted_goal_items);
    //                 }
    //             }

    //             // $this->UserGoalItemObj->saveUpdateUserGoalItem([
    //             //     'user_id' =>  $requested_data['user_id'],
    //             //     'goal_item_id' => $requested_data['goal_item_id'],
    //             // ]);
    //         }

    //         // Educational Information
    //         else if ($requested_data['step'] == 7) {

    //             $rules = array(

    //                 'user_id' => 'required|exists:users,id',
    //                 'education_info_id' => 'required|exists:general_titles,id',
    //                 'university_school_name' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
    //                 'degree_discipline' => 'required|regex:/^[a-zA-Z0-9()\/\-\ ]*$/u',
    //                 // 'university_school_name' => 'required|regex:/^[a-zA-Z ()-]+$/u',
    //                 //'degree_discipline' => 'required|regex:/^[a-zA-Z ()-]+$/u'
    //                 // 'university_school_name' => 'required||regex:/^[a-zA-Z ]+$/u',
    //                 // 'degree_discipline' => 'required||regex:/^[a-zA-Z ]*(?:[\/\-\(\)][a-zA-Z ]*)+$/u'
    //             );
    //             $validator = \Validator::make($requested_data, $rules, [
    //                 'university_school_name.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
	// 	            'degree_discipline.regex' => 'Only letters and can only one of each special character ( - / ) are allowed',
    //                // 'university_school_name.regex' => 'University school name format not correct kindly Only leters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
    //                // 'degree_discipline.regex' => 'Degree discipline format not correct kindly Only leters and numbers should be allowed Can include only one of each of these characters ‘ / - ( ) Special characters only allowed if already contain leters Characters >= 4',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }
    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             $user_educational_record = $this->UserEducationalInfoObj->getUserEducationalInformation([
    //                 'user_id' => $requested_data['user_id'],
    //             ]);

    //             $this->dummy_enteries($requested_data['university_school_name'],7);
    //             $this->dummy_enteries($requested_data['degree_discipline'],8);
    //             if($user_educational_record){
    //                 $this->UserEducationalInfoObj->deleteUserEducationalInformation(0,['user_id' => $requested_data['user_id']]);
    //             }

    //             $this->UserEducationalInfoObj->saveUpdateUserEducationalInformation([
    //                 'user_id' =>  $requested_data['user_id'],
    //                 'general_title_id' => $requested_data['education_info_id'],
    //                 'university_school_name' => $requested_data['university_school_name'],
    //                 'degree_discipline' => $requested_data['degree_discipline']
    //             ]);
    //         }

    //         // User Industry Vertical
    //         else if ($requested_data['step'] == 8) {

    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //                 'industry_id' => 'required|exists:general_titles,id',
    //                 "industry_vertical_item_id"  => "exists:industry_vertical_items,id",
    //             );
    //             $validator = \Validator::make($requested_data, $rules, [
    //                 'industry_vertical_item_id.*.exists' => 'The selected industry vertical item id is invalid.',
    //                 'industry_id.required' => 'The industry id is required.',
    //                 'industry_id.*.exists' => 'The selected industry id is invalid.',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }
    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //                 'user_id' => $requested_data['user_id'],
    //                 'intrested_vertical' => 'Industry',
    //             ]);

    //             if($user_detail_record['same_as_industry'] == 'No'){
    //                 if($user_industry_vertical_record){
    //                     $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Industry']);
    //                 }
    //             }
    //             else if($user_detail_record['same_as_industry'] == 'Yes'){
    //                 $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id']]);
    //             }


    //             if (isset($requested_data['industry_vertical_item_id'])) {
    //                 foreach ($requested_data['industry_vertical_item_id'] as $key => $industry_vertical_item_value) {

    //                     $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
    //                         'id' => $industry_vertical_item_value,
    //                         'detail' => true
    //                     ]);
    //                     if ($industry_list) {
    //                         $industry_data = array();
    //                         $industry_data['user_id'] = $requested_data['user_id'];
    //                         $industry_data['intrested_vertical'] = 'Industry';
    //                         $industry_data['general_title_id'] = $industry_list['general_title_id'];
    //                         $industry_data['industry_vertical_item_id'] = $industry_vertical_item_value;
    //                         $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
    //                     }
    //                 }
    //             }
    //             if (isset($requested_data['industry_id'])) {
    //                 foreach ($requested_data['industry_id'] as $key => $industry_vertical_item_value) {

    //                     $industry_list = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //                         'user_id' => $requested_data['user_id'],
    //                         'general_title_id' => $industry_vertical_item_value,
    //                         'detail' => true
    //                     ]);

    //                     // $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
    //                     //     'general_title_id' => $industry_vertical_item_value,
    //                     //     'detail' => true
    //                     // ]);
    //                     if (!$industry_list) {
    //                         $industry_data = array();
    //                         $industry_data['user_id'] = $requested_data['user_id'];
    //                         $industry_data['general_title_id'] = $industry_vertical_item_value;
    //                         $industry_data['intrested_vertical'] = 'Industry';

    //                         $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
    //                     }
    //                 }
    //             }
    //         }

    //         // User Interested Vertical
    //         else if ($requested_data['step'] == 9) {

    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //             );
    //             $validator = \Validator::make($requested_data, $rules);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];


    //             if (isset($requested_data['same_industry_vertical']) && $requested_data['same_industry_vertical']== 1) {

    //                 $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //                     'user_id' => $requested_data['user_id'],
    //                     'intrested_vertical' => 'Interest',
    //                 ]);

    //                 if($user_industry_vertical_record){
    //                     $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Interest']);
    //                 }

    //                 $posted_data['same_as_industry'] = 'Yes';

    //                 $user_industry_lis = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //                     'user_id' => $requested_data['user_id']
    //                 ]);

    //                 if ($user_industry_lis) {
    //                     foreach ($user_industry_lis as $key => $user_industry_value) {
    //                         $industry_data = array();
    //                         $industry_data['user_id'] = $user_industry_value['user_id'];
    //                         $industry_data['general_title_id'] = $user_industry_value['general_title_id'];
    //                         $industry_data['industry_vertical_item_id'] = $user_industry_value['industry_vertical_item_id'];
    //                         $industry_data['intrested_vertical'] = 'Interest';
    //                         $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
    //                     }
    //                 }
    //             } else {

    //                 $rules = array(
    //                     'intrest_id' => 'required|exists:general_titles,id',
    //                     "interested_vertical_item_id"  => "exists:industry_vertical_items,id",
    //                 );
    //                 $validator = \Validator::make($requested_data, $rules, [
    //                     'interested_vertical_item_id.*.exists' => 'The selected intrested vertical item id is invalid.',
    //                     'intrest_id.required' => 'The Intrest id is required.',
    //                     'intrest_id.*.exists' => 'The selected Intrest id is invalid.',
    //                 ]);
    //                 if ($validator->fails()) {
    //                     return $this->sendError($validator->errors()->first(), $validator->messages());
    //                 }

    //                 $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //                     'user_id' => $requested_data['user_id'],
    //                     'intrested_vertical' => 'Interest',
    //                 ]);
    //                 if($user_industry_vertical_record){
    //                     $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['user_id'], 'intrested_vertical' => 'Interest']);
    //                 }

    //                 $posted_data['same_as_industry'] = 'No';

    //                 if (isset($requested_data['interested_vertical_item_id'])) {


    //                     foreach ($requested_data['interested_vertical_item_id'] as $key => $industry_vertical_item_value) {

    //                         $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
    //                             'id' => $industry_vertical_item_value,
    //                             'detail' => true
    //                         ]);
    //                         if ($industry_list) {
    //                             $interest_data = array();
    //                             $interest_data['user_id'] = $requested_data['user_id'];
    //                             $interest_data['general_title_id'] = $industry_list['general_title_id'];
    //                             $interest_data['industry_vertical_item_id'] = $industry_vertical_item_value;
    //                             $interest_data['intrested_vertical'] = 'Interest';
    //                             $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
    //                         }
    //                     }
    //                 }
    //                 if (isset($requested_data['intrest_id'])) {

    //                     foreach ($requested_data['intrest_id'] as $key => $industry_vertical_item_value) {

    //                         $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
    //                             'general_title_id' => $industry_vertical_item_value,
    //                             'detail' => true
    //                         ]);
    //                         if (!$industry_list) {
    //                             $interest_data = array();
    //                             $interest_data['user_id'] = $requested_data['user_id'];
    //                             $interest_data['general_title_id'] = $industry_vertical_item_value;
    //                             $interest_data['intrested_vertical'] = 'Interest';

    //                             $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // Specialty And Skills
    //         else if ($requested_data['step'] == 10) {

    //             if(isset($requested_data['specialty_skill_id'])){
    //                 $requested_data['industry_vertical_item_id'] = $requested_data['specialty_skill_id'];
    //             }
    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
	// 	    'industry_vertical_item_id' => 'required',
    //                 //'industry_vertical_item_id' => 'required|exists:industry_vertical_items,id'
    //                 // 'industry_vertical_item_id' => 'required|exists:user_industy_vertical_items,industry_vertical_item_id'
    //             );
    //             $validator = \Validator::make($requested_data, $rules,[
    //                 'industry_vertical_item_id.exists' => 'The selected specialty skill is invalid.',
    //                 'industry_vertical_item_id.required' => 'The specialty skill is required.',
    //             ]);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             $user_specialty_record = $this->UserSpecialtyObj->getUserSpecialty([
    //                 'user_id' => $requested_data['user_id'],
    //             ]);
    //             // $user_industry_vertical_item_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
    //             //     'user_id' => $requested_data['user_id'],
    //             // ]);


    //             if($user_specialty_record){
    //                 $this->UserSpecialtyObj->deleteUserSpecialty(0,['user_id' => $requested_data['user_id']]);
    //             }

    //             if (isset($requested_data['industry_vertical_item_id'])) {
    //                 for ($i = 0; $i < count($requested_data['industry_vertical_item_id']); $i++) {
    //                     $update_data = array();
    //                     $update_data['user_id'] = $requested_data['user_id'];
    //                     $update_data['industry_vertical_item_id'] = $requested_data['industry_vertical_item_id'][$i];
	// 		$check = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
	// 				'id' => $requested_data['industry_vertical_item_id'][$i],
	// 				'detail' =>	true
	// 				]);
	// 		if($check){
    //             	        $this->UserSpecialtyObj->saveUpdateUserSpecialty($update_data);
	// 	            }
    //     }
    //             }
    //         }


    //         // upload user Profile Image
    //         else if ($requested_data['step'] == 11) {

    //             $rules = array(
    //                 'user_id' => 'required|exists:users,id',
    //                 'profile_image' => 'required|mimes:jpeg,png,jpg|max:2048',
    //             );
    //             $validator = \Validator::make($requested_data, $rules);
    //             if ($validator->fails()) {
    //                 return $this->sendError($validator->errors()->first(), $validator->messages());
    //             }

    //             $posted_data['update_id'] = $requested_data['user_id'];

    //             if ($request->file('profile_image')) {
    //                 $extension = $request->profile_image->getClientOriginalExtension();
    //                 if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {

    //                     // if (!empty(\Auth::user()->profile_image) && \Auth::user()->role != 1) {
    //                     //     $url = $base_url.'/'.\Auth::user()->profile_image;
    //                     //     if (file_exists($url)) {
    //                     //         unlink($url);
    //                     //     }
    //                     // }

    //                     // $file_name = time().'_'.$request->profile_image->getClientOriginalName();
    //                     $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

    //                     $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
    //                     $posted_data['profile_image'] = 'storage/profile_image/' . $file_name;
    //                 } else {
    //                     $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
    //                     return $this->sendError($error_message['error'], $error_message);
    //                 }
    //             }
    //         }

    //         if (count($posted_data) > 0) {

    //             // $validator = \Validator::make($requested_data, $rules);
    //             // if ($validator->fails()) {
    //             //     return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
    //             // } else {
    //                 $user = $this->UserObj->saveUpdateUser($posted_data);
    //                 if ($requested_data['step'] == 1) {
    //                     $user = $this->UserObj->getUser([
    //                         'phone_number' => $user['phone_number'],
    //                         'detail' => true
    //                     ]);
    //                     \Auth::login($user);
    //                     $user['token'] =  $user->createToken('MyApp')->accessToken;
    //                 }
    //                 return $this->sendResponse($user, 'User information added successfully.');
    //             // }
    //         } else {
    //             $error_message['error'] = 'Please submit user data.';
    //             return $this->sendError($error_message['error'], $error_message);
    //         }
    //     }
    //     $error_message['error'] = 'Please add step value.';
    //     return $this->sendError($error_message['error'], $error_message);
    // }

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

    public function profile_update(Request $request)
    {
        $posted_data = array();
        $requested_data = $request->all();
        $requested_data['update_id'] = \Auth::user()->id;
        $posted_data['update_id'] = \Auth::user()->id;


        if(isset($requested_data['update_id'])){
            $user_detail_record = $this->UserObj->getUser([
                'id' => $requested_data['update_id'],
                'detail'=>true
            ]);
        }

        if (isset($requested_data['first_name'])) {
            $posted_data['first_name'] = $requested_data['first_name'];
        }
        if (isset($requested_data['last_name'])) {
            $posted_data['last_name'] = $requested_data['last_name'];
        }
        if (isset($requested_data['first_name']) && isset($requested_data['last_name'])) {
            $posted_data['user_name'] = strtolower($requested_data['first_name']) . '' . strtolower($requested_data['last_name']) . '_' . mt_rand(1, 999);
        }
        if (isset($requested_data['other_name'])) {
            $posted_data['other_name'] = $requested_data['other_name'];
        }
        if (isset($requested_data['about_me'])) {
            $posted_data['about_me'] = $requested_data['about_me'];
        }
        if (isset($requested_data['dob'])) {
            $posted_data['dob'] = $requested_data['dob'];
        }
        if (isset($requested_data['gender'])) {
            $posted_data['gender'] = $requested_data['gender'];
        }
        if (isset($requested_data['about_us'])) {
            $posted_data['about_us'] = $requested_data['about_us'];
        }

        // User goals
        if (isset($requested_data['goal_item_id'])) {
            $rules = array(
                "goal_item_id" => "required|exists:goal_items,id",
            );

            $validator = \Validator::make($requested_data, $rules,[
                'goal_item_id.*.exists' => 'The selected goal item id is invalid.',
                'goal_item_id.required' => 'The selected goal item id is invalid.',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->messages());
            }

            $user_goals_record = $this->UserGoalItemObj->getUserGoalItem([
                'user_id' => $requested_data['update_id'],
            ]);

            if($user_goals_record){
                $this->UserGoalItemObj->deleteUserGoalItem(0,['user_id' => $requested_data['update_id']]);
            }

            if (isset($requested_data['goal_item_id'])) {
                foreach ($requested_data['goal_item_id'] as $key => $goal_item_value) {
                    $posted_goal_items = array();
                    $posted_goal_items['user_id'] = $requested_data['update_id'];
                    $posted_goal_items['goal_item_id'] = $goal_item_value;
                    $this->UserGoalItemObj->saveUpdateUserGoalItem($posted_goal_items);
                }
            }
        }

        // Professional roles
        if (isset($requested_data['prof_info_id'])) {
            $rules = array(
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

            $user_professional_role_record = $this->UserProRolteItemObj->getUserProRoleTypeItem([
                'user_id' => $requested_data['update_id'],
            ]);

            if($user_professional_role_record){
                $this->UserProRolteItemObj->deleteUserProRoleTypeItem(0,['user_id' => $requested_data['update_id']]);
            }

            // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
            $update_data = array();
            $update_data['prof_role_type_id'] = $requested_data['prof_role_type_id'];
            $update_data['general_title_id'] = $requested_data['prof_info_id'];
            $update_data['user_id'] = $requested_data['update_id'];

            // if (isset($requested_data['prof_role_type_item_id'])) {
            //     $update_data['prof_role_type_item_id'] = $requested_data['prof_role_type_item_id'];
            // }
            // $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);

            if (isset($requested_data['prof_role_type_item_id'])) {
                for ($i = 0; $i < count($requested_data['prof_role_type_item_id']); $i++) {
                $update_data['prof_role_type_item_id'] = $requested_data['prof_role_type_item_id'][$i];
                $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);
                }
            }
            else{
                $this->UserProRolteItemObj->saveUpdateUserProRoleTypeItem($update_data);
            }

        }
        // Educational info
        if (isset($requested_data['education_info_id'])) {
            $rules = array(
                'education_info_id' => 'required|exists:general_titles,id',
                'university_school_name' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                'degree_discipline' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
            );
            $validator = \Validator::make($requested_data, $rules, [
                'university_school_name.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )',
                'degree_discipline.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->messages());
            }

            $user_educational_record = $this->UserEducationalInfoObj->getUserEducationalInformation([
                'user_id' => $requested_data['update_id'],
            ]);

            if($user_educational_record){
                $this->UserEducationalInfoObj->deleteUserEducationalInformation(0,['user_id' => $requested_data['update_id']]);
            }

            $this->UserEducationalInfoObj->saveUpdateUserEducationalInformation([
                'user_id' =>  $requested_data['update_id'],
                'general_title_id' => $requested_data['education_info_id'],
                'university_school_name' => $requested_data['university_school_name'],
                'degree_discipline' => $requested_data['degree_discipline']
            ]);

        }

        // Speciality and skills
        if (isset($requested_data['specialty_skill_id'])) {
            if(isset($requested_data['specialty_skill_id'])){
                $requested_data['industry_vertical_item_id'] = $requested_data['specialty_skill_id'];
                // $posted_data['step'] = 10;
            }
            $user_specialty_record = $this->UserSpecialtyObj->getUserSpecialty([
                'user_id' => $requested_data['update_id'],
            ]);

            if($user_specialty_record){
                $this->UserSpecialtyObj->deleteUserSpecialty(0,['user_id' => $requested_data['update_id']]);
            }

            if (isset($requested_data['industry_vertical_item_id'])) {
                for ($i = 0; $i < count($requested_data['industry_vertical_item_id']); $i++) {
                    $update_data = array();
                    $update_data['user_id'] = $requested_data['update_id'];
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

        // Industry and industry vertical items
        if (isset($requested_data['industry_id'])) {
            $rules = array(
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

            $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                'user_id' => $requested_data['update_id'],
                'intrested_vertical' => 'Industry',
            ]);

            if(\Auth::user()->same_as_industry == 'No'){
                if($user_industry_vertical_record){
                    $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['update_id'], 'intrested_vertical' => 'Industry']);
                }
            }
            else if( \Auth::user()->same_as_industry == 'Yes'){
                $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['update_id']]);
            }


            if (isset($requested_data['industry_vertical_item_id'])) {
                foreach ($requested_data['industry_vertical_item_id'] as $key => $industry_vertical_item_value) {

                    $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                        'id' => $industry_vertical_item_value,
                        'detail' => true
                    ]);
                    if ($industry_list) {
                        $industry_data = array();
                        $industry_data['user_id'] = $requested_data['update_id'];
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
                        'user_id' => $requested_data['update_id'],
                        'intrested_vertical' => 'Industry',
                        'general_title_id' => $industry_vertical_item_value,
                        'detail' => true
                    ]);
                    // echo '<pre>'; print_r($industry_vertical_item_value); echo '</pre>';
                    // $industry_list = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([

                    //     'general_title_id' => $industry_vertical_item_value,
                    //     'detail' => true
                    // ]);

                    if (!$industry_list) {
                        $industry_data = array();
                        $industry_data['user_id'] = $requested_data['update_id'];
                        $industry_data['general_title_id'] = $industry_vertical_item_value;
                        $industry_data['intrested_vertical'] = 'Industry';

                        $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($industry_data);
                    }
                }
            }
        }

        // Interest and same as industry vertical items
        if (isset($requested_data['intrest_id']) || isset($requested_data['same_industry_vertical'])) {



            if (isset($requested_data['same_industry_vertical']) && $requested_data['same_industry_vertical']== 1) {

                $user_industry_vertical_record = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                    'user_id' => $requested_data['update_id'],
                    'intrested_vertical' => 'Interest',
                ]);

                if($user_industry_vertical_record){
                    $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['update_id'], 'intrested_vertical' => 'Interest']);
                }

                $posted_data['same_as_industry'] = 'Yes';

                $user_industry_lis = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                    'user_id' => $requested_data['update_id']
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
                    'user_id' => $requested_data['update_id'],
                    'intrested_vertical' => 'Interest',
                ]);

                if($user_industry_vertical_record){
                    $this->UserindustryVerticalItemObj->deleteUserIndustyVerticalItem(0,['user_id' => $requested_data['update_id'], 'intrested_vertical' => 'Interest']);
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
                            $interest_data['user_id'] = $requested_data['update_id'];
                            $interest_data['general_title_id'] = $industry_list['general_title_id'];
                            $interest_data['industry_vertical_item_id'] = $industry_vertical_item_value;
                            $interest_data['intrested_vertical'] = 'Interest';
                            $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
                        }
                    }
                }
                if (isset($requested_data['intrest_id'])) {
                    // echo '<pre>'; print_r($requested_data); echo '</pre>'; exit;
                    foreach ($requested_data['intrest_id'] as $key => $industry_vertical_item_value) {

                        // $industry_list = $this->IndustryVerticalItemObj->getIndustryVerticalItem([
                        //     'general_title_id' => $industry_vertical_item_value,
                        //     'detail' => true
                        // ]);
                        $industry_list = $this->UserindustryVerticalItemObj->getUserIndustyVerticalItem([
                            'user_id' => $requested_data['update_id'],
                            'general_title_id' => $industry_vertical_item_value,
                            'intrested_vertical' => 'Interest',
                            'detail' => true
                        ]);

                        if (!$industry_list) {
                            $interest_data = array();
                            $interest_data['user_id'] = $requested_data['update_id'];
                            $interest_data['general_title_id'] = $industry_vertical_item_value;
                            $interest_data['intrested_vertical'] = 'Interest';
                            $this->UserindustryVerticalItemObj->saveUpdateUserIndustyVerticalItem($interest_data);
                        }
                    }
                }
            }
        }

        // Company career info
        if (isset($requested_data['job_title'])) {

            $rules = array(
                'company_career_info_id' => 'required|exists:general_titles,id',
                'job_title' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
                'company_name' => 'required|regex:/^[A-Za-z]*[A-Za-z][A-Za-z0-9()\/\-\ ]*$/',
            );
            $validator = \Validator::make($requested_data, $rules, [
                'company_name.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )',
                'job_title.regex' => 'The string must start with an alphabet (letter), and it can contain letters, numbers, and only one of the special characters ( - / )'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->messages());
            }
            // $posted_data['update_id'] = $requested_data['user_id'];

            $user_career_record = $this->UserCareerStatusObj->getUserCareerStatusPosition([
                'user_id' => $requested_data['update_id'],
            ]);

            if($user_career_record){
                $this->UserCareerStatusObj->deleteUserCareerStatusPosition(0,['user_id' => $requested_data['update_id']]);
            }

            $this->UserCareerStatusObj->saveUpdateUserCareerStatusPosition([
                'user_id' => $requested_data['update_id'],
                'general_title_id' => $requested_data['company_career_info_id'],
                'company_name' => $requested_data['company_name'],
                'job_title' => $requested_data['job_title'],
            ]);
        }

        // Profile image
        if (isset($requested_data['profile_image'])) {

            $rules = array(
                'profile_image' => 'mimes:jpeg,png,jpg|max:2048',
            );
            $validator = \Validator::make($requested_data, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $validator->messages());
            }

            if ($request->file('profile_image')) {

                $base_url = public_path();
                $extension = $request->profile_image->getClientOriginalExtension();
                if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {

                    if (!empty(\Auth::user()->profile_image) && \Auth::user()->role != 1) {
                        $url = $base_url.'/'.\Auth::user()->profile_image;
                        if (file_exists($url)) {
                            unlink($url);
                        }
                    }

                    // $file_name = time().'_'.$request->profile_image->getClientOriginalName();
                    $file_name = time() . '_' . rand(1000000, 9999999) . '.' . $extension;

                    $filePath = $request->file('profile_image')->storeAs('profile_image', $file_name, 'public');
                    $posted_data['profile_image'] = 'storage/profile_image/' . $file_name;
                    $posted_data['step'] = 11;
                } else {
                    $error_message['error'] = 'Profile Image Only allowled jpg, jpeg or png image format.';
                    return $this->sendError($error_message['error'], $error_message);
                }
            }
        }

        if (count($posted_data) > 0) {
            $user = $this->UserObj->saveUpdateUser($posted_data);
            return $this->sendResponse($user, 'User information updated successfully.');
            // }
        } else {
            $error_message['error'] = 'Please submit user data.';
            return $this->sendError($error_message['error'], $error_message);
        }
    }

    public function user_info(Request $request){

        $requested_data = $request->all();
        $rules = array(
            'id' => 'required|exists:users,id',
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }



        $requested_data['detail'] = true;
        $requested_data['id'] = $request->id;
        $data =  $this->UserObj->getUser($requested_data);
        $connectPeopleList = array();
        $connectPeopleList['auth_connect_id'] = Auth::user()->id;
        $connectPeopleList['other_connect_id'] = $request->id;
        $connectPeopleList['detail'] = true;
        $data->connect_people = $this->ConnectPeopleObj->getConnectPeople($connectPeopleList);


        $recommended_ids = $this->recommended_users();
        $data->is_recommended = false;
        if(isset($recommended_ids) && in_array($data->id, $recommended_ids)){
            $data->is_recommended = true;
        }

        $data->is_bookmark = false;
        $book_mark_user_ids =  $this->bookmark_user();
        if(isset($book_mark_user_ids) && in_array($data->id, $book_mark_user_ids)){
            $data->is_bookmark = true;
        }

        $data->profile_visibility = true;

        $user_id_with_profile_visibility = $this->SettingObj->getSetting([
            'user_id' =>  $request->id,
            'profile_visibility' => 'None',
            'detail' => true
        ]);
        if (isset($user_id_with_profile_visibility)) {
            $data->profile_visibility = false;
        }


        $user_id_with_privacy_filter = $this->SettingObj->getSetting([
            'user_id' => $request->id,
            'profile_visibility' => 'Privacy Filter',
        ])->ToArray();

        $auth_id_with_privacy_filter = $this->UserObj->getUser([
            'id' => Auth::user()->id,
            'detail' => true
        ]);

        if (isset($user_id_with_privacy_filter) && isset($auth_id_with_privacy_filter)) {

            $user_profile_visibility_filter = array_column($user_id_with_privacy_filter, 'privacy_filter');

            $user_profile_visibility_decoded = [];
            foreach ($user_profile_visibility_filter as $filter) {
                $user_profile_visibility_decoded[] = json_decode($filter);
            }

            $data->profile_visibility = false;
            $auth_user_gender = user_gender($auth_id_with_privacy_filter->gender);

            $auth_user_dob = $auth_id_with_privacy_filter->dob;
            $auth_user_age = calculateAge($auth_user_dob);
            $auth_user_location = substr($auth_id_with_privacy_filter['phone_number'], 0, 3);


            $auth_user_prof = @$auth_id_with_privacy_filter['userProfRoleTypeItem']['general_title_id'];
            $auth_user_career_position = @$auth_id_with_privacy_filter['companyCareerInfo']['general_title_id'];

            foreach ($user_profile_visibility_decoded as $decoded_filter) {
                if (isset($decoded_filter->gender) && in_array($auth_user_gender, $decoded_filter->gender)) {
                    if (isset($decoded_filter->age_from) && isset($decoded_filter->age_to) && $auth_user_age >= $decoded_filter->age_from && $auth_user_age <= $decoded_filter->age_to) {
                        if (isset($decoded_filter->location) && in_array($auth_user_location, $decoded_filter->location)) {
                            if (isset($decoded_filter->professional_role) && !array_diff($decoded_filter->professional_role, (array)$auth_user_prof) && !array_diff((array)$auth_user_prof, $decoded_filter->professional_role)) {
                                if (isset($decoded_filter->career_status_position) && !array_diff($decoded_filter->career_status_position, (array)$auth_user_career_position) && !array_diff((array)$auth_user_career_position, $decoded_filter->career_status_position)) {
                                    $data->profile_visibility = true;
                                }
                            }
                        }
                    }
                }
            }


        }



//         if (isset($user_id_with_connection_filter) && count($user_id_with_connection_filter) > 0) {
//             $profile_connection_filter = array_column($user_id_with_connection_filter, 'connection_search');
//
//             $profile_connection_decord_record= [];
//             foreach ($profile_connection_filter as $key => $profile_connection) {
//                 $profile_connection_decord_record[] = json_decode($profile_connection);
//                 // echo '<pre>'; print_r($profile_connection_decord_record); echo '</pre>'; exit;
//
//                 if ($profile_connection_decord_record[$key]->gender) {
//                     $request_data['gender'] = $profile_connection_decord_record[$key]->gender;
//                 }
//                 if (isset($profile_connection_decord_record[$key]->age_from)  && isset($profile_connection_decord_record[$key]->age_to)) {
//                     $request_data['age_from'] = $profile_connection_decord_record[$key]->age_from;
//                     $request_data['age_to'] = $profile_connection_decord_record[$key]->age_to;
//                 }
//                 if (isset($profile_connection_decord_record[$key]->location)) {
//                     $request_data['location'] = $profile_connection_decord_record[$key]->location;
//                 }
//                 if (isset($profile_connection_decord_record[$key]->professional_role)) {
//                     $request_data['professional_role'] = $profile_connection_decord_record[$key]->professional_role;
//                 }
//                 if (isset($profile_connection_decord_record[$key]->career_status_position)) {
//                     $request_data['career_status_position'] = $profile_connection_decord_record[$key]->career_status_position;
//                 }
//             }
//         }
//



        return $this->sendResponse($data, 'User profile is successfully loaded.');
    }
}
