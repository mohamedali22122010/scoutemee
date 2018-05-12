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

class ImportYoutubeVideo extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $profile;
    protected $youtube_channel;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->youtube_channel = $profile->youtube_channel;
    }

    public function handle()
    {
       $channelId = Profile::getYoutubeChannelIdFromUrl($this->youtube_channel);
       if($channelId){
           $AllVideos = array();
           Profile::getYouTubeChannelVideos($AllVideos,$channelId,true);
           if(!empty($AllVideos)){
               foreach ($AllVideos  as $video) {
                  $link_url = "https://www.youtube.com/watch?v=".$video->id->videoId;
                  $post = Post::where('link_url','=',$link_url)->first();
                  if(!$post){
                      $post = new Post;
                      $post->profile_id = $this->profile->id;
                      $post->type = 1;
                      $post->link_url = $link_url;
                      $post->external_provider = "youtube";
                      $post->save();
                  }
               }
           }
       }
    }
}