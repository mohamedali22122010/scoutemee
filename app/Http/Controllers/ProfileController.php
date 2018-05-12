<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Carbon\Carbon;
use App\Profile;
use App\Follow;
use Vinkla\Vimeo\Facades\Vimeo;

use App\Http\Requests;
use Response;
use Auth;
use File;
use Storage;
use AWS;
use Location;
use App\Post;
use App\YoutubeApi;
use Youtube;
use Image;
use App\Event;
use App\Review;
use App\Like;
use App\Rate;
use App\Jobs\ImportYoutubeVideo;
use App\Jobs\ImportSoundCloudTracks;

class ProfileController extends HomeController
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['show', 'showVideos', 'showMusics', 'showImages','showAbout','postDetails']]);
    }
    
    public function index(Request $request)
    {
        $profile = Profile::where('user_id','=',Auth::user()->id)->first();
        if($profile)
        {
            return redirect()->to("/profile/$profile->id");
        }
        return redirect()->to("/");
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $profile = Profile::where('user_id','=',Auth::user()->id)->first();
        if($profile){
            return redirect()->route('profile.edit',$profile->id);
        }else{
            $profile = new Profile;
            return view('profile.create',compact('profile'));
        }
    }
    
    public function redirect()
    {
        if(\URL::previous() != env('APP_URL')){
            if(strpos(\URL::previous(),'register') !== false){
                // do nothing and continue code to redirect by type 
            }else{
                return redirect()->back();
            }
        }
        if(Auth::user()->type == 1) // this mean regular user
        {
            return redirect()->to('/search');
        }
        $profile = Profile::where('user_id','=',Auth::user()->id)->first();
        if($profile){
            return redirect()->to("/profile/$profile->profile_url");
        }else{
            return redirect()->to('profile/create');
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        $profile = new Profile;
        $profile->user_id = Auth::user()->id;
        $profile->fill($request->except('_token'));
        $influnced_by = [];
        if($request->influnced_by && !empty($request->influnced_by)){
            array_filter($request->influnced_by);
            foreach ($request->influnced_by as $value) {
                $influnced_by[] = $value;
            }
        }
        $profile->influnced_by=array_values($influnced_by);
        $services = [];
        foreach ($profile->servicesNames as $name=>$value) {
               if($request->{$name."_subservice_name"} && !empty($request->{$name."_subservice_name"})){
                    foreach ($request->{$name."_subservice_name"} as $key=>$service) {
                        if($service && $request->{$name."_subservice_cost"}[$key])
                            $services[$name][$service] = $request->{$name."_subservice_cost"}[$key];
                }
            }
        }
        $profile->services=$services;
        if ($request->hasFile('profile_image')) {
            $profile_image = [];
            $profile_image['x']=$request->get('profile_image_x');
            $profile_image['y']=$request->get('profile_image_y');
            $profile_image['width']=$request->get('profile_image_width');
            $profile_image['height']=$request->get('profile_image_height');
            
            $profile->profile_image = $this->cropImageAndSave(File::get($request->file('profile_image')->getRealPath()),$profile_image);
            
        }
        if ($request->hasFile('profile_video')) {
            $params = [
                'title' => Auth::user()->first_name." ".Auth::user()->last_name.' profile video',
                'description' => 'My profile video in scoutemee',
                'tags' => [
                    'scoutemee',
                    'awesome' // Of course!
                ]
            ];
            
            $YoutubeApi = new YoutubeApi;
            if($YoutubeApi->getLatestAccessTokenFromDB())
            {
                $id = $YoutubeApi->upload($request->file('profile_video'), $params);
                $profile->profile_video = "https://www.youtube.com/watch?v=".$id;
            }else{
                $profile->profile_video = $this->UploadFile($request, 'profile_video','profiles/videos');
            }
            
        }else{
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $request->profile_video, $matches);
            if(isset($matches[1])){
                $profile->profile_video = "https://www.youtube.com/watch?v=".$matches[1];
            }else{
                $profile->profile_video = $request->profile_video;
            }
        }
        if ($request->hasFile('profile_background')) {
            $profile->profile_background = $this->UploadFile($request, 'profile_background','profiles/background');
            
        }
        $profile->location = str_replace(substr(strrchr($profile->location, ","), 0),"",$profile->location);
        $profile->save();
        $user = Auth::user();
        $user->type = 2;
        $user->save();
        \Mail::later(86400,'mail.boost_profile', [], function($message) use($user){
                $message->to($user->email)->subject('BOOST YOUR PROFILE');
            });
        return redirect()->route('profile.show',$profile->id);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //
        $profile = Profile::where('id','=',$id)->orWhere('profile_url','=',$id)->firstOrFail();
        if($profile->profile_url &&  $profile->profile_url != $id)
        {
            return redirect()->to("/profile/$profile->profile_url");
        }
        $posts = $profile->posts()->orderBy('id','desc')->paginate(10);
        if ($request->ajax()) {
            return Response::json(view('partials.profile.userPosts', array('posts' => $posts,'profile'=>$profile))->render());
        }
        if($profile->user_id != @Auth::user()->id)
        {
            \DB::table('profiles')->where('id','=',$id)->orWhere('profile_url','=',$id)->increment('count_views');
        }
        if($profile->count_views % 10 == 0){
            $profile->trending_score = Profile::calculateTrinding($profile->id);
            $profile->save();
        }
        $events = $profile->events()->orderBy('id','desc')->paginate(10);
        return view('profile.show',compact('profile','posts','events'));
    }
    
    public function showVideos(Request $request,$id)
    {
        $profile = Profile::where('id','=',$id)->orWhere('profile_url','=',$id)->firstOrFail();
        if($profile->profile_url &&  $profile->profile_url != $id)
        {
            return redirect()->to("/profile/$profile->profile_url/videos");
        }
        $posts = $profile->posts()->where('type',1)->orderBy('id','desc')->paginate(10);
        if ($request->ajax()) {
            return Response::json(view('partials.profile.userPosts', array('posts' => $posts,'profile'=>$profile))->render());
        }
        $events = $profile->events()->orderBy('id','desc')->paginate(10);
        return view('profile.videos',compact('posts','profile','events'));
    }
    
    public function showMusics(Request $request,$id)
    {
        $profile = Profile::where('id','=',$id)->orWhere('profile_url','=',$id)->firstOrFail();
        if($profile->profile_url &&  $profile->profile_url != $id)
        {
            return redirect()->to("/profile/$profile->profile_url/music");
        }
        $posts = $profile->posts()->where('type',2)->orderBy('id','desc')->paginate(9);
        if ($request->ajax()) {
            return Response::json(view('partials.profile.userPosts', array('posts' => $posts,'profile'=>$profile))->render());
        }
        $events = $profile->events()->orderBy('id','desc')->paginate(10);
        
        return view('profile.musics',compact('posts','profile','events'));
    }
    
    public function showImages(Request $request,$id)
    {
        $profile = Profile::where('id','=',$id)->orWhere('profile_url','=',$id)->firstOrFail();
        if($profile->profile_url &&  $profile->profile_url != $id)
        {
            return redirect()->to("/profile/$profile->profile_url/images");
        }
        $images = $profile->posts()->where('type',3)->orderBy('id','desc')->paginate(9);
        if ($request->ajax()) {
            return Response::json(view('partials.profile.images', array('images' => $images,'profile'=>$profile))->render());
        }
        return view('profile.images',compact('images','profile'));
    }
    
    public function showAbout($id)
    {
        $profile = Profile::where('id','=',$id)->orWhere('profile_url','=',$id)->firstOrFail();
        if($profile->profile_url &&  $profile->profile_url != $id)
        {
            return redirect()->to("/profile/$profile->profile_url/about");
        }
        $events = $profile->events()->orderBy('id','desc')->paginate(10);
        return view('profile.about',compact('profile','events'));
    }
    
    public function postReviewAjax(Request $request)
    {
       $this->validate($request,['review'=>'required']);
       if(Auth::check()){
           $review = new Review;
           $review->profile_id = $request->profile_id;
           $review->user_id = Auth::user()->id;
           $review->review = $request->review;
           $review->save();
           return Response::json(array('success'=>true,'data'=>view('partials.profile.review_row',compact('review'))));
       }
       
    }
    public function postDetails($post_id)
    {
       $post = Post::findOrFail($post_id);
       if($post){
           if($post->type == 1)
           {
               $view = 'video_details';
           }
           if($post->type == 2)
           {
               $view = 'music_details';
           }
           if($post->type == 3)
           {
               $view = 'photo_details';
           }
           return view('partials.profile.'.$view,compact('post'));
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $profile = Profile::where('user_id','=',Auth::user()->id)->where(function($query) use ($id)
            {
                $query->where('id','=',$id)->orWhere('profile_url','=',$id);
            })->first();
        if($profile){
            if($profile->profile_url &&  $profile->profile_url != $id)
            {
                return redirect()->route('profile.edit',$profile->profile_url);
            }if($id != $profile->id){
                if($id != $profile->profile_url )
                    return redirect()->route('profile.edit',$profile->id);
            }
            return view('profile.edit',compact('profile'));
        }else{
            return redirect()->route('profile.create');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id)
    {
        $profile = Profile::findOrFail($id);
        if($profile->user_id != Auth::user()->id){
            return redirect()->to("/profile/$profile->id");
        }
        $oldProfile = clone $profile; 
        $profile->user_id = Auth::user()->id;
        $profile->fill($request->except('_token'));
        $influnced_by = [];
        if($request->influnced_by && !empty($request->influnced_by)){
            foreach ($request->influnced_by as $value) {
                $influnced_by[] = $value;
            }
        }
        $profile->influnced_by=array_values($influnced_by);
        $services = [];
        foreach ($profile->servicesNames as $name=>$value) {
               if($request->{$name."_subservice_name"} && !empty($request->{$name."_subservice_name"})){
                    foreach ($request->{$name."_subservice_name"} as $key=>$service) {
                        if($service && $request->{$name."_subservice_cost"}[$key])
                            $services[$name][$service] = $request->{$name."_subservice_cost"}[$key];
                }
            }
        }
        $profile->services=$services;
        if ($request->hasFile('profile_image')) {
            if($oldProfile->profile_image)
                Storage::Delete($oldProfile->profile_image);
            $profile_image = [];
            $profile_image['x']=$request->get('profile_image_x');
            $profile_image['y']=$request->get('profile_image_y');
            $profile_image['width']=$request->get('profile_image_width');
            $profile_image['height']=$request->get('profile_image_height');
            
            $profile->profile_image = $this->cropImageAndSave(File::get($request->file('profile_image')->getRealPath()),$profile_image);
            
        }
        if ($request->hasFile('profile_video')) {
            $params = [
                'title' => Auth::user()->first_name." ".Auth::user()->last_name.' profile video',
                'description' => 'My profile video in scoutemee',
                'tags' => [
                    'scoutemee',
                    'awesome' // Of course!
                ]
            ];
            
            $YoutubeApi = new YoutubeApi;
            if($YoutubeApi->getLatestAccessTokenFromDB())
            {
                $id = $YoutubeApi->upload($request->file('profile_video'), $params);
                $profile->profile_video = "https://www.youtube.com/watch?v=".$id;
            }else{
                $profile->profile_video = $this->UploadFile($request, 'profile_video','profiles/videos');
            }
            
        }else{
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $request->profile_video, $matches);
            if(isset($matches[1])){
                $profile->profile_video = "https://www.youtube.com/watch?v=".$matches[1];
            }else{
                $profile->profile_video = $request->profile_video;
            }
        }
        if ($request->hasFile('profile_background')) {
            $profile->profile_background = $this->UploadFile($request, 'profile_background','profiles/background');
            
        }
        $profile->location = str_replace(substr(strrchr($profile->location, ","), 0),"",$profile->location);
        $profile->user->type = 2;
        $profile->user->save();
        $profile->save();
        return redirect()->route('profile.show',$profile->id);
    }
    
    protected function cropImageAndSave($image,$coordinates,$width=210,$height=210)
    {
        $image=Image::make($image);
        $image->crop((int)$coordinates['width'],
                     (int)$coordinates['height'],
                     (int)$coordinates['x'],
                     (int)$coordinates['y']);
        $image->resize((int)$width, (int)$height);
        $mdName = md5(time().rand(1, 100)).'.jpg';
        $image->save(storage_path().'/app/'.$mdName);
        $name = 'profiles/images/cropped/'.$mdName;
        if(app()->environment('local'))
            $name = "testing/".$name;
        Storage::put($name,File::get(storage_path().'/app/'.$mdName),'public');
        @unlink(storage_path().'/app/'.$mdName);
        return $name;
    }
    
    protected function resizeImageAndSave($image,$path,$width=210,$height=210)
    {
        $image=Image::make($image);
        $image->resize((int)$width, (int)$height);
        $mdName = md5(time().rand(1, 100)).'.jpg';
        $image->save(storage_path().'/app/'.$mdName);
        $name = $path.$mdName;
        if(app()->environment('local'))
            $name = "testing/".$name;
        Storage::put($name,File::get(storage_path().'/app/'.$mdName));
        @unlink(storage_path().'/app/'.$mdName);
        return $name;
    }
    
    public function events($profile_id)
    {
       $profile = Profile::findOrFail($profile_id);
       $events = $profile->events()->orderBy('id','desc')->paginate(21);
       return view('profile.events.index',compact('events'));
    }
    
    public function addEvent(Request $request)
    {
        if(@Auth::user()->Profile->id){
            $event = new Event;
            $profile = Profile::findOrFail(Auth::user()->Profile->id);
            $events = $profile->events()->orderBy('id','desc')->paginate(21);
            if ($request->ajax()) {
                return Response::json(view('partials.profile.events', array('showActions'=>true,'events' => $events,'profile'=>$profile))->render());
            }
            return view('profile.events.create',compact('event','events','profile'));
        }else{
            return redirect()->route('profile.create');
        }
    }
    public function storeEvent(Request $request)
    {
        $this->validate($request,[
                'title'=>'required',
                'image'=>'required|image',
                'location'=>'required',
                'event_date'=>'required',
                ]);
        $event = new Event;
        $event->profile_id = Auth::user()->Profile->id;
        $event->fill($request->except('_token'));
        if($request->scoutmee_booked){
            $event->scoutmee_booked = 1;
        }
        if ($request->hasFile('image')) {
            $event->image = $this->resizeImageAndSave(File::get($request->file('image')->getRealPath()),'profiles/events/',100,100);
            //$event->image = $this->UploadFile($request, 'image','profiles/events');
        }
        $event->save();
        return redirect()->to('profile/addevent');
    }
    
    public function editEvent($id)
    {
        if(@Auth::user()->Profile->id){
            $event = Event::where('profile_id',Auth::user()->Profile->id)->findOrFail($id);
            $profile = Profile::findOrFail(Auth::user()->Profile->id);
            $events = $profile->events()->orderBy('id','desc')->paginate(10);
            return view('profile.events.edit',compact('profile','event','events'));
        }else{
            return redirect()->route('profile.create');
        }
    }
    public function updateEvent(Request $request,$id)
    {
        $this->validate($request,[
                'title'=>'required',
                'image'=>'image',
                'location'=>'required',
                'event_date'=>'required',
                ]);
        $event =  Event::where('profile_id',Auth::user()->Profile->id)->findOrFail($id);
        $oldEventImage = $event->image; 
        $event->fill($request->except('_token'));
        if($request->scoutmee_booked){
            $event->scoutmee_booked = 1;
        }else{
            $event->scoutmee_booked = 0;
        }
        if ($request->hasFile('image')) {
            if($oldEventImage)
                Storage::Delete($oldEventImage);
            $event->image = $this->resizeImageAndSave(File::get($request->file('image')->getRealPath()),'profiles/events/',100,100);
        }
        $event->save();
        return redirect()->to('profile/addevent');
    }
    
    public function deleteEventAjax(Request $request)
    {
        $response = array('message'=>'error deleting event','status'=>false);
       if(Auth::check() && isset(Auth::user()->Profile->id)){
           $event = Event::where('profile_id','=',Auth::user()->Profile->id)->find($request->event_id);
           if($event){
               if($event->image){
                   Storage::Delete($event->image);
               }
               $event->delete();
               $response = array('message'=>'event deleted successfully','status'=>true);
           }
       }
       return Response::json($response);
    }
    
    
    public function deletePostAjax(Request $request)
    {
        $response = array('message'=>'error deleting post','status'=>false);
        if(Auth::check() && isset(Auth::user()->Profile->id)){
           $post = Post::where('profile_id','=',Auth::user()->Profile->id)->find($request->post_id);
           if($post){
               if ($post->link_url && Storage::exists($post->link_url))
                {
                    Storage::Delete($post->link_url);
                }
                if($post->type == 1 && $post->videoType($post->link_url)== 'vimeo'){
                    $vimeo = Vimeo::request(str_replace("//player.vimeo.com/video", '/videos', $post->link_url), array(), 'DELETE');
                }
               Like::where('post_id','=',$request->post_id)->delete();
               $post->delete();
               $response = array('message'=>'post deleted successfully','status'=>true);
           }
       }
       return Response::json($response);
    }
    
    public function likePostAjax(Request $request)
    {
        $response = array('message'=>'error like this post','status'=>false);
        $post = Post::where('id','=',$request->post_id)->first();
        if($post && Auth::check()){
            if(!$post->isLiked($post->id)->count())
            {
                // do like
                $like = Like::create(array(
                    'user_id' => Auth::user()->id,
                    'post_id' => $post->id
                ));
                $response = array('message'=>'you liked this post','status'=>true,'type'=>'liked');
            }else {
               // un like 
               $unLike = Like::where('user_id','=',Auth::user()->id)->where('post_id','=',$post->id)->delete();
               $response = array('message'=>'you unliked this post','status'=>true,'type'=>'unliked');
            }
            
        }
        return Response::json($response);
    }
    
    public function rateProfileAjax(Request $request)
    {
        
        $profile = Profile::findOrFail($request->profile_id);
        if(Auth::check() && $profile->user_id != Auth::user()->id){
            $rate = Rate::where('profile_id','=',$request->profile_id)->where('user_id','=',Auth::user()->id)->first();
            if($rate){
                $rate->rate_value = $request->rate_value;
                $rate->save();
            }else{
                Rate::create(array(
                    'user_id' => Auth::user()->id,
                    'profile_id' => $profile->id,
                    'rate_value' => $request->rate_value
                ));
            }
            $profile->rate_average = $profile->rateAverage();
            $profile->save();
        }
    }
    
    public function boostProfile()
    {
       if(Auth::check() && isset(Auth::user()->Profile->id)){
           $profile = Profile::findOrFail(Auth::user()->Profile->id);
           $facebookAppId = config('services.facebook.client_id');
           return view('profile.boost',compact('profile','facebookAppId'));
       }else{
           return redirect()->to('/');
       }
    }
    
    public function doBoostProfile(Request $request)
    {
    	$this->validate($request,[
            'youtube_channel'=>'url',
            'sound_cloud_page'=>'url'
        ]);
       if(Auth::check() && isset(Auth::user()->Profile->id)){
           $profile = Profile::findOrFail(Auth::user()->Profile->id);
           if ($request->hasFile('profile_background')) {
               if ($profile->profile_background && Storage::exists($profile->profile_background))
                {
                    Storage::Delete($profile->profile_background);
                }
               $profile->profile_background = $this->UploadFile($request, 'profile_background','profiles/background');
           }
            //facebook connect
           if($request->facebook_page){
               $profile->facebook_page = $request->facebook_page;
               $profile->facebook_subscribe = 1;
           }else{
               $profile->facebook_page = "";
               $profile->facebook_subscribe = 0;
           }
           //youtube connect
           if($request->youtube_channel && Profile::isValidYoutubeChannelUrl($request->youtube_channel))
           {
               if(strpos($request->youtube_channel, 'youtube') !== false && strpos($request->youtube_channel, 'user') !== false )
               {
                    $userName = basename(parse_url($request->youtube_channel , PHP_URL_PATH ));
                    $channel = Youtube::getChannelByName($userName);
                    if(isset($channel->id)){
                        $profile->youtube_channel = "https://www.youtube.com/channel/".$channel->id;
                        $profile->youtube_subscribe = 1;
                    }
               }else{
                   $profile->youtube_channel = $request->youtube_channel;
                   $profile->youtube_subscribe = 1;
               }
               $importYoutube = (new ImportYoutubeVideo($profile));
               $this->dispatch($importYoutube);
           }else{
               $profile->youtube_channel = "";
               $profile->youtube_subscribe = 0;
           }
           
           //soundcloud connect
           if($request->sound_cloud_page && Profile::isValidSoundCloudUrl($request->sound_cloud_page))
           {
               $profile->sound_cloud_page = $request->sound_cloud_page;
               $profile->soundcloud_subscribe = 1;
               $importSoundCloudTracks = (new ImportSoundCloudTracks($profile));
               $this->dispatch($importSoundCloudTracks);
           }else{
               $profile->sound_cloud_page = "";
               $profile->soundcloud_subscribe = 0;
           }
           $profile->save();
       }
       return redirect()->to('/profile');
    }
    
    public function follow($profile_id)
    {
        $response = array('message'=>'error following this profile','status'=>false);
        $profile = Profile::where('id','=',$profile_id)->first();
        if($profile){
            $follow = Follow::where('follower_id','=',Auth::user()->id)->where('followed_id','=',$profile_id)->first();
            if($follow)
            {
                $response = array('message'=>'you are follow this profile','status'=>false);
            }else {
               $follow = Follow::create(array(
                    'follower_id' => Auth::user()->id,
                    'followed_id' => $profile_id
                ));
                $response = array('message'=>'you are now follow this profile','status'=>true,'followers'=>Follow::where('followed_id','=',$profile_id)->count()." followers");
            }
        }
       return Response::json($response);
    }
    
    public function unfollow($profile_id)
    {
        $unfollow = Follow::where('follower_id','=',Auth::user()->id)->where('followed_id','=',$profile_id)->delete();
        if($unfollow)
            $response = array('message'=>'you are now not follow this profile','status'=>true,'followers'=>Follow::where('followed_id','=',$profile_id)->count()." followers");
        else
            $response = array('message'=>'error following this profile','status'=>false);
        return Response::json($response);
    }
    
    public function addPost(Request $request)
    {
        $post = new Post;
        $post->profile_id = Auth::user()->Profile->id;
        $input_fields = $request->all();
        $post->fill($request->except('_token'));
        if($input_fields['type'] == 1) // this mean video
        {
            if($request->hasFile('video_file'))
            {
                $name = md5(Carbon::now()->toDateTimeString() . rand(1, 100) . '.' .$request->file('video_file')->getClientOriginalName()).".".$request->file('video_file')->getClientOriginalExtension();
                $vedio = $request->file('video_file')->move(storage_path('app/posts/videos'),$name);
                
                $vimeo = Vimeo::upload(storage_path('app/posts/videos/'.$name), false);
                File::delete(storage_path('app/posts/videos/'.$name));
                $vimeoId =  filter_var($vimeo , FILTER_SANITIZE_NUMBER_INT);
                $post->link_url = "//player.vimeo.com/video/".$vimeoId;
            }else if($request->video_link){
                preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $request->video_link, $matches);
                if(isset($matches[1])){
                    $post->link_url = "https://www.youtube.com/watch?v=".$matches[1];
                }else{
                    $post->link_url = $request->video_link;
                }
            }
        }
        else if($input_fields['type'] == 2) // this audio video
        {
            if($request->hasFile('audio_file'))
            {
                //$this->validate($request,['file'=>'image']);
                $post->link_url = $this->UploadFile($request, 'audio_file','posts/audio','public');
            }else if($request->audio_link){
                $post->link_url = $request->audio_link;
            }
        }
        else if($input_fields['type'] == 3) // this image video
        {
            if($request->hasFile('image_file'))
            {
                $this->validate($request,['image_file'=>'image']);
                $post->link_url =  $this->UploadFile($request, 'image_file','posts/images','public');
            }
        }
        
        $post->save();
        return redirect()->to("/profile/$post->profile_id");
    }
    
    
    // not used now
    public function postAddAjax(Request $request)
    {
        /*$this->validate($request, [
            'university_name' => 'required',
        ]);*/
        $input_fields = $request->all();
        if($request->link_url)
        {
            $this->validate($request,['link_url'=>'url']);
        }
        if($input_fields['type'] == 1) // this mean video
        {
            if($request->hasFile('file'))
            {
                //$this->validate($request,['file'=>'image']);
                /*$input_fields['file'] = $this->UploadFile($request, 'file','','public');*/
                $name = md5(Carbon::now()->toDateTimeString() . rand(1, 100) . '.' .$request->file('file')->getClientOriginalName()).".".$request->file('file')->getClientOriginalExtension();
                $vedio = $request->file('file')->move(storage_path('app/posts/videos'),$name);
                
                $vimeo = Vimeo::upload(storage_path('app/posts/videos/'.$name), false);
                File::delete(storage_path('app/posts/videos/'.$name));
                $vimeoId =  filter_var($vimeo , FILTER_SANITIZE_NUMBER_INT);
                $input_fields['link_url'] = "//player.vimeo.com/video/".$vimeoId;
            }
        }
        else if($input_fields['type'] == 2) // this audio video
        {
            if($request->hasFile('file'))
            {
                //$this->validate($request,['file'=>'image']);
                $input_fields['file'] = $this->UploadFile($request, 'file','posts/audio','public');
                $input_fields['link_url'] = $input_fields['file'];
            }
        }
        else if($input_fields['type'] == 3) // this image video
        {
            if($request->hasFile('file'))
            {
                $this->validate($request,['file'=>'image']);
                $input_fields['file'] = $this->UploadFile($request, 'file','posts/images','public');
                $input_fields['link_url'] = $input_fields['file'];
            }
        }else{
            
        }
        return Response::json($input_fields);
    }
    
    public function showChangePasswordForm()
    {
        if(Auth::check())
            return view('profile._password_change');
    }
     
     public function postChangePassword(Request $request)
    {
        $this->validate($request, [
               'password' => 'required|confirmed',
        ]);
        $user = Auth::user();
        $credentials = $request->only(
              'password', 'password_confirmation'
        );
        $user->password = bcrypt($credentials['password']);
        $user->save();
        return redirect('profile')->with('message','password changes successfully');
    }
    
}
