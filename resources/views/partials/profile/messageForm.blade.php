<div id="messageForm" class="container mfp-with-anim mfp-hide">
    <div class="wrapper">
        @if($profile->travelling_distance)
            <h4><i class="icon">&#xf05a;</i>This Musician can travel as far as {{$profile->travelling_distance}} miles</h4>
        @endif
        <h5>Send a message to {{$profile->full_name}}</h5>
        <hr>
        {!! Form::open(['class'=>'form-horizontal' ,'id'=>'create_message','data-parsley-validate'=>'true','files'=>true ,'route' => 'message.store']) !!}
        <input type="hidden" name="users[]" value="{{@$profile->user_id}}"/>
        <input type="hidden" name="thread_id" value="{{@$threadId}}"/>
            <label class="full">
                <span class="label"></span>
                <select id="message-title" class="select-input" name="title" required>
                    <option value>Select Service</option>
                    @if($profile->getMainService())
                        @foreach($profile->getMainService() as $service)
                            <option value="{{$service}}">{{$service}}</option>
                        @endforeach
                    @endif
                    <option value="others">Others</option>
                </select>
            </label>

            <label class="full">
                <span class="label">Write a message:</span>
                <textarea name="content" placeholder="Leave as many details as possible!" required></textarea>
            </label>

            <div id="sendMessage-button" >
                <input type="submit" value="Send" class="buttonDefault">
            </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- messageForm -->
@section('message_js')
<script type="text/javascript">
    $(document).on('change', '#message-title', function (e) {
        $("#message-title-input-label").remove();
        $(".live-performance-extra-fields").remove();
        $(".music-lessons-extra-fields").remove();
        if($(this).val() == "Live Performances"){
            $(this).parent().after('<label class="full live-performance-extra-fields"><span class="label">Select Duration:</span><select  class="select-input" name="duration" ><option value="30 Mins">30 Mins</option><option value="1 hour">1 Hour</option><option value="1.5 hours">1.5 Hours</option><option value="2 hours">2 Hours</option><option value="more than 2 hours">More Than 2 Hours</option></select></label>');
            $(this).parent().after('<label class="full live-performance-extra-fields"><span class="label">Select Date:</span><input type="text" class="dateInput" name="date" placeholder="Date Of Event" required/></label>');
            $(this).parent().after('<div class="label full live-performance-extra-fields"><span class="label">Select Location:</span><input type="text" id="location" name="place" placeholder="Your Apartment? Or, Do You Need A Venue?" required/></div>');
            subservice = "{{($profile->getSubService('live_performance'))}}";
            if(subservice){
                subservice=subservice.replace(/{/gi,"");
                subservice=subservice.replace(/&quot;/gi,"");
                subservice=subservice.replace(/\[/gi,"");
                subservice=subservice.replace(/\]/gi,"");
                subservice=subservice.replace(/}/gi,"");
                subservice=subservice.split(',');
                $(this).parent().after('<label class="full live-performance-extra-fields"><span class="label">Select Service:</span><select  class="select-input live-performance-subservice" name="subservice" ></select></label>');
                $.each(subservice, function(key, value) {
                    value = value.split(':');
                    $(".live-performance-subservice").append("<option value='"+value[0]+"'>"+value[0]+" ( "+value[1]+" $ ) </option>")
                });
            }
            // add adderss picker to place input
            /*var addressPicker = new AddressPicker({
                autocompleteService : {
                    types : ['(cities)'],
                    componentRestrictions : {
                        country : 'US'
                    }
                }
            });
            $('#location').typeahead(null, {
                displayKey : 'description',
                source : addressPicker.ttAdapter()
            });
            addressPicker.bindDefaultTypeaheadEvent($('#location'))
            $(addressPicker).on('addresspicker:selected', function(event, result) {
                $("#location").val(result.address());
            });*/
            
            // add date picker to date input
            $('.dateInput').datetimepicker({
                timepicker: false,
                minDate: new Date(),
                format: 'd/m/Y'
            });
            
            var addressPicker = new AddressPicker({
                autocompleteService : {
                    types : ['(cities)'],
                    componentRestrictions : {
                        country : 'US'
                    }
                }
            });
            $('#location').typeahead(null, {
                displayKey : 'description',
                source : addressPicker.ttAdapter()
            });
            addressPicker.bindDefaultTypeaheadEvent($('#location'))
            $(addressPicker).on('addresspicker:selected', function(event, result) {
                $("#location").val(result.address());
            });
        }
        if($(this).val() == "Music Lessons"){
            $(this).parent().after('<label class="full music-lessons-extra-fields"><span class="label">Select Proficiency:</span><select  class="select-input" name="proficiency" ><option value="Beginner">Beginner</option><option value="Advanced Beginner">Advanced Beginner</option><option value="Intermediate">Intermediate</option><option value="Advanced">Advanced</option></select></label>');
            $(this).parent().after('<label class="full music-lessons-extra-fields"><span class="label">Select Place:</span><select  class="select-input" id="music-lessons-place" name="place" ><option value="online">Online</option><option value="my place">My Place</option><option value="tutor place">Tutor Place</option><option value="otherplace">Other</option></select></label>');
            subservice = "{{($profile->getSubService('music_lessons'))}}";
            if(subservice){
                subservice=subservice.replace(/{/gi,"");
                subservice=subservice.replace(/&quot;/gi,"");
                subservice=subservice.replace(/\[/gi,"");
                subservice=subservice.replace(/\]/gi,"");
                subservice=subservice.replace(/}/gi,"");
                subservice=subservice.split(',');
                $(this).parent().after('<label class="full music-lessons-extra-fields"><span class="label">Service:</span><select  class="select-input music-lessons-subservice" name="subservice" ></select></label>');
                $.each(subservice, function(key, value) {
                    value = value.split(':');
                    $(".music-lessons-subservice").append("<option value='"+value[0]+"'>"+value[0]+" ( "+value[1]+" $ ) </option>")
                });
            }
        }
        if($(this).val() == "others"){
            $(this).parent().after('<label id="message-title-input-label" class="full"><span class="label">Write Title:</span><input type="text" name="title" placeholder="Title" required/></label>')
        }
    });
    $(document).on('click', '.message-box-trigger', function (e) {
        $(".inboxOrMessage a").trigger("click")
        if($(".inboxOrMessage a").attr("href") == "#messageForm"){
            $("#message-title").val($(this).attr("attr"))
            $("#message-title").trigger("change")
        }
    })
    
    $(document).on('change', '#music-lessons-place', function (e) {
        $("#other-place-input").remove();
        if($(this).val() == "otherplace"){
            $(this).parent().after('<label id="other-place-input" class="full music-lessons-extra-fields"><span class="label">Write Place:</span><input type="text" name="place" placeholder="Place" required/></label>')
        }
    })
        
</script>

@endsection
