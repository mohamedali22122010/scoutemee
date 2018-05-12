@extends('layouts.app')

@section('content')
<div class="profileWrapper">
    <div class="container">
        <div class="borderWrapper">
            @include('partials.profile.profileHeader',array('profile'=>Auth::user()->Profile))
            @include('partials.profile.profileInfo',array('profile'=>Auth::user()->Profile))

            <div class="profileContentWrapper">
                @include('partials.profile.profileTabs',array('profile'=>Auth::user()->Profile))
                <div class="inboxWrapper">
                    @if(count($ThreadUsers) > 0)
                    <div class="messagesHistory">
                        <div class="searchHistory">
                            {!! Form::open(['class'=>'searchForm' ,'id'=>'search-threads','files'=>false ,'route' => 'message.index','method' => 'get']) !!}
                                <label>
                                    <input name="title" type="text" class="inputDefault" placeholder="Search Messages ...">
                                    <div>
                                        <input type="submit" class="buttonDefault icon" value="&#xf002;">
                                    </div>
                                </label>
                            {!! Form::close() !!}
                        </div>
                        <!-- searchHistory -->

                        <div class="messagesList">
                            <ul>
                                @foreach($ThreadUsers as $ThreadUser)
                                    <li >
                                        <a href='{{ URL::to("message/{$ThreadUser->thread->id}")}}' class="@if(!$ThreadUser->is_read)unread @endif">
                                            <div class="contactAvatar">
                                                @if($ThreadUser->thread->threadusers  && count($ThreadUser->thread->threadusers) == 2)
                                                    @foreach($ThreadUser->thread->threadusers as $threaduser)
                                                        @if($threaduser->user->id != Auth::user()->id && @$threaduser->user->Profile->profile_image)
                                                            <img src="{{ $threaduser->user->Profile->getProfileImage($threaduser->user->Profile->profile_image) }}" alt="User Avatar">
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <img src="{{ asset('assets/images/placeholders/avatar.jpg')}} " alt="User Avatar">
                                                @endif
                                            </div>
                                            <div class="contactInfo">
                                                @if($ThreadUser->thread->threadusers)
                                                    @foreach($ThreadUser->thread->threadusers as $threaduser)
                                                        @if($threaduser->user->id != Auth::user()->id)
                                                            <h3>{{$threaduser->user->first_name}}</h3>
                                                        @endif
                                                    
                                                    @endforeach
                                                @endif
                                                <h5>{{$ThreadUser->thread->title}}</h5>
                                            </div>
                                            <p class="messageDate">{{\Carbon\Carbon::parse($ThreadUser->updated_at)->format('F jS, Y g:i:s a')}}</p>
                                        </a>
                                    </li>
                                     <!-- message -->
                                @endforeach
                            </ul>
                        </div>
                        <!-- messagesList -->
                    </div>
                    <!-- messagesHistory -->

                    <div class="activeConversation">
                        <div class="conversation">
                            
                        </div>
                        <!-- conversation -->
                    </div>
                    <!-- activeConversation -->
                    @else
                        <div class="noContent">
                            <p>No message available!</p>
                        </div>
                        <!-- noMessage -->
                    @endif
                </div>
                <!-- inboxWrapper -->
                
            </div>
            <!-- profileContentWrapper -->
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileWrapper -->
@endsection