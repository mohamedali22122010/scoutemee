@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="loginSignUpWrapper">
    <div class="container">
        <div class="loginWrapper">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}
                
                <h3>Forgot Password</h3>

                <div class="message">
                    <p>Enter your mail to send you a new password.</p>
                </div>
                <!-- message -->
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

                <label class="full">
                    <input type="email" placeholder="E-mail ..." name="email" value="{{ $email or old('email') }}">
                </label>

                <div>
                    <input type="submit" value="Send" class="buttonDefault">
                </div>
            </form>
        </div>
        <!-- loginWrapper -->
    </div>
    <!-- container -->
</div>
<!-- loginForm -->
@endsection
