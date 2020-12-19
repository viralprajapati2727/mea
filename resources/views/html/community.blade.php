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
                    <button class="btn">Ask A Question</button>
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
@endsection