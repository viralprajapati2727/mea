@isset($messages)
@foreach ($messages as $key => $message)
{{-- right side  --}}

    @if($key%2 != 0)  
        <div class="user-content mb-3">
            <div class="logout-wrap d-flex align-items-center justify-content-end">
                <div class="block-content">
                    <p class="text-black m-0 px-3">
                        {{ $message->created_at->format('d M, Y h:i:s A') ?? '' }} </p>
                    <div class="chat-normal-content px-3 py-1 border radius-normal bg-primary position-relative mx-3">
                        <p class="m-0" style="word-break:normal">
                            {!! $message->text ?? '' !!}
                        </p>
                    </div>
                </div>
                <div class="mt-3 profile-image">
                    @php
                        $ProfileUrl = Helper::images(config('constant.profile_url'));
                        $img_url = (isset($message->user->logo) && $message->user->logo != '') ? $ProfileUrl . $message->user->logo : $ProfileUrl.'default.png';
                    @endphp
                    <img src="{{ $img_url ?? '' }}" alt="profile image" class="w-100">
                </div>
            </div>
        </div>
    @else
    
    {{-- left side --}}
        @php
        $ProfileUrl = Helper::images(config('constant.profile_url'));
        $img_url = (isset($message->user->logo) && $message->user->logo != '') ? $ProfileUrl . $message->user->logo : $ProfileUrl.'default.png';
        @endphp

        <div class="user-content mb-3">
            <div class="logout-wrap chat-image d-flex align-items-center">
                <div class="profile-image mt-3">
                    <img src="{{ $img_url }}" alt="profile image" class="w-100">
                </div>
                <div class="block-content">
                    <p class="text-black font-light m-0 px-3">
                        {{ $message->created_at->format('d M, Y h:i:s A') ?? '' }}</p>
                    <div class="chat-normal-content px-3 py-1 radius-normal bg-secondary position-relative mx-3 after-left">
                        <p class="text-black font-light m-0" style="word-break:normal">{!! $message->text ?? '' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@else
<h3 class="pt-4 text-center">No message found</h3>
@endisset