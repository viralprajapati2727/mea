<div class="tab-content user-messages" id="v-pills-tabContent">
    @if(!empty($currentChats) && $currentChats->count())
        @foreach ($currentChats as $key => $group)
            @foreach ($group->members as $member)
                @if($member->user_id != $currentUser->id)
                    @php
                        $activated_section = "";
                        if((!empty($user) && $user->id == $member->user->id) || (!empty($activated_group) && $activated_group == $group->id)){
                            $activated_section = "active";
                        }

                        if(empty($user) && empty($activated_group)){
                            if($key == 0){
                                $activated_section = "active";
                            }
                        }

                    @endphp

                    <div class="tab-pane fade show {{ $activated_section }}" id="chat{{ $group->id }}" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="message-list">
                            
                        </div>
                        <div class="group-options">
                            <input type="hidden" name="page" class="page" value="1">
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
    @endif
</div>