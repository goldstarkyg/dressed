<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use DB;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use LaravelFCM\Message\Topics;

class APIController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('America/Los_Angeles');
    }
    public function authorization(Request $request){
        $fbid = $request->get('fb_id', '');
        $first_name = $request->get('first_name', '');
        $last_name = $request->get('last_name', '');
        $_token = $request->get('_token', '');
        $avatar = $request->get('avatar', '');
        $email = $request->get('email', '');
        if($fbid == ''){
            $data = array('code'=>0, 'data'=>'facebook id is not exist.');
            return \GuzzleHttp\json_encode($data);
        }else if($first_name == ''){
            $data = array('code'=>0, 'data'=>'First name is not exist.');
            return \GuzzleHttp\json_encode($data);
        }else if($last_name == ''){
            $data = array('code'=>0, 'data'=>'Last name is not exist.');
            return \GuzzleHttp\json_encode($data);
        }else if($_token == ''){
            $data = array('code'=>0, 'data'=>'Token is not exist.');
            return \GuzzleHttp\json_encode($data);
        }else if($email == ''){
            $data = array('code'=>0, 'data'=>'Email is not exist.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = \DB::table('users')->where('name', $fbid)->first();
            if (empty($user)) {
                DB::table('users')->insert(['name' => $fbid, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'token' => $_token, 'activated'=>1, 'password' => $_token, 'avatar' => $avatar, 'created_at'=>date('Y-m-d H:i:s')]);
                DB::table('profiles')->insert(['user_id'=>$fbid, 'mynotification'=>1, 'friendnotification'=>1]);
                $profile = DB::table('profiles')->where('user_id', $fbid)->select('mynotification', 'friendnotification')->first();
                $userstyles = DB::table('userstyles')->where('user_id', $fbid)->select('style_id')->get();
                $userstyle1 = '';
                foreach($userstyles as $userstyle){
                    if($userstyle1 == ''){
                        $userstyle1 = $userstyle->style_id;
                    }else{
                        $userstyle1 .= ','.$userstyle->style_id;
                    }
                }
                $data = array('code'=>1, 'data'=>'Successfully authorized.', 'profile'=>$profile, 'userstyles'=>$userstyle1);

            } else {
                if($user->activated == 0){
                    $data = array('code'=>0, 'data'=>'Your account got inactivated from admin.');
                }else{
                    DB::table('users')->where('name', $fbid)->update(['name' => $fbid, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'token' => $_token, 'password' => $_token, 'avatar' => $avatar]);
                    $profile = DB::table('profiles')->where('user_id', $fbid)->select('mynotification', 'friendnotification')->first();
                    $userstyles = DB::table('userstyles')->where('user_id', $fbid)->select('style_id')->get();
                    $userstyle1 = '';
                    foreach($userstyles as $userstyle){
                        if($userstyle1 == ''){
                            $userstyle1 = $userstyle->style_id;
                        }else{
                            $userstyle1 .= ','.$userstyle->style_id;
                        }
                    }
                    $data = array('code'=>1, 'data'=>'Successfully authorized.', 'profile'=>$profile, 'userstyles'=>$userstyle1);
                }
            }
            return \GuzzleHttp\json_encode($data);
        }
    }
    public function stylelist(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $styles = DB::table('styles')->select(['id', 'name'])->orderby('id', 'desc')->get();
                $my_style = DB::table('userstyles as u')
                    ->leftJoin('styles as s', 's.id', '=', 'u.style_id')->select(['s.id as id', 's.name as name'])
                    ->where('u.user_id', $user_id)->get();
                $data = array('code'=>1, 'data'=>$styles, 'my_style'=>$my_style);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getuserstyle(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }if($user_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your User id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $userstyle = DB::table('userstyles as u')
                    ->leftJoin('styles as s', 's.id', '=', 'u.style_id')->select(['s.id as id', 's.name as stylename'])
                    ->where('u.user_id', $user_id)->get();
            if(empty($userstyle)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $data = array('code'=>1, 'data'=>$userstyle);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function invitetofriend(Request $request){
        $_token = $request->get('_token', '');
        $myfb_id = $request->get('myfb_id', '');
        $fb_ids = $request->get('fb_ids', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($myfb_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your facebook id.');
            return \GuzzleHttp\json_encode($data);
        }else if($fb_ids == ''){
            $data = array('code'=>0, 'data'=>'There is not friends selected to invite.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $fbids_all = explode(',', $fb_ids);
                $fbids = array();
                foreach ($fbids_all as $key=>$fbid) {
                    $noti = \DB::table('notifications')->where('user_id', $myfb_id)->where('friend_id', $fbid)->where('type_id', 2)->first();
                    if(empty($noti))
                        $fbids[] = $fbid;
                }
                if(!empty($fbids)) {
                    //notication logic

                    //$body = array('user_id'=>$myfb_id, 'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'avatar'=>$user->avatar);

                    $title = 'Notification';
                    $body = $user->first_name . ' ' . $user->last_name . ' requested you as a friend.';

                    foreach ($fbids as $key => $fbid) {
                        $noti = \DB::table('notifications')->where('user_id', $myfb_id)->where('friend_id', $fbid)->where('type_id', 2)->first();
                        if (empty($noti))
                            \DB::table('notifications')->insert(['user_id' => $myfb_id, 'friend_id' => $fbid, 'post_id' => 0, 'type_id' => 2, 'content' => $body, 'acceptflg' => 1, 'created_at' => date('Y-m-d H:i:s')]);
                    }

                    $notificationBuilder = new PayloadNotificationBuilder($title);
                    $notificationBuilder->setBody($body)
                        ->setSound('default');

                    $notification = $notificationBuilder->build();

                    $topic = new Topics();

                    $topic->topic('dressd_' . $fbids[0])->andTopic(function ($condition) use ($fbids, $myfb_id) {
                        $i = 0;
                        foreach ($fbids as $key => $fbid) {
                            if (empty($noti)) {
                                if ($i != 0)
                                    $condition->topic('dressd_' . $fbid);
                                $i++;
                            }
                        }
                    });

                    $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                    if ($topicResponse->isSuccess()) {
                        //success
                    } else {
                        //failed
                    }

                }

            }
            $data = array('code'=>1, 'data'=>'Successfully invited');
            return \GuzzleHttp\json_encode($data);
        }
    }
   public function accepttofriend(Request $request){
        $_token = $request->get('_token', '');
        $myfb_id = $request->get('myfb_id', '');
        $fb_id = $request->get('fb_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($myfb_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your facebook id.');
            return \GuzzleHttp\json_encode($data);
        }else if($fb_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend selected to accept.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $invite = DB::table('notifications')->where('friend_id', $myfb_id)->where('user_id', $fb_id)->where('type_id', 2)->where('acceptflg', 1)->first();
                if(!empty($invite)){
                    //notication logic
                    $friend = DB::table('friends')->where('user_id', $myfb_id)->where('friend_id', $fb_id)->first();
                    if(empty($friend)) {
                        DB::table('friends')->insert(['user_id' => $myfb_id, 'friend_id' => $fb_id]);
                        DB::table('friends')->insert(['user_id' => $fb_id, 'friend_id' => $myfb_id]);
                    }

                    $content = $user->first_name.' '.$user->last_name.' accepted you as friend.';
                    DB::table('notifications')->where('friend_id', $myfb_id)->where('user_id', $fb_id)->where('type_id', 2)->delete();
                    DB::table('notifications')->insert(['user_id'=>$myfb_id, 'friend_id'=>$fb_id, 'content'=>$content, 'type_id'=>5, 'acceptflg'=>0, 'created_at'=>date('Y-m-d H:i:s')]);

                    //$body = array('user_id'=>$myfb_id, 'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'avatar'=>$user->avatar);
                    $title = 'Notification';
                    $body = $user->first_name.' '.$user->last_name.' accepted you as friend.';

                    $notificationBuilder = new PayloadNotificationBuilder($title);
                    $notificationBuilder->setBody($body)
                        ->setSound('default');

                    $notification = $notificationBuilder->build();

                    $topic = new Topics();
                    $topic->topic('dressd_' . $fb_id);

                    $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                    if($topicResponse->isSuccess()){
                        //success
                    }else{
                        //failed
                    }

                }else{
                    $data = array('code'=>0, 'data'=>'There is not invitation.');
                    return \GuzzleHttp\json_encode($data);
                }
                $data = array('code'=>1, 'data'=>'Successfully accepted');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function inviteuserlist(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($user_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your facebook id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $invite_user1 = \DB::table('notifications')->select(['friend_id as user_id'])->where('user_id', $user_id)->where('type_id', 2)->get();
            $invite_user2 = \DB::table('notifications')->select(['user_id as user_id'])->Where('friend_id', $user_id)->where('type_id', 2)->get();

            $invite_user = array();
            foreach ($invite_user1 as $user1){
                $user['user_id'] = $user1->user_id;
                array_push($invite_user, $user);
            }
            foreach ($invite_user2 as $user2){
                $flag = 0;
                foreach ($invite_user1 as $user1){
                    if($user2->user_id == $user1->user_id)
                        $flag = 1;
                }
                if($flag == 0) {
                    $user['user_id'] = $user2->user_id;
                    array_push($invite_user, $user);
                }
            }
            $data = array('code'=>1, 'data'=>$invite_user);
            return \GuzzleHttp\json_encode($data);
        }
    }
    public function cancelinvite(Request $request){
        $_token = $request->get('_token', '');
        $myfb_id = $request->get('myfb_id', '');
        $fb_id = $request->get('fb_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($myfb_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your facebook id.');
            return \GuzzleHttp\json_encode($data);
        }else if($fb_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend selected to accept.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $invite = DB::table('invites')->where('fb_id', $myfb_id)->where('myfb_id', $fb_id)->first();
                if(!empty($invite)){
                    DB::table('invites')->where('fb_id', $myfb_id)->where('myfb_id', $fb_id)->delete();
                }else{
                    $data = array('code'=>0, 'data'=>'There is not invitation.');
                    return \GuzzleHttp\json_encode($data);
                }
                $data = array('code'=>1, 'data'=>'Successfully cancelled');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function acceptlist(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $styles = DB::table('invites as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.myfb_id')->select(['i.myfb_id as user_id', 'u.first_name', 'u.last_name', 'u.avatar'])->where('fb_id', $user_id)->orderby('i.id', 'desc')->get();
                $data = array('code'=>1, 'data'=>$styles);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function friendlist(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $friend_id = $request->get('friend_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $styles = DB::table('friends as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                    ->select(['i.friend_id', 'u.first_name', 'u.last_name', 'u.avatar','u.signup_ip_address as relation'])
                    ->where('user_id', $friend_id)
                    ->where('i.status', 1)
                    ->orderby('u.first_name', 'asc')->get();

                $friend1 = \DB::table('friends')->where('user_id', $user_id)->get();
                $friends = array();
                foreach ($friend1 as $value)
                    $friends[] = $value->friend_id;

                foreach ($styles as $user){
                    if (in_array($user->friend_id, $friends))
                    {
                        $user->relation = "friend";
                    }
                    else
                    {
                        $invite = DB::table('notifications')->where('user_id',$user->friend_id)->where('friend_id',$user_id)->where('type_id',2)->first();
                        $invite1 = DB::table('notifications')->where('user_id',$user_id)->where('friend_id',$user->friend_id)->where('type_id',2)->first();
                        if(empty($invite) && empty($invite1)){
                            $user->relation = "none";
                        }
                        if(!empty($invite) && empty($invite1)){
                            $user->relation = "receive";
                        }
                        if(empty($invite) && !empty($invite1)){
                            $user->relation = "sent";
                        }
                        if(!empty($invite) && !empty($invite1)){
                            $user->relation = "sent";
                        }
                    }
                }

                $data = array('code'=>1, 'data'=>$styles);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }

    public function friendstate(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $friend = DB::table('friends as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                    ->select(['i.friend_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                    ->where('user_id', $user_id)
                    ->where('i.status', 1)
                    ->orderby('u.first_name', 'asc')->get();

                $sent = DB::table('notifications as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                    ->select(['i.friend_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                    ->where('i.user_id',$user_id)
                    ->where('type_id',2)
                    ->orderby('u.first_name', 'asc')->get();

                $receive = DB::table('notifications as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.user_id')
                    ->select(['i.user_id as friend_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                    ->where('i.friend_id',$user_id)
                    ->where('type_id',2)
                    ->orderby('u.first_name', 'asc')->get();


                $data = array('code'=>1, 'friend'=>$friend, 'sent'=>$sent, 'receive'=>$receive);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function mutefriend(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $friend_id = $request->get('friend_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $friends1 = DB::table('friends as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                    ->select(['i.friend_id','u.name as friend_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                    ->where('user_id', $user_id)
                    ->where('i.status', 1)
                    ->orderby('u.first_name', 'asc')->get();

                $friends2 = DB::table('friends as i')
                    ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                    ->select(['i.friend_id','u.name as friend_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                    ->where('user_id', $friend_id)
                    ->where('i.status', 1)
                    ->orderby('u.first_name', 'asc')->get();

                $friend  = array();
                $result = array();

                foreach ($friends1 as $friend1){
                    $flag = 0;
                    foreach ($friends2 as $friend2){
                        if($friend1->friend_id == $friend2->friend_id){
                            $flag = 1;
                        }
                    }
                    if($flag == 1){
                        $result['friend_id'] = $friend1->friend_id;
                        $result['first_name'] = $friend1->first_name;
                        $result['last_name'] = $friend1->last_name;
                        $result['avater'] = $friend1->avatar;
                        array_push($friend, $result);
                    }
                }
                $data = array('code'=>1, 'data'=>$friend);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getfriendcount(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $count = DB::table('friends')
                    ->where('user_id', $user_id)
                    ->get()->count();
                $data = array('code'=>1, 'data'=>$count);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function setuserstyles(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $style_ids = $request->get('style_ids', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($style_ids == ''){
            $data = array('code'=>0, 'data'=>'There is not the selected styles.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $styleids = explode(',', $style_ids);
                DB::table('userstyles')->where('user_id', $user_id)->delete();
                foreach($styleids as $key => $styleid){
                    $userstyle = DB::table('userstyles')->where('user_id', $user_id)->where('style_id', $styleid)->first();
                    if(empty($userstyle)){
                        DB::table('userstyles')->insert(['user_id'=>$user_id, 'style_id'=>$styleid]);
                    }
                }
                $userstyles = DB::table('userstyles')->where('user_id', $user_id)->select('style_id')->get();
                $data = array('code'=>1, 'data'=>'Successfully updated.', 'userstyles'=>$userstyles);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function adduserstyle(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $style_id = $request->get('style_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($style_id == ''){
            $data = array('code'=>0, 'data'=>'There is not the selected style.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $style = DB::table('userstyles')->where('user_id', $user_id)->where('style_id', $style_id)->first();
                if(empty($style))
                    DB::table('userstyles')->insert(['user_id'=>$user_id, 'style_id'=>$style_id]);

                $userstyles = DB::table('userstyles')->where('user_id', $user_id)->select('style_id')->get();
                $data = array('code'=>1, 'data'=>'Successfully added.', 'userstyles'=>$userstyles);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function deleteuserstyle(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $style_id = $request->get('style_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($style_id == ''){
            $data = array('code'=>0, 'data'=>'There is not the selected style.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $style = DB::table('userstyles')->where('user_id', $user_id)->where('style_id', $style_id)->first();
                if(!empty($style))
                    DB::table('userstyles')->where('user_id', $user_id)->where('style_id', $style_id)->delete();

                $userstyles = DB::table('userstyles')->where('user_id', $user_id)->select('style_id')->get();
                $data = array('code'=>1, 'data'=>'Successfully deleted.', 'userstyles'=>$userstyles);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function setmynotification(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $mynotification = $request->get('mynotification', 0);
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                DB::table('profiles')->where('user_id', $user_id)->update(['mynotification'=>$mynotification]);

                $data = array('code'=>1, 'data'=>'Successfully updated.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function setfriendnotification(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        $friendnotification = $request->get('friendnotification', 0);
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                DB::table('profiles')->where('user_id', $user_id)->update(['friendnotification'=>$friendnotification]);

                $data = array('code'=>1, 'data'=>'Successfully updated.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function addpost(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $subject = $request->get('subject', ' ');
        $brand = $request->get('brand', ' ');
        $style_ids = $request->get('style_ids', '');
        $expiredhour = $request->get('expiredhour', 0);
        $noexpire = $request->get('noexpire', '');
        $createdtime = time();
        $expiredtime = $createdtime + $expiredhour * 3600;
        /*$createdtime = $request->get('createdtime', '');
        $expiredtime = $request->get('expiredtime', '');*/
        $location = 'location';//$request->get('location', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }/*else if($style_ids == ''){
            $data = array('code'=>0, 'data'=>'There is not styles.');
            return \GuzzleHttp\json_encode($data);
        }*//*else if($createdtime == ''){
            $data = array('code'=>0, 'data'=>'There is not the created time.');
            return \GuzzleHttp\json_encode($data);
        }*/
        else if($noexpire == ''){
            $data = array('code'=>0, 'data'=>'There is not the expire option.');
            return \GuzzleHttp\json_encode($data);
        }
        else if($expiredhour == ''){
            $data = array('code'=>0, 'data'=>'There is not the expired hours.');
            return \GuzzleHttp\json_encode($data);
        }else if($location == ''){
            $data = array('code'=>0, 'data'=>'There is not the location.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                
                $photo = '';
                if($file = $request->file('photo1')) {
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $folderName = '/img/posts/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = 'post_'.time() . '.' . $extension;
                    $file->move($destinationPath, $safeName);
                    $photo = $safeName;
                }else{
                    $data = array('code'=>0, 'data'=>'There is not the photo.');
                    return \GuzzleHttp\json_encode($data);
                }

                $photo2 = '';
                if($file2 = $request->file('photo2')) {
                    $fileName2 = $file2->getClientOriginalName();
                    $extension2 = $file2->getClientOriginalExtension();
                    $folderName2 = '/img/posts/';
                    $destinationPath2 = public_path() . $folderName2;
                    $safeName2 = 'post_'.time() . '2.' . $extension2;
                    $file2->move($destinationPath2, $safeName2);
                    $photo2 = $safeName2;
                }
                $photo3 = '';
                if($file3 = $request->file('photo3')) {
                    $fileName3 = $file3->getClientOriginalName();
                    $extension3 = $file3->getClientOriginalExtension();
                    $folderName3 = '/img/posts/';
                    $destinationPath3 = public_path() . $folderName3;
                    $safeName3 = 'post_'.time() . '3.' . $extension3;
                    $file3->move($destinationPath3, $safeName3);
                    $photo3 = $safeName3;
                }
                $photo4 = '';
                if($file4 = $request->file('photo4')) {
                    $fileName4 = $file4->getClientOriginalName();
                    $extension4 = $file4->getClientOriginalExtension();
                    $folderName4 = '/img/posts/';
                    $destinationPath4 = public_path() . $folderName4;
                    $safeName4 = 'post_'.time() . '4.' . $extension4;
                    $file4->move($destinationPath4, $safeName4);
                    $photo4 = $safeName4;
                }

                if($brand != ''){
                    $brand_list = explode(',', $brand);
                    foreach ($brand_list as $brand_item){
                        $s = \DB::table('brand')->where('brand',$brand_item)->first();
                        if(empty($s)){
                            \DB::table('brand')->insert(['brand' => $brand_item,'created_at' => date('Y-m-d H:i:s')]);
                        }
                    }
                    $brand = ",".$brand.",";
                }

                if($noexpire == "no") {
                    \DB::table('posts')->insert(['user_id' => $user_id, 'photo' => $photo, 'photo2' => $photo2, 'photo3' => $photo3, 'photo4' => $photo4, 'subject' => $subject, 'brand' => $brand, 'style_id' => $style_ids, 'location' => $location, 'createdtime' => $createdtime, 'expiredtime' => $expiredtime, 'expiredhour' => $expiredhour, 'created_at' => date('Y-m-d H:i:s')]);
                    $post = \DB::table('posts')->orderby('createdtime', 'desc')->first();
                }else{
                    \DB::table('posts')->insert(['user_id' => $user_id, 'photo' => $photo, 'photo2' => $photo2, 'photo3' => $photo3, 'photo4' => $photo4, 'subject' => $subject, 'brand' => $brand, 'style_id' => $style_ids, 'location' => $location, 'createdtime' => $createdtime, 'expiredtime' => $createdtime,'expiredflg'=>2, 'expiredhour' => $expiredhour, 'created_at' => date('Y-m-d H:i:s')]);
                    $post = \DB::table('posts')->orderby('createdtime', 'desc')->first();
                    \DB::table('saves')->insert(['user_id' => $user_id, 'post_id' => $post->id, 'savedflg' => 1]);
                }

                if($noexpire == 'no') {
                    $profile = \DB::table('profiles')->where('user_id', $user_id)->first();
                    $usertopics = array();
                    $userids = array();

                    if (!empty($profile)) {
                        //if ($profile->mynotification == 1) {
                            $friend_list = \DB::table('friends as f')
                                ->leftJoin('profiles as p', 'p.user_id', '=', 'f.friend_id')
                                ->select('f.friend_id', 'p.friendnotification')
                                ->where('f.user_id', $user_id)
                                ->where('f.status', 1)
                                ->get();
                            if (!empty($friend_list)) {
                                foreach ($friend_list as $friend) {
                                    if ($friend->friendnotification == 1) {
                                        $usertopics[] = 'dressd_' . $friend->friend_id;
                                        $userids[] = $friend->friend_id;
                                    }
                                }
                            }
                        //}
                    }
                    //    /topics/
                    if (!empty($usertopics)) {
                        //notication logic

                        $title = 'Notification';
                        $body = $user->first_name . ' ' . $user->last_name . ' posted an outfit.';

                        foreach ($userids as $key => $userid) {
                            \DB::table('notifications')->insert(['user_id' => $user_id, 'friend_id' => $userid, 'post_id' => $post->id, 'type_id' => 1, 'content' => $body, 'acceptflg' => 0, 'created_at' => date('Y-m-d H:i:s')]);
                        }

                        $notificationBuilder = new PayloadNotificationBuilder($title);
                        $notificationBuilder->setBody($body)
                            ->setSound('default');

                        $notification = $notificationBuilder->build();

//                    $topic = new Topics();
//                    $topic->topic($usertopics[0])->andTopic(function($condition) use($usertopics){
//                        $i = 0;
//                        foreach($usertopics as $key=>$usertopic){
//                            if($i != 0)
//                                $condition->topic($usertopic);
//                            $i++;
//                        }
//                    });
//                    $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                        foreach ($usertopics as $usertopic) {
                            $topic = new Topics();
                            $topic->topic($usertopic);

                            $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                        }


                        //$topicResponse->shouldRetry();
                        //$topicResponse->error();
                    }
                }
                
                $data = array('code'=>1, 'data'=>'Successfully added.');
                return \GuzzleHttp\json_encode($data);
            }
        }
        
        $data = array('code'=>0, 'data'=>'failed add.');
        return \GuzzleHttp\json_encode($data);
    }
    public function updatepost(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $subject = $request->get('subject', '');
        $post_id = $request->get('post_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($subject == ''){
            $data = array('code'=>0, 'data'=>'There is not subject.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                \DB::table('posts')->where('id', $post_id)->where('user_id', $user_id)->update(['subject' => $subject]);
                $data = array('code' => 1, 'data' => 'Successfully updated.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function deletepost(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                \DB::table('saves')->where('post_id', $post_id)->where('user_id', $user_id)->delete();
                \DB::table('posts')->where('id', $post_id)->where('user_id', $user_id)->delete();
                \DB::table('notifications')->where('post_id', $post_id)->delete();
                \DB::table('report')->where('post_id', $post_id)->delete();
                $data = array('code' => 1, 'data' => 'Successfully deleted.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function addlike(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        $likenum = $request->get('likenum', 0);
        
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else if($likenum == 0){
            $data = array('code'=>0, 'data'=>'There is not the like number.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $likenum = 'like'.$likenum;
                $like = \DB::table('likes')->where('post_id', $post_id)->where('user_id', $user_id)->first();
                $post = \DB::table('posts')->where('id', $post_id)->first();
                if(!empty($post)) {
                    if (!empty($like)) {
                        $likeval1 = $like->like1;
                        $likeval2 = $like->like2;
                        $likeval3 = $like->like3;
                        $likeval4 = $like->like4;
                        $likeval5 = $like->like5;
                        $likeval6 = $like->like6;
                        $likeval7 = $like->like7;
                        $likeval8 = $like->like8;
                        $likeval9 = $like->like9;
                        $likeval10 = $like->like10;
                        $likeval11 = $like->like11;
                        $likeval12 = $like->like12;

                        $oldlikeval1 = 0;
                        $oldlikeval2 = 0;
                        $oldlikeval3 = 0;
                        $oldlikeval4 = 0;
                        $oldlikeval5 = 0;
                        $oldlikeval6 = 0;
                        $oldlikeval7 = 0;
                        $oldlikeval8 = 0;
                        $oldlikeval9 = 0;
                        $oldlikeval10 = 0;
                        $oldlikeval11 = 0;
                        $oldlikeval12 = 0;

                        $post_like1 = $post->like1;
                        $post_like2 = $post->like2;
                        $post_like3 = $post->like3;
                        $post_like4 = $post->like4;
                        $post_like5 = $post->like5;
                        $post_like6 = $post->like6;
                        $post_like7 = $post->like7;
                        $post_like8 = $post->like8;
                        $post_like9 = $post->like9;
                        $post_like10 = $post->like10;
                        $post_like11 = $post->like11;
                        $post_like12 = $post->like12;

                        if($likenum == 'like1' || $likenum == 'like2' || $likenum == 'like3'){
                            if($like->like1 == 1) $oldlikeval1 = -1;
                            if($like->like2 == 1) $oldlikeval2 = -1;
                            if($like->like3 == 1) $oldlikeval3 = -1;
                            if($likenum == 'like1'){
                                if($like->like1 == 1) $likeval1 = 0;
                                else {$likeval1 = 1; $likeval2 = 0; $likeval3 = 0;}
                            }
                            if($likenum == 'like2'){
                                if($like->like2 == 1) $likeval2 = 0;
                                else {$likeval1 = 0; $likeval2 = 1; $likeval3 = 0;}
                            }
                            if($likenum == 'like3'){
                                if($like->like3 == 1) $likeval3 = 0;
                                else {$likeval1 = 0; $likeval2 = 0; $likeval3 = 1;}
                            }

                            $post_like1 = $post->like1 + $likeval1 + $oldlikeval1;
                            $post_like2 = $post->like2 + $likeval2 + $oldlikeval2;
                            $post_like3 = $post->like3 + $likeval3 + $oldlikeval3;
                        }
                        if($likenum == 'like4' || $likenum == 'like5' || $likenum == 'like6'){
                            if($like->like4 == 1) $oldlikeval4 = -1;
                            if($like->like5 == 1) $oldlikeval5 = -1;
                            if($like->like6 == 1) $oldlikeval6 = -1;
                            if($likenum == 'like4'){
                                if($like->like4 == 1) $likeval4 = 0;
                                else {$likeval4 = 1; $likeval5 = 0; $likeval6 = 0;}
                            }
                            if($likenum == 'like5'){
                                if($like->like5 == 1) $likeval5 = 0;
                                else {$likeval4 = 0; $likeval5 = 1; $likeval6 = 0;}
                            }
                            if($likenum == 'like6'){
                                if($like->like6 == 1) $likeval6 = 0;
                                else {$likeval4 = 0; $likeval5 = 0; $likeval6 = 1;}
                            }

                            $post_like4 = $post->like4 + $likeval4 + $oldlikeval4;
                            $post_like5 = $post->like5 + $likeval5 + $oldlikeval5;
                            $post_like6 = $post->like6 + $likeval6 + $oldlikeval6;
                        }
                        if($likenum == 'like7' || $likenum == 'like8' || $likenum == 'like9'){
                            if($like->like7 == 1) $oldlikeval7 = -1;
                            if($like->like8 == 1) $oldlikeval8 = -1;
                            if($like->like9 == 1) $oldlikeval9 = -1;

                            if($likenum == 'like7'){
                                if($like->like7 == 1) $likeval7 = 0;
                                else {$likeval7 = 1; $likeval8 = 0; $likeval9 = 0;}
                            }
                            if($likenum == 'like8'){
                                if($like->like8 == 1) $likeval8 = 0;
                                else {$likeval7 = 0; $likeval8 = 1; $likeval9 = 0;}
                            }
                            if($likenum == 'like9'){
                                if($like->like9 == 1) $likeval9 = 0;
                                else {$likeval7 = 0; $likeval8 = 0; $likeval9 = 1;}
                            }

                            $post_like7 = $post->like7 + $likeval7 + $oldlikeval7;
                            $post_like8 = $post->like8 + $likeval8 + $oldlikeval8;
                            $post_like9 = $post->like9 + $likeval9 + $oldlikeval9;
                        }
                        if($likenum == 'like10' || $likenum == 'like11' || $likenum == 'like12'){
                            if($like->like10 == 1) $oldlikeval10 = -1;
                            if($like->like11 == 1) $oldlikeval11 = -1;
                            if($like->like12 == 1) $oldlikeval12 = -1;

                            if($likenum == 'like10'){
                                if($like->like10 == 1) $likeval10 = 0;
                                else {$likeval10 = 1; $likeval11 = 0; $likeval12 = 0;}
                            }
                            if($likenum == 'like11'){
                                if($like->like11 == 1) $likeval11 = 0;
                                else {$likeval10 = 0; $likeval11 = 1; $likeval12 = 0;}
                            }
                            if($likenum == 'like12'){
                                if($like->like12 == 1) $likeval12 = 0;
                                else {$likeval10 = 0; $likeval11 = 0; $likeval12 = 1;}
                            }

                            $post_like10 = $post->like10 + $likeval10 + $oldlikeval10;
                            $post_like11 = $post->like11 + $likeval11 + $oldlikeval11;
                            $post_like12 = $post->like12 + $likeval12 + $oldlikeval12;
                        }

                        \DB::table('posts')->where('id', $post_id)->update(['like1'=>$post_like1, 'like2'=>$post_like2, 'like3'=>$post_like3, 'like4'=>$post_like4, 'like5'=>$post_like5, 'like6'=>$post_like6, 'like7'=>$post_like7, 'like8'=>$post_like8, 'like9'=>$post_like9, 'like10'=>$post_like10, 'like11'=>$post_like11, 'like12'=>$post_like12]);
                        \DB::table('likes')->where('post_id', $post_id)->where('user_id', $user_id)->update(['like1' => $likeval1, 'like2' => $likeval2, 'like3' => $likeval3, 'like4' => $likeval4, 'like5' => $likeval5, 'like6' => $likeval6, 'like7' => $likeval7, 'like8' => $likeval8, 'like9' => $likeval9, 'like10' => $likeval10, 'like11' => $likeval11, 'like12' => $likeval12]);

                        $result = array('like1' => strval($post_like1),'like2' => strval($post_like2),'like3' => strval($post_like3),'like4' => strval($post_like4),'like5' => strval($post_like5),'like6' => strval($post_like6),'like7' => strval($post_like7),'like8' => strval($post_like8),'like9' => strval($post_like9),'like10' => strval($post_like10),'like11' => strval($post_like11),'like12' => strval($post_like12),'me1' => strval($likeval1),'me2' => strval($likeval2),'me3' => strval($likeval3),'me4' => strval($likeval4),'me5' => strval($likeval5),'me6' => strval($likeval6),'me7' => strval($likeval7),'me8' => strval($likeval8),'me9' => strval($likeval9),'me10' => strval($likeval10),'me11' => strval($likeval11),'me12' => strval($likeval12) );

                    } else {
                        $likeval1 = 0; $likeval2 = 0; $likeval3 = 0;
                        $likeval4 = 0; $likeval5 = 0; $likeval6 = 0;
                        $likeval7 = 0; $likeval8 = 0; $likeval9 = 0;
                        $likeval10 = 0; $likeval11 = 0; $likeval12 = 0;

                        $post_like1 = $post->like1;
                        $post_like2 = $post->like2;
                        $post_like3 = $post->like3;
                        $post_like4 = $post->like4;
                        $post_like5 = $post->like5;
                        $post_like6 = $post->like6;
                        $post_like7 = $post->like7;
                        $post_like8 = $post->like8;
                        $post_like9 = $post->like9;
                        $post_like10 = $post->like10;
                        $post_like11 = $post->like11;
                        $post_like12 = $post->like12;

                        if($likenum == 'like1') $likeval1 = 1;
                        if($likenum == 'like2') $likeval2 = 1;
                        if($likenum == 'like3') $likeval3 = 1;
                        if($likenum == 'like4') $likeval4 = 1;
                        if($likenum == 'like5') $likeval5 = 1;
                        if($likenum == 'like6') $likeval6 = 1;
                        if($likenum == 'like7') $likeval7 = 1;
                        if($likenum == 'like8') $likeval8 = 1;
                        if($likenum == 'like9') $likeval9 = 1;
                        if($likenum == 'like10') $likeval10 = 1;
                        if($likenum == 'like11') $likeval11 = 1;
                        if($likenum == 'like12') $likeval12 = 1;

                        if($likenum == 'like1'){
                            $post_like1 = $post->like1 + $likeval1;
                        }else if($likenum == 'like2'){
                            $post_like2 = $post->like2 + $likeval2;
                        }else if($likenum == 'like3'){
                            $post_like3 = $post->like3 + $likeval3;
                        }else if($likenum == 'like4'){
                            $post_like4 = $post->like4 + $likeval4;
                        }else if($likenum == 'like5'){
                            $post_like5 = $post->like5 + $likeval5;
                        }else if($likenum == 'like6'){
                            $post_like6 = $post->like6 + $likeval6;
                        }else if($likenum == 'like7'){
                            $post_like7 = $post->like7 + $likeval7;
                        }else if($likenum == 'like8'){
                            $post_like8 = $post->like8 + $likeval8;
                        }else if($likenum == 'like9'){
                            $post_like9 = $post->like9 + $likeval9;
                        }else if($likenum == 'like10'){
                            $post_like10 = $post->like10 + $likeval10;
                        }else if($likenum == 'like11'){
                            $post_like11 = $post->like11 + $likeval11;
                        }else if($likenum == 'like12'){
                            $post_like12 = $post->like12 + $likeval12;
                        }

                        \DB::table('posts')->where('id', $post_id)->update(['like1'=>$post_like1, 'like2'=>$post_like2, 'like3'=>$post_like3, 'like4'=>$post_like4, 'like5'=>$post_like5, 'like6'=>$post_like6, 'like7'=>$post_like7, 'like8'=>$post_like8, 'like9'=>$post_like9, 'like10'=>$post_like10, 'like11'=>$post_like11, 'like12'=>$post_like12]);
                        \DB::table('likes')->insert(['user_id' => $user_id, 'post_id' => $post_id, 'like1' => $likeval1, 'like2' => $likeval2, 'like3' => $likeval3, 'like4' => $likeval4, 'like5' => $likeval5, 'like6' => $likeval6, 'like7' => $likeval7, 'like8' => $likeval8, 'like9' => $likeval9, 'like10' => $likeval10, 'like11' => $likeval11, 'like12' => $likeval12]);

                        $result = array('like1' => strval($post_like1),'like2' => strval($post_like2),'like3' => strval($post_like3),'like4' => strval($post_like4),'like5' => strval($post_like5),'like6' => strval($post_like6),'like7' => strval($post_like7),'like8' => strval($post_like8),'like9' => strval($post_like9),'like10' => strval($post_like10),'like11' => strval($post_like11),'like12' => strval($post_like12),'me1' => strval($likeval1),'me2' => strval($likeval2),'me3' => strval($likeval3),'me4' => strval($likeval4),'me5' => strval($likeval5),'me6' => strval($likeval6),'me7' => strval($likeval7),'me8' => strval($likeval8),'me9' => strval($likeval9),'me10' => strval($likeval10),'me11' => strval($likeval11),'me12' => strval($likeval12) );


                    }
                    //notification topic

                    //$body = array('user_id'=>$user_id, 'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'avatar'=>$user->avatar);
                    $title = 'Notification';
                    $body = $user->first_name.' '.$user->last_name.' liked your outfit.';

                    \DB::table('notifications')->insert(['user_id'=>$user_id, 'friend_id'=>$post->user_id, 'post_id'=>$post->id, 'type_id'=>3, 'content'=>$body, 'acceptflg'=>0, 'created_at'=>date('Y-m-d H:i:s')]);

                    $notificationBuilder = new PayloadNotificationBuilder($title);
                    $notificationBuilder->setBody($body)
                        ->setSound('default');

                    $notification = $notificationBuilder->build();

                    $topic = new Topics();
                    $topic->topic('dressd_' . $post->user_id);

                    $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                    if($topicResponse->isSuccess()){
                        //success
                    }else{
                        //failed
                    }

                    $data = array('code' => 1, 'data' => $result);
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This post is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function likelist(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        $like_num = $request->get('like_num', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else if($like_num == ''){
            $data = array('code'=>0, 'data'=>'There is not like number.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $post = \DB::table('posts')->where('id', $post_id)->first();
                if(!empty($post)) {
                    $likes = DB::table('likes as l')
                        ->leftJoin('users as u', 'u.name', '=', 'l.user_id')
                        ->select(['u.name as friend_id','u.first_name', 'u.last_name', 'u.avatar'])
                        ->where('l.post_id',$post_id)
                        ->where('l.like'.$like_num,1)
                        ->get();

                    $data = array('code' => 1, 'data' => $likes);
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This post is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function addbio(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $bio_content = $request->get('bio_content', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $bio = \DB::table('bio')->where('user_id', $user_id)->first();
                if(empty($bio)) {
                    DB::table('bio')->insert(['user_id' => $user_id, 'content' => $bio_content]);

                    $data = array('code' => 1, 'data' => 'Successfully added.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    DB::table('bio')->where('user_id', $user_id)->update(['content'=>$bio_content]);

                    $data = array('code'=>1, 'data'=>'Successfully updated.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function getbio(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');        
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $bio = \DB::table('bio')->select(['user_id','content as bio_content'])->where('user_id', $user_id)->first();
                if(empty($bio)) {
                    $data = array('code' => 0, 'data' => 'There is not bio.');
                    return \GuzzleHttp\json_encode($data);
                }else{                   
                    if($bio->bio_content == null) $bio->bio_content = '';
                    $data = array('code'=>1, 'data'=>$bio);
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function savepost(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        $currenttime = time();
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $post = \DB::table('posts')->where('id', $post_id)->first();
                if(!empty(!empty($post))) {
                    $save = \DB::table('saves')->where('post_id', $post_id)->where('user_id', $user_id)->first();
                    if (empty($save)) {
                        \DB::table('saves')->insert(['user_id' => $user_id, 'post_id' => $post_id, 'savedflg' => 1]);

                        \DB::table('posts')->where('id', $post_id)->update(['expiredflg'=>2,'expiredtime'=>$currenttime]);
                        $noti = \DB::table('notifications')
                            ->where('user_id',$user_id)
                            ->where('friend_id',$user_id)
                            ->where('post_id',$post_id)
                            ->where('type_id',6)
                            ->first();
                        if(!empty($noti))
                            \DB::table('notifications')
                                ->where('user_id',$user_id)
                                ->where('friend_id',$user_id)
                                ->where('post_id',$post_id)
                                ->where('type_id',6)
                                ->delete();
                    }
                    $data = array('code' => 1, 'data' => 'Successfully saved.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This post is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function commentpost(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        $comment = $request->get('comment', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $post = \DB::table('posts')->where('id', $post_id)->first();
                if(!empty(!empty($post))) {
                    //$comment1 = \DB::table('comments')->where('post_id', $post_id)->where('user_id', $user_id)->first();
                    //if (empty($comment1)) {
                        \DB::table('comments')->insert(['user_id' => $user_id, 'post_id' => $post_id, 'comment' => $comment, 'created_at'=>date('Y-m-d H:i:s')]);
                        $commentcount = $post->comment+1;
                        \DB::table('posts')->where('id', $post_id)->update(['comment'=>$commentcount]);
                        if($user_id != $post->user_id) {
                            //notification topic
                            $title = 'Notification';
                            $body = $user->first_name . ' ' . $user->last_name . ' commented on your outfit.';

                            \DB::table('notifications')->insert(['user_id' => $user_id, 'friend_id' => $post->user_id, 'post_id' => $post->id, 'type_id' => 4, 'content' => $body, 'acceptflg' => 0, 'created_at' => date('Y-m-d H:i:s')]);

                            $notificationBuilder = new PayloadNotificationBuilder($title);
                            $notificationBuilder->setBody($body)
                                ->setSound('default');

                            $notification = $notificationBuilder->build();

                            $topic = new Topics();
                            $topic->topic('dressd_' . $post->user_id);

                            $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
                            if ($topicResponse->isSuccess()) {
                                //success
                            } else {
                                //failed
                            }
                        }
                        

                    //}
                    $data = array('code' => 1, 'data' => 'Successfully commented.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This post is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function deletecomment(Request $request){
        $friend_id = $request->get('friend_id', '');
        $_token = $request->get('_token', '');
        $comment_id = $request->get('comment_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($comment_id == ''){
            $data = array('code'=>0, 'data'=>'There is not comment id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $friend_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                \DB::table('comments')->where('id', $comment_id)->delete();

                $data = array('code' => 1, 'data' => 'Successfully deleted.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getallactivepost(Request $request){
        $user_id = $request->get('user_id', '');
        $currenttime = time();
        $pageno = $request->get('pageno', '');
        $num = $request->get('num', 10);
        $_token = $request->get('_token', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($pageno == ''){
            $data = array('code'=>0, 'data'=>'There is not page number.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $start_num = (int)$pageno * (int)$num;
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $friend1 = \DB::table('friends')->where('user_id', $user_id)->where('status',1)->select('friend_id')->get();
                $friends = array();
                array_push($friends,$user_id);
                foreach ($friend1 as $value)
                    array_push($friends,$value->friend_id);

                $posts_active = \DB::table('posts as p')
                    ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                    ->select('p.id', 'p.user_id', 'p.subject', 'p.brand', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                    ->where('p.expiredtime', '>', $currenttime)
                    ->whereIn('p.user_id', $friends)
                    ->orderby('remaintime', 'asc')
                    ->get();
                $posts_save = \DB::table('posts as p')
                    ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                    ->leftJoin('saves as s', 's.post_id', '=', 'p.id')
                    ->select('p.id', 'p.user_id', 'p.subject', 'p.brand', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                    ->where('p.expiredflg', 2)
                    ->whereIn('p.user_id', $friends)
                    ->orderby('s.id', 'desc')
                    ->get();
                $posts = array_merge($posts_active->toArray(), $posts_save->toArray());
                //->orderByRaw('(p.expiredtime - '.$currenttime.') ASC')
                $posts = array_slice($posts,$start_num,$num);
                $data = array('code' => 1, 'data' => $posts);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public function getallactivepostwithstyle(Request $request){
        $user_id = $request->get('user_id', '');
        $style_id = $request->get('style_id', '');
        $currenttime = time();
        $pageno = $request->get('pageno', '');
        $num = $request->get('num', 10);
        $_token = $request->get('_token', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($style_id == ''){
            $data = array('code'=>0, 'data'=>'There is not style id.');
            return \GuzzleHttp\json_encode($data);
        }else if($pageno == ''){
            $data = array('code'=>0, 'data'=>'There is not page number.');
            return \GuzzleHttp\json_encode($data);
        }else {

            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $styletab = \DB::table('profiles')->where('user_id', $user_id)->first()->styletab;
                $block = \DB::table('block')->select('friend_id')->where('user_id', $user_id)->get();
                $blockByUser = \DB::table('block')->select('user_id')->where('friend_id', $user_id)->get();

                $post_public1 = \DB::table('profiles')->where('public', 1)->select('user_id')->get();
                $friend1 = \DB::table('friends')->where('user_id', $user_id)->where('status',1)->select('friend_id')->get();

                $post_public = array();
                $friends = array();

                if($styletab == 1){
                    array_push($friends,$user_id);
                    foreach ($friend1 as $value) {
                        $flag = 0;
                        foreach($block as $block_item){
                            if($value->friend_id == $block_item->friend_id){
                                $flag = 1;
                            }
                        }
                        foreach($blockByUser as $blockByUser_item){
                            if($value->friend_id == $blockByUser_item->user_id){
                                $flag = 1;
                            }
                        }
                        if($flag == 0)
                            array_push($friends, $value->friend_id);
                    }

                }else{
                    array_push($friends,$user_id);
                    foreach ($friend1 as $value) {
                        $flag = 0;
                        foreach($block as $block_item){
                            if($value->friend_id == $block_item->friend_id){
                                $flag = 1;
                            }
                        }
                        foreach($blockByUser as $blockByUser_item){
                            if($value->friend_id == $blockByUser_item->user_id){
                                $flag = 1;
                            }
                        }
                        if($flag == 0)
                            array_push($friends, $value->friend_id);
                    }

                    foreach ($post_public1 as $value) {
                        $flag = 0;
                        foreach($block as $block_item){
                            if($value->user_id == $block_item->friend_id){
                                $flag = 1;
                            }
                        }
                        foreach($blockByUser as $blockByUser_item){
                            if($value->user_id == $blockByUser_item->user_id){
                                $flag = 1;
                            }
                        }
                        if($flag == 0)
                            array_push($post_public, $value->user_id);
                    }
                }

                $public = array();
                if(!empty($post_public) && !empty($friends)){
                    $public = array_merge($post_public, $friends);
                }else if(!empty($post_public)){
                    $public = $post_public;
                }else if(!empty($friends)){
                    $public = $friends;
                }
                if(!empty($public)){
                    array_unique($public);
                    $posts_active = \DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->select('p.id', 'p.user_id', 'p.subject', 'p.brand','p.style_id', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                        ->where('p.expiredtime', '>', $currenttime)
                        ->whereIn('p.user_id', $public)
                        ->orderby('remaintime', 'asc')
                        ->get();
                    $posts_save = \DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->leftJoin('saves as s', 's.post_id', '=', 'p.id')
                        ->select('p.id', 'p.user_id', 'p.subject', 'p.brand','p.style_id', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                        ->where('p.expiredflg', 2)
                        ->whereIn('p.user_id', $public)
                        ->orderby('s.id', 'desc')
                        ->get();
                }else{
                    $posts_active = \DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->select('p.id', 'p.user_id', 'p.subject', 'p.brand','p.style_id', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                        ->where('p.expiredtime', '>', $currenttime)
                        ->orderby('remaintime', 'asc')
                        ->get();
                    $posts_save = \DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->leftJoin('saves as s', 's.post_id', '=', 'p.id')
                        ->select('p.id', 'p.user_id', 'p.subject', 'p.brand','p.style_id', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                        ->where('p.expiredflg', 2)
                        ->orderby('s.id', 'desc')
                        ->get();
                }
                $posts = array();
                if(!empty($posts_active) && !empty($posts_save)){
                    $posts = array_merge($posts_active->toArray(), $posts_save->toArray());
                }else if(!empty($posts_active)){
                    $posts = $posts_active->toArray();
                }else if(!empty($posts_save)){
                    $posts = $posts_save->toArray();
                }

                //$user_styles = DB::table('userstyles')->select('style_id')->where('user_id',$user_id)->get();
                $user_styles = explode(',', $style_id);
                $result = array();
                $post_list = array();

                $start_num = (int)$pageno * (int)$num;
                if(!empty($posts)){
                    foreach ($posts as $post){
                        $post_style = ",".$post->style_id.",";
                        $exist_flag = 0;
                        foreach ($user_styles as $user_style){
                            $style = ",".$user_style.",";
                            if(strpos( $post_style, $style) !== false){
                                // if exist
                                $exist_flag = 1;
                            }
                        }
                        if($exist_flag == 1){
                            $result['id'] = $post->id;
                            $result['user_id'] = $post->user_id;
                            $result['style_id'] = $post->style_id;
                            $result['subject'] = $post->subject;
                            $result['brand'] = $post->brand;
                            $result['photo'] = $post->photo;
                            $result['photo2'] = $post->photo2;
                            $result['photo3'] = $post->photo3;
                            $result['photo4'] = $post->photo4;
                            $result['createdtime'] = $post->createdtime;
                            $result['expiredtime'] = $post->expiredtime;
                            $result['expiredhour'] = $post->expiredhour;
                            $result['remaintime'] = $post->remaintime;
                            $result['first_name'] = $post->first_name;
                            $result['last_name'] = $post->last_name;
                            $result['avatar'] = $post->avatar;
                            $result['like1'] = $post->like1;
                            $result['like2'] = $post->like2;
                            $result['like3'] = $post->like3;
                            $result['like4'] = $post->like4;
                            $result['like5'] = $post->like5;
                            $result['like6'] = $post->like6;
                            $result['like7'] = $post->like7;
                            $result['like8'] = $post->like8;
                            $result['like9'] = $post->like9;
                            $result['like10'] = $post->like10;
                            $result['like11'] = $post->like11;
                            $result['like12'] = $post->like12;
                            $result['me1'] = $post->me1;
                            $result['me2'] = $post->me2;
                            $result['me3'] = $post->me3;
                            $result['me4'] = $post->me4;
                            $result['me5'] = $post->me5;
                            $result['me6'] = $post->me6;
                            $result['me7'] = $post->me7;
                            $result['me8'] = $post->me8;
                            $result['me9'] = $post->me9;
                            $result['me10'] = $post->me10;
                            $result['me11'] = $post->me11;
                            $result['me12'] = $post->me12;

                            array_push($post_list, $result);

                        }
                    }
                    $post_list = array_slice($post_list,$start_num,$num);
                }

                $data = array('code' => 1, 'data' => $post_list);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public function getpostdetail(Request $request){
        $user_id = $request->get('user_id', '');
        $post_id = $request->get('post_id', '');
        $_token = $request->get('_token', '');
        $currenttime = time();
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $post = \DB::table('posts as p')
                    ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                    ->select('p.id', 'p.user_id', 'p.subject', 'p.brand', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.location', 'p.createdtime', 'p.expiredtime', 'p.expiredhour',DB::raw('p.expiredtime - '.$currenttime.'  as remaintime'), 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'p.comment as commentcount', 'u.first_name', 'u.last_name', 'u.avatar',DB::raw('(select like1 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me1'),DB::raw('(select like2 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me2'),DB::raw('(select like3 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me3'),DB::raw('(select like4 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me4'),DB::raw('(select like5 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me5'),DB::raw('(select like6 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me6'),DB::raw('(select like7 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me7'),DB::raw('(select like8 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me8'),DB::raw('(select like9 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me9'),DB::raw('(select like10 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me10'),DB::raw('(select like11 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me11'),DB::raw('(select like12 from likes where user_id  =  '.$user_id.'  and post_id = p.id) as me12') )
                    ->where('p.id', $post_id)
                    ->first();
                if(!empty($post)){
                    $comments = \DB::table('comments as c')
                        ->leftJoin('users as u', 'u.name', '=', 'c.user_id')
                        ->select('c.id','c.comment','c.user_id as friend_id', 'u.first_name', 'u.last_name', 'u.avatar')->where('c.post_id', $post_id)->get();
                    if($comments == null){
                        $comments = array();
                    }
                    $comment1 = \DB::table('comments')->where('user_id', $user_id)->where('post_id', $post_id)->first();
                    $mycommentflg = 0;
                    if(!empty($comment1)){
                        $mycommentflg = 1;
                    }
                    $save = \DB::table('saves')->where('user_id', $user_id)->where('post_id', $post_id)->first();
                    $mysaveflg = 0;
                    if(!empty($save)){
                        $mysaveflg = 1;
                    }
                    $data = array('code' => 1, 'data' => $post, 'comments'=>$comments, 'mycommentflg'=>$mycommentflg, 'mysaveflg'=>$mysaveflg);
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code' => 0, 'data' =>'there is not the outfit.');
                    return \GuzzleHttp\json_encode($data);
                }
            }

        }
    }
    public function getpostbyuser(Request $request){
        $user_id = $request->get('user_id', '');
        $friend_id = $request->get('friend_id', '');
        $currenttime = time();
        $_token = $request->get('_token', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else if($currenttime == ''){
            $data = array('code'=>0, 'data'=>'There is not current time.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
//                if($user_id == $friend_id){
//                    $activeposts = \DB::table('posts as p')
//                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
//                        ->select('p.id', 'p.user_id', 'p.subject', 'p.photo', 'p.photo2', 'p.location', 'p.createdtime', 'p.expiredtime', 'p.expiredhour', 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.comment as commentcount', 'u.first_name', 'u.last_name', 'u.avatar')->where('p.user_id', $friend_id)->orderby('p.createdtime', 'desc')->get();
//                    $saveposts = array();
//                    $userstyles = \DB::table('userstyles as us')
//                        ->leftJoin('styles as s', 's.id', '=', 'us.style_id')
//                        ->select('s.name', 's.id')->where('us.user_id', $friend_id)->get();
//                    $data = array('code' => 1, 'activeposts' => $activeposts, 'saveposts' => $saveposts, 'user' => $user, 'userstyles' => $userstyles);
//                }else {
                    $activeposts = \DB::table('posts as p')
                        ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                        ->select('p.id', 'p.user_id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.location', 'p.createdtime', 'p.expiredtime', 'p.expiredhour', 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.comment as commentcount', 'u.first_name', 'u.last_name', 'u.avatar')
                        ->where('p.user_id', $friend_id)
                        ->where('p.expiredtime', '>=', $currenttime)
                        ->orderby('p.createdtime', 'desc')->get();
                    $saveposts = \DB::table('saves as s')
                        ->leftJoin('posts as p', 'p.id', '=', 's.post_id')
                        ->select('p.id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4')
                        ->where('s.user_id', $friend_id)
                        ->orderby('s.id', 'desc')->get();
                    $user = \DB::table('users')->where('name', $friend_id)->select('first_name', 'last_name', 'avatar')->first();
                    $userstyles = \DB::table('userstyles as us')
                        ->leftJoin('styles as s', 's.id', '=', 'us.style_id')
                        ->select('s.name', 's.id')->where('us.user_id', $friend_id)->get();

                    $count = DB::table('friends')->where('user_id', $friend_id)->get()->count();

                    $block_state = 'no';
                    $block = \DB::table('block')->where('user_id',$user_id)->where('friend_id',$friend_id)->first();
                    if(!empty($block))
                        $block_state = 'yes';

                    $friend = DB::table('friends')->where('user_id',$user_id)->where('friend_id',$friend_id)->first();
                    if(empty($friend)){
                         $invite = DB::table('notifications')->where('user_id',$friend_id)->where('friend_id',$user_id)->where('type_id',2)->first();
                         $invite1 = DB::table('notifications')->where('user_id',$user_id)->where('friend_id',$friend_id)->where('type_id',2)->first();
                        if(empty($invite) && empty($invite1)){
                            $sentinvite = "none";
                        }else if(!empty($invite) && empty($invite1)){
                            $sentinvite = "receive";
                        }else if(empty($invite) && !empty($invite1)){
                            $sentinvite = "sent";
                        }else if(!empty($invite) && !empty($invite1)){
                            $sentinvite = "sent";
                        }
                         
                    } else {
                        $sentinvite = "friend";
                          
                    }

                    $bio = DB::table('bio')->select('content')->where('user_id',$friend_id)->first();

                    if(empty($bio) || $bio == null) $bio_content = '';
                    else if($bio->content == null) $bio_content = '';
                    else $bio_content = $bio->content;

                    $friends1 = DB::table('friends as i')
                        ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                        ->select(['i.friend_id','u.name as user_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                        ->where('user_id', $user_id)
                        ->where('i.status', 1)
                        ->orderby('u.first_name', 'asc')->get();
                    $friends2 = DB::table('friends as i')
                        ->leftJoin('users as u', 'u.name', '=', 'i.friend_id')
                        ->select(['i.friend_id','u.name as user_id', 'u.first_name', 'u.last_name', 'u.avatar'])
                        ->where('user_id', $friend_id)
                        ->where('i.status', 1)
                        ->orderby('u.first_name', 'asc')->get();
                    $mutefriendcount = 0;
                    foreach ($friends1 as $friend1){
                        $flag = 0;
                        foreach ($friends2 as $friend2){
                            if($friend1->friend_id == $friend2->friend_id){
                                $flag = 1;
                            }
                        }
                        if($flag == 1)
                            $mutefriendcount++;
                    }
                    $data = array('code' => 1, 'activeposts' => $activeposts, 'saveposts' => $saveposts, 'user' => $user, 'userstyles' => $userstyles,'blockstate'=>$block_state, 'sentinvite' => $sentinvite,'friendcount'=>$count,'bio'=>$bio_content,'mutefriendcount'=>$mutefriendcount);
//                }
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getpostsbyuserstyle(Request $request){
        $user_id = $request->get('user_id', '');
        $friend_id = $request->get('friend_id', '');
        $currenttime = time();
        $_token = $request->get('_token', '');
        $style_id = $request->get('style_id', '');

        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not user id.');
            return \GuzzleHttp\json_encode($data);
        }else if($currenttime == ''){
            $data = array('code'=>0, 'data'=>'There is not current time.');
            return \GuzzleHttp\json_encode($data);
        }else if($style_id == ''){
            $data = array('code'=>0, 'data'=>'There is not style id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $activeposts = \DB::table('posts as p')
                    ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                    ->select('p.id', 'p.user_id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.location', 'p.createdtime', 'p.expiredtime', 'p.expiredhour', 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.like9', 'p.like10', 'p.like11', 'p.like12', 'p.comment as commentcount', 'u.first_name', 'u.last_name', 'u.avatar')
                    ->where('p.user_id', $friend_id)
                    ->where('p.style_id','like',  '%' . $style_id . '%')
                    ->where('p.expiredtime', '>=', $currenttime)
                    ->orderby('p.createdtime', 'desc')->get();
                $saveposts = \DB::table('saves as s')
                    ->leftJoin('posts as p', 'p.id', '=', 's.post_id')
                    ->select('p.id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4')
                    //->where('s.user_id', $user_id)
                    ->where('p.user_id', $friend_id)
                    ->where('p.style_id','like', '%' . $style_id . '%')
                    ->orderby('s.id', 'desc')->get();
                $user = \DB::table('users')->where('name', $friend_id)->select('first_name', 'last_name', 'avatar')->first();

                $data = array('code' => 1, 'activeposts' => $activeposts, 'saveposts'=>$saveposts, 'user'=>$user);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public function getbrand(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $brand = \DB::table('brand')->select(['brand'])->get();
                if(empty($brand)) {
                    $data = array('code' => 0, 'data' => 'There is not brand.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>1, 'data'=>$brand);
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function searchbrand(Request $request){
        $user_id = $request->get('user_id', '');
        $keyword = $request->get('keyword', '');
        $_token = $request->get('_token', '');
        $currenttime = time();

        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {

                if($keyword == '')
                    $brand_list = \DB::table('brand')
                        ->select('brand')
                        ->get();
                else
                    $brand_list = \DB::table('brand')
                        ->where('brand','like','%'.$keyword.'%')
                        ->select('brand')
                        ->get();

                $result = array();
                $result_list = array();
                foreach ($brand_list as $brand){
                    $result['brand'] = $brand->brand;
                    $brand_key =  ",".$brand->brand.",";
                    $c1 = \DB::table('posts as p')
                        ->select(DB::raw('p.brand'.',  as brands'))
                        ->where('brands','like','%'.$brand_key.'%')
                        ->where('expiredtime', '>=', $currenttime)
                        ->get()->count();
                    $c2 = \DB::table('saves as s')
                        ->leftJoin('posts as p', 'p.id', '=', 's.post_id')
                        ->where('p.brand','like','%'.$brand_key.'%')
                        ->get()->count();

                    $result['szpostwithbrand'] = $c1 + $c2;
                    array_push($result_list, $result);
                }
                usort($result_list, create_function('$a, $b',
                    'if ($a["szpostwithbrand"] == $b["szpostwithbrand"]) return 0; return ($a["szpostwithbrand"] > $b["szpostwithbrand"]) ? -1 : 1;'));

                $data = array('code' => 1, 'data' => $result_list);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public function getpostwithbrand(Request $request){
        $user_id = $request->get('user_id', '');
        $brand = $request->get('brand', '');
        $_token = $request->get('_token', '');
        $currenttime = time();
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($brand == ''){
            $data = array('code'=>0, 'data'=>'There is not brand.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $brand = ",".$brand.",";
                $activeposts = \DB::table('posts as p')
                    ->leftJoin('users as u', 'u.name', '=', 'p.user_id')
                    ->select('p.id', 'p.user_id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'p.location', 'p.createdtime', 'p.expiredtime', 'p.expiredhour', 'p.like1', 'p.like2', 'p.like3', 'p.like4', 'p.like5', 'p.like6', 'p.like7', 'p.like8', 'p.comment as commentcount', 'u.first_name', 'u.last_name', 'u.avatar')
                    ->where('p.brand','like','%'.$brand.'%')
                    ->where('p.expiredtime', '>=', $currenttime)
                    ->orderby('p.createdtime', 'desc')->get();
                $saveposts = \DB::table('saves as s')
                    ->leftJoin('posts as p', 'p.id', '=', 's.post_id')
                    ->select('p.id', 'p.subject', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4')
                    ->where('p.brand','like','%'.$brand.'%')
                    ->orderby('s.id', 'desc')->get();                

                $data = array('code' => 1, 'activeposts' => $activeposts, 'saveposts'=>$saveposts);                
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
   public function searchusers(Request $request){  
        $user_id = $request->get('user_id', '');
        $keyword = $request->get('keyword', '');
        $_token = $request->get('_token', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($keyword == ''){
            $data = array('code'=>0, 'data'=>'There is not keyword.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $block = \DB::table('block')->where('friend_id', $user_id)->get();
                $block_ids = array();
                foreach ($block as $item)
                    array_push($block_ids, $item->user_id);

                $sentinvite = "none";
                $friend1 = \DB::table('friends')->where('user_id', $user_id)->get();
                $friends = array();
                foreach ($friend1 as $value)
                    $friends[] = $value->friend_id;
                $users = \DB::table('users')
                    ->where('name', '!=', $user_id)
                    ->where(function($query) use ($keyword){
                        $query->where('first_name','like','%'.$keyword.'%')
                            ->orWhere('last_name','like','%'.$keyword.'%');
                    })
                    ->whereNotIn('name',$block_ids)
                    ->select('name as user_id', 'first_name', 'last_name', 'avatar','signup_ip_address as relation')
                    ->orderby('first_name')
                    ->get();

                foreach ($users as $user){
                    if (in_array($user->user_id, $friends))
                    {
                        $user->relation = "friend";
                    }
                    else
                    {
                        $invite = DB::table('notifications')->where('user_id',$user->user_id)->where('friend_id',$user_id)->where('type_id',2)->first();
                        $invite1 = DB::table('notifications')->where('user_id',$user_id)->where('friend_id',$user->user_id)->where('type_id',2)->first();
                        if(empty($invite) && empty($invite1)){
                            $user->relation = "none";
                        }
                        if(!empty($invite) && empty($invite1)){
                            $user->relation = "receive";
                        }
                        if(empty($invite) && !empty($invite1)){
                            $user->relation = "sent";
                        }
                        if(!empty($invite) && !empty($invite1)){
                            $user->relation = "sent";
                        }
                    }
                }
                $data = array('code' => 1, 'data' => $users);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public function getnotifications(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $pageno = $request->get('pageno', '');
        $num = $request->get('num', 10);
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($pageno == ''){
            $data = array('code'=>0, 'data'=>'There is not page number.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $start_num = (int)$pageno * (int)$num;
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $notifications = \DB::table('notifications as n')
                    ->leftJoin('posts as p', 'p.id', '=', 'n.post_id')
                    ->leftJoin('users as u', 'u.name', '=', 'n.user_id')
                    ->select('n.user_id','n.friend_id', 'n.post_id', 'n.type_id', 'n.content', 'p.photo', 'p.photo2', 'p.photo3', 'p.photo4', 'u.first_name', 'u.last_name', 'u.avatar')
                    ->where('n.friend_id', $user_id)
                    ->orderby('n.id', 'desc')
                    ->offset((int)$start_num)->limit((int)$num)
                    ->get();

                $data = array('code' => 1, 'data' => $notifications);
                return \GuzzleHttp\json_encode($data);
            }

        }
    }
    public  function deletefriend(Request $request){
        $user_id = $request->get('user_id','');
        $_token = $request->get('_token','');
        $friend_id = $request->get('friend_id','');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($user_id ==''){
            $data = array('code'=>0, 'data'=>'There is not user id.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id ==''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $friend = DB::table('friends')->where('user_id', $user_id)->where('friend_id', $friend_id)->where('status', 1)->first();
            if(empty($friend)){
                $data = array('code'=>0, 'data'=>'There is not friend.');
                return \GuzzleHttp\json_encode($data);
            }else{
                DB::table('friends')->where('user_id', $user_id)->where('friend_id', $friend_id)->delete();
                DB::table('friends')->where('user_id', $friend_id)->where('friend_id', $user_id)->delete();
                $noti = DB::table('notifications')->where('user_id',$user_id)->where('friend_id',$friend_id)->where('type_id',5)->first();
                if(!empty($noti)){
                    DB::table('notifications')->where('user_id',$user_id)->where('friend_id',$friend_id)->where('type_id',5)->delete();
                }
                    
                $noti2 = DB::table('notifications')->where('user_id',$friend_id)->where('friend_id',$user_id)->where('type_id',5)->first();
                if(!empty($noti2)){
                    DB::table('notifications')->where('user_id',$friend_id)->where('friend_id',$user_id)->where('type_id',5)->delete();
                }
                $data = array('code'=>1, 'data'=>'Successfully deleted.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public  function settingnotification(Request $request){
        $user_id = $request->get('user_id','');
        $public = $request->get('public','');
        $_token = $request->get('_token','');
        $mynotification = $request->get('mynotification','');
        $friendnotification = $request->get('friendnotification','');
        $styletab = $request->get('styletab','');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($user_id ==''){
            $data = array('code'=>0, 'data'=>'There is not user id.');
            return \GuzzleHttp\json_encode($data);
        }else if($public ==''){
            $data = array('code'=>0, 'data'=>'There is not public value.');
            return \GuzzleHttp\json_encode($data);
        }else if($mynotification ==''){
            $data = array('code'=>0, 'data'=>'There is not mynotification value.');
            return \GuzzleHttp\json_encode($data);
        }if($friendnotification ==''){
            $data = array('code'=>0, 'data'=>'There is not friendnotification value.');
            return \GuzzleHttp\json_encode($data);
        }if($styletab ==''){
            $data = array('code'=>0, 'data'=>'There is not styletab value.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $notification_setting = DB::table('profiles')->where('user_id', $user_id)->first();
                if(empty($notification_setting)){
                    DB::table('profiles')->insert(['user_id'=>$user_id,'public'=>$public,'mynotification'=>$mynotification,'friendnotification'=>$friendnotification,'styletab'=>$styletab,'created_at'=>date('Y-m-d H:i:s')]);
                }else{
                    DB::table('profiles')->where('user_id', $user_id)
                        ->update(['public'=>$public,'mynotification'=>$mynotification,'friendnotification'=>$friendnotification,'styletab'=>$styletab]);
                }

                $data = array('code'=>1, 'data'=>'Successfully setted.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getnotificationsetting(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }if($user_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your User id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $noti = DB::table('profiles')->select(['public','mynotification','friendnotification','styletab'])->where('user_id', $user_id)->first();
            if(empty($noti)){
                $data = array('code'=>0, 'data'=>'There is not notification setting value.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $data = array('code'=>1, 'data'=>$noti);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public  function setprivacy(Request $request){
        $user_id = $request->get('user_id','');
        $_token = $request->get('_token','');
        $friends = $request->get('friends','');
        $style = $request->get('style','');
        $public = $request->get('public','');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($user_id ==''){
            $data = array('code'=>0, 'data'=>'There is not user id.');
            return \GuzzleHttp\json_encode($data);
        }else if($friends ==''){
            $data = array('code'=>0, 'data'=>'There is not friends value.');
            return \GuzzleHttp\json_encode($data);
        }if($style ==''){
            $data = array('code'=>0, 'data'=>'There is not style value.');
            return \GuzzleHttp\json_encode($data);
        }if($public ==''){
            $data = array('code'=>0, 'data'=>'There is not public value.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $privacy = DB::table('privacy')->where('user_id', $user_id)->first();
                if(empty($privacy)){
                    DB::table('privacy')->insert(['user_id'=>$user_id,'friends'=>$friends,'style'=>$style,'public'=>$public,'created_at'=>date('Y-m-d H:i:s')]);
                }else{
                    DB::table('privacy')->where('user_id', $user_id)
                        ->update(['friends'=>$friends,'style'=>$style,'public'=>$public]);
                }

                $data = array('code'=>1, 'data'=>'Successfully setted.');
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function getprivacy(Request $request){
        $_token = $request->get('_token', '');
        $user_id = $request->get('user_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }if($user_id == ''){
            $data = array('code'=>0, 'data'=>'There is not your User id.');
            return \GuzzleHttp\json_encode($data);
        }else{
            $privacy = DB::table('privacy')->select(['friends','style','public'])->where('user_id', $user_id)->first();
            if(empty($privacy)){
                $data = array('code'=>0, 'data'=>'There is not privacy values.');
                return \GuzzleHttp\json_encode($data);
            }else{
                $data = array('code'=>1, 'data'=>$privacy);
                return \GuzzleHttp\json_encode($data);
            }
        }
    }
    public function report(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $post_id = $request->get('post_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($post_id == ''){
            $data = array('code'=>0, 'data'=>'There is not post id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $post = \DB::table('posts')->where('id', $post_id)->first();
                if(!empty($post)) {
                    $report = \DB::table('report')->where('post_id', $post_id)->where('user_id', $user_id)->first();
                    if (empty($report)) {
                        \DB::table('report')->insert(['user_id' => $user_id, 'post_id' => $post_id, 'created_at'=>date('Y-m-d H:i:s')]);
                    }
                    $data = array('code' => 1, 'data' => 'Successfully reported.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This post is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function block(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $friend_id = $request->get('friend_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $friend = DB::table('users')->where('name', $friend_id)->where('activated', 1)->first();
                if(!empty($friend)) {
                    $block = \DB::table('block')->where('user_id', $user_id)->where('friend_id', $friend_id)->first();
                    if (empty($block)) {
                        \DB::table('block')->insert(['user_id' => $user_id, 'friend_id' => $friend_id, 'created_at'=>date('Y-m-d H:i:s')]);
                    }
                    $data = array('code' => 1, 'data' => 'Successfully blocked.');
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This friend is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public function unblock(Request $request){
        $user_id = $request->get('user_id', '');
        $_token = $request->get('_token', '');
        $friend_id = $request->get('friend_id', '');
        if($_token == ''){
            $data = array('code'=>0, 'data'=>'Failed token.');
            return \GuzzleHttp\json_encode($data);
        }else if($friend_id == ''){
            $data = array('code'=>0, 'data'=>'There is not friend id.');
            return \GuzzleHttp\json_encode($data);
        }else {
            $user = DB::table('users')->where('name', $user_id)->where('token', $_token)->where('activated', 1)->first();
            if(empty($user)){
                $data = array('code'=>0, 'data'=>'Failed token.');
                return \GuzzleHttp\json_encode($data);
            }else {
                $friend = DB::table('users')->where('name', $friend_id)->where('activated', 1)->first();
                if(!empty($friend)) {
                    $block = \DB::table('block')->where('user_id', $user_id)->where('friend_id', $friend_id)->first();
                    if (!empty($block)) {
                        \DB::table('block')->where('user_id', $user_id)->where('friend_id', $friend_id)->delete();
                        $data = array('code' => 1, 'data' => 'Successfully unblocked.');
                    }else{
                        $data = array('code' => 0, 'data' => 'Not block.');
                    }
                    return \GuzzleHttp\json_encode($data);
                }else{
                    $data = array('code'=>0, 'data'=>'This friend is deleted just.');
                    return \GuzzleHttp\json_encode($data);
                }
            }
        }
    }
    public static function calculateToExpire(){
        $now = time();
        $remaintime = time() + 20 * 60;
        $posts = \DB::table('posts')->where('expiredtime', '<', $remaintime)->where('expiredflg', 0)->get();
        $usertopics = array();
        $userids = array();
        $postids = array();
        foreach($posts as $post){
            $userids[] = $post->user_id;
            $usertopics[] = 'dressd_'.$post->user_id;
            $postids[] = $post->id;
            \DB::table('posts')->where('id', $post->id)->update(['expiredflg'=>1]);
        }
        if(!empty($usertopics)){
            //notication logic
            $title = 'Notification';
            $body = 'Your outfit is about to expire.';
            foreach($userids as $key=>$userid){
                \DB::table('notifications')->insert(['user_id'=>$userid, 'friend_id'=>$userid, 'post_id'=>$postids[$key], 'type_id'=>6, 'content'=>$body, 'acceptflg'=>0, 'created_at'=>date('Y-m-d H:i:s')]);
            }
            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)
                ->setSound('default');

            $notification = $notificationBuilder->build();

            $topic = new Topics();
            $topic->topic($usertopics[0])->andTopic(function($condition) use($usertopics){
                $i = 0;
                foreach($usertopics as $key=>$usertopic){
                    if($i != 0)
                        $condition->topic($usertopic);
                    $i++;
                }
            });
            $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
        }
        

    }
    public static function calculateExpired(){
        $now = time();
        $remaintime = time();
        $posts = \DB::table('posts')->where('expiredtime', '<', $remaintime)->where('expiredflg', 1)->get();
        $usertopics = array();
        $userids = array();
        $postids = array();
        foreach($posts as $post){
            $userids[] = $post->user_id;
            $usertopics[] = 'dressd_'.$post->user_id;
            $postids[] = $post->id;
            \DB::table('posts')->where('id', $post->id)->update(['expiredflg'=>3]);
        }
        if(!empty($usertopics)){
            //notication logic
            $title = 'Notification';
            $body = 'Your outfit expired.';
            foreach($userids as $key=>$userid){
                \DB::table('notifications')->where('post_id', $postids[$key])->where('user_id', $userid)->where('type_id', 6)->delete();
            }
            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)
                ->setSound('default');

            $notification = $notificationBuilder->build();

            $topic = new Topics();
            $topic->topic($usertopics[0])->andTopic(function($condition) use($usertopics){
                $i = 0;
                foreach($usertopics as $key=>$usertopic){
                    if($i != 0)
                        $condition->topic($usertopic);
                    $i++;
                }
            });
            $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
        }
        

    }
}
