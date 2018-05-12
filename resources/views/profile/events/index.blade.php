@extends('layouts.app')

@section('content')
<div class="profileAddEditWrapper">
    <div class="container">
            <div class="eventsList">
                @include('partials.profile.events',array('events'=>$events,'showActions'=>true))
                
            </div>
    </div>
</div>
<!-- profileAddEditWrapper -->
@endsection

