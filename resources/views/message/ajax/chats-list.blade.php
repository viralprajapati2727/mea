@if(isset($currentChats) && !empty($currentChats) && count($currentChats))
    @foreach($currentChats as $key => $chat)
        @php
            // $eventImage = Helper::assets('images/event-band.jpg');
            // $unreadmsg_data = 0;
            // if(!empty($event->receivers) && $event->receivers->count() > 0){
            //     $unreadmsg_data = $event->receivers[0]->unread_count;
            // }
        @endphp
        <li class="sync-event {{ $key == 0 ? 'active' : '' }} event-band" data-id="{{ $chat->id }}">
            <a data-toggle="tab" href="#event{{ $chat->id }}" class="event">
                <img class="event-icons profile-image" height="50" src="{{  Helper::images(config('constant.profile_url')).'default.png' }}">
                <span class="event-name">{{ ucwords(($chat->members[0]->user->name ?? "name")) }}</span>
            </a> 
            @if(isset($unreadmsg_data) && $unreadmsg_data > 0) 
                <div class="unread_count">{{ $unreadmsg_data }}</div>
            @endif
        </li>
    @endforeach
@else
    <li class="notfound"><div class="nofound-center">No Data Found</div></li>
@endif