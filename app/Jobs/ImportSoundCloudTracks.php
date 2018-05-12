<?php

namespace App\Jobs;

use App\User;
use App\Post;
use App\YoutubeApi;
use App\Profile;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSoundCloudTracks extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $profile;
    protected $sound_cloud_page;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->sound_cloud_page = $profile->sound_cloud_page;
    }

    public function handle()
    {
       $tracks = array();
       $tracks = Profile::getSoundCloudTracks($this->sound_cloud_page,100);
       if(!empty($tracks)){
           foreach ($tracks as $track) {
               $link_url = $track->permalink_url;
               $post = Post::where('link_url','=',$link_url)->first();
               if(!$post){
                   $post = new Post;
                   $post->profile_id = $this->profile->id;
                   $post->type = 2;
                   $post->link_url = $link_url;
                   $post->external_provider = "soundcloud";
                   $post->save();
               }
           }
       }
    }
}
