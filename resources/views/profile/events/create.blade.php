@extends('layouts.app')

@section('content')
<div class="profileAddEditWrapper">
    <div class="container">
        <div class="borderWrapper">
            <div class="tabs">
                <header class="sectionHeader">
                    <h3>Create Your Event</h3>
                </header>
                <!-- sectionHeader -->

                <ul>
                    <li><a href="{{URL::to('profile/create')}}"><span>General</span></a></li>
                    <li><a href="{{URL::to('profile/addevent')}}" class="active"><span>Events</span></a></li>
                    <li><a href="{{URL::to('changePassword')}}"><span>Setting</span></a></li>
                </ul>
            </div>
            <!-- tabs -->

            <div class="editEvents">
                {!! Form::model($event,['class'=>'form-horizontal','data-parsley-validate'=>'true' ,'id'=>'create_profile','files'=>true ,'route' => 'event.store']) !!}
                
                @include('partials.profile._event_form')
                {!! Form::close() !!}
            </div>
            <!-- editEvents -->
            <div class="eventsList">
                <section class="events">
                    <header class="sectionHeader">
                        <h3>Your Events </h3>
                    </header>
                    @if($profile->events->count())
                        <ul id="eventsList-ul">
                        @include('partials.profile.events',array('events'=>$events,'showActions'=>true))
                
                        </ul>
                    @endif
                </section>
                <!-- events -->
            </div>
            
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileAddEditWrapper -->
@endsection

