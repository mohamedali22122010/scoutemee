<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="Scoutmee Web App">
    <title>ScoutMee</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="{{ asset('assets/images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('assets/images/mobile-icons-ios-180x180.png') }}" rel="apple-touch-icon" />
    <link href="{{ asset('assets/images/mobile-icons-ios-180x180.png') }}" rel="apple-touch-icon" sizes="180x180" />
    <link href="{{ asset('assets/images/mobile-icons-android-192x192.png') }}" rel="icon" sizes="192x192" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="application-name" content="ScoutMee">
    <meta property="og:type" content="website"/>
    <meta property="og:country-name" content="USA" />
    <meta property="og:locale" content="EN_USA" />
    <meta property="og:url" content="{{URL::current()}}"/>
    <meta property="og:site_name" content="ScoutMee"/>
    @if(isset($profile) && isset($profile->full_name))
    <meta property="og:title" content="{{$profile->full_name}}"/>
    <meta property="og:description" content="{{strip_tags($profile->about)}}" />
    <meta property="og:image" content="{{$profile->getProfileImage($profile->profile_image)}}" />
    <meta property="description" content="{{strip_tags($profile->about)}}" />
    @endif
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <![endif]-->


    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', '1702771533342927');
    fbq('track', "PageView");</script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1702771533342927&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
</head>

<body>
    <div class="globalWrapper">
        <a href="#" class="backToTop"><i class="icon">&#xf106;</i></a>
        @include('partials.instructions')
        @include('partials.headermenu')
        
        @yield('content')
        <!-- recommenedWrapper -->
        @include('partials.footer')
        @include('partials.flashMessage')
    </div>
    <!-- globalWrapper -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>
    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', '{{Config::get('scoutmee.GA')}}', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- End Google Analytics -->
    @include('partials.logInForm')
    @include('partials.PopUpForm')
    @include('partials.signUpFromAsMusician')
    @include('partials.js')
    @yield('extra_js')
    @yield('message_js')
</body>

</html>
