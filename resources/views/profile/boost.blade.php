@extends('layouts.app')

@section('content')
<div class="profileAddEditWrapper">
    <div class="container">
        <div class="borderWrapper">
            <div class="generalInfo">
                <header class="sectionHeader">
                    <h3>Boost Your Profile</h3>
                </header>
                <!-- sectionHeader -->

                {!! Form::model($profile,['class'=>'form-horizontal', 'id'=>'boost_profile','files'=>true ,'route' => ['profile.boost'],'method'=>'post']) !!}
                     <div class="full">
                        <span class="label">Facebook:</span>
                        <div id="facebook" class="formElement">
                            <p class="FacebookConnectIcon">
                                <fb:login-button scope="public_profile,email,manage_pages" onlogin="checkLoginState();"></fb:login-button>
                            </p>
                            <div id="status" style="display: none"></div>
                            <h4 class="pages" style="display: none">Your Pages:</h4>
                            <div class="FaceBookPagesFound formElement include-error-1" style="display: none">
                            </div>
                            <p class="FaceBookPagesNotFound" style="display: none">
                                        No Facebook Pages Founded
                            </p>

                        </div>
                    </div>
                    <!-- div.full -->

                    <label class="full">
                        <span class="label">Youtube Channel:</span>
                        <div class="formElement">
                            <input value="{{$profile->youtube_channel}}" name="youtube_channel" type="text" class="full">
                        </div>
                    </label>
                    <!-- label.full -->

                    <label class="full">
                        <span class="label">SoundCloud Page:</span>
                        <div class="formElement">
                            <input value="{{$profile->sound_cloud_page}}" name="sound_cloud_page" type="text" class="full">
                        </div>
                    </label>
                    <!-- label.full -->
                    
                    <div class="full">
                        <span class="label">Profile Background:</span>
                        <div class="formElement">
                            <label class="uploadBackground">
                                <span>Upload Background Image</span>
                                <input name="profile_background" type="file">
                            </label>
                        </div>
                    </div>
                    <!-- div.full -->
                    <input id="facebookPageValue" type="hidden" name="facebook_page" value="{{$profile->facebook_page}}" />

                    <div class="submit">
                        <input type="submit" value="save" class="buttonDefault">
                    </div>
                    <!-- submit -->
                </form>
            </div>
            <!-- generalInfo -->
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileAddEditWrapper -->
@endsection
@section('extra_js')
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      GetUserLoggedInInfo();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please Connect Your Facebook account with This App .';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please Connect Your Facebook account with This App .';
    }
    api_pages();
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '{{$facebookAppId}}',
    cookie     : true,  // enable cookies to allow the server to access 
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function subscribePageForApp(page_id, page_access_token) {
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token},
      function(response) {
          if(response.success){
            console.log('Successfully subscribed page', response);
        }
    });
  }
  
  function unSubscribePageForApp(page_id, page_access_token) {
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'delete',
      {access_token: page_access_token},
      function(response) {
          if(response.success)
            console.log('Successfully un subscribed page', response);
    });
  }
  
  function checkPageSubscription(page_id, page_access_token){
      FB.api(
        "/"+ page_id +"/subscribed_apps",
        'get',
        {access_token: page_access_token},
        function (response) {
          //if (response && !response.error) {
            /* handle the result */
           if(response.data.length > 0){
               len = response.data.length
                for (var i = 0 ; i < len; i++) {
                   if(response.data[i].id == '{{$facebookAppId}}'){
                       $("#page"+page_id).attr('checked', true);
                       $("#page"+page_id).addClass('checked');
                   }
                }
            }
        }
    );
  }
  
    function GetUserLoggedInInfo() {
      FB.api('/me', function (response) {
        $(".FacebookConnectIcon").hide();
        $("#msgtoconnectFB").hide();
        document.getElementById('status').innerHTML = '<div class="welcomemsg"> You Are Logged In As '+ response.name + '!</div>';
      });
    }

  // Only works after `FB.init` is called
  function api_pages() {
      FB.getLoginStatus(function (response) {
        if (response.status !== "connected") {
          return false;
        }
        access_token = response.authResponse.accessToken;
      });
      FB.api('/me/accounts', function(response) {
        var pages = response.data;
        var pages_str = ''
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          pages_str = pages_str +'<label class="radioInput"><input id="page'+ page.id +'" name="facebook_app_page" type="radio" class="facebookPages" data_id="' + page.id + '" data_page_token="' + page.access_token + '" /><span>'+page.name+'</span></label>'
          checkPageSubscription(page.id, page.access_token)
        }
        if (pages_str.length > 0) {
            pages_str = pages_str + '<label class="radioInput"><input name="facebook_app_page" type="radio" class="facebookPages" /><span>UNSUBSCRIBE</span></label>';
            $(".FaceBookPagesFound").append(pages_str).show();
            $(".pages").show();
            $("#status").show();
          } else {
            $(".FaceBookPagesNotFound").show();
            $(".FaceBookPagesFound").hide();
            $(".pages").hide();
            $("#status").hide();
          }
      });
      FB.api('/me',function(response) {
            $.ajax({
                url : "{{ URL::action('SocialAuthController@connectProfileWithFacebook')}}",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                 },
                data:{"facebook_id":response.id,"token":access_token},
                type : "post",
                success : function(res) {
                    console.log(res)
                }
            })
      });
  }
  
  $("#boost_profile").submit(function(e){
      e.preventDefault();
      if($(".facebookPages:checked").attr('data_id')){
          subscribePageForApp($(".facebookPages:checked").attr('data_id'),$(".facebookPages:checked").attr('data_page_token'));
      }
      $(".facebookPages").not(":checked").each(function(){
          unSubscribePageForApp($(this).attr('data_id'),$(this).attr('data_page_token'))
      })
      if($(".facebookPages:checked").attr('data_id')){
          $("#facebookPageValue").val("https://www.facebook.com/"+$(".facebookPages:checked").attr('data_id'))
      }else{
          $("#facebookPageValue").val("");
      }
       $(this).unbind('submit').submit();
  })
</script>
@endsection
