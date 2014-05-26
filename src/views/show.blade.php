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
                    <i class="icon-envelope"></i> Open Message
                </h3>
                <h5>
                    <span></span>
                </h5>
            </div>
        </div>

        <div class="container" ng-controller="messageControl">
            <div class="row padded">
                @include('admin::partials.status')
            </div>
            <div class="row">
                <div class="col-md-8">
                    <ul class="chat-box timeline">
                    @include('message::partials.message.paper')
                    </ul>
                    @include('message::partials.compose')
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header">
                            <span class="title">{{ trans('message::message.title.conversations') }}</span>
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