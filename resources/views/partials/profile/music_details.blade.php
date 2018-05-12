@if($post)
<div id="musicDetails" class="container">
    <div class="wrapper">
        <div class="mediaWrapper">
        @if($post->soundType($post->link_url) == 'soundcloud')
                <?php $trackId = $post->getSoundCloudTrack($post->link_url) ?>
                @if($trackId)
                    <iframe src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/{{$trackId}}&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
                @endif
        @elseif(strpos($post->link_url, 'posts/audio') > 0)
            <audio class="customAudioPlayer video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" poster="{{asset('assets/images/placeholders/music-bg-thumb-3.jpg')}}">
                <source src="{{$post->getS3Url($post->link_url)}}" type="audio/mpeg">
                <p class="vjs-no-js">
                    To listen to this audio file please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 audio</a>
                </p>
            </audio>
        @else
            <audio class="customAudioPlayer video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" poster="{{asset('assets/images/placeholders/music-bg-3.jpg')}}">
                <source src="{{$post->link_url}}" type="audio/mpeg">
                <p class="vjs-no-js">
                    To listen to this audio file please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 audio</a>
                </p>
            </audio>
        @endif
        </div>
        <p>{{$post->description}}</p>
    </div>
</div>
<!-- videoDetails -->
@endif
