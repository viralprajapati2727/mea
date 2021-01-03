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
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
                    <div class="com-que">
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
                    </div>
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

            
            <form class="ask_question_form" action="{{ route('appointment.update-appointment') }}" class="form-horizontal" data-fouc method="POST" autocomplete="off">
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
        limit : 10,
        // showAutocompleteOnFocus: true
        createTokensOnBlur: true,
    });

    
</script>
<script type="text/javascript" src="{{ Helper::assets('js/pages/community.js') }}"></script>
@endsection
