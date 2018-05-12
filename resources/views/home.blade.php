@extends('layouts.home')

@section('content')
<section class="recommendedMusicians">
    <header class="sectionHeader">
        <h3><i class="icon">&#xf0e7;</i>TRENDING NOW</h3>
    </header>
    <!-- sectionHeader -->

    <ul class="users">
        @if(count($profiles))
            @foreach($profiles as $profile)
                <li class="userCard">
                    <div class="userAvatar" ontouchstart="this.classList.toggle('hover');">
                        <div class="flipper">
                            <div class="front">
                                <a href="{{ url('/profile',@$profile['profile_url']) }}" ><img src="{{$profileModel->getProfileImage($profile['profile_image'])}}" alt="User Avatar"></a>
                            </div>
                            <!-- <div class="back">
                                <a href="{{@$profile['profile_video']}}"><span><i class="icon">&#xf03d;</i></span></a>
                            </div> -->
                        </div>
                        @if(@$profileModel->isTrending($profile['trending_score']))
                            <span class="trending" title="Trending Now"><i class="icon">&#xf0e7;</i></span>
                        @endif
                    </div>
                    <!-- userAvatar -->
        
                    <h3 class="userName">{{@$profile['full_name']}}</h3>
                    <h4 class="userType">
                        @if(@$profile['geners'])
                            {{@json_decode($profile['geners'])[0]}}
                        @endif
                        @if(@$profile['role'])
                            {{@json_decode($profile['role'])[0]}}
                        @endif
                        ({{@$profile['location']}})
                    </h4>
                    <ul class="userServices">
                        <li class="listTitle">Book Services</li>
                        @foreach($profileModel->servicesNames as $key=>$service)
                            @if(@$profile['services'] && array_key_exists ( $key , $profile['services'] ))
                                <li><a href="{{url('/profile/'.$profile['profile_url'].'/about')}}" class="{{$profileModel->getServiceIconByName($key)}}" title="{{$profileModel->servicesNames[$key]}} : start at {{min((array)$profile['services']->$key)}} $"></a><span>{{$profileModel->servicesNames[$key]}}</span></li>
                            @else
                                <li><cite class="{{$profileModel->getServiceIconByName($key)}} disabled"></cite><span>{{$profileModel->servicesNames[$key]}}</span></li>
                            @endif
                        @endforeach
                    </ul>
                    <!-- userServices -->
                    <div class="wrapper">
                       <div class="userRate">
                           <div class="rate">
                               @if(@$profile['rating'])
                               <i class="icon">&#xf005;</i>
                               <span>{{round($profile['rating'],1)}} / 5</span>
                               @else
                               <p>Not yet rated</p>
                               @endif
                           </div>
                       </div>
                   </div>
                   <!-- userRate -->
                    <div class="wrapper">
                        <div class="callToAction">
                            <a href="{{ url('/profile',@$profile['profile_url']) }}" class="buttonDefault">View Profile</a>
                        </div>
                    </div>
                    <!-- callToAction -->
                </li>
                <!-- userCard -->
            @endforeach
        @else
            OOPS! No result found!

Please try to modify Your criteria and repeat your search. 

We are constantly expanding our platform, so we'll be able to better assist your request shortly.

In the meantime, drop us a message at support@scoutmee.com to get informed as soon as the profile you have searched for becomes available.

Thank you for your patience! 
        @endif
    </ul>
    <!-- users -->
    <div class="membersCount">
        <h5><span data="{{$profilesCount+90}}">0</span>Musicians to discover</h5>
    </div>
</section>
<!-- recommendedMusicians -->
@endsection
