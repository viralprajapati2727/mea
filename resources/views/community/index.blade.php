@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="community-wraper">
        <div class="container">
            <h1 class="page-title">
                Community
            </h1>
            <div class="global-search">
                <div class="top-search">
                    <form>
                        <div class="form-control">
                            <input type="search" name="search" placeholder="Search by name, tags and more">
                            <button class="search-btn">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="community-question-wraper">
                <div class="add-que-wrap d-flex justify-content-end">
                    <a href="javascript:;" class="btn" data-toggle="modal" data-target="#ask-question">Ask A Question</a>
                </div>
                <div class="com-que-list">
                    <div class="com-que header">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="community-que">
                                    <h2>Questions</h2>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="views">
                                   <h2>Views</h2>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="answer">
                                    <h2>Answers</h2>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="votes">
                                    <h2>Votes</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @isset($questions)
                        @foreach ($questions as $question)
                            <div class="com-que">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="community-que">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    @php
                                                        $ProfileUrl = Helper::images(config('constant.profile_url'));
                                                        $img_url = (isset(Auth::user()->logo) && Auth::user()->logo != '') ? $ProfileUrl . Auth::user()->logo : $ProfileUrl.'default.png';
                                                    @endphp 
                                                    <div class="profile">
                                                        <img src="{{ $img_url }}" alt="user-profile" class="w-100" style="border-radius:100%;width: 100%; ">
                                                    </div>
                                                </div>
                                                <div class="com-md-11">
                                                    <div class="question">
                                                        <h3>
                                                            <a href={{ route("community.questions-details",$question->slug) }}>
                                                                {{  $question->title }}
                                                            </a>
                                                        </h3>
                                                        <span>By {{ $question->user->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="views">
                                            <span>{{ $question->views }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="answer">
                                            <span>{{ 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="votes">
                                            <span>{{ 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                        @endforeach
                    @endisset
                    {{-- <div class="com-que">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="community-que">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="profile">
                                                <img src="{{ Helper::assets('images/profile/profile.png') }}" alt="" class="w-100">
                                            </div>
                                        </div>
                                        <div class="com-md-11">
                                            <div class="question">
                                                <h3>Discuss your business ideas here!</h3>
                                                <span>By Nida Yasir</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="views">
                                    <span>65</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="answer">
                                    <span>21</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="votes">
                                    <span>11</span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
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
                        </select>
                    </div>
                    <div class="form-group tag">
                        <label class="form-control-label">Tag <span class="required-star-color">*</span></label>
                        <input type="text" name="tag" id="tag" class="form-control tokenfield" value="" data-fouc>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Submit form</button>
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
