<nav id="sideNav">
    <ul>
        <li><a href="#" class="openInstructions">How it works</a></li>
        @if (Auth::guest())
        <li><a href="{{URL::to('/register#howItWorksMusicians')}}" class="reverseButton musician">Sign Up As A Musician</a></li>
        <li><a href="{{URL::to('/login#signUpForm')}}">Sign Up As A Fan</a></li>
        @endif
        <li><a href="{{URL::to('/about_us')}}">About Us</a></li>
        <li><a href="{{URL::to('/contact_us')}}">Contact Us</a></li>
        <li><a href="{{URL::to('/privacy')}}">Privacy Statement</a></li>
        <li><a href="{{URL::to('/terms')}}">Terms of Use</a></li>
        @if (Auth::check())
            @if(auth()->user()->type == 2 && isset(auth()->user()->Profile->id))
            <li><a href="{{ url('/profile/boost') }}" class="reverseButton import">Boost your profile</a></li>
            @elseif(auth()->user()->type == 2)
            <li><a href="{{ url('/profile/create') }}" class="reverseButton import">Create profile</a></li>
            @endif
        <li><a href="{{ url('/logout') }}">Logout</a></li>
        @endif
    </ul>
</nav>
<!-- sideNav -->
<div class="headerWrapper">
    <div class="container">
        <header>
            <div class="openMenu">
                <a href="#sideNav" class="openNav"><i class="icon">&#xf0c9;</i></a>
            </div>
            <!-- openMenu -->
            <div class="logo">
                <a href="{{ url('/') }}">Scoutmee</a>
            </div>
            @if (Auth::guest())
            <div class="rightActions">
                <a href="{{URL::to('/search')}}" class="reverseButton"><i class="icon">&#xf002;</i><span>Search</span></a>

                <a href="#logInForm" class="reverseButton logIn" data-effect="mfp-3d-unfold">
                    <i class="icon">&#xf13e;</i><span>Login</span>
                </a>
                <a href="#signUpForm" class="reverseButton signUp" data-effect="mfp-3d-unfold">
                    <i class="icon">&#xf040;</i><span>Sign Up As A Fan</span>
                </a>
                <a href="{{URL::to('/register#howItWorksMusicians')}}" class="reverseButton musician" >
                    <i class="icon">&#xf001;</i><span>Sign Up As A Musician</span>
                </a>
            </div>
            @elseif(Auth::user()->type == 1)
            <div class="rightActions">
                <a href="{{ url('/logout') }}" class="logOut reverseButton" title="logout">
                    <i class="icon">&#xf023;</i>
                </a>
                <a href='{{URL::to("message")}}' class="inboxLink">
                    <i class="icon">&#xf0e0;</i>
                    @if($unReadMessageCount)
                     <span>{{$unReadMessageCount}}</span>
                    @endif
                </a>
                <a href="{{URL::to('/search')}}"  class="reverseButton"><i class="icon">&#xf002;</i><span>Search</span></a>
            </div>
            <!-- rightActions -->
            @elseif(Auth::user()->type == 2)
            <div class="rightActions">
                <a href="{{ url('/logout') }}" class="logOut reverseButton" title="logout">
                    <i class="icon">&#xf023;</i>
                </a>
                <a href="{{ url('/profile') }}" class="profileLink">
                    @if(@Auth::user()->Profile->profile_image)
                    <img src="{{Auth::user()->Profile->getProfileImage(Auth::user()->Profile->profile_image)}}" alt="User Avatar">
                    @else
                    <img src="{{ asset('assets/images/placeholders/avatar.jpg')}} " alt="User Avatar">
                    @endif
                </a>

                <a href='{{URL::to("message")}}' class="inboxLink">
                    <i class="icon">&#xf0e0;</i>
                    @if($unReadMessageCount)
                     <span>{{$unReadMessageCount}}</span>
                    @endif
                </a>
                
                @if(isset(Auth::user()->Profile))
                <a href="{{ url('/profile/boost') }}" class="reverseButton import">
                    <i class="icon">&#xf019;</i><span>Boost your profile</span>
                </a>
                @else
                <a href="{{ url('/profile/create') }}" class="reverseButton import">
                    <i class="icon">&#xf019;</i><span>Create profile</span>
                </a>
                @endif
                
                <a href="{{URL::to('/search')}}"  class="reverseButton"><i class="icon">&#xf002;</i><span>Search</span></a>
            </div>
            <!-- rightActions -->
            @endif
        </header>
        <!-- header -->
    </div>
</div>
<!-- headerWrapper -->
