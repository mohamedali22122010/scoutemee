@if($profile)
<div id="reviewsPopup" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        @if($profile->user_id != Auth::user()->id)
        <div class="writeReview">
            {!! Form::open(['class'=>'form-horizontal','data-parsley-validate'=>'true' ,'id'=>'post_review','files'=>true ,'route' => 'profile.postReview']) !!}
                <label class="full">
                    <textarea name="review" id="text_area_review" placeholder="Your Review ..." data-parsley-required="true" ></textarea>
                </label>
                <input type="hidden" name="profile_id" value="{{$profile->id}}" />

                <div>
                    <input type="submit" value="Post" class="buttonDefault">
                </div>
            {!! Form::close() !!}
        </div>

        <hr>
        @endif

        <div class="reviewsList">
            @if($profile->reviews->count() > 0)
            <h2>Users reviews ({{$profile->reviews->count()}})</h2>
            <ul>
                @foreach($profile->reviews as $review)
                <li>
                    <h3>{{$review->user->first_name}} {{$review->user->last_name}}</h3>
                    <p class="date">{{$review->created_at}}</p>
                    <p>{{$review->review}}</p>
                </li>
                @endforeach
            </ul>
            @else
            <h2>Be the first to review this musician</h2>
            @endif
            
            <!-- ul -->

            <!-- <div class="loadMore">
                <a href="#" class="buttonDefault"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
            </div> -->
            <!-- loadMore -->
        </div>
        <!-- reviewsList -->
    </div>
</div>
<!-- reviewsPopup -->
@endif