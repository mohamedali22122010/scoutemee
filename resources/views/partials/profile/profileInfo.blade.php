@if($profile )
<div class="userInfoBottom">
    <ul class="userServices">
        @if($profile->services)
        <li class="listTitle">Book Services</li>
            @foreach($profile->services as $key=>$service)
                <li><a class="{{$profile->getServiceIconByName($key)}} message-box-trigger"  attr="{{$profile->servicesNames[$key]}}"><h4>{{$profile->servicesNames[$key]}} <span> {{min($service)}}$ </span></h4></a></li>
            @endforeach
        @else
        
        @endif
    </ul>
    <!-- userServices -->
    <div class="shareBox-Responsive">
        <ul>
            <li><i class="icon">&#xf1e0;</i></li>
            <li><a href="https://plus.google.com/share?url={{URL::to('/profile',$profile->profile_url)}}"  target="_blank" class="google"><i class="icon">&#xf0d5;</i></a></li>
            <li><a href="https://twitter.com/home?status={{URL::to('/profile',$profile->profile_url)}}"  target="_blank" class="twitter"><i class="icon">&#xf099;</i></a></li>
            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{URL::to('/profile',$profile->profile_url)}}" target="_blank" class="facebook"><i class="icon">&#xf09a;</i></a></li>
        </ul>
    </div>
    <!-- shareBox -->

    <div class="reviewInfo">
        <div class="ratingStars">
            <select id="userRating" class="@if(Auth::check() && $profile->user_id == Auth::user()->id)self @endif rate" rel="{{$profile->id}}" attr-auth="{{Auth::check()?'1':'0'}}">
                <option></option>
                @for($i=1 ;$i <=5 ; $i++)
                <option value="{{$i}}" @if(Auth::check() && @$profile->authUserRate()->first()->rate_value == $i) selected @elseif(Auth::check() && $profile->user_id == Auth::user()->id && round($profile->rate_average) == $i ) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <p class="grossRating">{{round($profile->rate_average,1)}} / 5</p>
        </div>
        <!-- ratingStars -->

        <div class="usersReviews">
            @if(Auth::check())
                <a href="#reviewsPopup" data-effect="mfp-3d-unfold">{{$profile->reviews->count()}} Reviews</a>
            @else
                <a href="#signUpForm" data-effect="mfp-3d-unfold">{{$profile->reviews->count()}} Reviews</a>
            @endif
        </div>
        <!-- usersReviews -->

    </div>
    <!-- reviewInfo -->
</div>
<!-- userInfoBottom -->
@endif