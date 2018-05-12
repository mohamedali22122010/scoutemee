@extends('layouts.app')

@section('content')
@include('partials.profile.profileBackGround',array('profile'=>$profile))
<div class="profileWrapper">
    <div class="container">
        <div class="borderWrapper">
            @include('partials.profile.profileHeader',array('profile'=>$profile))
            @include('partials.profile.profileInfo',array('profile'=>$profile))
            <div id="profileContentWrapperId" class="profileContentWrapper">
                @include('partials.profile.profileTabs',array('profile'=>$profile,'active'=>'images'))
                <div class="photosWrapper">
                    @if(count($images) > 0)
                    <ul id="imageList-ul"><!-- $video->link_url}} -->
                        @include('partials.profile.images',array('images'=>$images))
                    </ul>
                    <!-- ul -->
                    @else
                        <div class="noContent">
                            <p>No images available!</p>
                        </div>
                        <!-- noContent -->
                    @endif
                </div>
                <!-- videosWrapper -->
            </div>
        </div>
    </div>
</div>
@include('partials.profile.messageForm',array('profile'=>$profile))
@if(Auth::check())
    @include('partials.profile.review',array('profile'=>$profile))
@endif
@endsection