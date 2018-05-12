<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use File;
use App\Profile;
use App\Post;
use App\YoutubeApi;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
            $profile = Profile::orderBy('trending_score', 'desc')->skip(49)->take(1)->get(array('trending_score'))->toArray();
            $title = "// generated at ".date("Y-m-d H:i:s");
            if( isset($profile[0]['trending_score'])){
                 $data = var_export(array('trending_score'=>$profile[0]['trending_score']), 1);
                 $contents = File::put(config_path().'/setting.php',"<?php\n $title \nreturn $data;");
            }
             
            $youTubeProfiles = Profile::where("youtube_subscribe",1)->get();
            if(!empty($youTubeProfiles)){
                foreach ($youTubeProfiles as $profile) {
                   $channelId = Profile::getYoutubeChannelIdFromUrl($profile->youtube_channel);
                   if($channelId){
                       $AllVideos = array();
                       Profile::getYouTubeChannelVideos($AllVideos,$channelId,true);
                       if(!empty($AllVideos)){
                           foreach ($AllVideos  as $video) {
                              $link_url = "https://www.youtube.com/watch?v=".$video->id->videoId;
                              $post = Post::where('link_url','=',$link_url)->first();
                              if(!$post){
                                  $post = new Post;
                                  $post->profile_id = $profile->id;
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
            $soundCloudProfiles = Profile::where("soundcloud_subscribe",1)->get();
            if(!empty($soundCloudProfiles)){
                foreach ($soundCloudProfiles as $profile) {
                   $tracks = Profile::getSoundCloudTracks($profile->sound_cloud_page,100);
                   if(!empty($tracks)){
                       foreach ($tracks as $track) {
                           $link_url = $track->permalink_url;
                           $post = Post::where('link_url','=',$link_url)->first();
                           if(!$post){
                               $post = new Post;
                               $post->profile_id = $profile->id;
                               $post->type = 2;
                               $post->link_url = $link_url;
                               $post->external_provider = "soundcloud";
                               $post->save();
                           }
                       }
                   }
                    
                }
            }
        })->daily();
    }
}
