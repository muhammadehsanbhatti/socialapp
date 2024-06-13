<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\FCM_TokenController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ConnectionBookMarkController;
use App\Http\Controllers\Api\PitchController;
use App\Http\Controllers\Api\PitchBookMarkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('login', [RegisterController::class, 'login_user']);
Route::post('register', [RegisterController::class, 'register_user']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
Route::post('user_validation', [RegisterController::class, 'user_validation']);
Route::post('change_password', [RegisterController::class, 'changePassword']);
Route::get('verify-email/{token?}', [RegisterController::class, 'verifyUserEmail'])->name('email_verify');



/*
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
*/

Route::post('/dummy_records', [RegisterController::class, 'enter_records']);
Route::middleware('auth:api')->group( function () {

    // Route::post('logout', [RegisterController::class, 'logoutProfile']);

    Route::get('get_profile', [RegisterController::class, 'get_profile']);
    Route::get('/connect_people_list', [RegisterController::class, 'connect_people_list']);
    Route::post('/connect_people_list', [RegisterController::class, 'connect_people_list']);
    Route::post('/contact_user_list', [RegisterController::class, 'contact_user_list']);
    Route::post('/check_connect_people', [RegisterController::class, 'check_connect_people']);
    Route::get('/connect_list', [RegisterController::class, 'connect_list']);
    Route::post('/connect_people', [RegisterController::class, 'connects_people']);
    Route::post('/update_connect_people/{id}', [RegisterController::class, 'update_connects_people']);
    Route::post('/delete_connection/{id}', [RegisterController::class, 'delete_connection']);
    Route::get('/education_information', [RegisterController::class, 'educational_info']);
    Route::get('/degree_information', [RegisterController::class, 'degree_info']);
    Route::post('/add_general_title', [RegisterController::class, 'create_general_title']);
    Route::get('/general_titles', [RegisterController::class, 'general_titles']);
    Route::get('/goals', [RegisterController::class, 'goals']);


    // Messages
    Route::post('/message_read', [MessageController::class, 'message_read']);
    Route::post('/forward_message', [MessageController::class, 'forward_message']);
    Route::post('/send_dynamic_link', [MessageController::class, 'send_dynamic_link']);
    Route::post('/forward_message_to', [MessageController::class, 'forward_message_to']);
    Route::post('/message_status', [MessageController::class, 'user_message_status']);
    Route::post('/delete_conversation', [MessageController::class, 'delete_user_converstaion']);
    Route::post('/delete_messsages', [MessageController::class, 'delete_messages']);
    Route::get('/specific_general_title_list', [MessageController::class, 'specific_general_title']);
    Route::post('/message_list', [MessageController::class, 'message_list']);
    Route::resource('message', MessageController::class);

    // Group
    Route::post('/group_message', [GroupController::class, 'group_message']);
    Route::post('group/{id}', [GroupController::class, 'update']);
    Route::post('destroy_group_member/{id}', [GroupController::class, 'destroy_group_member']);
    Route::resource('group', GroupController::class);

    // Setting
    Route::get('/terms_privacy', [SettingController::class, 'terms_privacy']);
    Route::get('/block_users', [SettingController::class, 'block_users']);
    Route::get('/seed_store', [SettingController::class, 'get_seed_store']);
    Route::post('/terminate_account', [SettingController::class, 'terminate_account']);
    Route::post('account_setting/{id}',  [SettingController::class, 'update']);
    Route::resource('account_setting', SettingController::class);

    // Pitch
    Route::get('get_pitch_reply',  [PitchController::class, 'get_pitch_reply']);
    Route::get('get_general_tag',  [PitchController::class, 'get_general_tag']);
    Route::get('pitches_record',  [PitchController::class, 'pitches_record']);
    Route::get('pitch_shares_detail',  [PitchController::class, 'pitch_shares_detail']);
    Route::post('pitch_reply',  [PitchController::class, 'pitch_reply']);
    Route::post('pitch_assets_store',  [PitchController::class, 'pitch_assets_store']);
    Route::post('delete_pitch_asset',  [PitchController::class, 'delete_pitch_asset']);
    Route::post('pitch_share',  [PitchController::class, 'pitch_share']);
    Route::post('user_seen_pitch',  [PitchController::class, 'user_seen_pitch']);
    Route::post('pitch_contribution',  [PitchController::class, 'pitch_contribution']);
    Route::post('pitch/{id}', [PitchController::class, 'update']);
    Route::resource('pitch', PitchController::class);

    // Pitch BookMark
    Route::resource('pitch_bookmark', PitchBookMarkController::class);

    // Connection BookMark
    Route::post('/connection_refuse', [ConnectionBookMarkController::class, 'refuse_connection']);
    Route::post('/delete_connection_refuse/{id}', [ConnectionBookMarkController::class, 'delete_refuse_connection']);
    Route::resource('connection_bookmark', ConnectionBookMarkController::class);

    // FCM Token
    Route::resource('fcm_tokens', FCM_TokenController::class);

    // Get User info
    Route::get('user_info',  [RegisterController::class, 'user_info']);
    Route::post('profile_update',  [RegisterController::class, 'profile_update']);

    // Route::post('update_profile', [RegisterController::class, 'updateProfile']);

    //resouce routes
    // Route::resource('services', ServiceController::class);
});
