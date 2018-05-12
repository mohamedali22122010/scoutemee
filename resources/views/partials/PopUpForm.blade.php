<div id="signUpForm" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        <a href="{{ url('/redirect/1') }}" class="facebookButton"><i class="icon">&#xf09a;</i>Sign Up With Facebook</a>

        <div class="sep">
            <span>or</span>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="type" value="1" >
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
</div>
<!-- signUpForm -->
<div id="coolIdeas" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        <ul>
            <li>
                <a href="{{URL::to('/advanced-search?role=DJ&live_performance=on')}}">
                    <img src="{{ asset('assets/images/ui/idea-1.png') }}" alt="Idea Image">
                    <div class="coolContent">
                        <p>Wanna make your party EPIC? Hire a band or a DJ to entertain your guests!</p>
                        <button>Find Now</button>
                    </div>
                    <!-- coolContent -->
                </a>
            </li>
    
            <li>
                <a href="{{URL::to('/advanced-search?role=Acoustic+Guitar&live_performance=on')}}">
                    <img src="{{ asset('assets/images/ui/idea-2.png') }}" alt="Idea Image">
                    <div class="coolContent">
                        <p>About to propose? Make that moment even more unforgettable with an acoustic performer and your favorite ballad.</p>
                        <button>Find Now</button>
                    </div>
                    <!-- coolContent -->
                </a>
            </li>
    
            <li>
                <a href="{{URL::to('/advanced-search?role=Guitar&music_lessons=on')}}">
                    <img src="{{ asset('assets/images/ui/idea-3.png') }}" alt="Idea Image">
                    <div class="coolContent">
                        <p>Children who learn to play an instrument at a young age become smarter and quicker. Get a music tutor for your kid!</p>
                        <button>Find Now</button>
                    </div>
                    <!-- coolContent -->
                </a>
            </li>
    
            <li>
                <a href="{{URL::to('/advanced-search?influenced_by=sia&music_lessons=on')}}">
                    <img src="{{ asset('assets/images/ui/idea-4.png') }}" alt="Idea Image">
                    <div class="coolContent">
                        <p>Always wanted to hit Sia’s higher notes? Book some singing lessons and give yourself a try!</p>
                        <button>Find Now</button>
                    </div>
                    <!-- coolContent -->
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- coolIdeas -->
<!-- <div id="howItWorksMusicians" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        <div class="sectionHeader">
            <h3>Process</h3>
        </div>

        <ul>
            <li>
                <img src="{{ asset('assets/images/ui/process-1.png') }}" alt="Idea Image">
                <div class="coolContent">
                    <h3>SIGN UP</h3>
                    <p>Complete your profile and build it out by linking any additional music or social platforms. By doing so, you’ll increase your visibility and chances of booking more gigs!</p>
                </div>
                <!-- coolContent
            </li>

            <li>
                <img src="{{ asset('assets/images/ui/process-2.png') }}" alt="Idea Image">
                <div class="coolContent">
                    <h3>UPDATE &amp; SHARE</h3>
                    <p>Use your profile as a business card and share it on your platforms and with your contacts. Your personal ‘scoutmee.com/profile/YOURNAME' URL is ready and waiting for you.</p>
                </div>
                <!-- coolContent
            </li>

            <li>
                <img src="{{ asset('assets/images/ui/process-3.png') }}" alt="Idea Image">
                <div class="coolContent">
                    <h3>GET DISCOVERED</h3>
                    <p>Interact with your audience via status updates, private messages, and easily book your next gig! Upload pics from your gigs and lessons to further spread the word about your talent and services offered.</p>
                </div>
                <!-- coolContent
            </li>
        </ul>
    </div> -->
</div>
<!-- howItWorksMusicians --> 
