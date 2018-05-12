<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AWS;
use Location;
use Youtube;
use Auth;
use Storage;
use Request;

class Profile extends Model
{
    //
     protected $fillable = ['user_id', 'profile_url', 'facebook_page', 'sound_cloud_page', 'youtube_channel', 'location', 'services', 'profile_image', 'profile_video', 'gender', 'role', 'about' ,'count_views' , 'geners' , 'profile_background', 'latitude','longitude','full_name', 'tagline', 'influnced_by','youtube_subscribe','soundcloud_subscribe','facebook_subscribe', 'trending_score','travelling_distance'];
   // protected $fillable = ['user_id', 'name', 'facebook_page', 'sound_cloud_page', 'youtube_channel', 'location', 'services', 'profile_image', 'profile_video', 'gender', 'theme','latitude','longitude'];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'services' => 'array',
        'influnced_by'=>'array',
        'role'=>'array',
        'geners'=>'array',
    ];
    
    public $staticRoles = array(
                                'Accordion'=>'Accordion',
                                'Acoustic Guitar'=>'Acoustic Guitar',
                                'Bagpipes'=>'Bagpipes',
                                'Accordion'=>'Accordion',
                                'Band'=>'Band',
                                'Bass Guitar'=>'Bass Guitar',
                                'Bongo Drums'=>'Bongo Drums',
                                'Cello'=>'Cello',
                                'Clarinet'=>'Clarinet',
                                'Cornet'=>'Cornet',
                                'DJ'=>'DJ',
                                'Double Bass'=>'Double Bass',
                                'Drums'=>'Drums',
                                'Electic Guitar'=>'Electic Guitar',
                                'Electric Upright Bass'=>'Electric Upright Bass',
                                'Electric Violin'=>'Electric Violin',
                                'Flute'=>'Flute',
                                'French Horn'=>'French Horn',
                                'Gong'=>'Gong',
                                'Guitar'=>'Guitar',
                                'Harmonica'=>'Harmonica',
                                'Harp'=>'Harp',
                                'Horn'=>'Horn',
                                'Keyboards'=>'Keyboards',
                                'Mandolin'=>'Mandolin',
                                'Marimba'=>'Marimba',
                                'Ocarina'=>'Ocarina',
                                'Organ'=>'Organ',
                                'Piano'=>'Piano',
                                'Piccolo'=>'Piccolo',
                                'Producer'=>'Producer',
                                'Saxophone'=>'Saxophone',
                                'Singer'=>'Singer',
                                'Sitar'=>'Sitar',
                                'Synthesizer'=>'Synthesizer',
                                'Tabla'=>'Tabla',
                                'Triangle'=>'Triangle',
                                'Trombone'=>'Trombone',
                                'Trumpet'=>'Trumpet',
                                'Turntables'=>'Turntables',
                                'Ukulele'=>'Ukulele',
                                'Viola'=>'Viola',
                                'Violin'=>'Violin',
                                'Xylophone'=>'Xylophone',
                                );
    public $staticGeners = array(
                                'African'=>'African',
                                'Asian'=>'Asian',
                                'Blues'=>'Blues',
                                'Comedy'=>'Comedy',
                                'Country'=>'Country',
                                'Disco'=>'Disco',
                                'Easy listening'=>'Easy listening',
                                'Electronic'=>'Electronic',
                                'Folk'=>'Folk',
                                'Funk'=>'Funk',
                                'Hip hop'=>'Hip hop',
                                'Jazz'=>'Jazz',
                                'Latin'=>'Latin',
                                'Pop'=>'Pop',
                                'Reggae'=>'Reggae',
                                'R&B'=>'R&B',
                                'Rock'=>'Rock',
                                'Soul'=>'Soul',
                                'World'=>'World',
                                );
    public $servicesNames = array(
                               // 'live performances'=>'live performances',
                                //'request a cover'=>'request a cover',
                               // 'music lessons'=>'music lessons',
                                
                                'live_performance'=>'Live Performances',
                                'music_lessons'=>'Music Lessons',
                                );
    public $servicesIcons = array(
                               // 'live performances'=>'livePerformance',
                                //'request a cover'=>'2',
                               // 'music lessons'=>'musicLessons',
                                
                                'live_performance'=>'livePerformance',
                                'music_lessons'=>'musicLessons',
                                );
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function isfollowed($profile_id)
    {
        if(Auth::check())
        {
            return $this->hasOne(Follow::class,'followed_id')->where('follower_id','=', Auth::user()->id)->where('followed_id','=', $profile_id);
        }
        else{
            return false;
        }
    }
    
    public function follower($profile_id)
    {
        return $this->hasMany(Follow::class,'followed_id')->where('followed_id','=',$profile_id);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class,'profile_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class,'profile_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'profile_id')->orderBy('id','desc');
    }
    
    public function authUserRate()
    {
        if(Auth::check())
        {
            return $this->hasOne(Rate::class,'profile_id')->where('user_id','=', Auth::user()->id);
        }
        else{
            return false;
        }
    }
    public function rateAverage()
    {
        return $this->hasMany(Rate::class,'profile_id')->avg('rate_value');
    }
    
    public function isTrending($trending_score)
    {
        if($trending_score >= config('setting.trending_score'))
            return true;
        return false;
    }
    
    public static function getYoutubeChannelIdFromUrl($url)
    {
        if(Profile::isValidYoutubeChannelUrl($url))
        {
            return basename(parse_url($url , PHP_URL_PATH ));
        }
        return false;
    }

    public static function isValidYoutubeChannelUrl($url) {
        return strpos($url, 'youtube') !== false && (strpos($url, 'channel') || strpos($url, 'user') !== false);
    }

	public static function isValidSoundCloudUrl($url) {
        return strpos($url, 'soundcloud') !== false && (strpos($url, 'soundcloud') || strpos($url, 'user') !== false);
    }
    
    public function getimageUrl($path,$visibility=null)
    {
       if(Storage::getDefaultDriver() == 's3') {
            //createPresignedRequest
            $s3 = AWS::createClient('S3');
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_S3_BUCKET'),
                'Key'    => $path,
            ]);
            if($visibility=='public'){
                return $s3->getObjectUrl(env('AWS_S3_BUCKET'),$path);
            }
            $request =  $s3->createPresignedRequest($cmd, '+30 minutes');
            return (string) $request->getUri();
        }
        if(Storage::getDefaultDriver() == 'local') {
            return \URL::to('/storage/app/');
        }
        return \URL::to('/');
    }
    
    public function getProfileImage($path,$visibility='public'){
        return $this->getimageUrl($path,$visibility);
    }
    
    // return array of std object of youtube videos in channel 
    public static function getYouTubeChannelVideos(&$AllVideos,$channelId,$all=false,$nextPageToken='')
    {
        $params = array(
            'q' => '',
            'type' => 'video',
            'channelId' => $channelId,
            'part' => 'id',
            'maxResults' => 50,
            'pageToken'=>$nextPageToken,
        );
        $video = Youtube::searchAdvanced($params,true);
        if($video['results'])
        {
            foreach ($video['results'] as $key => $value) {
               $AllVideos[] = $value;
            }
        }
        if($video['info']['nextPageToken'] && $all)
        {
            $self::getYouTubeChannelVideos($AllVideos,$channelId,$all,$video['info']['nextPageToken']);
        }
    }
    
    public static function getSoundCloudTracks($url,$limit=50)
    {
        $clientid = config('soundcloud.client_id');
        // build our API URL
        /*$url = "http://api.soundcloud.com/resolve.json
         . "url=http://soundcloud.com/".$userName."&client_id=".$clientid;*/
        $url = "http://api.soundcloud.com/resolve.json?"
         . "url=".$url."&client_id=".$clientid;
        // Grab the contents of the URL
        $user_json = file_get_contents($url);
         
        // Decode the JSON to a PHP Object
        $user = json_decode($user_json);
         
        $userid =  $user->id; // ID of the user you are fetching the information for
        
        $soundcloud_url = "http://api.soundcloud.com/users/{$userid}/tracks.json?client_id={$clientid}&limit={$limit}";
        
        $tracks_json = file_get_contents($soundcloud_url);
        return json_decode($tracks_json);
    }
    
    public static function calculatePriceAverage($services)
    {
       $cost = 0;
       $costAverage = 0;
       if($services && is_array($services))
       {
           $i = 0;
           foreach ($services as $subService) {
               if($subService && is_array($subService)){
                   foreach ($subService as $subName => $subCost) {
                       $cost += $subCost;
                       $i++;
                   }
               }
           }
           if($i != 0)
           $costAverage = (int)($cost/$i);
       }
       return $costAverage;
    }
    
    public function completion()
    {
        $attributes = $this->attributes;
        $completion = count(array_filter(array_values($attributes)));
        return intval(($completion/count($attributes))*100);
    }
    
    public static function calculateRating($profileId)
    {
       return 4;
    }
    
    public static function calculateTrinding($profileId)
    {
        $profile = Profile::findOrFail($profileId);
        $completion = count(array_filter(array_values($profile->getAttributes())));
        $countMessages = $profile->user->threadusers()->count();
        $countFollowers = $profile->follower($profile->id)->count();
        return ($profile->count_views/100)+($completion/5)+($countMessages/10)+($countFollowers/10)+($profile->rate_average*10);
    }
    
    public function getServiceIconByName($serviceName)
    {
        return @$this->servicesIcons[$serviceName];//"&#xf025;";
    }
    
    public function getSubService($mainService)
    {
       $profile = $this;
        if($profile->services &&!empty(array_filter($profile->services)) ){
            foreach($profile->services as $key=>$subServices){
                if(array_key_exists($mainService,$profile->services)){
                    return json_encode($profile->services[$mainService]);
                }
            }
        }
    }
    
    public function getMainService()
    {
       $profile = $this;
        if($profile->services &&!empty(array_filter($profile->services)) ){
            $mainServices = array();
            foreach($profile->services as $key=>$subServices){
                 $mainServices[$key] = $this->servicesNames[$key];
            }
            return $mainServices;
        }
    }
    
    public static function cloudSearch($query,$queryParser='simple',$page=1,$size=4)
    {
        $client = AWS::createClient('CloudSearchDomain');
        $headers = Request::header();
        if (array_key_exists('x-forwarded-for', $headers))
            $ip = Request::header('x-forwarded-for');
        else
            $ip = Request::ip();
        $arrayOfIps = explode(",", $ip);
        $location = Location::get($arrayOfIps[0]);
        $latitude = $location->latitude;
        if(!$latitude){
            $latitude = '37.773972';
        }
        $longitude = $location->longitude;
        if(!$longitude){
            $longitude = '-122.431297';
        }
        $sort = "distance asc";
        if(isset($query['latitude']) && !empty($query['latitude']) && isset($query['longitude']) && !empty($query['longitude']) ){
            $latitude = $query['latitude'];
            $longitude = $query['longitude'];
        }
        if(isset($query['sort']) && !empty($query['sort'])){
            $sort = $query['sort'];
        }
        try{
            $result = $client->search([
                'size'     => $size,
                'expr' => json_encode(array('distance'=>"haversin($latitude,$longitude,latlon.latitude,latlon.longitude)")),
                'query' => $query['search'], // REQUIRED
                'queryParser' => $queryParser,//'simple|structured|lucene|dismax',
                'sort' => $sort,
                'start'=> ($page-1)*$size,
            ]);
            if(!empty($result->toArray()['hits']) && is_array($result->toArray()['hits'])){
                return self::parseAwsCloudSearchToCollection($result->toArray()['hits']);
            }
        }catch(\Aws\Exception\AwsException $e){
            return ;
        }
        return ;
    }

    private static function parseAwsCloudSearchToCollection($hits)
    {
        $colums['data'] = [];
        $colums['total'] =  $hits['found'];
        foreach ($hits['hit'] as $key1=>$hit) {
            $colums['data'][$key1]['id'] = $hit['id'];
           foreach ($hit['fields'] as $key => $value) {
               if($key == 'services'){
                   $colums['data'][$key1][$key] = json_decode($value[0]);
               }else{
                   $colums['data'][$key1][$key] = $value[0];
               }
              
           };
        }
       return collect($colums['data']);
    }
    
    private function defineIndexFieldCloadSearch($DomainName,$indexFieldColumn,$indexFieldType='text-array')
    {
       $client = AWS::createClient('cloudsearch');
       $result = $client->defineIndexField([
                    'DomainName' => $DomainName, // REQUIRED
                    'IndexField' => [ // REQUIRED
                        'IndexFieldName' => $indexFieldColumn,
                        'IndexFieldType' => $indexFieldType,//'int|double|literal|text|date|latlon|int-array|double-array|literal-array|text-array|date-array', // REQUIRED
                    ],
                ]);
        $result1 = $client->indexDocuments([
            'DomainName' => $DomainName,//'scoutmee-dev', // REQUIRED
        ]);
        return $result;
    }
}
