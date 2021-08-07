@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="member-wraper">
        <div class="container">
            <div class="global-search">
                <div class="top-search">
                    <form>
                        <input type="search" name="keyword" placeholder="Search by Name" value="{{ array_key_exists('keyword',$params) ? $params['keyword'] : old('keyword') }}">
                    </form>
                </div>
                <div class="member-list-wraper d-md-flex align-items-md-start job-list-wraper">
                    <div class="sidebar-left">
                        <form class="global-search" autocomplete="off">
                            <div class="sidebar-main">
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline">
                                        <h5 class="font-black m-0">Filter</h5>
                                        <div class="header-elements">
                                            <a class="jb_nav_btn_link text-center font-weight-bold" 
                                            href="{{ route('page.members') }}"
                                            >
                                            Clear All</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Skills  -->
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline border-b-n">
                                        <h5 class="font-black m-0">Skills</h5>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group custom-multi">
                                            <select name="skill[]" multiple="multiple" placeholder="Skill">
                                                @forelse ($skills as $skill)
                                                <option value="{{ $skill->title }}" {{ isset($params['skill']) ? ((array_key_exists('skill',$params) && in_array($skill->title, $params['skill'])) ? 'selected' : '') : '' }}> {{ ucfirst($skill->title) }} </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Skills end -->
                                <!-- City -->
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline border-b-n">
                                        <h5 class="font-black m-0">City</h5>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group custom-multi">
                                            <select name="city[]" multiple="multiple" class="" placeholder="City">
                                                @forelse ($cities as $city)
                                                    <option value="{{ $city->city }}" {{ isset($params['city']) ? ((array_key_exists('city',$params) && in_array($city->city, $params['city'])) ? 'selected' : '') : '' }} >{{ ucfirst($city->city) }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-0 py-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-apply mx-auto">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="right-column">
                        @if(!$members->isEmpty())
                        <div class="right-column-header">
                            <h5 class="m-0">{{ sizeof($members) }} results</h5>
                        </div>
                        <div class="member-list">
                            @foreach ($members as $member)
                                <div class="card">
                                    <div class="member-item d-flex flex-column flex-sm-row p-2 align-items-center">
                                        <div class="media-left">
                                            <a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}" class="profile-image">
                                                @php
                                                    $ProfileUrl = Helper::images(config('constant.profile_url'));
                                                    $img_url = (isset($member->logo) && $member->logo != '') ? $ProfileUrl . $member->logo : $ProfileUrl.'default.png';
                                                @endphp
                                                <img src="{{ $img_url }}" alt="" class="w-100">
                                            </a>
                                        </div>
                                        <div class="member-detail">
                                            <h2 class="name">
                                                <a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}">
                                                    {{ $member->name }}
                                                </a>
                                            </h2>
                                            <div class="skills">
                                                <label>Skills</label>
                                                @if (sizeof($member->skills) > 0)
                                                    @foreach ($member->skills as $skill)
                                                        <p>{{ $skill->title }}</p>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </div>
                                            <div class="location">
                                                <label>City</label>
                                                <p>{{ $member->userProfile ? $member->userProfile->city : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="contact-details">
                                            <ul>
                                                <li><a href="{{ route("user.view-profile", ["slug" => $member->slug]) }}">Contact</a></li>
                                                <li><a href="{{ route('member.message', ['user'=> $member->slug]) }}">Message</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pagination my-5">
                            {{ $members->onEachSide(1)->appends($params)->links() }}
                        </div>
                        @else
                            <div class="pagination my-5">No Members Found!!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_script')
<script>
    $(document).ready(function(){
    $('select[multiple]').multiselect({
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        selectAllJustVisible: true,
        enableFiltering: true,
        allSelectedText: 'All',
        numberDisplayed: 1,
        nSelectedText: 'Selected',
        nonSelectedText: 'None Selected',
        selectAllText: 'All',
        onChange: function(option, checked) {
            parentNode = option[0].parentNode;
            var selectedOptions = $(parentNode).find('option:selected');
            var allOptions = $(parentNode).find('option');
            if(selectedOptions.length == allOptions.length){
                $(parentNode).multiselect('selectAll').multiselect('refresh');
            }else if(selectedOptions.length < allOptions.length){
                $(parentNode).multiselect('deselectAll');
                $(selectedOptions).prop('selected',true);
                option.prop('selected',checked);
                $(parentNode).multiselect('refresh');
            }
        },
        onInitialized: function(select, container) {

        },
    });

    $(document).on('keyup','.multiselect-filter input',function(){
        var parentNode = $(this).parents('.multiselect-native-select').find('select');
        var selectedOptions = $(parentNode).find('option:selected');
        var allOptions = $(parentNode).find('option');

        if(selectedOptions.length == allOptions.length){
            $(parentNode).multiselect('selectAll').multiselect('refresh');
        }else if(selectedOptions.length < allOptions.length){
            $(parentNode).multiselect('deselectAll');
            $(selectedOptions).prop('selected',true);
            $(parentNode).multiselect('refresh');
        }
        
        // var count_option = 0;
        // allOptions.each(function(index,element){
        //     //$(element).hasClass('.multiselect-filter-hidden');
        //     if(count_option > 4){
        //         $(element).hide();
        //     }
        //     count_option++
        // });
    });

    $('select[multiple]').each(function(){
        var selectedOptions = $(this).find('option:selected');
        var allOptions = $(this).find('option');
        if(selectedOptions.length == allOptions.length){
            $(this).multiselect('rebuild').multiselect('selectAll').multiselect('refresh');
        }
        $(this).parents('.multiselect-native-select').find('.multiselect-search').attr('placeholder', $(this).attr('placeholder'));

        var allOptions = $(this).parents('.multiselect-native-select').find('.multiselect-container.dropdown-menu .multiselect-item.dropdown-item.form-check');
        // var count_option = 0;
        // allOptions.each(function(index,element){
        //     if(count_option > 4){
        //         $(element).hide();
        //     }
        //     count_option++
        // });
    });

    $('.sort').select2({
        minimumResultsForSearch: Infinity,
    });
});
</script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/apply.js') }}"></script>
@endsection