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
                    <i class="fa fa-envelope-o"></i> Open Message
                </h3>
                <h5><span>Received : {{ $message->created_at->toDayDateTimeString() }}</span></h5>
            </div>
        </div>

        <div class="container" ng-controller="messageControl">
            @include('core::partials.error')
            <div class="row">
                <div class="col-md-12">
                    <ul class="chat-box timeline">
                    @include('message::partials.message.paper')
                    </ul>
                    @include('message::partials.compose')
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