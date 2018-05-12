<div class="heroWrapper">

    <div class="message">
        <div class="container">
            <h2>Book Local Musicians</h2>
            <h3>for Live Performances</h3>
            <a href="#" class="openInstructions buttonDefault">How it works</a>
        </div>
    </div>
    <!-- message -->
    <div class="searchWrapper">
        <div class="container">
                {!! Form::open(['class'=>'searchForm' ,'id'=>'search-profiles','files'=>false ,'route' => 'search.index','method' => 'get']) !!}
                <div class="label">
                    <span>I'm looking for:</span>
                    <input name='search' type="text" id="bands" placeholder="Wedding DJ, 80's Cover Band, Piano Lessons..." value="{{@Request::input('search')}}">
                </div>

                <div class="label">
                    <span>In:</span>
                    <input name='location' type="text" id="location" placeholder="City">
                    <input name='latitude' type="hidden" id="latitude">
                    <input name='longitude' type="hidden" id="longitude">
                </div>
                
                <div class="submitSearch">
                    <input type="submit" value="Search" class="buttonDefault">
                </div>
                
                <div class="coolIdeas">
                    <a href="#coolIdeas" class="coolIdeas buttonDefault" data-effect="mfp-3d-unfold">Get Cool Ideas</a>
                </div>
                <!-- coolIdeas -->
            {!! Form::close() !!}
        </div>
    </div>
    <!-- searchWrapper -->
</div>
<!-- heroWrapper -->
