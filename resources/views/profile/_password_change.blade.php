@extends('layouts.app')

@section('content')
<div class="profileAddEditWrapper">
    <div class="container">
        <div class="borderWrapper">
            <div class="tabs">
                <header class="sectionHeader">
                    <h3>Change Password</h3>
                </header>
                <!-- sectionHeader -->

                <ul>
                    <li><a href="{{URL::to('profile/create')}}"><span>General</span></a></li>
                    <li><a href="{{URL::to('profile/addevent')}}"><span>Events</span></a></li>
                    <li><a href="{{URL::to('changePassword')}}" class="active"><span>Setting</span></a></li>
                </ul>
            </div>
            <!-- tabs -->

            <div class="generalInfo">
                <form method="POST" action="{{ url('changePassword') }}" class="form-horizontal" data-parsley-validate="" novalidate="">
                    {!! csrf_field() !!}
                    <label class="full">
                        <span class="label">New Password :</span>
                        <div class="formElement">
                            <input name="password" type="password" class="full" data-parsley-required="true" data-parsley-id="6">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </label>
                    <!-- label.full -->
                    
                    <label class="full">
                        <span class="label">Confirm Password:</span>
                        <div class="formElement">
                            <input name="password_confirmation" type="password" class="full" data-parsley-required="true" data-parsley-id="8">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </label>
                    <!-- label.full -->
                    <div class="submit">
                        <input type="submit" value="Change Password" class="buttonDefault">
                    </div>
                    <!-- submit -->
                </form>
            </div>
            <!-- changepassword -->
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileAddEditWrapper -->
@endsection

