@if( isset($message) )
    <div class="paper">
        <div class="paper-header">
            <div class="info" style="background: none;">
                <div class="avatar" style="float:left; margin-top: 10px;"><img src="{{ Gravatar::src( $message->user->email ) }}" class="avatar-small"></div>
                <span class="name" style="margin-left:40px; float:none;">
                    <strong>{{ $message->user->full_name }}</strong>
                    <span class="label label-green">{{ $message->user->roles[0]->name }}</span>
                </span>
            </div>
        </div>
        <div class="paper-section" style="padding:0;">
            <div class="info">
                {{ $message->conversation->subject }}
                <span class="time" title="{{ $message->created_at->toDayDateTimeString() }}"><i class="icon-time"></i> {{ $message->created_when }}</span>
            </div>
        </div>
        <div class="paper-section">
            {{ $message->body }}
        </div>
    </div>
@endif