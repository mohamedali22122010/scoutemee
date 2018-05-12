@if($post)
<div id="videoDetails" class="container">
    <div class="wrapper">
        @if($post->videoType($post->link_url) == 'youtube')
            <div class="videoWrapper">
                <iframe src="https://www.youtube.com/embed/{{$post->getYoutubeVedioIdFromUrl($post->link_url)}}"></iframe>
            </div>
            @elseif($post->videoType($post->link_url) == 'vimeo')
            <div class="videoWrapper">
                <iframe src="{{$post->link_url}}"></iframe>
            </div>
       @endif

        <p>{{$post->description}}</p>
    </div>
</div>
<!-- videoDetails -->
@endif