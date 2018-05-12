<?php
use App\SocialAccount;
use App\YoutubeApi;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Illuminate\Support\Facades\Session;
// use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');
    Route::get('/about_us', 'HomeController@aboutUs');
    Route::get('/contact_us', 'HomeController@contactUs');
    Route::get('/privacy', 'HomeController@privacy');
    Route::get('/terms', 'HomeController@terms');
    Route::get('/faq', 'HomeController@faq');
    
    Route::get('/redirect', 'SocialAuthController@redirect');
    Route::get('/redirect/{type}', 'SocialAuthController@redirect');
    Route::get('/callback', 'SocialAuthController@callback');
    Route::get('/youtubeCallBack', 'HomeController@youtubeCallBack');
    Route::get('/google/getAccessToken', function()
    {
        $YoutubeApi = new YoutubeApi;
        return redirect()->to($YoutubeApi->createAuthUrl());
    });
    Route::get('/user_webhook', 'SocialAuthController@userWebhook');
    Route::get('/page_webhook', 'SocialAuthController@getPageWebhook');
    Route::post('/page_webhook', 'SocialAuthController@postPageWebhook');
    Route::get('/callBackSubscriptions', 'SocialAuthController@callBackSubscriptions');
    Route::get('/subscriptions', 'SocialAuthController@subscriptions');
    Route::get('/test','ProfileController@cloudSearch');
    Route::get('/home', 'HomeController@index');
    
    Route::post('/connectfacebook', 'SocialAuthController@connectProfileWithFacebook');
    
    Route::get('changePassword', 'ProfileController@showChangePasswordForm');
    Route::post('changePassword', 'ProfileController@postChangePassword');
    Route::get('register/verify/{confirmation_code}','HomeController@confirmCode');
    
    Route::get('/profile/redirect','ProfileController@redirect');
    Route::post('/profile/postAddAjax','ProfileController@postAddAjax');
    Route::post('/profile/post','ProfileController@addPost');
    
    Route::post('/profile/{profile_id}/follow','ProfileController@follow');
    Route::post('/profile/{profile_id}/unfollow','ProfileController@unfollow');
    
    Route::get('/profile/{profile_id}/music','ProfileController@showMusics');
    Route::get('/profile/{profile_id}/videos','ProfileController@showVideos');
    Route::get('/profile/{profile_id}/images','ProfileController@showImages');
    Route::get('/profile/{profile_id}/about','ProfileController@showAbout');
    Route::get('/profile/post/{post_id}','ProfileController@postDetails');
    
    Route::get('/profile/event/edit/{id}','ProfileController@editEvent');
    Route::post('/profile/event/edit/{id}',array('as'=>'event.update','uses'=>'ProfileController@updateEvent'));
    
    Route::get('/profile/addevent','ProfileController@addEvent');
    Route::post('/profile/addevent',array('as'=>'event.store','uses'=>'ProfileController@storeEvent'));
    Route::get('/profile/{profile_id}/events','ProfileController@events');
    Route::post('/profile/event/delete','ProfileController@deleteEventAjax');
    Route::post('/profile/post/delete','ProfileController@deletePostAjax');
    Route::post('/profile/post/like','ProfileController@likePostAjax');
    Route::post('/profile/rate','ProfileController@rateProfileAjax');
    
    Route::post('/profile/review', array('as'=>'profile.postReview','uses'=>'ProfileController@postReviewAjax'));
    
    Route::get('/profile/boost','ProfileController@boostProfile');
    Route::post('/profile/boost', array('as'=>'profile.boost','uses'=>'ProfileController@doBoostProfile'));
    
    Route::resource('/profile','ProfileController');
    Route::get('/message/{userid}/send', array('as'=>'message.create','uses'=>'MessageController@sendSingleMessage'));
    //Route::get('/message/{threadId}/reply', array('as'=>'message.create','uses'=>'MessageController@sendGroupMessage'));
    Route::get('/message', array('as'=>'message.index','uses'=>'MessageController@index'));
    Route::get('/message/index', array('as'=>'message.index','uses'=>'MessageController@index'));
    Route::get('/message/unread','MessageController@unread');
    Route::get('/message/{id}',array('as'=>'message.view','uses'=>'MessageController@view'));
    Route::post('/message', array('as'=>'message.store','uses'=>'MessageController@store'));
  //  Route::get('/search', array('as'=>'search.index','uses'=>'ProfileController@search'));
    Route::any('/search', array('as'=>'search.index','uses'=>'HomeController@search'));
    Route::any('/advanced-search', array('as'=>'search.advanced','uses'=>'HomeController@advancedSearch'));
    //Route::get('/autocomplete/{any?}','HomeController@autoComplete');
    
    Route::get('/testing',function(){
       $client = AWS::createClient('CloudSearchDomain');
       $result = $client->suggest([
            'query' => 'The', // REQUIRED
            'suggester' => 'name', // REQUIRED
        ]);
        //dd($result);
    });

    Route::get('/location',function(){
        $headers = Request::header();
        if (array_key_exists('x-forwarded-for', $headers))
            $ip = Request::header('x-forwarded-for');
        else
            $ip = Request::ip();

        $arrayOfIps = explode(",", $ip);
        $location = Location::get($arrayOfIps[0]);
        $latitude = $location->latitude;
        $longitude = $location->longitude;
        $result = array(
            'session' => Session::get('location'),
            'latitude' => $latitude,//Location::get(explode(",", $ip)[0])->latitude,
            'longitude' => $longitude,//Location::get(explode(",", $ip)[0])->longitude,
            'ip1' => $arrayOfIps[0],
            'ip2' => $ip
        );
        $result2 = array(
            'latitude' => Location::get($arrayOfIps[0])->latitude,
            'longitude' => Location::get($arrayOfIps[0])->longitude,
            'ip1' => $arrayOfIps[0],
            'ip2' => $ip
        );
        dd($result,$result2,Location::get($arrayOfIps[0]),Location::get());
    });

    Route::any('/request',function() {
        return Response::json(Request::header());
    });

});

