@foreach($images as $image)
    <li>
        <a href='{{URL::to("/profile/post/{$image->id}")}}' class="openAjax playMediaButton" style="background-image: url('{{$profile->getimageUrl($image->link_url)}}')">
            <span><i class="icon">&#xf03e;</i></span>
        </a>
    </li>
@endforeach
@if($images->nextPageUrl())
    <div class="loadMore">
        <a href="#" rel="{{$images->nextPageUrl()}}" class="buttonDefault loadMoreImages"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
    </div>
@endif