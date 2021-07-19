@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="member-wraper">
        <div class="container">
            <div class="global-search">
                <div class="top-search">
                    <form>
                        <input type="search" name="keyword" placeholder="Search by name, tags and more" value="{{ array_key_exists('keyword',$params) ? $params['keyword'] : old('keyword') }}">
                    </form>
                </div>
                <div class="member-list-wraper job-list-wraper d-md-flex align-items-md-start">
                    <div class="sidebar-left">
                        <form class="global-search" autocomplete="off">
                            <div class="sidebar-main">
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline">
                                        <h5 class="font-black m-0">Filter</h5>
                                        <div class="header-elements">
                                            <a class="jb_nav_btn_link text-center font-weight-bold" href="{{ route('job.global-search',['sort' => isset($params['sort']) ? $params['sort'] : '']) }}">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Job title -->
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline border-b-n">
                                        <h5 class="font-black m-0">Job Title</h5>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group custom-multi">
                                            <select name="title[]" multiple="multiple" class="" placeholder="Job Title">
                                                @if(isset($jobtitles))
                                                    @forelse ($jobtitles as $job_title)
                                                        <option value="{{ $job_title->id }}" {{ isset($params['title']) ? ((array_key_exists('title',$params) && in_array($job_title->id,$params['title'])) ? 'selected' : '') : '' }}>{{ $job_title->title }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            {{--  <a href="javascript:;" class="more show-more show">Show More</a>  --}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /Job title -->
                                <!-- Business Categories -->
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline border-b-n">
                                        <h5 class="font-black m-0">Category</h5>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group custom-multi">
                                            <select name="category[]" multiple="multiple" placeholder="Category">
                                                @forelse ($business_categories as $category)
                                                <option value="{{ $category->id }}" {{ isset($params['category']) ? ((array_key_exists('category',$params) && in_array($category->id, $params['category'])) ? 'selected' : '') : '' }}> {{ $category->title }} </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            {{--  <a href="javascript:;" class="more show-more show">Show More</a>  --}}
                                        </div>
                                    </div>
                                </div>
                                <!-- /Business Categories -->
                                <!-- Job type -->
                                <div class="card">
                                    <div class="card-header bg-transparent header-elements-inline border-b-n">
                                        <h5 class="font-black m-0">Job Type</h5>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group custom-multi">
                                            <select name="type[]" multiple="multiple" class="" placeholder="Job Type">
                                                @forelse (config('constant.job_type') as $key => $jobtype)
                                                    <option value="{{ $key }}" {{ isset($params['type']) ? ((array_key_exists('type',$params) && in_array($key, $params['type'])) ? 'selected' : '') : '' }}>{{ $jobtype }}</option>
                                                @empty
                                                @endforelse                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Job type -->
                                <div class="border-0 py-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-apply mx-auto">Apply</button>
                                    {{-- <input hidden name="keyword" value="{{ array_key_exists('keyword',$params) ? $params['keyword'] : old('keyword') }}"> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="right-column">
                        <div class="job-lists-wrap">
                            @if(!$jobs->isEmpty() && $jobs->count())
                                @forelse ($jobs as $job)
                                    <div class="card">
                                        <div class="jobs-details-wrap">
                                            <div class="row align-items-center">
                                                <div class="col-md-9">
                                                    <div class="job-detail-left">
                                                        <div class="job-media">
                                                            <img src="{{ Helper::assets('images/job-portal/designer.jpg') }}" alt="">
                                                        </div>
                                                        <div class="job-description">
                                                            <h2 class="job-title"><a href="{{ route('job.job-detail',['id' => $job->job_unique_id]) }}" class="font-black">{{ $job->job_title_id > 0 ? $job->jobTitle->title : $job->other_job_title }}</a></h2>
                                                            {{-- <div class="job-company-location">
                                                                <p class="company">@ Company A</p>
                                                            </div> --}}
                                                            <div class="d-sm-inline d-inline-block mr-3">
                                                                <i class="fa fa-map-marker"></i>
                                                                <span>{{ $job->location }}</span>
                                                            </div>
                                                            @if($job->job_type == 1)
                                                            <div class="d-sm-inline d-inline-block mr-3">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span>{{ config('constant.job_type')[$job->job_type_id] }}</span>
                                                            </div>
                                                            <div class="d-sm-inline1 d-inline-block1">
                                                                <i class="fa fa-money"></i>
                                                                <span>{{ $job->is_paid ? ($job->currency->code ." ". $job->min_salary." - ".$job->currency->code ." ". $job->max_salary) : "" }}</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="job-actions">
                                                        <ul>
                                                            <li><a href="{{ route('job.job-detail',['id' => $job->job_unique_id]) }}" class="job-detail-btn">Job Detail</a></li>
                                                            <li>
                                                                @if(Auth::check())
                                                                    <a href="javascript:;" class="apply-btn" data-id="{{ $job->id }}">Quick Apply</a>
                                                                @else
                                                                    <a href="{{ route('login') }}" class="apply-btn">Apply</a>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                <div class="d-flex pt-3 mb-3">
                                    {!! $jobs->onEachSide(1)->appends($params)->links() !!}
                                </div>
                            @else
                            <div class="pagination my-5">No Data Found !!</div>
                            @endif
                        </diV>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- apply job modal -->
<div id="apply-job-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <p class="already_applied_text text-success" style="display: none"></p>
            <form class="apply_job_form" action="{{ route('job.apply-job') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group mt-md-2 ckeditor">
                        <label class="col-form-label">Additional Information:<span class="required-star-color">*</span></label>
                        <div class="input-group custom-start">
                            <textarea name="description" id="description" rows="5" placeholder="Message" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                </div>
                <input type="hidden" name="job_id" class="job_id">
                <input type="hidden" name="job_applied_id" class="job_applied_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('footer_script')
<script>
var check_already_applied_link = "{{ route('job.check-apply-job') }}";
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