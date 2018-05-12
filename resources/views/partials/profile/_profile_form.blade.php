<label class="full">
    <span class="label">*Name:</span>
    <div class="formElement">
        <input name="full_name" type="text" class="full" data-parsley-required="true" data-parsley-maxlength="14" value="{{old('full_name')?old('full_name'):$profile->full_name}}">
        @if ($errors->has('full_name'))
            <span class="help-block">
                <strong>{{ $errors->first('full_name') }}</strong>
            </span>
        @endif
    </div>
</label>
<!-- label.full -->

<div class="full">
    <span class="label">*Gender:</span>
    <div class="formElement include-error-1">
        <label class="radioInput">
            <input type="radio" name="gender" data-parsley-required="true" data-parsley-errors-container=".include-error-1" value="male" <?php if((old('gender') && (old('gender')=="male")) || ($profile->gender && ($profile->gender == "male"))) echo"checked"; ?> >
            <span>Male</span>
        </label>

        <label class="radioInput">
            <input type="radio" name="gender" data-parsley-required="true" data-parsley-errors-container=".include-error-1" value="female" <?php if((old('gender') && (old('gender')=="female")) || ($profile->gender && ($profile->gender == "female"))) echo"checked"; ?> >
            <span>Female</span>
        </label>
        @if ($errors->has('gender'))
            <span class="help-block">
                <strong>{{ $errors->first('gender') }}</strong>
            </span>
        @endif
    </div>
</div>
<!-- label.full -->

<div class="full">
    <span class="label">*Location:</span>
    <div class="formElement">
        <input id="location" type="text" name="location" class="full" data-parsley-required="false" data-parsley-error-message="Please choose your location" value="{{$profile->location}}">
        <input name='latitude' type="hidden" id="latitude" value="<?php echo ($profile->latitude)?$profile->latitude:'37' ?>">
        <input name='longitude' type="hidden" id="longitude" value="<?php echo ($profile->longitude)?$profile->longitude:'-122' ?>">
        @if ($errors->has('location'))
            <span class="help-block">
                <strong>{{ $errors->first('location') }}</strong>
            </span>
        @endif
    </div>
</div>
<!-- label.full -->

<label class="full">
    <span class="label">Tagline:</span>
    <div class="formElement">
        <input name="tagline" type="text" data-parsley-maxlength="39" class="full" placeholder="ex: Never give up" value="{{$profile->tagline}}">
    </div>
</label>
<!-- label.full -->

<label class="full">
    <span class="label">*Role:</span>
    <div class="formElement include-error-2">
        <select name="role[]" data-parsley-required="true" data-parsley-errors-container=".include-error-2" class="full multiValueSelect" multiple="multiple">
            @if($profile->role && !empty($profile->role))
                @foreach($profile->role as $role)
                    <option value="{{$role}}" selected>{{$role}}</option>
                @endforeach
            @endif
            @foreach($profile->staticRoles as $role)
                @if($profile->role && in_array($role, $profile->role))
                
                @else
                    <option value="{{$role}}" >{{$role}}</option>
                @endif
            @endforeach
        </select>
    </div>
</label>
<!-- label.full -->
<label class="full">
    <span class="label">*Genres:</span>
    <div class="formElement .include-error-3">
        <select name="geners[]" data-parsley-required="true" data-parsley-errors-container=".include-error-3" class="full multiValueSelect" multiple="multiple">
            @if($profile->geners && !empty($profile->geners))
                @foreach($profile->geners as $geners)
                    <option value="{{$geners}}" selected>{{$geners}}</option>
                @endforeach
            @endif
            @foreach($profile->staticGeners as $geners)
                @if($profile->geners && in_array($geners, $profile->geners))
                
                @else
                    <option value="{{$geners}}" >{{$geners}}</option>
                @endif
            @endforeach
        </select>
    </div>
</label>
<div class="full">
    <span class="label">*Profile Picture:</span>
    <div class="formElement">
        <label class="uploadAvatar">
            <span>Upload Avatar</span>
            @if($profile->profile_image)
                <input name="profile_image" type="file" data-parsley-required="false">
            @else
                <input name="profile_image" type="file" data-parsley-required="true">
            @endif
        </label>

        <div class="cropArea hide">
            <img id="profileImage" src="#" alt="Placeholder">
            <input type="hidden" id='profileImage_x' name='profile_image_x'>
            <input type="hidden" id='profileImage_y' name='profile_image_y'>
            <input type="hidden" id='profileImage_width' name='profile_image_width'>
            <input type="hidden" id='profileImage_height' name='profile_image_height'>
        </div>
        <!-- cropArea -->
        @if ($errors->has('profile_image'))
            <span class="help-block">
                <strong>{{ $errors->first('profile_image') }}</strong>
            </span>
        @endif
    </div>
