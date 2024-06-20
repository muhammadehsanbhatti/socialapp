<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
// use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use DB;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // use SoftDeletes;

    protected $appends = ['conect_people_count','pitches_count']
    // protected $appends = ['conect_people_status','conect_people_count','pitches_count']
    ;
    public function getGenderAttribute($value){
        if ($value == 1) {
           return $this->attributes['gender'] = 'Male';
        }
        if ($value == 2) {
            return $this->attributes['gender'] = 'Female';
        }

        if ($value == 3) {
            return $this->attributes['gender'] = 'Other';
        }
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /*
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // public function Role()
    // {
    //     return $this->belongsTo(Role::class, 'role')
    //         ->select(['id', 'name']);
    // }
    // public function getUserStatusAttribute($value)
    // {
    //     $status ='';
    //     if($value == 1){
    //         $status = 'Active';
    //     }
    //     else if($value == 2){
    //         $status = 'Block';
    //     }
    //     return $status;
    // }

    function companyCareerInfo(){
        return $this->hasOne(UserCareerStatusPosition::class, 'user_id')->with('generalTitle');
    }
    // function yourBookMarkUser(){
    //     return $this->hasMany(ConnectionBookMark::class, 'user_id_from');
    // }
    function bookMarkUser(){
        if(Auth::check()){
            return $this->hasOne(ConnectionBookMark::class, 'user_id_to')->where('user_id_from',Auth::user()->id);
        }else{
            return $this->hasOne(ConnectionBookMark::class, 'user_id_to');
        }
    }
    public function connect_user_id()
    {
        return $this->hasOne(ConnectPeople::class, 'user_id', 'id')->where('connect_user_id',Auth::user()->id);
    }

    public function getConectPeopleCountAttribute()
    {
        $ConnectPeopleObj = new \App\Models\ConnectPeople;
        $connect_people_count = 0;
        $connect_people_count = $ConnectPeopleObj->getConnectPeople([
            'auth_connect_id' => $this->id,
            'status' => 'Accept',
            'count' =>true
        ]);

        // $connect_people_count = 0;
        // if ($this->connect_people_user_id) {
        //     $data = collect($this->connect_people_user_id);
        //     $connect_people_count = $ConnectPeopleObj->getConnectPeople([
        //         'user_id' => $data['connect_user_id'],
        //         'status' => 'Accept',
        //         'count' =>true
        //     ]);
        // }
        return $connect_people_count;
    }
    public function getPitchesCountAttribute()
    {
        $pitch_count = 0;
        $pitchObj = new \App\Models\Pitch;
        $pitch_count = $pitchObj->getPitch([
            'user_id' => $this->id,
            'count' =>true
        ]);
        return $pitch_count;
    }
    // function userProfRoleTypeItems(){
    //     return $this->hasOne(UserProRoleTypeItem::class, 'user_id')->with('generalTitle','proRoleType');
    // }
    function userProfRoleTypeItem(){
        // return $this->hasOne(UserProRoleTypeItem::class, 'user_id')->with('generalTitle','proRoleType');

        // if(!\Auth::check()){
        //     return $this->hasOne(UserProRoleTypeItem::class, 'user_id')->with('professionalRoleTypeItem','generalTitle','proRoleType','selectedprofRoleTypeItem');
        // }else{
            // echo '<pre>'; print_r(\URL::current()); echo '</pre>';
            // echo '<pre>'; print_r(asset(url('api/connect_people_list'))); echo '</pre>'; exit;
            // if (\URL::current() == asset(url('api/connect_people_list'))) {

            //     return $this->hasOne(UserProRoleTypeItem::class, 'user_id')
            //     ->with('generalTitle','proRoleType');
            // }
            // else{
                return $this->hasOne(UserProRoleTypeItem::class, 'user_id')
                // ->where('user_pro_role_type_items.user_id', \Auth::user()->id)
                ->join('users','users.id','user_pro_role_type_items.user_id')
                ->select('user_pro_role_type_items.*')
                ->with('generalTitle','proRoleType','selectedprofRoleTypeItem');
            // }
        // }
        // ->with('professionalRoleTypeItem');
        // return $this->hasOne(UserProRoleTypeItem::class, 'user_id')->with('professionalRoleTypeItem','generalTitle','proRoleType');
    }



    public static function getUser($posted_data = array())
    {
        $query = User::latest()
                        // ->with('userSetting')
        ;
        // if (isset($posted_data['general_title_response'])) {
        //     $query->with(['userProfRoleTypeItems']);
        // }
        if (isset($posted_data['id'])) {
            $query = $query->where('users.id', $posted_data['id']);
        }
        if (isset($posted_data['users_in'])) {
            $query = $query->whereIn('users.id', $posted_data['users_in']);
        }
        if (isset($posted_data['connect_type_user_ids'])) {
            $query = $query->whereIn('users.id', $posted_data['connect_type_user_ids']);
        }
        if (isset($posted_data['phone_numbers_in'])) {
            $query = $query->whereIn('users.phone_number', $posted_data['phone_numbers_in']);
        }
        if (isset($posted_data['phone_numbers_like'])) {
            $phoneNumbersLike = implode("','", $posted_data['phone_numbers_like']);
            $query = $query->whereRaw("SUBSTRING(users.phone_number, -10) IN ('$phoneNumbersLike')")
                           ->distinct();
        }

        if (isset($posted_data['country_code'])) {
            $countryCodes = $posted_data['country_code'];
            $query->where(function ($query) use ($countryCodes) {
                foreach ($countryCodes as $countryCode) {
                    $query->orWhere(DB::raw("LEFT(users.phone_number, 3)"), 'like', '%' . $countryCode . '%');
                }
            });
            $query->distinct();
        }

        if (isset($posted_data['except_auth_id'])) {
            $query = $query->where('users.id','!=', $posted_data['except_auth_id']);
        }

        if (isset($posted_data['phone_numbers_not_in'])) {
            $query = $query->whereNotIn('users.phone_number', $posted_data['phone_numbers_not_in']);
        }
        if (isset($posted_data['users_not_in'])) {
            $query = $query->whereNotIn('users.id', $posted_data['users_not_in']);
        }
        if (isset($posted_data['profile_not_in_ids'])) {
            $query = $query->whereNotIn('users.id', $posted_data['profile_not_in_ids']);
        }

        // if (isset($posted_data['status'])) {
        //     $query = $query->where('users.id','!=', $posted_data['status']);
        // }
        if (isset($posted_data['email'])) {
            $query = $query->where('users.email', $posted_data['email']);
        }
        if (isset($posted_data['first_not_null'])) {
            if (isset($posted_data['or_users_in'])) {
                $user_in_data = $posted_data['or_users_in'];

                $query = $query->where(function ($query) use ($user_in_data) {
                    $query->WhereNotIn('users.id', $user_in_data);
                })->Where(function ($query) {
                    $query->whereNotNull('users.first_name')
                        // ->whereNull('users.deleted_at')
                        ;
                });

                // $query = $query->where(function ($query) use ($user_in_data) {
                //     $query->whereIn('users.id', $user_in_data);
                // })->orWhere(function ($query) {
                //     $query->whereNotNull('users.first_name')
                //         // ->whereNull('users.deleted_at')
                //         ;
                // });
            }
            else{
                $query = $query->whereNotNull('users.first_name');
            }
            // $query = $query->whereNotNull('users.first_name');
        }
        if (isset($posted_data['first_name'])) {
            $query = $query->where('users.first_name', 'like', '%' . $posted_data['first_name'] . '%');
        }
        if (isset($posted_data['last_name'])) {
            $query = $query->where('users.last_name', 'like', '%' . $posted_data['last_name'] . '%');
        }
        if (isset($posted_data['other_name'])) {
            $query = $query->where('users.other_name', 'like', '%' . $posted_data['other_name'] . '%');
        }
        if (isset($posted_data['user_name'])) {
            $query = $query->where('users.user_name', 'like', '%' . $posted_data['user_name'] . '%');
        }
        if (isset($posted_data['full_name'])) {
            $query = $query->where('users.full_name', 'like', '%' . $posted_data['full_name'] . '%');
        }
        if (isset($posted_data['roles'])) {
            $query = $query->whereHas("roles", function($qry) use ($posted_data) {
                        $qry->where("name", $posted_data['roles']);
                    });
        }
        if (isset($posted_data['name'])) {
            $str = $posted_data['name'];
            $query = $query->where(
                function ($query) use ($str) {
                    return $query
                        ->where('users.first_name', 'like', '%' . $str . '%')
                        ->orwhere('users.last_name', 'like', '%' . $str . '%');
                });
        }
        if (isset($posted_data['location'])) {
            $phoneNumbers = $posted_data['location'];
            $query = $query->where(function ($query) use ($phoneNumbers) {
                foreach ($phoneNumbers as $phoneNumber) {
                    $query->orWhere('users.phone_number', 'like', "%$phoneNumber%");
                }
            });
        }
        if (isset($posted_data['user_data'])) {
            $str = $posted_data['user_data'];
            $query = $query->where(function ($query) use ($str) {
                return $query
                    ->whereNotNull('users.first_name')
                    ->whereNotNull('users.last_name');
            });
        }


	    if (isset($posted_data['step'])) {
            $query = $query->where('users.step', $posted_data['step']);
        }
	    if (isset($posted_data['dob'])) {
            $query = $query->where('users.dob', $posted_data['dob']);
        }
	    // if (isset($posted_data['gender'])) {
        //     $query = $query->where('users.gender', $posted_data['gender']);
        // }
        if (isset($posted_data['phone_number'])) {
            $query = $query->where('users.phone_number', $posted_data['phone_number']);
        }
        if (isset($posted_data['user_status'])) {
            $query = $query->where('users.user_status', $posted_data['user_status']);
        }
        if (isset($posted_data['total_archived_messages_tmp'])) {
            $query = $query->where('users.total_archived_messages_tmp', $posted_data['total_archived_messages_tmp']);
        }
        if (isset($posted_data['total_unarchived_messages_tmp'])) {
            $query = $query->where('users.total_unarchived_messages_tmp', $posted_data['total_unarchived_messages_tmp']);
        }
        if (isset($posted_data['total_unread_messages_tmp'])) {
            $query = $query->where('users.total_unread_messages_tmp', $posted_data['total_unread_messages_tmp']);
        }
        if (isset($posted_data['request_count'])) {
            $query = $query->where('users.request_count', $posted_data['request_count']);
        }
        if (isset($posted_data['new_connection_count'])) {
            $query = $query->where('users.new_connection_count', $posted_data['new_connection_count']);
        }
        if (isset($posted_data['last_seen'])) {
            $query = $query->where('users.last_seen', $posted_data['last_seen']);
        }
        if (isset($posted_data['about_us'])) {
            $query = $query->where('users.about_us', $posted_data['about_us']);
        }
        if (isset($posted_data['time_spent'])) {
            $query = $query->where('users.time_spent', $posted_data['time_spent']);
        }
        if (isset($posted_data['theme_mode'])) {
            $query = $query->where('users.theme_mode', $posted_data['theme_mode']);
        }
        if (isset($posted_data['login_having_thirty_minutes'])) {
            $query = $query->where('users.last_seen','<=', $posted_data['login_having_thirty_minutes']);
        }
        if (isset($posted_data['comma_separated_ids'])) {
            $query = $query->selectRaw("GROUP_CONCAT(id) as ids");
            $posted_data['detail'] = true;
        }
        // if (isset($posted_data['user_ids']) && count($posted_data['user_ids'])>0) {
        if (isset($posted_data['user_ids'])) {
            $query = $query->whereIn('users.id', $posted_data['user_ids']);
        }


        if (isset($posted_data['search']) && !empty($posted_data['search'])) {
            $query = $query->where(
            function ($query) use ($posted_data) {

                $str = $posted_data['search'];
                $search1 = '';
                $search2 = '';
                $str_ary = explode(' ', $str);
                if ($str == trim($str) && strpos($str, ' ') !== false && isset($str_ary[0]) && isset($str_ary[1])) {
                    $search1 = $str_ary[0];
                    $search2 = $str_ary[1];
                }

                return $query

                    ->where('users.first_name','like', '%' .$str. '%')
                    ->orwhere('users.last_name','like', '%' .$str. '%')
                    ->orwhere('users.user_name','like', '%' .$str. '%')
                    ->orwhere(function ($query) use ($search1,$search2) {
                        if ($search2 != '' && $search1 != '') {
                            $query->where('users.first_name','like', '%' . $search1. '%');
                            $query->where('users.last_name','like', '%' .$search2. '%');
                        }
                    })
                    ;

            });
        }







        if(isset($posted_data['age_from']) && isset($posted_data['age_to'])){

            $posted_data['age_from']--;
            $posted_data['age_to']--;
            $posted_data['age_from'] = date('Y', strtotime('-'.$posted_data['age_from'].' years'));
            $posted_data['age_to'] = date('Y', strtotime('-'.$posted_data['age_to'].' years'));
            $query = $query->where(
                function ($query) use ($posted_data) {
                    return $query
                        ->where('users.dob', '<=', $posted_data['age_from'])
                        ->where('users.dob', '>=', $posted_data['age_to']);
                });

        }
	    if (isset($posted_data['gender'])) {
            $query = $query->whereIn('users.gender', $posted_data['gender']);
        }

        if (isset($posted_data['location'])) {
            $phoneNumbers = $posted_data['location'];
            $query = $query->where(function ($query) use ($phoneNumbers) {
                foreach ($phoneNumbers as $phoneNumber) {
                    $query->orWhere('users.phone_number', 'like', "%$phoneNumber%");
                }
            });
        }
        $query->select('users.*');
        // $query->select('users.*', DB::raw('count(*) as total'));

        $query->getQuery()->orders = null;
        if(isset($posted_data['search'])){
            $string = $posted_data['search'];
            $query->orderByRaw("case when `users`.`first_name` LIKE '$string%' then 0 else 1 end, `users`.`first_name`");
        } else if (isset($posted_data['orderBy_name']) && isset($posted_data['orderBy_value'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('users.id', 'DESC');
        }


        if (isset($posted_data['groupBy']) && $posted_data['groupBy']) {
            $query->groupBy($posted_data['groupBy']);
        }


        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else if (isset($posted_data['array'])) {
                $result = $query->get()->ToArray();
            } else if (isset($posted_data['get_ids'])) {
                $result = $query->pluck('id')->all();
            } else {
                $result = $query->get();
            }
        }

        if(isset($posted_data['printsql'])){
            $result = $query->toSql();
            echo '<pre>';
            print_r($result);
            print_r($posted_data);
            exit;
        }
        return $result;
    }

    public static function saveUpdateUser($posted_data = array(), $where_posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = User::find($posted_data['update_id']);
        } else {
            $data = new User;
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            $is_updated = false;
            if (isset($where_posted_data['user_status'])) {
                $is_updated = true;
                $data = $data->where('user_status', $where_posted_data['user_status']);
            }

            if($is_updated){
                return $data->update($posted_data);
            }else{
                return false;
            }
        }

        if (isset($posted_data['first_name'])) {
            $data->first_name = $posted_data['first_name'];
        }
        if (isset($posted_data['last_name'])) {
            $data->last_name = $posted_data['last_name'];
        }
        if (isset($posted_data['other_name'])) {
            $data->other_name = $posted_data['other_name'];
        }
        if (isset($posted_data['user_name'])) {
            $data->user_name = $posted_data['user_name'];
        }

        if (isset($posted_data['full_name'])) {
            $data->full_name = $posted_data['full_name'];
        }
        if (isset($posted_data['email'])) {
            $data->email = $posted_data['email'];
        }
        if (isset($posted_data['password'])) {
            $data->password = Hash::make($posted_data['password']);
        }
        if (isset($posted_data['user_type'])) {
            $data->user_type = $posted_data['user_type'];
        }
        if (isset($posted_data['step'])) {
            $data->step = $posted_data['step'];
        }
        if (isset($posted_data['dob'])) {
            $data->dob = $posted_data['dob'];
        }
        if (isset($posted_data['gender'])) {
            $data->gender = $posted_data['gender'];
        }
        if (isset($posted_data['location'])) {
            $data->location = $posted_data['location'];
        }
        if (isset($posted_data['country'])) {
            $data->country = $posted_data['country'];
        }
        if (isset($posted_data['city'])) {
            $data->city = $posted_data['city'];
        }
        if (isset($posted_data['state'])) {
            $data->state = $posted_data['state'];
        }
        if (isset($posted_data['latitude'])) {
            $data->latitude = $posted_data['latitude'];
        }
        if (isset($posted_data['longitude'])) {
            $data->longitude = $posted_data['longitude'];
        }
        if (isset($posted_data['profile_image'])) {
            $data->profile_image = $posted_data['profile_image'];
        }
        if (isset($posted_data['about_us'])) {
            $data->about_us = $posted_data['about_us'];
        }
        if (isset($posted_data['phone_number'])) {
            $data->phone_number = $posted_data['phone_number'];
        }
        if (isset($posted_data['user_status'])) {
            $data->user_status = $posted_data['user_status'];
        }
        if (isset($posted_data['request_count'])) {
            $data->request_count = $posted_data['request_count'];
        }
        if (isset($posted_data['new_connection_count'])) {
            $data->new_connection_count = $posted_data['new_connection_count'];
        }
        if (isset($posted_data['total_archived_messages_tmp'])) {
            $data->total_archived_messages_tmp = $posted_data['total_archived_messages_tmp'];
        }
        if (isset($posted_data['total_unarchived_messages_tmp'])) {
            $data->total_unarchived_messages_tmp = $posted_data['total_unarchived_messages_tmp'];
        }
        if (isset($posted_data['total_unread_messages_tmp'])) {
            $data->total_unread_messages_tmp = $posted_data['total_unread_messages_tmp'];
        }
        if (isset($posted_data['register_from'])) {
            $data->register_from = $posted_data['register_from'];
        }
        if (isset($posted_data['last_seen'])) {
            $data->last_seen = $posted_data['last_seen'];
        }
        if (isset($posted_data['email_verified_at'])) {
            $data->email_verified_at = $posted_data['email_verified_at'];
        }
        if (isset($posted_data['time_spent'])) {
            $data->time_spent = $posted_data['time_spent'];
        }
        if (isset($posted_data['theme_mode'])) {
            $data->theme_mode = $posted_data['theme_mode'];
        }
        if (isset($posted_data['remember_token'])) {
            $data->remember_token = $posted_data['remember_token'];
        }

        if (isset($posted_data['same_as_industry'])) {
            $data->same_as_industry = $posted_data['same_as_industry'];
        }
        $data->save();

        $data = User::getUser([
            'detail' => true,
            'id' => $data->id
        ]);
        return $data;
    }

    public function deleteUser($id = 0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = User::find($id);
        }else{
            $data = User::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){
            if (isset($where_posted_data['user_status'])) {
                $is_deleted = true;
                $data = $data->where('user_status', $where_posted_data['user_status']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
