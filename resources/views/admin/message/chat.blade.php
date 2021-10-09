<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="message-list">
        <table class="table table-bordered mb-5" style="width: 100%;">
            <thead>
                <tr class="table-danger">
                    <th scope="col" style="text-align: left; width:40%;"></th>
                    <th scope="col" style="text-align: left; width:40%;"></th>
                    <th scope="col" style="text-align: left; width:20%;"></th>
                    {{-- <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">DOB</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $key => $message)
                @php
                    $class = "left";
                    $text_left = $text_right = "";
                    if($key%2 != 0){
                        $class = "right";
                        $text_right = $message->text;
                    } else {
                        $text_left = $message->text;
                    }
                @endphp
                <tr class="{{ $class }}">
                    <td style="width:40%;">{!! $text_left ?? '' !!}</td>
                    <td style="width:40%;">{!! $text_right ?? '' !!}</td>
                    <td style="width:20%;">{{ $message->created_at->format('d M, Y h:i:s A') ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>