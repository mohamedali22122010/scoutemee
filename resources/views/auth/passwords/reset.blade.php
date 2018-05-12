@extends('layouts.app')

@section('content')
<div class="loginSignUpWrapper">
    <div class="container">
        <div class="loginWrapper">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}
                
                <input type="hidden" name="token" value="{{ $token }}">

                <label class="full">
                    <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
                </label>
                
                <label class="full">
                    <input type="password" placeholder="Password" class="form-control" name="password">
                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </label>

                <label class="full">
                    <input type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </label>

                <div>
                    <input type="submit" value="Reset Password" class="buttonDefault">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
