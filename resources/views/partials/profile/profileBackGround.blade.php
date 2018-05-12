@if(isset($profile) && !empty($profile) && @$profile['profile_background'])
    <div class="profileBg" style="background-image: url('{{ $profile->getimageUrl($profile->profile_background)}} ')"></div>
@else
    <div class="profileBg" style="background-image: url('{{ asset("assets/images/placeholders/profile-bg.jpg")}} ')"></div>
@endif