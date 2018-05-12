@extends('layouts.app')

@section('content')
<div class="profileAddEditWrapper">
    <div class="container">
        <div class="borderWrapper">
            <div class="tabs">
                <header class="sectionHeader">
                    <h3>Edit Your Profile</h3>
                </header>
                <!-- sectionHeader -->

                <ul>
                    <li><a href="{{URL::to('profile/create')}}" class="active"><span>General</span></a></li>
                    <li><a href="{{URL::to('profile/addevent')}}"><span>Events</span></a></li>
                    <li><a href="{{URL::to('changePassword')}}"><span>Setting</span></a></li>
                </ul>
            </div>
            <!-- tabs -->

            <div class="generalInfo">
                {!! Form::model($profile,['class'=>'form-horizontal','data-parsley-validate'=>'true' ,'id'=>'update_profile','files'=>true ,'route' => ['profile.update',$profile->id],'method'=>'put']) !!}

                @include('partials.profile._profile_form')
                {!! Form::close() !!}
            </div>
            <!-- generalInfo -->
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileAddEditWrapper -->
<div id="uploadVideo" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        <form action="#" class="uploadUserProfileVideoForm">
            <div class="device">
                <label class="upload">
                    <span><i class="icon">&#xf093;</i>Upload From Your Device</span>
                    <input type="file" class="userProfileVideo">
                </label>
            </div>

            <div class="sep">
                <span>or</span>
            </div>

            <div class="external">
                <label class="full">
                    <input type="text" class="userProfileVideoLink" placeholder="Add Youtube Video Link ...">
                </label>
            </div>

            <div>
                <a href="#" class="buttonDefault submitForm">Insert</a>
                <!-- <input type="submit" value="Insert" class="buttonDefault"> -->
            </div>
        </form>
    </div>
</div>
<!-- uploadVideo -->
@endsection