</div>
<!-- div.full -->
<div class="full">
    <span class="label">*Profile Video:</span>
    <div class="formElement">
        <!-- <label class="uploadVideo">
            <span>Upload Video</span>
            <input type="file">
        </label> -->

        <a href="#uploadProfileVideo" class="profileVideo buttonDefault" data-effect="mfp-3d-unfold"><i class="icon">&#xf093;</i><span>Add Profile Video</span></a>

        <div id="uploadProfileVideo" class="container mfp-with-anim mfp-hide">
            <div class="wrapper">
                <p class="tips"><i class="icon">&#xf05a;</i> BE CATCHY!<br>Users normally watch the first 10’’ of a video - select or edit one that showcase your skills in the very beginning!</p>
                <div class="device">
                    <label class="upload">
                        <span><i class="icon">&#xf093;</i>Upload From Your Device</span>
                        <input name="profile_video" type="file" class="userProfileVideo">
                    </label>
                </div>

                <div class="sep">
                    <span>or</span>
                </div>

                <div class="external">
                    <label class="full">
                        <input name="profile_video" type="text" class="userProfileVideoLink" placeholder="Add Youtube Video Link ..." value="{{$profile->profile_video}}">
                    </label>
                </div>

                <label class="full">
                    <a href="#" class="buttonDefault insertProfileVideo">Insert</a>
                    <!-- <input type="submit" value="Insert" class="buttonDefault"> -->
                </label>
            </div>
        </div>
        <!-- uploadProfileVideo -->
        @if($profile->profile_video)
            <input type="text" hidden="hidden" class="profileVideoValue">
        @else
            <input type="text" hidden="hidden" class="profileVideoValue" data-parsley-required="true" data-parsley-error-message="Profile video is required">
        @endif
    </div>
</div>
<!-- div.full -->

<label class="full">
    <span class="label">*Profile URL:</span>
    <div class="formElement">
        <div class="profileURL">
            <span>scoutmee.com/profile/</span>
            <input name="profile_url" type="text" class="full" value="{{$profile->profile_url}}" data-parsley-required="true">
        </div>
        @if ($errors->has('profile_url'))
            <span class="help-block">
                <strong>{{ $errors->first('profile_url') }}</strong>
            </span>
        @endif
    </div>
</label>
<!-- label.full -->

<div class="full">
    <span class="label">Influenced By:</span>
    <div class="formElement">
        @if($profile->influnced_by)
                @foreach($profile->influnced_by as $key=>$value)
                <label class="moreFields">
            
                    <input name="influnced_by[]" type="text" class="full" placeholder="ex: Micheal Jackson" value="{{$value}}">
                    @if($key == 0)
                        <button class="addMoreFields"><i class="icon">&#xf067;</i></button>
                    @else
                        <button class="removeField"><i class="icon"></i></button>
                    @endif
                </label>
                @endforeach
        @else
        <label class="moreFields">
            
            <input name="influnced_by[]" type="text" class="full" placeholder="ex: Micheal Jackson">
            <button class="addMoreFields"><i class="icon">&#xf067;</i></button>
        </label>
        @endif
    </div>
</div>
<!-- label.full -->

<!-- <label class="full">
    <span class="label">Influenced By:</span>
    <div class="formElement">
        <select class="tagSelect" multiple="">
            <option>Peter</option>
            <option>Nicolas</option>
            <option>John</option>
            <option>Jack</option>
            <option>Mostafa</option>
        </select>
    </div>
</label> -->
<!-- label.full -->

<label class="full">
    <span class="label">Travelling Distance:</span>
    <div class="formElement">
        <input name="travelling_distance" type="text" class="full" placeholder="(In Miles) ex: 200" value="{{$profile->travelling_distance}}">
    </div>
</label>
<!-- label.full -->

