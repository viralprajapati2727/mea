@extends('layouts.app')
@section('content')

<div class="job-portal-main">
    <div class="jp-front-banner">
        <div class="container">
            <div class="jp-banner-content text-center">
                <h1>Find Your Desired Opportunity</h1>
                <p>Jobs, Employment & Future Career Opportunities</p>
            </div>
            <form class="global-search-form" action="{{ route('job.global-search') }}">
                <div class="form-group">
                    <div class="form-body">
                        <div class="input-control">
                            <input type="text" name="title" placeholder="Job TItle">
                        </div>
                        <div class="input-control">
                            <input type="text" name="city[]" placeholder="City">
                        </div>
                        <div class="input-control">
                            <select name="category[]">
                                <option value="">Job Category</option>
                                @forelse ($business_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="form-btn">Serch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="jp-job-category">
        <div class="container">
            <div class="title">
                <h2>Choose Your Interest</h2>
            </div>
            <div class="jp-job-category-listing">
                <ul class="m-auto">
                    @php
                        // $bCategories = $business_categories->limit(12)->all();
                    @endphp
                    @forelse ($business_categories as $category)
                    @php
                        // echo "<pre>"; print_r($category); echo "</pre>";
                    @endphp
                        <li class="text-center jp-job-cat-lwrp">
                            <a href="{{ route('job.global-search') }}?category%5B%5D={{ $category->id }}">
                                <div class="jp-job-cat-icon">
                                    @php
                                        $bcategoryUrl = Helper::images(config('constant.business_category_url'));
                                    @endphp
                                    <img src="{{ $bcategoryUrl.$category->src }}" alt="">
                                </div>
                                <h5 class="jp-job-cat-title">{{ $category->title }}</h5>
                            </a>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @if(!$recentJobs->isEmpty() && $recentJobs->count())
        <div class="recent-jobs">
            <div class="container">
                <div class="title text-center">
                    <h2>Recent Jobs</h2>
                    <p>Make the most of the opportunity available by browsing among the most trending categories and get hired today.</p>
                </div>
                <div class="job-lists-wrap">
                    @forelse ($recentJobs as $job)
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
                </diV>
            </div>
        </div>
    @endif
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
</script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/apply.js') }}"></script>
@endsection