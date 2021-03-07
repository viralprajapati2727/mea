@extends('layouts.app')
@section('content')
<div class="page-main">
    <div class="page-wraper fund-inner-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="fund-left-content">
                        <div class="header">
                            <h2 class="title">Basic Necessities Cause Fund</h2>
                            <p class="user">
                                <img src="images/profile/default.png">
                                <span>Viral Prajapati</span>
                            </p>
                        </div>
                        <div class="fund-content">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
                            ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat 
                            cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <p>The purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn't distract from the layout. A practice not without controversy, 
                            laying out pages with meaningless filler text can be very useful when the focus is meant to be on design, not content.</p>                        
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fund-sidebar">
                        <div class="sidebar-inner">
                            <div class="siderbar-header">
                                <div class="fund-amount">
                                    <h3>$185,806</h3><span>raised of $250, 000 goal</span>
                                </div>
                                <progress id="file" value="32" max="100"> 32% </progress>
                            </div>
                            <div class="donate-info">
                                <ul>
                                    <li>
                                        <p>2.9K</p>
                                        <span>Donors</span>
                                    </li>
                                    <li> 
                                        <p>1.5K</p>
                                        <span>Shares</span>
                                    </li>
                                    <li>
                                        <p>2.8K</p>
                                        <span>Followers</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="fund-actions">
                                <button class="btn share-btn">Share</button>
                                <button class="btn donate-btn">Donate now</button>
                            </div>
                            <div class="sidebar-footer">
                                <ul>
                                    <li>
                                        <div class="icon"><img src="../images/fund/graph_icon.png"></div>
                                        <span>87 people just donated</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 