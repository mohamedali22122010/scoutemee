@if($posts && count($posts) > 0)
    <?php
        if(@$profile->profile_image)
            $profile_image = $profile->getProfileImage($profile->profile_image);
     ?>
@foreach($posts as $post)
<article class="userPost">
    <header class="postHeader">
        <div class="avatar">
            @if(@$profile->profile_image)
                <img src="{{ $profile_image }}" alt="User Avatar">
            @else
                <img src="{{ asset('assets/images/placeholders/avatar.jpg')}} " alt="User Avatar">
            @endif
        </div>

        <div class="userInfo">
            <h3>{{$post->profile->full_name}}</h3>
            <p>{{\Carbon\Carbon::parse($post->created_at)->format('F j, Y \a\t g:ia')}}</p>
        </div>
        @if(Auth::check() && $profile && $profile->user_id == Auth::user()->id)
        <a href="#" rel="{{$post->id}}" class="deletePost" title="Delete Post"><i class="icon">&#xf014;</i></a>
        @endif
    </header>
    <!-- postHeader -->

    <div class="postBody">
        @if($post->type == 1) {{-- for video type --}}
            @if($post->videoType($post->link_url) == 'youtube')
            <div class="videoWrapper">
                <iframe src="https://www.youtube.com/embed/{{$post->getYoutubeVedioIdFromUrl($post->link_url)}}"></iframe>
            </div>
            @elseif($post->videoType($post->link_url) == 'vimeo')
            <div class="videoWrapper">
                <iframe src="{{$post->link_url}}" width="500" height="100" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            @endif
        @elseif($post->type == 2) {{-- for audio type --}}
            <div class="mediaWrapper">
            @if($post->soundType($post->link_url) == 'soundcloud')
                    <iframe src="https://w.soundcloud.com/player/?url={{$post->link_url}}&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
            @elseif(strpos($post->link_url, 'posts/audio') !== false)
            <audio class="customAudioPlayer video-js vjs-default-skin vjs-big-play-centered" data-setup='{"controls":true}' controls preload="auto" poster="{{asset('assets/images/placeholders/music-bg-3.jpg')}}">
                <source src="{{$post->getS3Url($post->link_url)}}" type="audio/mpeg">
                <p class="vjs-no-js">
                    To listen to this audio file please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 audio</a>
                </p>
            </audio>
            @else
            <audio class="customAudioPlayer video-js vjs-default-skin vjs-big-play-centered" data-setup='{"controls":true}' controls preload="auto" poster="{{asset('assets/images/placeholders/music-bg-3.jpg')}}">
                <source src="{{$post->link_url}}" type="audio/mpeg">
                <p class="vjs-no-js">
                    To listen to this audio file please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 audio</a>
                </p>
            </audio>
            @endif
            </div>
        @elseif($post->type == 3) {{-- for image type --}}
        <img src="{{ $post->profile->getimageUrl($post->link_url) }}" alt="User Avatar">
        @else
        <!-- do nothing -->
        @endif

        <p>{{$post->description}}</p>
    </div>
    <!-- postBody -->

    <div class="postActions">
        <div class="likeButton">
            @if(Auth::check())
                @if($post->isLiked($post->id)->count())
                <a href="#" rel="{{$post->id}}" rel-type="unlike" class="buttonDefault LikeUnLikePost active"><i class="icon">&#xf164;</i></a>
                @else
                <a href="#" rel="{{$post->id}}" rel-type="like" class="buttonDefault LikeUnLikePost"><i class="icon">&#xf164;</i></a>
                @endif
            @endif
            <span class="count">{{$post->likes->count()}}</span>
        </div>
        <!-- likeButton -->

        <div class="shareBox">
            <ul>
                <li><i class="icon">&#xf1e0;</i></li>
                <li><a href="https://plus.google.com/share?url={{URL::to('/profile',$profile->profile_url)}}" target="_blank" class="google"><i class="icon">&#xf0d5;</i></a></li>
                <li><a href="https://twitter.com/home?status={{URL::to('/profile',$profile->profile_url)}}" target="_blank" class="twitter"><i class="icon">&#xf099;</i></a></li>
                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{URL::to('/profile',$profile->profile_url)}}" target="_blank" class="facebook"><i class="icon">&#xf09a;</i></a></li>
            </ul>
        </div>
        <!-- shareBox -->
    </div>
    <!-- postActions -->
</article>
<!-- userPost -->
@endforeach
    @if($posts->nextPageUrl())
    <div class="loadMore">
        <a href="#" rel="{{$posts->nextPageUrl()}}" class="buttonDefault loadMorePost"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
    </div>
    <!-- loadMore -->
    @endif
@endif