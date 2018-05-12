<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Illuminate\Support\Facades\Session;
use Facebook\FacebookSession as FacebookSession;
use Facebook\FacebookRedirectLoginHelper as FacebookRedirectLoginHelper;
use Facebook\FacebookRequest as FacebookRequest;
use Facebook\FacebookResponse as FacebookResponse;
use Facebook\FacebookSDKException as FacebookSDKException;
use Facebook\FacebookRequestException as FacebookRequestException;
use Facebook\FacebookAuthorizationException as FacebookAuthorizationException;
use Facebook\GraphObject as GraphObject;
use Facebook\Facebook as Facebook;
use Facebook\FacebookApp;
use Storage;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $type = Session::get('type');
        if(!$type){
            $type=1;
        }
        $account = SocialAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook',
                'token'    => $providerUser->token
            ]);
            $facebookEmail = $providerUser->getEmail();
            if(!$facebookEmail)
                $facebookEmail = $providerUser->getId().".facebook.com";

            $user = User::whereEmail($facebookEmail)->first();
            if (!$user) {
                $confirmation_code = str_random(30);

                $user = User::create([
                    'email' => $facebookEmail,
                    'first_name' => $providerUser->getName(),
                    'type' => $type,
                    'confirmation_code' => $confirmation_code,
                ]);
                
                if($type == 1){ // add image to user if is music lover
                    $facebook = new Facebook([
                      'app_id' => config('services.facebook.client_id'),
                      'app_secret' => config('services.facebook.client_secret'),
                      'default_graph_version' => 'v2.5',
                    ]);
                    $facebook->setDefaultAccessToken($providerUser->token);
                    $request = $facebook->get('/'.$providerUser->getId().'/picture?type=large');
                    if(isset($request->getHeaders()['Location'])){
                        $mdName = md5(time().rand(1, 100)).'.jpg';
                        $name = 'profiles/images/facebook/'.$mdName;
                        Storage::put($name,file_get_contents($request->getHeaders()['Location']),'public');
                        $user->user_image = $name;
                        $user->save();
                    }
                }
                $data = array('email'=>$facebookEmail,'first_name'=>$providerUser->getName(),'confirmation_code' => $confirmation_code);
                if($type == 1){ // for music lover
                    $view = "music_lover";
                }if($type == 2){ // for musican
                    $view = "musican";
                }else{
                    $view = "music_lover";
                }
                
                if($data['email'] && strpos($data['email'], '@')  !== false) {
                    \Mail::send('mail.verify_'.$view, $data, function($message) use ($data) {
                        $message->to($data['email'], $data['first_name'])
                            ->subject('VERIFY YOUR EMAIL');
                    });
                }
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}