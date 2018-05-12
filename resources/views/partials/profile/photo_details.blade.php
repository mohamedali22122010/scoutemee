@if($post)
<div id="photoDetails" class="container">
    <div class="wrapper">
        <div class="imageWrapper">
            <img src="{{$post->profile->getimageUrl($post->link_url)}}" alt="Photo">
        </div>

        <p>{{$post->description}}</p>
    </div>
</div>
<!-- photoDetails -->
@endif