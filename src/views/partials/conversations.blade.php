<div class="box">
    <div class="box-header">
        <ul class="nav nav-tabs nav-tabs-left">
            <li class="active"><a data-toggle="tab" href="#messages"><i class="icon-envelope"></i> <span>Makluman</span></a></li>
            <li><a data-toggle="tab" href="#broadcast"><i class="icon-comments-alt"></i> <span>Pengumuman</span></a></li>
        </ul>
    </div>
    <div class="box-content padded">
        <div class="tab-content">
            <div id="messages" class="tab-pane active">
                @if( !empty($conversations) )
                    @foreach( $conversations as $conversation )
                        <div class="col-fixed">
                            <div class="col-fixed-30"><img src="{{ Gravatar::src( $conversation->messages()->first()->user->email ) }}"></div>
                            <div class="content">
                                <div class="title"><a href="{{ url('message/thread/'.$conversation->id) }}" ng-click="messageRead({{$conversation->id}})">{{ $conversation->subject }}</a></div>
                                <div>
                                    <span class="label label-info">#{{ $conversation->messages()->first()->user->first_name }}</span>
                                </div>
                            </div>
                            <div class="col-fixed-30 time">
                                <span>{{ $conversation->updated_at->format('d') }}</span> {{ $conversation->updated_at->format('M') }}
                            </div>
                        </div>
                    @endforeach
                    @if( count($conversations) == 0 )
                        No Message
                    @endif
                @endif
            </div>
            <div id="broadcast" class="tab-pane">
                @if( !empty($broadcasts) )
                    @foreach( $broadcasts as $broadcast )
                        <div class="col-fixed">
                            <div class="col-fixed-30"><img src="{{ Gravatar::src( $broadcast->messages()->first()->user->email ) }}"></div>
                            <div class="content">
                                <div class="title"><a href="{{ url('message/thread/'.$broadcast->id) }}" ng-click="messageRead({{$broadcast->id}})">{{ $broadcast->subject }}</a></div>
                                <div>
                                    <span class="label label-info">#{{ $broadcast->messages()->first()->user->full_name }}</span>
                                </div>
                            </div>
                            <div class="col-fixed-30 time">
                                <span>{{ $broadcast->updated_at->format('d') }}</span> {{ $broadcast->updated_at->format('M') }}
                            </div>
                        </div>
                    @endforeach
                    @if( count($broadcasts) == 0 )
                        No Broadcast
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

