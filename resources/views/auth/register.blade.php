@extends('layouts.app')

@section('content')
<div class="loginSignUpWrapper">
    <div class="container">
        <div class="signUpWrapper">
            <a href="{{ url('/redirect/2') }}" class="facebookButton"><i class="icon">&#xf09a;</i>Sign Up With Facebook</a>

            <div class="sep">
                <span>or</span>
            </div>

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="type" value="2" >
                <label class="full">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail ...">
                </label>
    
                <label class="halfLeft">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Your Name ...">
                </label>
    
                <label class="halfRight">
                    <input type="text" name="last_name" value="{{ old('first_name') }}" placeholder="Last Name ...">
                </label>
    
                <label class="full">
                    <input type="password" name="password" placeholder="Your password ...">
                </label>
    
                <div>
                    <input type="submit" value="Sign Up" class="buttonDefault">
                </div>
            </form>

            <hr>

            <div class="alt">
                <p>Already have an account?</p>
                <a href="#logInForm" class="buttonDefault" data-effect="mfp-3d-unfold">Login</a>
            </div>
            <!-- alt -->
        </div>
        <!-- signUpWrapper -->
    </div>
    <!-- container -->
</div>
<!-- loginForm -->
@endsection
