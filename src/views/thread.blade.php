@extends('themes/default::layouts.fluid')
@section('stylesheet')
    @parent
    <style>
        .chat-box.timeline + .closable-chat-box {
            margin-left: 0;
        }
    </style>
@stop

@section('base')
    <div class="main-content">
        <div class="area-top clearfix">
            <div class="pull-left header">
                <h3 class="title">
                    <i class="fa fa-envelope-o"></i> Message Thread
                </h3>
                <h5><span>Received : {{ $conversation->created_at->toDayDateTimeString() }}</span></h5>
            </div>
        </div>

        <div class="container" ng-controller="messageControl">
            @include('core::partials.error')
            <div class="row">
                <div class="col-md-8">
                    @include('message::partials.messages.paper')
                    @include('message::partials.compose')
                </div>
                <div class="col-md-4">
                    <div class="panel panel-profile">
                        <div class="panel-heading">
                            Message Thread Info
                        </div>
                        <div class="list-justified-container" style="padding:0;">
                            <ul class="list-justified text-center" style="margin: 10px 0">
                                <li>
                                    <p class="size-h3">{{ $conversation->participants()->count() }}</p>
                                    <p class="text-muted">Participants</p>
                                </li>
                                <li>
                                    <p class="size-h3">{{ $conversation->messages()->count() }}</p>
                                    <p class="text-muted">Message in Thread</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    @parent
    <script>
        var user_id = '{{ $user->id }}';

        function messageControl($scope, $resource){
        }
    </script>
@stop