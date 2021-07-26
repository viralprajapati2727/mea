@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="community-wraper">
        <div class="container">
            <h1 class="page-title">
                Community
            </h1>
            <div class="global-search">
                <div class="top-search" style="margin-left: 0px">
                    <form class="row">
                        <div class="form-group col-lg-8 pr-lg-0">
                            <div class="input-group">
                                <input type="search" class="form-control" name="search" placeholder="Search by question and category" value="{{ $request['search'] ?? old("search") }}">
                            </div>
                        </div>
                        <div class="col-lg-2 pl-lg-0">
                            <select name="category" class="form-control">
                                <option value="">Category</option>
                                @if (sizeof($categories) > 0)
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" @if($request['category'] == $cat->id) selected @endif >{{ $cat->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <button class="search-btn">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="community-question-wraper">
                <div class="add-que-wrap d-flex justify-content-end">
            @if (Auth::check())
                <a href="javascript:;" class="btn" data-toggle="modal" data-target="#ask-question">Ask A Question</a>
            @else
                {{-- <p>Please login to ask question.</p> --}}
            @endif
            </div>                
            @if(sizeof($questions) > 0) 
                <div class="com-que-list"> 
                    <div class="com-que header">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="community-que">
                                    <h2>Questions</h2>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="views">
                                   <h2>Views</h2>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="answer">
                                    <h2>Answers</h2>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="votes">
                                    <h2>Likes</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($questions as $question)
                        <div class="com-que">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="community-que">
                                        @php
                                            $ProfileUrl = Helper::images(config('constant.profile_url'));
                                            $img_url = (isset($question->user->logo) && $question->user->logo != '') ? $ProfileUrl . $question->user->logo : $ProfileUrl.'default.png';
                                        @endphp
                                        <div class="profile">
                                            <img src="{{ $img_url }}" alt="user-profile" class="w-100" style="border-radius:100%;width: 100%; ">
                                        </div>
                                        <div class="question">
                                            <h3>
                                                <a href={{ route("community.questions-details",$question->slug) }}>
                                                    {{  $question->title }}
                                                </a>
                                            </h3>
                                            <span>By {{ $question->user->name ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="views">
                                        <span>{{ $question->views }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="answer">
                                        <span>{{ $question->comments->count() }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="votes">
                                        <span>{{ $question->countCommunityTotalLikes($question->id) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>                                
                    @endforeach
                </div>
            @else
            No question found
            @endif
            </div>
        </div>
    </div>
</div>


<!-- ask question modal -->
<div id="ask-question" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ask Question</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            
            <form class="ask_question_form" action="{{ route('community.update-community') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row mt-md-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Title <span class="required-star-color">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" value="{{ old('title') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-md-2 ckeditor">
                        <label class="col-form-label">About Question:<span class="required-star-color">*</span></label>
                        <div class="input-group custom-start">
                            <textarea name="description" id="description" rows="5" placeholder="About Question" class="form-control"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                    <div class="form-group mt-md-2">
                        <label class="form-control-label">Category <span class="required-star-color">*</span></label>
                        <select name="category_id" id="category_id" class="form-control select2 no-search-select2" data-placeholder="Select Category">
                            <option></option>
                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @empty
                            @endforelse
                            <option value="-1"> Other </option>
                        </select>
                    </div>
                    <div class="row mt-md-2 div_other_category" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Other Category </label>
                                <input type="text" class="form-control" name="other_category" id="other_category" placeholder="Enter Category" value="{{ old('other_category') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group tag">
                        <label class="form-control-label">Tag</label>
                        <input type="text" name="tag" id="tag" class="form-control tokenfield" value="" data-fouc>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Submit Post </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_script')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ Helper::assets('js/plugins/forms/tags/tokenfield.min.js') }}"></script>
<script>
    $('.tokenfield').tokenfield({
        autocomplete: {
            source: @json($tags),
            delay: 100
        },
        limit : 10,
        // showAutocompleteOnFocus: true
        createTokensOnBlur: true,
    });

    $('.tokenfield').on('tokenfield:createtoken', function (event) {
        var existingTokens = $(this).tokenfield('getTokens');
        //check the capitalized version

        $.each(existingTokens, function(index, token) {
            if ((token.label === event.attrs.value || token.value === event.attrs.value)) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/community.js') }}"></script>
@endsection
