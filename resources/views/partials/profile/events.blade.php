@if($events)
    @foreach($events as $event)
        <li>
            <div class="eventImage">
                <img src="{{$event->profile->getimageUrl($event->image)}}" alt="Event Image">
            </div>
            <div class="eventDetials">
                <h3>{{$event->title}} @if($event->scoutmee_booked == 1)<span>(Booked on ScoutMee)</span>@endif</h3>
                <h4>{{$event->location}}</h4>
                <p>{{$event->event_date}}</p>
                @if(Auth::check() && $event->profile_id == @Auth::user()->profile->id && isset($showActions) && $showActions==true)
                    <div class="eventActions">
                        <a href="{{URL::to('profile/event/edit',$event->id)}}" class="edit"><i class="icon">&#xf040;</i></a>
                        <a href="#" rel="{{$event->id}}" class="delete delete-event"><i class="icon">&#xf1f8;</i></a>
                    </div>
                @endif
            </div>
        </li>
    @endforeach
    @if($events->nextPageUrl())
    <div class="loadMore">
        <a href="#" rel="{{$events->nextPageUrl()}}" class="buttonDefault loadMoreEvents"><i class="icon">&#xf0e2;</i><span>Load More</span></a>
    </div>
    <!-- loadMore -->
    @endif
@endif

@section('extra_js')
<script type="text/javascript">
$(document).ready(function() {
    $(document).on('click', '.loadMoreEvents', function (e) {
       e.preventDefault();
       element = $(this);
       $.ajax({
            url : element.attr("rel"),
            dataType: 'json',
        }).done(function (data) {
            $('#eventsList-ul').append(data);
            element.parent().remove();
        }).fail(function () {
            element.parent().remove();
        });
    });
    $(document).on('click', '.delete-event', function (e) {
        e.preventDefault();
        event_id = $(this).attr('rel');
        element = $(this);
        if (confirm("Are You Sure You Want To Delete") == true) {
            $.ajax({
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo csrf_token() ?>');
                },
                url: "{{ URL::action('ProfileController@deleteEventAjax')}}",
                data: {"event_id":event_id},
                success: function (data) {
                    if(data.status)
                        element.closest('li').remove();
                },
                error: function (data) {
                    
                }
            });
        }
    });
});
</script>
@endsection