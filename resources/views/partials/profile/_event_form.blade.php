<label class="full">
    <span class="label">Event Name:</span>
    <div class="formElement">
        <input name="title" type="text" class="full" data-parsley-required="true" value="{{old('title')?old('title'):$event->title}}">
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>
</label>
<!-- label.full -->

<label class="full">
    <span class="label">Event Date:</span>
    <div class="formElement">
        <input name="event_date" type="text" class="full dateInput eventDate" data-parsley-required="true" value="{{old('event_date')?old('event_date'):$event->event_date}}">
        @if ($errors->has('event_date'))
            <span class="help-block">
                <strong>{{ $errors->first('event_date') }}</strong>
            </span>
        @endif
    </div>
</label>
<!-- label.full -->

<div class="full">
    <span class="label">Event Place:</span>
    <div class="formElement">
        <input id="location" name="location"  type="text" class="full" value="{{old('location')?old('location'):$event->location}}">
        @if ($errors->has('location'))
            <span class="help-block">
                <strong>{{ $errors->first('location') }}</strong>
            </span>
        @endif
    </div>
</div>
<!-- label.full -->

<div class="mixed">
    <span class="label">Booked On ScoutMee:</span>
    <div class="formElement">
        <label>
            <input id="scoutmee_booked" name="scoutmee_booked"  type="checkbox"  class="checkbox" @if(isset($event->scoutmee_booked) && !empty($event->scoutmee_booked))checked @endif>
        </label>
    </div>
</div>
<!-- label.full -->

<div class="full">
    <span class="label">Profile Event Image:</span>
    <div class="formElement">
        <label class="uploadEventImage">
            <span>Upload Event Image</span>
            @if($event->image)
                <input name="image" type="file" >
            @else
                <input name="image" type="file" data-parsley-required="true" >
            @endif
        </label>
        @if ($errors->has('image'))
            <span class="help-block">
                <strong>{{ $errors->first('image') }}</strong>
            </span>
        @endif
    </div>
</div>
<!-- div.full -->

<div class="submit">
    @if($event->id)
    <input type="submit" value="Update Event" class="buttonDefault">
    @else
    <input type="submit" value="Add Event" class="buttonDefault">
    @endif
</div>
<!-- submit -->