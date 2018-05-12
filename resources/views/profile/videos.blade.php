@extends('layouts.app')

@section('content')
@include('partials.profile.profileBackGround',array('profile'=>$profile))
<div class="profileWrapper">
    <div class="container">
        <div class="borderWrapper">
            @include('partials.profile.profileHeader',array('profile'=>$profile))
            @include('partials.profile.profileInfo',array('profile'=>$profile))
            <div id="profileContentWrapperId" class="profileContentWrapper">
                @include('partials.profile.profileTabs',array('profile'=>$profile,'active'=>'videos'))
                <div class="latestWrapper">
                    <div class="left">
                        <section class="events">
                            <header class="sectionHeader">
                                <h3>Events </h3>
                            </header>
                            @if($profile->events->count())
                                <ul>
                                @include('partials.profile.events',array('profile'=>$profile))
                                </ul>
                            @else
                                <div class="noContent">
                                    <p>No events available!</p>
                                </div>
                                <!-- noContent -->
                            @endif
                        </section>
                        <!-- events -->
                        @if(!empty(array_filter($profile->influnced_by)))
                            <section class="influncedBy">
                                <header class="sectionHeader">
                                    <h3>Influenced By</h3>
                                </header>
                                <ul>
                                    @foreach($profile->influnced_by as $influnced_by)
                                        @if($influnced_by)
                                        <li>{{$influnced_by}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </section>
                            <!-- influncedBy -->
                        @endif
                    </div>
                    <div class="latest user-posts">
                        @if(count($posts) == 0)
                            <div class="noContent">
                                <p>No videos available!</p>
                            </div>
                            <!-- noContent -->
                        @endif
                        
                        @include('partials.profile.userPosts',array('profile'=>$profile))
                    </div>
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
