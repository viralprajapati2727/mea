<div class="tab-content user-messages" id="v-pills-tabContent">
    @if(!empty($currentChats) && $currentChats->count())
        @foreach ($currentChats as $key => $group)
            <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}" id="chat{{ $group->id }}" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="message-list">
                    
                </div>
                <div class="group-options">
                    <input type="hidden" name="page" class="page" value="1">
                </div>
            </div>
        @endforeach
    @endif
</div>