<div class="mixed">
    <span class="label">Services:</span>
    <div class="formElement">
            @foreach($profile->servicesNames as $name=>$value)
            <div class="service">
                <label class="serviceCheckbox">
                    <input name="services_checkbox" type="checkbox" class="checkbox" @if(isset($profile->services) && array_key_exists($name,$profile->services))checked @endif>
                    <span>{{$value}}</span>
                </label>
                @if($profile->services &&!empty(array_filter($profile->services)) )
                    @foreach($profile->services as $key=>$subServices)
                    <?php $i=0 ;?>
                        @if(array_key_exists($name,$profile->services) && $name == $key)
                            @foreach($subServices as $subService=>$cost)
                                <label class="subService">
                                    <div>
                                        <span>Title:</span>
                                        <input name="{{$key}}_subservice_name[]" type="text" data-parsley-error-message="Please set a name for this service." value="{{$subService}}">
                                    </div>
                    
                                    <div class="servicePrice">
                                        <span>Price:</span>
                                        <div>
                                            <span class="first">$</span>
                                            <input name="{{$key}}_subservice_cost[]" type="text" data-parsley-type="number" data-parsley-error-message="Please set a price for this service and must be number only." value="{{$cost}}">
                                            <span class="last">/ Hour</span>
                                        </div>
                                    </div>
                                    @if($i == 0)
                                    <button class="addSubService"  data-subServiceName="{{$key}}"><i class="icon">&#xf067;</i></button>
                                    @else
                                    <button class="removeSubService"><i class="icon"></i></button>
                                    @endif
                                    <?php $i++ ;?>
                                </label>
                            @endforeach
                        @elseif(!array_key_exists($name,$profile->services))
                        <label class="subService">
                            <div>
                                <span>Title:</span>
                                <input name="{{$name}}_subservice_name[]" type="text" data-parsley-error-message="Please set a name for this service." value="">
                            </div>
            
                            <div class="servicePrice">
                                <span>Price:</span>
                                <div>
                                    <span class="first">$</span>
                                    <input name="{{$name}}_subservice_cost[]" type="text" data-parsley-type="number" data-parsley-error-message="Please set a price for this service and must be number only." value="">
                                    <span class="last">/ Hour</span>
                                </div>
                            </div>
                            <button class="addSubService"  data-subServiceName="{{$name}}"><i class="icon">&#xf067;</i></button>
                        </label>
                        @endif
                    @endforeach
                @else
                <label class="subService">
                    <div>
                        <span>Title:</span>
                        <input name="{{$name}}_subservice_name[]" type="text" data-parsley-error-message="Please set a name for this service." value="">
                    </div>
    
                    <div class="servicePrice">
                        <span>Price:</span>
                        <div>
                            <span class="first">$</span>
                            <input name="{{$name}}_subservice_cost[]" type="text" data-parsley-type="number" data-parsley-error-message="Please set a price for this service and must be number only." value="">
                            <span class="last">/ Hour</span>
                        </div>
                    </div>
                    <button class="addSubService"  data-subServiceName="{{$name}}"><i class="icon">&#xf067;</i></button>
                </label>
                @endif
                
            </div>
            <!-- service -->
            @endforeach
    </div>
</div>
<!-- mixed -->

<div class="mixed">
    <label class="full">
        <span class="label">About :</span>
        <div class="formElement">
            <textarea name="about" id="aboutForm" name="aboutForm">
                {{old('about')?old('about'):$profile->about}}
            </textarea>
        </div>
    </label>
</div>
<div class="mixed">
    <span class="label">&nbsp;</span>
    <div class="formElement">
        <!-- <div class="userApprove">
            <label>
                <input type="checkbox" class="checkbox">
                <span>Keep me posted with newsletters updates.</span>
            </label>
        </div> -->
        <!-- userApprove -->

        <div class="userApprove">
            <label>
                @if($profile->id)
                <input name="aggree" type="checkbox" class="checkbox" checked>
                @else
                <input  name="aggree" type="checkbox" class="checkbox" data-parsley-required="true" >
                @endif
                <span>I read and approve to the <a href="{{URL::to('terms')}}">user agreement</a></span>
            </label>
        </div>
        <!-- userApprove -->
    </div>
</div>
<!-- mixed -->

<div class="submit">
    <input type="submit" value="save" class="buttonDefault">
</div>
<!-- submit -->
@section('extra_js')
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script>
        var editor = CKEDITOR.replace("aboutForm");
    </script>
@endsection
