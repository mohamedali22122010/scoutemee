
<div class="updateStatus">
    <form id="post-form-create" method="POST" action="{{URL::to('/profile/post')}}" enctype='multipart/form-data' data-parsley-validate >
        {!! csrf_field() !!}
        <label>
            <textarea name="description" placeholder="Update Your Status ..."  data-parsley-required="true" data-parsley-error-message="Please Add something."></textarea>
        </label>
        
        <input class="partial-form-value" type="hidden" name="type" value="0">

        <div class="attachments">
            <span class="icon">&#xf0c6;</span>
            <div></div>
        </div>
        <!-- attachments -->

        <div class="actions">
            <ul>
                <li><a href="#uploadVideo" class="video" data-effect="mfp-3d-unfold"><i class="icon">&#xf03d;</i></a></li>
                <li><a href="#uploadMusic" class="music" data-effect="mfp-3d-unfold"><i class="icon">&#xf001;</i></a></li>
                <li><a href="#uploadPhoto" class="photo" data-effect="mfp-3d-unfold"><i class="icon">&#xf03e;</i></a></li>
            </ul>

            <button type="submit" class="buttonDefault"><i class="icon">&#xf040;</i><span>Post</span></button>
        </div>
        <!-- actions -->
        <div id="uploadVideo" class="container mfp-with-anim mfp-hide">
            <div class="wrapper">
                <div class="device">
                    <label class="upload">
                        <span><i class="icon">&#xf093;</i>Upload From Your Device</span>
                        <input name="video_file" type="file">
                    </label>
                </div>

                <div class="sep">
                    <span>or</span>
                </div>

                <div class="external">
                    <label class="full">
                        <input name="video_link" type="text" placeholder="Insert External Link ...">
                    </label>
                </div>

                <div>
                    <a href="#" class="buttonDefault closeUploadVideo">Insert</a>
                    <!-- <input type="submit" value="Insert" class="buttonDefault"> -->
                </div>
            </div>
        </div>
        <!-- uploadVideo -->

        <div id="uploadMusic" class="container mfp-with-anim mfp-hide">
            <div class="wrapper">
                <div class="device">
                    <label class="upload">
                        <span><i class="icon">&#xf093;</i>Upload From Your Device</span>
                        <input name="audio_file" type="file">
                    </label>
                </div>

                <div class="sep">
                    <span>or</span>
                </div>

                <div class="external">
                    <label class="full">
                        <input name="audio_link" type="text" placeholder="Insert External Link ...">
                    </label>
                </div>

                <div>
                    <a href="#" class="buttonDefault closeUploadMusic">Insert</a>
                    <!-- <input type="submit" value="Insert" class="buttonDefault"> -->
                </div>
            </div>
        </div>
        <!-- uploadMusic -->

        <div id="uploadPhoto" class="container mfp-with-anim mfp-hide">
            <div class="wrapper">
                <div class="device">
                    <label class="upload">
                        <span><i class="icon">&#xf093;</i>Upload From Your Device</span>
                        <input name="image_file" type="file">
                    </label>
                </div>

                <div>
                    <a href="#" class="buttonDefault closeUploadPhoto">Insert</a>
                    <!-- <input type="submit" value="Insert" class="buttonDefault"> -->
                </div>
            </div>
        </div>
        <!-- uploadPhoto -->
    </form>
</div>
<!-- updateStatus -->