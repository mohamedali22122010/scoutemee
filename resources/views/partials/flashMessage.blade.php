@if ($errors && !empty($errors->all()))
    <div class="flashMessages show">
        <div class="warning">
        @foreach($errors->all() as $error)
            <p>{{ $error }} @if($error == 'These credentials do not match our records.') <a href="{{ url('/password/reset') }}">Forgot password?</a> @endif</p>
            @break
        @endforeach
        <a href="#">X</a>
        </div>
    </div>
    <!-- flashMessages -->
@endif
@if (Session::has('errorMessage'))
    <div class="flashMessages show">
        <div class="warning">
            <p>{{ Session::get('errorMessage') }}</p>
        <a href="#">X</a>
        </div>
    </div>
    <!-- flashMessages -->
@endif