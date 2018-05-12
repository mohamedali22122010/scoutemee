@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Message</div>
                <div class="panel-body">
                    {!! Form::open(['class'=>'form-horizontal' ,'id'=>'create_message','files'=>true ,'route' => 'message.store']) !!}
                        <input type="hidden" name="users[]" value="{{@$reciverUserId}}"/>
                        <input type="hidden" name="thread_id" value="{{@$threadId}}"/>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Message Title</label>

                            <div class="col-md-6">
                                {!! Form::select('title',$message->titles,'', array('class' => 'select-input')) !!}

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Message Content</label>

                            <div class="col-md-6">
                                <textarea class="form-control" name="content" value="{{ old('content') }}"></textarea>

                                @if ($errors->has('content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Send Message
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
