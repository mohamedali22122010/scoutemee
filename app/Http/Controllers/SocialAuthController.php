<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SocialAccountService;

use Socialite;
use File;
use Storage;
use Log;
use App\Profile;
use App\SocialAccount;
use Auth;
use App\Post;
use App\Like;

class SocialAuthController extends Controller
{
    public function redirect(Request $request)
    {
        Session::put('type', $request->type);
        Session::put('lastUrl', \URL::previous());
        Session::save();
        $scopes = ['email'];
        if($request->type == 2){
            $scopes = ['email','user_about_me','manage_pages'];
        }
        return Socialite::driver('facebook')->scopes($scopes)->redirect();   
    }   

    public function callback(SocialAccountService $service, Request $request)
    {
        $type = Session::get('type');
        $lastUrl = Session::get('lastUrl');
        // when facebook call us a with token   
        if($request->error && $request->error_description && $request->error_reason){
            return redirect()->to('/')->withError($request->error_description);
        }
        try{
            $driverData = Socialite::driver('facebook')->fields(['bio','about','name', 'email', 'gender', 'verified'])->user();
        }catch (\Laravel\Socialite\Two\InvalidStateException $ex) {
            return redirect()->to('/')->withError("Error Auth");
        }
        $user = $service->createOrGetUser($driverData);
        Session::forget('type');
        Session::forget('lastUrl');
        Session::save();

        auth()->login($user);
        if($lastUrl && $lastUrl != env('APP_URL') ){
            if(strpos($lastUrl,'register') !== false){
                // do nothing and continue code to redirect by type 
            }else{
                return redirect()->to($lastUrl);
            }
        }
        if($type == 1) // this mean regular user
        {
            return redirect()->to('/search');
        }
        $profile = Profile::where("user_id",'=',$user->id)->first();
        if($profile)
        {
            return redirect()->to("/profile/$profile->profile_url");
        }
        $UserData = $driverData->getRaw();
        

        return redirect()->to('/profile/create')->withInput(['full_name'=>@$UserData['name'],'about'=>@$UserData['bio'],'gender'=>@$UserData['gender']]);
    }
    
    public function userWebhook()
    {
        $challenge = @$_REQUEST['hub_challenge'];
        $verify_token = @$_REQUEST['hub_verify_token'];
        
        if ($verify_token === '5ebf2484bea55b74106e5e95c53e1a1e') {
          echo $challenge;
        }
        
        //$input = json_decode(file_get_contents('php://input'), true);
        //error_log(print_r($input, true));
        //dd($_POST)
         //return time();
         /*
        // when facebook call us a with token   
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());

        auth()->login($user);

        return redirect()->to('/home');*/
    }
    
    public function getPageWebhook()
    {
        $challenge = @$_REQUEST['hub_challenge'];
        $verify_token = @$_REQUEST['hub_verify_token'];
        
        if ($verify_token === '5ebf2484bea55b74106e5e95c53e1a1e') {
          return $challenge;
        }else{
            return false;
        }
    }
    
    public function postPageWebhook()
    {
        //File::put(storage_path().'/postPage.php',file_get_contents("php://input"));
        $data = json_decode(file_get_contents("php://input"),true);
        $pageId = $data['entry'][0]['id'];
        $field = $data['entry'][0]['changes'][0]['field'];
         
        if($field == 'feed'){
            $value = $data['entry'][0]['changes'][0]['value'];
            if($value['verb'] == 'add' && isset($value['sender_id']) ){
                $post = new Post;
                $profile = Profile::where("facebook_page","=","https://www.facebook.com/".$value['sender_id'])->where("facebook_subscribe","=",1)->first();
                if($profile){
                    $post->profile_id = $profile->id;
                    if($value['item'] == 'photo'){
                        $post->type = 3;
                        $name = "posts/images/".basename($value['link']);
                        if(app()->environment('local'))
                            $name = "testing/".$name;
                        Storage::put($name,file_get_contents($value['link']));
                        $post->link_url = $name;
                    }
                    else if($value['item'] == 'share' && strpos($value['link'], 'youtube') > 0){
                        $post->type = 1;
                        $post->link_url = $value['link'];
                    }else{
                        $post->type = 0;
                    }
                    $post->description = $value['message'];
                    $post->external_provider = "facebook";
                    $post->facebook_post_id = $value['post_id'];
                    if($value['published'])
                        $post->save();
                    //$value['post_id']; // facebook post id;
                    //$value['item']; // facebook item [status,photo,share,post]
                    //$value['sender_id']; // facebook page id;
                    //$value['published']; // facebook published status;
                    //$value['message']; // facebook post text;
                    
                }
            }
            if($value['verb'] == 'remove'){
                $post = Post::where('facebook_post_id','=',$value['post_id'])->first();
           if($post){
               if ($post->type == 3 && Storage::exists($post->link_url))
               {
                   Storage::Delete($post->link_url);
               }
               Like::where('post_id','=',$post->id)->delete();
               $post->delete();
           }
            }
        }
    }
    
    public function connectProfileWithFacebook(Request $request)
    {
        /* because of douplication of connected user with facebook 
        $account = SocialAccount::whereProvider('facebook')
            ->whereProviderUserId($request->facebook_id)
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'user_id'=>Auth::user()->id,
                'provider_user_id' => $request->facebook_id,
                'provider' => 'facebook',
                'token'    => $request->token
            ]);
            $account->save();
        }*/
    }
    
    public function callBackSubscriptions()
    {
        
    }
}
