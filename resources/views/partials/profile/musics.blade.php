@foreach($musics as $music)
    <li>
        <a href='{{URL::to("/profile/post/{$music->id}")}}' class="openAjax playMediaButton" style="background-image: url('{{asset("assets/images/placeholders/music-bg-thumb-3.jpg")}}')">
            <span><i class="icon">&#xf001;</i></span>
        </a>
    </li>
@endforeach
@if($musics->nextPageUrl())
    <div class="loadMore">
        <a href="#" rel="{{$musics->nextPageUrl()}}" class="buttonDefault loadMoreAudios"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
    </div>
@endif
<!-- loadMore -->