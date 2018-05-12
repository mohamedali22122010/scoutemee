@extends('layouts.app')

@section('content')
@include('partials.profile.profileBackGround',array('profile'=>$profile))
<div class="profileWrapper">
    <div class="container">
        <div class="borderWrapper">
            @include('partials.profile.profileHeader',array('profile'=>$profile))
            @include('partials.profile.profileInfo',array('profile'=>$profile))
            <div id="profileContentWrapperId" class="profileContentWrapper">
                @include('partials.profile.profileTabs',array('profile'=>$profile,'active'=>'about'))
                <div class="aboutWrapper">
                    <div class="left">
                        <section class="generalInfo">
                            <header class="sectionHeader">
                                <h3>General Info</h3>
                            </header>
                
                            <ul>
                                <li>
                                    <p class="key">Name:</p>
                                    <p class="value">{{$profile->full_name}}</p>
                                </li>
                
                                <li>
                                    <p class="key">Location:</p>
                                    <p class="value">{{$profile->location}}</p>
                                </li>
                
                                <!-- <li>
                                    <p class="key">Born on:</p>
                                    <p class="value">April 16, 1987</p>
                                </li> -->
                            </ul>
                        </section>
                        <!-- generalInfo -->
                
                        <section class="bio">
                            <header class="sectionHeader">
                                <h3>Bio</h3>
                            </header>
                
                            {!! $profile->about !!}
                        </section>
                        <!-- bio -->
                    </div>
                    <!-- left -->
                
                    <div class="right">
                        <section class="services">
                            <header class="sectionHeader">
                                <h3>Services</h3>
                            </header>
                
                            @if($profile->services)
                                <ul>
                                        @foreach($profile->services as $key=>$service)
                                            <li>
                                                <div class="serviceIcon">
                                                    <span class="{{$profile->getServiceIconByName($key)}} message-box-trigger" attr="{{$profile->servicesNames[$key]}}"></span>
                                                </div>
                                                <!-- serviceIcon -->
                            
                                                <h2>{{$profile->servicesNames[$key]}}</h2>
                            
                                                <p class="price">Starts at ${{min($service)}} / H</p>
                            
                                                <div class="callToAction">
                                                    <a class="buttonDefault message-box-trigger" attr="{{$profile->servicesNames[$key]}}">Request Service</a>
                                                </div>
                                                <!-- callToAction -->
                                            </li>
                                        @endforeach
                                </ul>
                            @endif
                        </section>
                        <!-- services -->
                        <section class="events">
                             <header class="sectionHeader">
                                <h3>Events</h3>
                            </header>

                            <ul>
                                @include('partials.profile.events',array('events'=>$events))
                            </ul>
                        </section>
                        <!-- events -->
                    </div>
                    <!-- right -->
                </div>
                <!-- aboutWrapper -->
            </div>
        </div>
    </div>
</div>
@include('partials.profile.messageForm',array('profile'=>$profile))
@if(Auth::check())
    @include('partials.profile.review',array('profile'=>$profile))
@endif
@endsection