<div class="box">
    <div class="box-header">
        <span class="title">{{ $conversations[0]->subject }}</span>
    </div>
    <div class="box-content hpadded">
        <ul class="chat-box">
            @if( isset($messages) )
                @foreach( $messages as $message )
                    @include('message::partials.message.badge')
                    <li class="divider"></li>
                    @if( end($message) )
                    <p class="note text-center">{{ $message->updated_when }}</p>
                    @endif
                @endforeach
            @else
                <p class="note text-center">No Message</p>
            @endif
        </ul>
    </div>
</div>