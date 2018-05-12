@extends('layouts.app')

@section('content')
<div class="profileWrapper">
    <div class="container">
        <div class="borderWrapper">
            @include('partials.profile.profileHeader',array('profile'=>Auth::user()->Profile))
            @include('partials.profile.profileInfo',array('profile'=>Auth::user()->Profile))
            <div class="profileContentWrapper">
                @include('partials.profile.profileTabs',array('profile'=>Auth::user()->Profile))
                @if(count($ThreadUsers) > 0)
                <div class="inboxWrapper">
                    <div class="messagesHistory hide">
                        <div class="backButton">
                            <a href="{{URL::to('/message')}}" class="buttonDefault">Back to messages</a>
                        </div>
                        <!-- backButton -->
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
                                        <a href='{{ URL::to("message/{$ThreadUser->thread->id}")}}' class="@if(!$ThreadUser->is_read)unread @endif @if(@$messages[0]->thread->id == $ThreadUser->thread->id) active @endif" >
                                            <div class="contactAvatar">
                                                    @if($ThreadUser->thread->threadusers  && count($ThreadUser->thread->threadusers) == 2)
                                                        @foreach($ThreadUser->thread->threadusers as $threaduser)
                                                            @if($threaduser->user->id != Auth::user()->id && @$threaduser->user->Profile->profile_image)
                                                                <img src="{{$threaduser->user->Profile->getProfileImage($threaduser->user->Profile->profile_image) }}" alt="User Avatar">
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
                            <div class="tableView">
                                <ul>
                                    @foreach($messages as $message)
                                        @if($message->creator->id == Auth::user()->id)
                                            <?php $class= 'reply'; ?>
                                        @else
                                            <?php $class= 'message'; ?>
                                        @endif
                                        
                                        @if($message->content == "Accepted the offer")
                                        <li class="{{$class}} accepted">
                                        @elseif($message->content == "Declined the offer")
                                        <li class="{{$class}} declined">
                                        @else
                                        <li class="{{$class}}">
                                        @endif
                                            <div class="contactAvatar">
                                                @if(@$message->creator->Profile->profile_image)
                                                    <img src="{{$message->creator->Profile->getProfileImage($message->creator->Profile->profile_image) }}" alt="User Avatar">
                                                @elseif(@$message->creator->user_image)
                                                    <img src="{{$message->creator->getimageUrl($message->creator->user_image) }}" alt="User Avatar">
                                                @else
                                                    <img src="{{ asset('assets/images/placeholders/avatar.jpg')}} " alt="User Avatar">
                                                @endif
                                            </div>
                                            <!-- contactAvatar -->
    
                                            <div class="{{$class}}Content">
                                                <div class="contactInfo">
                                                    <h3>{{$message->creator->first_name}}</h3>
                                                </div>
    
                                                <div class="{{$class}}ItSelf">
                                                    <p>{!! $message->content !!}</p>
                                                </div>
    
                                                <p class="{{$class}}Date">{{\Carbon\Carbon::parse($message->created_at)->format('F jS, Y g:i:s a')}}</p>
                                            </div>
                                            <!-- messageContent -->
                                        </li>
                                        <!-- message -->
                                        @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- conversation -->

                        <div class="replyBox">
                            {!! Form::open(['class'=>'form-horizontal' ,'id'=>'reply_message','files'=>true ,'route' => 'message.store']) !!}
                            <input type="hidden" name="thread_id" value="{{@$id}}"/>
                                <label>
                                    <textarea id="message-reply-content" name="content" class="inputDefault" placeholder="Write a reply .."></textarea>
                                </label>

                                <div id="message-actions">
                                    <button type="submit" class="buttonDefault"><i class="icon">&#xf0e0;</i><span>Send</span></button>
                                    @if(isset(Auth::user()->Profile->id))
                                    <div class="actions">
                                        <button id="accept-message" class="buttonDefault"><i class="icon">&#xf00c;</i><span>Accept</span></button>

                                        <button id="decline-message" class="buttonDefault"><i class="icon">&#xf00d;</i><span>Decline</span></button>
                                    </div>
                                    @endif
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- replyBox -->
                    </div>
                    <!-- activeConversation -->
                </div>
                <!-- inboxWrapper -->
                @else
                    <div class="noContent">
                        <p>No message available!</p>
                    </div>
                    <!-- noMessage -->
                @endif
            </div>
            <!-- profileContentWrapper -->
        </div>
        <!-- borderWrapper -->
    </div>
</div>
<!-- profileWrapper -->
@endsection