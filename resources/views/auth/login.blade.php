@extends('layouts.app')

@section('content')
<div class="loginSignUpWrapper">
    <div class="container">
        <div class="loginWrapper">
            <a href="{{ url('/redirect') }}" class="facebookButton"><i class="icon">&#xf09a;</i>Login</a>

            <div class="sep">
                <span>or</span>
            </div>
    
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {!! csrf_field() !!}
                <label class="full">
                    <input type="email" name="email" value="{{ old('email') }}"  placeholder="E-mail ...">
                </label>
    
                <label class="full">
                    <input type="password" name="password" placeholder="Password ...">
                </label>
    
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                    <a href="{{ url('/password/reset') }}">Forgot password?</a>
                </label>
    
                <div>
                    <input type="submit" value="Login" class="buttonDefault">
                </div>
            </form>

            <hr>

            <div class="alt">
                <p>Don't have an account?</p>
                <a href="#signUpForm" class="buttonDefault" data-effect="mfp-3d-unfold">Sign Up</a>
            </div>
            <!-- alt -->
        </div>
        <!-- loginWrapper -->
    </div>
    <!-- container -->
</div>
<!-- loginForm -->
@endsection