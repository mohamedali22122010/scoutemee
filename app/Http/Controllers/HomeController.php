<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\ThreadUsers;
use Auth;
use File;
use Storage;
use Carbon\Carbon;
use App\Profile;
use AWS;
use Location;
use App\YoutubeApi;
use Response;
use App\User;
use App\SearchLog;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requset)
    {
        $profileModel = new Profile;
        $profiles = Profile::cloudSearch(array('search'=>"matchall"),"structured");
        return view('home',compact('profiles','profileModel'));
    }
    
    public function youtubeCallBack(Request $request)
    {
       $code = $request->get('code');
        if(is_null($code)) {
            throw new Exception('$_GET[\'code\'] is not set.');
        } else {
            $YoutubeApi = new YoutubeApi;
            $token = $YoutubeApi->authenticate($code);
            $YoutubeApi->saveAccessTokenToDB($token);
        }
        return redirect('/');
    }
    
    // function to count messages in all site
    public function compose(View $view)
    {
        if(Auth::check())
        {
            $unReadMessageCount = ThreadUsers::where('is_read','=',0)->where('thread_users.user_id','=',Auth::user()->id)->count();
            $view->with('unReadMessageCount', $unReadMessageCount);
        }
        $profilesCount = Profile::count();
        $view->with('profilesCount', $profilesCount);
    }
    
    
    public function search(Request $request)
    {
        $profileModel = new Profile;
        $page = $request->page?$request->page:1;
        if($request->search)
        {
            $query = array('search'=>$request->search,'latitude'=>$request->latitude,'longitude'=>$request->longitude);
            if($request->sort_by)
                $query['sort'] = $request->sort_by;
            $profiles = Profile::cloudSearch($query,'simple',$page,9);
            $view = 'search_result';
        }else if($request->location)
        {
            $profiles = Profile::cloudSearch(array('search'=>'matchall','latitude'=>$request->latitude,'longitude'=>$request->longitude),'structured',$page,9);
            $view = 'search_result';
        }else{
            $profiles = Profile::cloudSearch(array('search'=>"matchall"),"structured",$page,8);
            $view = 'search';
            
        }
        /*if(count($profiles) == 0){
            $profiles = Profile::cloudSearch(array('search'=>"matchall"),"structured",$page,9);
            $view = 'search_result';
        }*/
        if(count($profiles) == 0) {
            // add log to search here
            $log = new SearchLog;
            $log->fill($request->except('_token'));
            $log->search_key = !$request->search ? '' : $request->search;
            $log->type = "Search";
            $log->save();
        }
        return view($view,compact('profiles','profileModel'));
    }
    
    public function advancedSearch(Request $request)
    {
        $profileModel = new Profile;
        $page = $request->page?$request->page:1;
        if($request)
        {
            $q = "";
            $concated = false;
            if($request->name){
                $concated = true;
                $q .= "(and (phrase field='full_name' '".$request->name."') ";
            }
            if($request->geners){
                $q .= "(and (phrase field='geners' '".$request->geners."')";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            if($request->role){
                $q .= "(and (phrase field='role' '".$request->role."') ";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            if($request->influenced_by){
                $q .= "(and (phrase field='influnced_by' '".$request->influenced_by."') ";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            if($request->gender){
                $q .= "(and (phrase field='gender' '".$request->gender."') ";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            if($request->live_performance){
                $q .= "(and (phrase field='services' 'live_performances') ";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            if($request->music_lessons){
                $q .= "(and (phrase field='services' 'music_lessons') ";
                if($concated)
                    $q .= ")";
                $concated = true;
            }
            $q .= ")";
            if(!$concated)
                $q ='matchall';
            $query = array('search'=>$q,'latitude'=>$request->latitude,'longitude'=>$request->longitude);
            if($request->sort_by)
                $query['sort'] = $request->sort_by;
            $profiles = Profile::cloudSearch($query,'structured',$page,9);
            $view = 'search_result';
        }else if($request->location)
        {
            $profiles = Profile::cloudSearch(array('search'=>'matchall','latitude'=>$request->latitude,'longitude'=>$request->longitude),'structured',$page,9);
            $view = 'search_result';
        }else{
            $profiles = Profile::cloudSearch(array('search'=>"matchall"),"structured",$page,9);
            $view = 'search_result';
            
        }
        if ($request->ajax()) {
            return Response::json(array('view'=>view('partials.profile.searchLists', array('profiles'=>$profiles,'profileModel' => new Profile))->render(),'counts'=>count($profiles)));
        }
        if(count($profiles) == 0) {
            // add log to search here
            $log = new SearchLog;
            $log->fill($request->except('_token'));
            $log->search_key = !$request->name ? '' : $request->name;
            $log->type = "advanced Search";
            $log->save();
        }
        return view($view,compact('profiles','profileModel'));
    }
    
    public function autoComplete(Request $request)
    {
        $client = AWS::createClient('CloudSearchDomain');
       $result = $client->suggest([
            'query' => $request->q, // REQUIRED
            'size' => 10,
            'suggester' => 'name', // REQUIRED
        ]);
        if($result->toArray()['suggest']['suggestions'] && !empty($result->toArray()['suggest']['suggestions'])){
            $return = array();
            foreach ($result->toArray()['suggest']['suggestions'] as $key => $value) {
               $return[] = $value['suggestion'];
            }
            return json_encode($return);
        }
    }
    
    public function aboutUs()
    {
       return view('about_us');
    }
    
    public function contactUs()
    {
       return view('contact_us');
    }
    
    public function privacy()
    {
       return view('privacy');
    }
    
    public function terms()
    {
       return view('terms');
    }
    
    public function faq()
    {
       return view('full_faq');
    }
    
    public function confirmCode(Request $request)
    {
       if( ! $request->confirmation_code)
        {
            abort(404,"Invalid Confirmation Code");
        }

        $user = User::where("confirmation_code","=",$request->confirmation_code)->first();
        if ( ! $user)
        {
            abort(404,"Invalid Confirmation Code");
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        
        if($user->type == 1){ // for music lover
            $view = "music_lover";
        }if($user->type == 2){ // for musican
            $view = "musican";
        }else{
            $view = "music_lover";
        }
        
        \Mail::send('mail.register_'.$view, $user->getAttributes() , function($message) use ($user) {
            $message->to($user->email, $user->first_name." ".$user->last_name)
                ->subject('THANK YOU FOR REGISTERING');
        });

        if ($user->type == 2) {
            // for musican
            return redirect('profile/create');
        } else {
            // for music lover
            return redirect('search');
        }
    }
}
