@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
                <a onclick="myFacebookLogin()">Login with Facebook</a>
                <ul id="list"></ul>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1079929165390465',
      xfbml      : true,
      version    : 'v2.5'
    });
    
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function subscribeApp(page_id, page_access_token) {
    console.log('Subscribing page to app! ' + page_id);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token},
      function(response) {
      console.log('Successfully subscribed page', response);
    });
    FB.api(
        "/"+ page_id +"/subscribed_apps",
        'get',
        {access_token: page_access_token},
        function (response) {
          //if (response && !response.error) {
            /* handle the result */
           console.log('get subscribed apps',response);
          //}
        }
    );
  }

  // Only works after `FB.init` is called
  function myFacebookLogin() {
    FB.login(function(response){
      console.log('Successfully logged in', response);
      FB.api('/me/accounts', function(response) {
        console.log('Successfully retrieved pages', response);
        var pages = response.data;
        var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
        }
      });
    }, {scope: 'manage_pages'});
  }
  
  function getUserPagesData(){
      FB.login(function(response){
          FB.api('/me/accounts', function(response) {
            console.log('Successfully retrieved pages', response);
            var pages = response.data;
            var ul = document.getElementById('list');
            for (var i = 0, len = pages.length; i < len; i++) {
              var page = pages[i];
              getPageData(page.id, page.access_token,"{{$field}}")
            }
          });
      }, {scope: 'manage_pages'});
  }
  function getPageData(page_id, page_access_token,field){
      FB.api(
            "/"+ page_id+"",
            'get',
            {access_token: page_access_token,fields:field},
            function (response) {
              //if (response && !response.error) {
                /* handle the result */
               console.log('get Page data',response);
              //}
            }
        );
    }
</script>