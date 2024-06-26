<?php

   /**
    *  @author  DANISH HUSSAIN <danishhussain9525@hotmail.com>
    *  @link    Author Website: https://danishhussain.w3spaces.com/
    *  @link    Author LinkedIn: https://pk.linkedin.com/in/danish-hussain-285345123
    *  @since   2020-03-01
   **/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Constants;
use Config;

class FCM_Token extends Model
{
    use HasFactory;

    protected $table = 'fcm_tokens';

    public function userDetails()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
            // ->with('role')
            // ->select(['id', 'role_id', 'name', 'email', 'profile_image']);
    }

    // public function receiverDetails()
    // {
    //     return $this->belongsTo('App\Models\User', 'receiver_id')
    //         ->with('role')
    //         ->select(['id', 'role_id', 'name', 'email', 'profile_image']);
    // }

    public static function getFCM_Tokens($posted_data = array())
    {
        $query = FCM_Token::latest();

        $query = $query->with('userDetails');
        //             ->with('receiverDetails');

        if (isset($posted_data['user_id'])) {
            $query = $query->where('fcm_tokens.user_id', $posted_data['user_id']);
        }
        if (isset($posted_data['user_id_in'])) {
            $query = $query->whereIn('fcm_tokens.user_id', $posted_data['user_id_in']);
        }
        if (isset($posted_data['device_id'])) {
            $query = $query->where('fcm_tokens.device_id', $posted_data['device_id']);
        }
        if (isset($posted_data['device_token'])) {
            $query = $query->where('fcm_tokens.device_token', $posted_data['device_token']);
        }
        if (isset($posted_data['device_type'])) {
            $query = $query->where('fcm_tokens.device_type', $posted_data['device_type']);
        }

        if (isset($posted_data['last_chat'])) {
            $posted_data['orderBy_name'] = 'id';
            $posted_data['orderBy_value'] = 'DESC';

            if(isset($posted_data['paginate'])) {
                unset($posted_data['paginate']);
            }

            $posted_data['detail'] = true;
        }

        $query->select('fcm_tokens.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        }
        else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            } else {
                $result = $query->get();
            }
        }


        return $result;
    }

    public static function sendFCM_Notification($posted_data = array())
    {
        // if ( !empty($posted_data) ) {

        //     if ( !isset($posted_data['title']) )
        //         $posted_data['title'] = 'Notification';

        //     //     $title = 'New Text';
        //     // if ( isset($posted_data['title']) && $posted_data['title'] == 'assign-job' )
        //     //     $title = 'Job Offer Accepted';
        //     // if ( isset($posted_data['title']) && $posted_data['title'] == 'new-bid' )
        //     //     $title = 'New Bid Posted';

        //     $decode_record= json_decode($posted_data['metadata']);

        //     if(!isset($posted_data['receiver_id'])){
        //         $posted_data['receiver_id'] = 0;
        //     }

        //     $decode_record->receiver_data = '';
        //     $posted_data['metadata'] = json_encode($decode_record);
        //     $unread_message_count = unread_message_count(['receiver_id'=> $posted_data['receiver_id']]);


        //     $message = array(
        //         "title"         => $posted_data['title'],
        //         "body"          => $posted_data['body'],
        //         "metadata"      => $posted_data['metadata'],
        //         'detail'        => $posted_data['details'],
        //         'badge'         =>  $unread_message_count > 0 ? $unread_message_count  : 1,
        //     );

        //     $fields	= array(
        //         'registration_ids'      => $posted_data['registration_ids'],
        //         'priority' 		        => 'high',
        //         'content-available'     => true,
        //         'contentAvailable'     => true,
        //         'data' 			        => $message,
        //         // 'apns' => [
        //         //     // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#apnsconfig
        //         //     'headers' => [
        //         //         'apns-priority' => '10',
        //         //     ],
        //         //     'payload' => [
        //         //         'aps' => [
        //         //             'alert' => [
        //         //                'title' => "Voice call made by ",
        //         //                'body' => "Please click to answer the video call",
        //         //             ],
        //         //             'mutable-content'=>1,
        //         //             'content-available'=>1,
        //         //             'badge' => 1,
        //         //             // 'sound' =>'task_cancel.caf'
        //         //         ],
        //         //     ],
        //         // ],
        //         'notification'          => $message
        //     );

        //     $headers	= array(

        //         // 'Authorization: key='.Constants::FIREBASE_SERVER_API_KEY,
        //         'Authorization: key='.Config::get('constants.FIREBASE_SERVER_API_KEY'),
        //         'Content-Type: application/json',
        //     );
        //     // echo '<pre>$fields:'; print_r($fields); echo '</pre>'; exit;

        //     #Send Reponse To FireBase Server
        //     $ch	= curl_init();

        //     curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        //     curl_setopt( $ch,CURLOPT_POST, true );
        //     curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //     curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //     curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

        //     $response = curl_exec($ch);
        //     curl_close($ch);

        //     // ob_flush();

        //     if($response === false) {
        //         $fcm_response['status'] = false;
        //         // die('Curl failed ' . curl_error());
        //     }
        //     else {
        //         $fcm_response['status'] = true;
        //     }

        //     $fcm_response['response'] = $response;
        //     return $fcm_response;

        //     /*
        //         firebase sample response_object
        //         <pre>Array
        //         (
        //             [status] => 1
        //             [response] => {
        //                 "multicast_id":3249491517268760704,
        //                 "success":0,
        //                 "failure":1,
        //                 "canonical_ids":0,
        //                 "results":[
        //                     {
        //                         "error":"MessageTooBig"
        //                     }
        //                 ]
        //             }
        //         )
        //         </pre>
        //     */
        // }
    }

    public static function saveUpdateFCM_Token($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = FCM_Token::find($posted_data['update_id']);
        } else {
            $data = new FCM_Token;
        }

        if (isset($posted_data['user_id'])) {
            $data->user_id = $posted_data['user_id'];
        }
        if (isset($posted_data['device_id'])) {
            $data->device_id = $posted_data['device_id'];
        }
        if (isset($posted_data['device_token'])) {
            $data->device_token = $posted_data['device_token'];
        }
        if (isset($posted_data['device_type'])) {
            $data->device_type = $posted_data['device_type'];
        }

        $data->save();
        return $data;
    }

    public static function deleteFCM_Token($id=0)
    {
        $data = FCM_Token::find($id);
        return $data->delete();
    }
}
