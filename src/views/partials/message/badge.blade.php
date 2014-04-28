@if( isset($message) )
    <li class="arrow-box-left gray">
        <div class="avatar"><img src="{{ Gravatar::src( $message->user->email ) }}" class="avatar-small"></div>
        <div class="info">
            <span class="name">
                <strong>{{ $message->user->full_name }}</strong>
                <span class="badge badge-blue">{{ $message->user->roles[0]->name }}</span>
            </span>
            <span class="time" title="{{ $message->created_at->toDayDateTimeString() }}">{{ $message->created_when }}</span>
        </div>
        <div class="content">
            {{ $message->body }}
        </div>
    </li>
@endif