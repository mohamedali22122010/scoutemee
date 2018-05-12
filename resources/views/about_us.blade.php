@extends('layouts.app')

@section('content')
<div class="staticPagesWrapper">
    <div class="container">
        <div class="aboutUs">
            <h2>About Us</h2>
            <img src="{{ asset('assets/images/placeholders/team.jpg') }}" alt="Team Image">
            <p>Just a year ago we - Maurizio, Aly, and Jonathan - were complete strangers.</p>
            <p>Being musicians ourselves, we've experienced first-hand the difficulties of being a musician - whether it be connecting with compatible peers or connecting with the local fan-base.</p>
            <p>So, we are dedicating all of our time, energy, and money to build a platform where musicians will have more opportunities to connect and create, and fans will have direct access to request their favorite local musicians for live performances and music lessons.</p>
        </div>
        <!-- aboutUs -->
    </div>
    <!-- container -->
</div>
<!-- staticPagesWrapper -->
@endsection
