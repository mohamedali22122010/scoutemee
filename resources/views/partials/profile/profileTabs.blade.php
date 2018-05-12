@if($profile )
<div class="tabs">
    <ul>
        @if($profile->profile_url)
        <li><a href='{{URL::to("profile/$profile->profile_url#profileContentWrapperId")}}' class="@if(@$active == 'index') active @endif" ><i class="icon">&#xf017;</i><span>Latest</span></a></li>
        <li><a href='{{URL::to("profile/$profile->profile_url/music#profileContentWrapperId")}}' class="@if(@$active == 'musics') active @endif"><i class="icon">&#xf001;</i><span>Music</span></a></li>
        <li><a href='{{URL::to("profile/$profile->profile_url/videos#profileContentWrapperId")}}' class="@if(@$active == 'videos') active @endif"><i class="icon">&#xf03d;</i><span>Videos</span></a></li>
        <li><a href='{{URL::to("profile/$profile->profile_url/images#profileContentWrapperId")}}' class="@if(@$active == 'images') active @endif" ><i class="icon">&#xf03e;</i><span>Photos</span></a></li>
        <li><a href='{{URL::to("profile/$profile->profile_url/about#profileContentWrapperId")}}' class="@if(@$active == 'about') active @endif" ><i class="icon">&#xf05a;</i><span>About</span></a></li>
        @else
        <li><a href='{{URL::to("profile/$profile->id")}}' class="@if(@$active == 'index') active @endif" ><i class="icon">&#xf017;</i><span>Latest</span></a></li>
        <li><a href='{{URL::to("profile/$profile->id/music")}}' class="@if(@$active == 'musics') active @endif"><i class="icon">&#xf001;</i><span>Music</span></a></li>
        <li><a href='{{URL::to("profile/$profile->id/videos")}}' class="@if(@$active == 'videos') active @endif"><i class="icon">&#xf03d;</i><span>Videos</span></a></li>
        <li><a href='{{URL::to("profile/$profile->id/images")}}' class="@if(@$active == 'images') active @endif" ><i class="icon">&#xf03e;</i><span>Photos</span></a></li>
        <li><a href='{{URL::to("profile/$profile->id/about")}}' class="@if(@$active == 'about') active @endif" ><i class="icon">&#xf05a;</i><span>About</span></a></li>
        @endif
    </ul>
</div>
<!-- tabs -->
@endif