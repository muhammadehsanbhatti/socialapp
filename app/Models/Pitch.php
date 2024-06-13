<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
    use HasFactory;
    // protected $appends = ['reply_count'];

    // public function getReplyCountAttribute()
    // {
    //     $PitcheReplyObj = new \App\Models\PitcheReply;
    //     $get_reply = 0;
    //     $get_reply = $PitcheReplyObj->getPitcheReply([
    //         'pitch_id' => $this->id,
    //         'count' =>true
    //     ]);
    //     return $get_reply;
    // }

    protected $appends = ['connection_people'];
    public function documentAsset()
    {
        return $this->hasMany(DocumentAsset::class, 'pitch_id');
    }

    public function pitchTag()
    {
        return $this->hasMany(PitchTag::class, 'pitch_id')->with('tagDetail');
    }

    public function pitchReply()
    {
        return $this->hasMany(PitcheReply::class, 'pitch_id')->with('pitchReplyDetail');
    }
    public function pitchShare()
    {
        return $this->hasMany(PitchShare::class, 'pitch_id');
    }
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user_id')->with('companyCareerInfo');
    }

    // public function userBookMarkDetail()
    // {
    //     return $this->hasMany(PitchesBookMark::class, 'pitch_id');
    // }
    public function userBookMarkDetail()
    {
        return $this->hasMany(PitchesBookMark::class, 'pitch_id')->where('user_id', \Auth::user()->id);
    }
    public function pitchSeenRecord()
    {
        return $this->hasMany(UserSeenPitch::class,'pitch_id');
    }

    public function connectPeopleUser()
    {
        return $this->hasOne(ConnectPeople::class, 'user_id', 'user_id')->where('connect_user_id', \Auth::user()->id);
    }
    public function connectPeopleConnectUser()
    {
        return $this->hasOne(ConnectPeople::class, 'connect_user_id', 'user_id')->where('user_id', \Auth::user()->id);
    }
    public function getConnectionPeopleAttribute($value)
    {
        $connectPeopleUser = $this->connectPeopleUser;
        $connectPeopleConnectUser = $this->connectPeopleConnectUser;
        unset($this->connectPeopleUser);
        unset($this->connectPeopleConnectUser);
        if($connectPeopleUser){
            return $connectPeopleUser;
        }else{
            return $connectPeopleConnectUser;
        }

    }

    public static function getPitch($posted_data = array())
    {
        $query = Pitch::latest()
                    ->with('documentAsset')
                    ->with('pitchTag')
                    ->with('userBookMarkDetail')
                    ->with('pitchReply')
                    ->with('pitchShare')
                    ->with('userDetail')
                    ->with('pitchSeenRecord');
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('pitches.id', $posted_data['id']);
        }
        if (isset($posted_data['ids_in'])) {
            $query = $query->whereIn('pitches.id', $posted_data['ids_in']);
        }
        if (isset($posted_data['user_id'])) {
            $query = $query->where('pitches.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['auth_id_not_in'])) {
            $query = $query->whereNotIn('pitches.user_id', $posted_data['auth_id_not_in']);
        }
        if (isset($posted_data['connect_people'])) {
            $query = $query->where('pitches.user_id', \Auth::user()->id);
        }
        if (isset($posted_data['user_id_in']) && !isset($posted_data['check_everyone_status'])) {
            $query = $query->whereIn('pitches.user_id', $posted_data['user_id_in']);
        }
        if (isset($posted_data['user_id_not'])) {
            $query = $query->where('pitches.user_id', '!=', $posted_data['user_id_not']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('pitches.title', $posted_data['title']);
        }
        if (isset($posted_data['description'])) {
            $query = $query->where('pitches.description', $posted_data['description']);
        }
        if (isset($posted_data['shares_count'])) {
            $query = $query->where('pitches.shares_count', $posted_data['shares_count']);
        }
        if (isset($posted_data['bookmark_count'])) {
            $query = $query->where('pitches.bookmark_count', $posted_data['bookmark_count']);
        }
        if (isset($posted_data['replies_count'])) {
            $query = $query->where('pitches.replies_count', $posted_data['replies_count']);
        }
        if (isset($posted_data['connection_status'])) {
            $query = $query->where('pitches.connection_status', $posted_data['connection_status']);
        }
        if (isset($posted_data['connection_search'])) {
            $query = $query->where('pitches.connection_search', $posted_data['connection_search']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('pitches.status', $posted_data['status']);
        }
        if (isset($posted_data['pitch_search'])) {
            $searchTerm = $posted_data['pitch_search'];
            if ($posted_data['search_filter'] == 'Pitch') {
                $query = $query->Where(function ($query) use ($searchTerm) {
                    // $query = $query->where(function ($query) use ($searchTerm) {
                        $query->where('pitches.title', 'like', '%' . $searchTerm . '%')
                                ->orwhere('pitches.description' , 'like', '%' . $searchTerm . '%')
                                ;
                    // });
                });
            }
            if ($posted_data['search_filter'] == 'All') {
                $query = $query->where(function ($query) use ($searchTerm) {
                    $query->where('users.first_name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('users.last_name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('pitches.title', 'like', '%' . $searchTerm . '%')
                          ->orWhere('pitches.description', 'like', '%' . $searchTerm . '%');
                });
                $query->join('users', 'pitches.user_id', '=', 'users.id');
            }
            if ($posted_data['search_filter'] == 'People') {
                $query = $query->where(function ($query) use ($searchTerm) {
                    $query->where('users.first_name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('users.last_name', 'like', '%' . $searchTerm . '%');
                });
                $query->join('users', 'pitches.user_id', '=', 'users.id');
            }
        }
        if (isset($posted_data['check_everyone_status'])) {

            // $query = $query->where(function ($query) use ($posted_data) {
                // // $query->whereIn('pitches.user_id', $posted_data['user_id_in'])
                // //       ->where(function ($query) use ($posted_data) {
                //           $query->where('pitches.connection_status', 'Everyone')
                //                 ->orWhereIn('pitches.user_id', $posted_data['user_id_in']);
                //     //   });
                    $query = $query->where(function ($query) use ($posted_data) {
                        $query->where('pitches.connection_status', 'Everyone')
                              ->orWhereIn('pitches.user_id', $posted_data['user_id_in']);
                    });


            // });

            // select `pitches`.* from `pitches` where `pitches`.`user_id` not in (?, ?, ?, ?) and `pitches`.`user_id` in (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) order by `pitches`.`id` desc limit 7 offset 0




            // select `pitches`.* from `pitches` where (`pitches`.`user_id` in (12,13,11,33,31,30,17,15,23,28,27,26) AND (`pitches`.`connection_status` = 'Everyone' OR `pitches`.`user_id` in (12,13,11,33,31,30,17,15,23,28,27,26) )) order by `pitches`.`id` desc limit 10 offset 0;
        }
        if (isset($posted_data['file_attachement'])) {
            if ($posted_data['file_attachement'] == 'No file') {
                $query->leftJoin('document_assets', 'document_assets.pitch_id', '=', 'pitches.id')
                      ->whereNull('document_assets.id')
                      ->distinct();
            } elseif ($posted_data['file_attachement'] == 'With file') {
                $query->join('document_assets', 'document_assets.pitch_id', '=', 'pitches.id')
                      ->distinct();
            }
        }
        if (isset($posted_data['phone_numbers_like'])) {
            $phoneNumbersLike = implode("','", $posted_data['phone_numbers_like']);
            $query = $query->whereRaw("SUBSTRING(users.phone_number, -10) IN ('$phoneNumbersLike')")
                           ->distinct();
        }
        if (isset($posted_data['pitches_have_seen'])) {
            if ($posted_data['pitches_have_seen'] == 'Yes') {
                $query->leftJoin('user_seen_pitches', 'pitches.id', '=', 'user_seen_pitches.pitch_id')
                      ->whereNotNull('user_seen_pitches.id');
            } elseif ($posted_data['pitches_have_seen'] == 'No') {
                $query->leftJoin('user_seen_pitches', 'pitches.id', '=', 'user_seen_pitches.pitch_id')
                      ->whereNull('user_seen_pitches.id');
            }
        }

        // if (isset($posted_data['phone_numbers'])) {
        //     $phoneNumbersLike = implode("','", $posted_data['phone_numbers']);
        //     $query->leftjoin('users', 'pitches.user_id', '=', 'users.id');
        //     $query = $query->whereRaw("SUBSTRING(users.phone_number, -10) IN ('$phoneNumbersLike')")
        //                        ->distinct();
        // }




        $query->select('pitches.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
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

    public function saveUpdatePitch($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = Pitch::find($posted_data['update_id']);
        } else {
            $data = new Pitch;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        if (isset($posted_data['description'])) {
            $data->description = $posted_data['description'];
        }
        if (isset($posted_data['connection_status'])) {
            $data->connection_status = $posted_data['connection_status'];
        }
        if (isset($posted_data['replies_count'])) {
            $data->replies_count = $posted_data['replies_count'];
        }
        if (isset($posted_data['shares_count'])) {
            $data->shares_count = $posted_data['shares_count'];
        }
        if (isset($posted_data['bookmark_count'])) {
            $data->bookmark_count = $posted_data['bookmark_count'];
        }
        if (isset($posted_data['connection_search'])) {
            $data->connection_search = $posted_data['connection_search'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        $data->save();
        $data = Pitch::getPitch([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deletePitch($id=0, $where_posted_data = array())
    {
        $is_deleted = false;
        if($id>0){
            $is_deleted = true;
            $data = Pitch::find($id);
        }else{
            $data = Pitch::latest();
        }

        if(isset($where_posted_data) && count($where_posted_data)>0){

            if (isset($where_posted_data['id'])) {
                $is_deleted = true;
                $data = $data->where('id', $where_posted_data['id']);
            }
        }

        if($is_deleted){
            return $data->delete();
        }else{
            return false;
        }
    }
}
