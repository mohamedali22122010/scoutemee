@if(count($profiles))
    @foreach($profiles as $profile)
        <li class="userCard">
            <div class="userAvatar" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <a href="{{ url('/profile',@$profile['profile_url']) }}" ><img src="{{$profileModel->getProfileImage($profile['profile_image'])}}" alt="User Avatar"></a>
                    </div>
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
                <div class="callToAction">
                    <a href="{{ url('/profile',$profile['profile_url']) }}" class="buttonDefault">View Profile</a>
                </div>
            </div>
            <!-- callToAction -->
        </li>
        <!-- userCard -->
    @endforeach
@endif