<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use AWS;
use Storage;

class Post extends Model
{
    protected $fillable = ['profile_id', 'type', 'link_url', 'description', 'external_provider','facebook_post_id'];
    
    
    public function profile()
    {
       return $this->belongsTo('App\Profile','profile_id');
    }
    
    public function likes()
    {
        return $this->hasMany('App\Like', 'post_id');
    }
    
    public function isLiked($post_id)
    {
        if(Auth::check())
        {
            return $this->hasOne(Like::class,'post_id')->where('user_id','=', Auth::user()->id)->where('post_id','=', $post_id);
        }
        else{
            return false;
        }
    }
    public function videoType($url) {
        if (strpos($url, 'youtube') !== false ) {
            return 'youtube';
        } elseif (strpos($url, 'vimeo') !== false) {
            return 'vimeo';
        } else {
            return 'unknown';
        }
    }
    
    public function soundType($url) {
        if (strpos($url, 'soundcloud')  !== false) {
            return 'soundcloud';
        } else {
            return 'unknown';
        }
    }
    public function getYoutubeVedioIdFromUrl($url)
    {
        if(self::videoType($url) == 'youtube')
        {
            parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
            if(isset($my_array_of_vars['v']) && !empty($my_array_of_vars['v']))
            {
                return $my_array_of_vars['v']; // this is vedio id
            }
        }
        return false;
    }

    public function getSoundCloudTrack($url)
    {
        $clientid = config('soundcloud.client_id');
        // build our API URL
        $url = "http://api.soundcloud.com/resolve.json?"
         . "url=".$url."&client_id=".$clientid;
         
        // Grab the contents of the URL
        $url_json = file_get_contents($url);
         
        // Decode the JSON to a PHP Object
        $url = json_decode($url_json);
         
        return $url->id; // ID of the track you are fetching the information for
    }
    
    public function getS3Url($path)
    {
       if(Storage::getDefaultDriver() == 's3') {
            //createPresignedRequest
            $s3 = AWS::createClient('S3');
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_S3_BUCKET'),
                'Key'    => $path,
            ]);
            
            $request =  $s3->createPresignedRequest($cmd, '+30 minutes');
            return (string) $request->getUri();
        }
        if(Storage::getDefaultDriver() == 'local') {
            return \URL::to('/storage/app/');
        }
        return \URL::to('/');
    }
    
}
