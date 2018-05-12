@foreach($videos as $video)
    <li>
        @if($video->videoType($video->link_url) == 'youtube')
        <a href='{{URL::to("/profile/post/{$video->id}")}}' class="openAjax playMediaButton" style="background-image: url('http://img.youtube.com/vi/{{$video->getYoutubeVedioIdFromUrl($video->link_url)}}/0.jpg')">
            <span><i class="icon">&#xf03d;</i></span>
        </a>
        @else
        <a href='{{URL::to("/profile/post/{$video->id}")}}' class="openAjax playMediaButton" style="background-image: url('{{asset("assets/images/placeholders/video-bg-8.jpg")}}')">
            <span><i class="icon">&#xf03d;</i></span>
        </a>
        @endif
    </li>
@endforeach
@if($videos->nextPageUrl())
    <div class="loadMore">
        <a href="#" rel="{{$videos->nextPageUrl()}}" class="buttonDefault loadMoreVideos"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
    </div>
@endif
<!-- loadMore -->