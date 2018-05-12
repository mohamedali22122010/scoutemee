@if($profile )
<section class="profileHeader">
    @if($profile->profile_video)
        <?php parse_str( parse_url( $profile->profile_video, PHP_URL_QUERY ), $my_array_of_vars );
     $vedioId = @$my_array_of_vars['v'];?>
        @if($vedioId)
        <div class="profileVideoBg" style="background-image: url('https://img.youtube.com/vi/<?php echo $vedioId ?>/hqdefault.jpg ')">
            <a href="{{$profile->profile_video}}" class="playButton">
                <span><i class="icon">&#xf04b;</i></span>
            </a>
            
        </div>
        @else
        <div class="profileVideoBg" style="background-image: url('https://img.youtube.com/vi/9bZkp7q19f0/hqdefault.jpg') ">
-        <a href="https://www.youtube.com/watch?v=9bZkp7q19f0" class="playButton">
                <span><i class="icon">&#xf04b;</i></span>
            </a>
            
        </div>
        @endif
    @endif
    <!-- profileVideoBg -->
    @if(Auth::check() && $profile->user_id == Auth::user()->id)
    <div class="editProfileLink">
        @if($profile->profile_url)
        <a href='{{URL::to("profile/{$profile->profile_url}/edit")}}'><i class="icon">&#xf040;</i><span>Edit Profile</span></a>
        @else
        <a href='{{URL::to("profile/{$profile->id}/edit")}}'><i class="icon">&#xf040;</i><span>Edit Profile</span></a>
        @endif
    </div>
    <!-- editProfileLink -->
    @endif
    
    @if(Auth::check() && $profile->user_id != Auth::user()->id)
        <div class="inboxOrMessage-Responsive">
            <a href="#messageForm" class="buttonDefault sendMessage" data-effect="mfp-3d-unfold"><i class="icon">&#xf0e0;</i></a>
            <!-- <a href="#" class="buttonDefault"><i class="icon">&#xf199;</i></a> -->
        </div>
    @endif
    @if(!Auth::check())
        <div class="inboxOrMessage-Responsive">
            <a href="#signUpForm" class="buttonDefault sendMessage" data-effect="mfp-3d-unfold"><i class="icon">&#xf0e0;</i></a>
            <!-- <a href="#" class="buttonDefault"><i class="icon">&#xf199;</i></a> -->
        </div>
    @endif
    
    <div class="followBox-Responsive">
        @if(Auth::check() && $profile->user_id != Auth::user()->id)
            @if($profile->isfollowed($profile->id)->count())
            <a id="followBox-id" href='{{URL::to("/profile/{$profile->id}/unfollow")}}' rel="{{$profile->id}}" class="buttonDefault followBox-link"><i class="icon">&#xf235;</i></a>
            @else
            <a id="un-followBox-id" href='{{URL::to("/profile/{$profile->id}/follow")}}' rel="{{$profile->id}}" class="buttonDefault followBox-link"><i class="icon">&#xf234;</i></a>
            @endif
        @endif
        @if(!Auth::check())
       <a href="#signUpForm" class="buttonDefault reverseButton logIn" data-effect="mfp-3d-unfold"><i class="icon">&#xf234;</i></a>
        @endif
    </div>
    <!-- followBox -->

    <div class="userInfoTop">
        @if(Auth::check() && $profile->user_id == Auth::user()->id)
            <div class="progressCircle progress-{{$profile->completion()}}">
        @else
            <div class="progressCircle">
        @endif
            @if(Auth::check() && $profile->user_id == Auth::user()->id)
            <div class="toolTip">
                <p>Profile Completion: (<span>{{$profile->completion()}}%</span>)</p>
            </div>
            @endif
            <div class="overlay">
                <div class="userAvatar">
                    @if(@$profile->profile_image)
                                                        <img src="{{$profile->getProfileImage($profile->profile_image)}}" alt="User Avatar">
                                                        @else
                                                        <img src="{{ asset('assets/images/placeholders/avatar.jpg')}} " alt="User Avatar">
                                                        @endif
                </div>
            </div>
            @if(@$profile->isTrending($profile->trending_score))
            <span class="trending" title="Trending Now"><i class="icon">&#xf0e7;</i></span>
            @endif
        </div>
        <!-- progressCircle -->

        <div class="userName">
            <h1>{{$profile->full_name}}</h1>
            <h2>{{@$profile->geners[0]}} <span> - {{@$profile->role[0]}} </span> ({{$profile->location}}) </h2>
            <h3>{{$profile->tagline}}</h3>
        </div>
        <!-- userName -->
        @if(Auth::check() && $profile->user_id != Auth::user()->id)
        <div class="inboxOrMessage">
            <a href="#messageForm" class="buttonDefault sendMessage" data-effect="mfp-3d-unfold"><i class="icon">&#xf0e0;</i><span>Message</span></a>
            <!-- <a href="#" class="buttonDefault"><i class="icon">&#xf199;</i><span>Inbox</span></a> -->
        </div>
        @endif
        @if(!Auth::check())
        <div class="inboxOrMessage">
            <a href="#signUpForm" class="buttonDefault reverseButton logIn" data-effect="mfp-3d-unfold"><i class="icon">&#xf0e0;</i><span>Message</span></a>
        </div>
        @endif
        <!-- inboxOrMessage -->

        <div class="followBox">
            @if(Auth::check() && $profile->user_id != Auth::user()->id)
                @if($profile->isfollowed($profile->id)->count())
                <a id="followBox-id" href='{{URL::to("/profile/{$profile->id}/unfollow")}}' rel="{{$profile->id}}" class="buttonDefault followBox-link"><i class="icon">&#xf235;</i></a>
                @else
                <a id="un-followBox-id" href='{{URL::to("/profile/{$profile->id}/follow")}}' rel="{{$profile->id}}" class="buttonDefault followBox-link"><i class="icon">&#xf234;</i></a>
                @endif
            @endif
            @if(!Auth::check())
           <a href="#signUpForm" class="buttonDefault reverseButton logIn" data-effect="mfp-3d-unfold"><i class="icon">&#xf234;</i></a>
            @endif
            <div class="count countfollowers">
                <p>{{$profile->follower($profile->id)->count()}} Followers</p>
                <i class="icon">&#xf0c0;</i>
            </div>
        </div>
        <!-- followBox -->

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
    <!-- userInfoTop -->
</section>
<!-- profileHeader -->
@endif