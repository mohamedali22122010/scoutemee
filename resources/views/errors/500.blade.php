<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="Scoutmee Web App">
    <title>ScoutMee</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="{{ asset('assets/images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <![endif]-->
</head>

<body>
    <div class="globalWrapper">

        <div class="errorContainer" style='background-image: url("{{ asset('assets/images/ui/error-bg.jpg') }}")' >
            <div class="errorContent">
                <h1><a href="{{URL::to('/')}}">ScoutMee</a></h1>
                <h2>500</h2>
                <h4>Internal Server Error!</h4>
                <hr>
                <ul>
                    <li><a href="{URL::to('/')}}">Home</a></li>
                    <li><a href="{URL::to('/search')}}">Search</a></li>
                    <li><a href="{URL::to('/about_us')}}">About Us</a></li>
                    <li><a href="{{URL::to('/contact_us')}}">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <!-- 404 -->

    </div>
    <!-- globalWrapper -->
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>
</body>

</html>