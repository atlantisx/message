@if( isset($conversation) )
    <div class="paper">
        <div class="paper-header">
            <div class="info" style="background: none;">
                <span class="time" title="{{ $conversation->created_at->toDayDateTimeString() }}"><i class="icon-time"></i> {{ $conversation->created_when }}</span>
                <span class="news-title">{{ $conversation->subject }}</span>
            </div>
        </div>
        <div class="paper-section" style="padding:0;">
            <div class="info">
                <span class="label label-info">#{{ $messages->first()->user->full_name }}</span>
                @foreach($conversation->participant_list as $participant)
                <span class="label label-info">#{{ $participant->first_name.' '.$participant->last_name }}</span>
                @endforeach
            </div>
        </div>
        <div class="paper-section">
            <ul class="chat-box">
            @foreach($messages as $message)
                @include('message::partials.message.general')
            @endforeach
            </ul>
        </div>
    </div>
@endif