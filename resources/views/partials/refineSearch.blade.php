<section class="refineSearch">
    <header class="sectionHeader">
        <h3>Refine Search</h3>
    </header>
    <!-- sectionHeader -->
    {!! Form::open(['class'=>'searchForm' ,'id'=>'search-profiles-advanced','files'=>false ,'route' => 'search.advanced','method' => 'get']) !!}
        <fieldset>
            <label>
                <input name="name" type="text" placeholder="Name" value="{{@Request::input('name')}}">
            </label>
            <input name='sort_by' type="hidden" id="sort-option-advanced" value="{{@Request::input('sort_by')}}" />
            <input name='page' type="hidden" class="currentPage" value="1">
            @if(Request::is('advanced-search'))
            <input id="advanced-search" type="hidden" >
            @endif
    
            <label>
                <select name="geners">
                    <option value="">Genres</option>
                    @foreach($profileModel->staticGeners as $geners)
                        <option value="{{$geners}}" <?php if(Request::input('geners') && Request::input('geners')== $geners) echo"selected"; ?> >{{$geners}}</option>
                    @endforeach
                </select>
            </label>
    
            <label>
                <select name="role">
                    <option value="">Role</option>
                    @foreach($profileModel->staticRoles as $role)
                        <option value="{{$role}}" <?php if(Request::input('role') && Request::input('role') == $role) echo "selected"; ?> >{{$role}}</option>
                    @endforeach
                </select>
            </label>
        </fieldset>
        <!-- fieldset -->
    
        <a href="#" class="showAdvanced">Advanced</a>
    
        <fieldset class="advancedOptions">
            <label>
                <input name="influenced_by" type="text" placeholder="Influenced Artists" value="{{@Request::input('influenced_by')}}">
            </label>
    
            <label>
                <select name="gender" >
                    <option value="">Gender</option>
                    <option value="male" <?php if(Request::input('gender') && Request::input('gender') == "male") echo "selected"; ?> >Male</option>
                    <option value="female" <?php if(Request::input('gender')&& Request::input('gender') == "female") echo "selected"; ?> >Female</option>
                </select>
            </label>
            <div class="label">
                <input name='location' type="text" id="advancedLocation" placeholder="Around what city">
                <input name='latitude' type="hidden" id="advancedLatitude" value="{{@Request::input('latitude')}}" />
                <input name='longitude' type="hidden" id="advancedLongitude" value="{{@Request::input('longitude')}}" />
            </div>
            <label class="checkbox">
                <input name="live_performance" type="checkbox" <?php if(Request::input('live_performance')) echo "checked"; ?> /> <span>Does Live Performance</span>
            </label>
            <label  class="checkbox">
                <input name="music_lessons" type="checkbox" <?php if(Request::input('music_lessons')) echo "checked"; ?> /> <span>Does Music Lessons</span>
            </label>
        </fieldset>
        <!-- fieldset -->
    
        <div class="submit">
            <input type="submit" value="Refine" class="buttonDefault">
        </div>
    </form>
    <!-- form -->
</section>
<!-- refineSearch -->