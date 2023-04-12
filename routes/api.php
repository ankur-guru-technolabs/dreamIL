<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('/login', 'AuthController@login');
	Route::post('/user_register', 'AuthController@register');
	Route::post('/user_conformation', 'AuthController@UserConformation');
	Route::post('/resend_otp', 'AuthController@resendOtp');
	Route::post('/signin', 'AuthController@signIn');
	Route::post('/social_login', 'AuthController@socialLogin');
	Route::post('forget_password', 'AuthController@forgetPassword');
	Route::post('forget_password_verification', 'AuthController@forgetPasswordVerification');
	Route::post('update_new_password', 'AuthController@updateNewPassword');
	Route::post('get_categories', 'AuthController@getCategories');
	Route::post('get_questions', 'AuthController@getQuestions');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('create_advertisements', 'AdvertisementsController@createAdvertisements');
	Route::post('get_user_advertisements', 'AdvertisementsController@getUserAdvertisements');
	Route::post('get_advertisements', 'AdvertisementsController@getAdvertisements');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('get_articles', 'ArticalController@getArticals');
	Route::get('get_article_categories', 'ArticalController@getArticalCategories');
});
Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('user_wallet_details', 'AuthController@userWalletDetails');
});


//Route::get('get_user_dashboard_data', 'App\Http\Controllers\Api\SearchController@getUserDashboardData');
Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('update_profile', 'AuthController@updateProfile');
	Route::get('get_profile', 'AuthController@getProfile');
	Route::post('email_verification', 'AuthController@emailVerification');
    Route::post('resend_otp_update_user', 'AuthController@resendOtpUpdateUser');
	Route::post('change_password', 'AuthController@changePassword');
	Route::post('get_user_dashboard_data', 'SearchController@getUserDashboardData');
	Route::post('get_questions_dashboard_data', 'SearchController@getQuestionsDashboardData');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('user_question_answer', 'SearchController@userQuestionAnswer');
	Route::post('update_user_question_answer', 'SearchController@updateUserQuestionAnswer');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('support', 'SettingController@support');
	Route::get('delete_account', 'AuthController@deleteAccount');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('user_settings', 'SettingController@userSettings');
	Route::get('get_user_settings', 'SettingController@getUserSettings');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('discover', 'SearchController@discover');
});


Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('logout', 'AuthController@logout');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('review_later_list', 'SearchController@getReviewLaterList');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('swipe_profile', 'SearchController@swipeProfile');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('purchase_plan', 'OrderController@purchasePlan');
	Route::get('get_plan_list', 'OrderController@getPlanList');
	Route::get('order_history', 'OrderController@orderHistory');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('get_who_like_me', 'SearchController@getWhoLikeMe');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('get_passion_list', 'SearchController@getPassionList');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('send_message', 'MessageController@sendMessage');
	Route::get('match_details', 'MessageController@matchDetails');
	Route::post('get_message_conversation', 'MessageController@getMessageConversation');
	Route::get('message_conversation', 'MessageController@messageConversation');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('update_user_lastseen', 'AuthController@updateUserLastseen');
});
Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('get_notification', 'SearchController@getNotifcation');
	Route::get('read_all_notification', 'SearchController@readAllNotifcation');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('get_reson', 'SearchController@getReson');
	Route::post('report_user', 'SearchController@reportUser');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
	Route::post('get_user_details', 'AuthController@getUserDetails');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
	Route::get('get_advertisement_details/{id}', 'AdvertisementsController@getAdvertisementDetails');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
	Route::any('get_article_details/{id}', 'ArticalController@getArticleDetails');
});