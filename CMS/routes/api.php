<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/stylelist', 'API\APIController@stylelist');
Route::post('/authorization', 'API\APIController@authorization');
Route::get('/invitetofriend', 'API\APIController@invitetofriend');
Route::get('/inviteuserlist', 'API\APIController@inviteuserlist');
Route::post('/accepttofriend', 'API\APIController@accepttofriend');
Route::get('/cancelinvite', 'API\APIController@cancelinvite');
Route::get('/acceptlist', 'API\APIController@acceptlist');
Route::get('/friendlist', 'API\APIController@friendlist');
Route::get('/friendstate', 'API\APIController@friendstate');
Route::get('/getfriendcount', 'API\APIController@getfriendcount');
Route::get('/setuserstyles', 'API\APIController@setuserstyles');
Route::get('/adduserstyle', 'API\APIController@adduserstyle');
Route::get('/deleteuserstyle', 'API\APIController@deleteuserstyle');
Route::get('/setmynotification', 'API\APIController@setmynotification');
Route::get('/setfriendnotification', 'API\APIController@setfriendnotification');
Route::post('/addpost', 'API\APIController@addpost');
Route::post('/updatepost', 'API\APIController@updatepost');
Route::get('/deletepost', 'API\APIController@deletepost');
Route::get('/deletefriend', 'API\APIController@deletefriend');
Route::get('/addlike', 'API\APIController@addlike');
Route::get('/likelist', 'API\APIController@likelist');
Route::post('/addbio', 'API\APIController@addbio');
Route::get('/getbio', 'API\APIController@getbio');
Route::get('/mutefriend', 'API\APIController@mutefriend');
Route::get('/savepost', 'API\APIController@savepost');
Route::post('/commentpost', 'API\APIController@commentpost');
Route::get('/deletecomment', 'API\APIController@deletecomment');
Route::get('/getallactivepost', 'API\APIController@getallactivepost');
Route::get('/getallactivepostwithstyle', 'API\APIController@getallactivepostwithstyle');
Route::get('/getpostdetail', 'API\APIController@getpostdetail');
Route::get('/getpostbyuser', 'API\APIController@getpostbyuser');
Route::get('/searchusers', 'API\APIController@searchusers');
Route::get('/getpostsbyuserstyle', 'API\APIController@getpostsbyuserstyle');
Route::get('/getnotifications', 'API\APIController@getnotifications');
Route::get('/getuserstyle', 'API\APIController@getuserstyle');
Route::get('/settingnotification', 'API\APIController@settingnotification');
Route::get('/getnotificationsetting', 'API\APIController@getnotificationsetting');
Route::get('/setprivacy', 'API\APIController@setprivacy');
Route::get('/getprivacy', 'API\APIController@getprivacy');
Route::get('/getbrand', 'API\APIController@getbrand');
Route::get('/searchbrand', 'API\APIController@searchbrand');
Route::post('/getpostwithbrand', 'API\APIController@getpostwithbrand');
Route::get('/report', 'API\APIController@report');
Route::get('/block', 'API\APIController@block');
Route::get('/unblock', 'API\APIController@unblock');
Route::get('/getalluserwithfb', 'API\APIController@getalluserwithfb');
//Route::get('/calculateExpired', 'API\APIController@calculateExpired');
Route::post('/signup', 'API\APIController@signup');
Route::get('/signin', 'API\APIController@signin');
Route::post('/avatarupdate', 'API\APIController@avatarupdate');
Route::get('/sendverifycode', 'API\APIController@sendverifycode');
Route::get('/changepassword', 'API\APIController@changepassword');
