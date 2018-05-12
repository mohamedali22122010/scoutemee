@extends('layouts.app')

@section('content')
<div class="searchFormWrapper" style='background-image: url("{{ asset('assets/images/ui/search-bg.jpg') }}")'>
    <div class="container">
        <section class="searchForm">
            <h2><u>Discover Breakthrough Musicians By</u>:
            <br>Genre<br>Similar Artists<br>Service</h2>
            <!-- sectionHeader -->
            {!! Form::open(['class'=>'searchForm' ,'id'=>'search-profiles','files'=>false ,'route' => 'search.index','method' => 'get']) !!}
            <div class="label">
                <span>I'm looking for:</span>
                <input name='search' type="text" id="bands" placeholder="Wedding DJ, 80's Cover Band, Piano Lessons..." value="{{@Request::input('search')}}">
            </div>

            <div class="label">
                <span>In:</span>
                <input name='location' type="text" id="location" placeholder="City" value="{{@Request::input('location')}}">
                <input name='latitude' type="hidden" id="latitude" value="{{@Request::input('latitude')}}">
                <input name='longitude' type="hidden" id="longitude" value="{{@Request::input('longitude')}}">
                <input name='page' type="hidden" class="currentPage" value="1">
            </div>

            <div class="submitSearch">
                <input type="submit" value="Search" class="buttonDefault">
            </div>
            
            <div class="coolIdeas">
                <a href="#coolIdeas" class="coolIdeas buttonDefault" data-effect="mfp-3d-unfold">Get Cool Ideas</a>
            </div>
        {!! Form::close() !!}
        </section>
    </div>
</div>
<!-- searchWrapper -->
<div id="searchResultDivId" class="recommenedWrapper">
    <div class="container">
        <section class="recommendedMusicians">
            <header class="sectionHeader">
                <h3>Hot in your area</h3>
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

Please try to modify Your criteria and repeat your search! 

<div>

We are constantly expanding our platform, so we'll be able to better assist your request shortly.

In the meantime, drop us a message at support@scoutmee.com to get informed as soon as the profile you have searched for becomes available.

Thank you for your patience! 
</div>
                @endif
            </ul>
            <!-- users -->
        </section>
        <!-- recommendedMusicians -->
    </div>
</div>
<!-- recommenedWrapper -->
@endsection
@section('extra_js')
<script type="text/javascript">
    $(document).ready(function() {
        if(window.location.search.length){
            history.pushState('#searchResultDivId','','#searchResultDivId');
        }
    });
</script>
@endsection
