@extends('admin::layouts.user')

@section('base')
    <div class="main-content">
        <div class="area-top clearfix">
            <div class="pull-left header">
                <h3 class="title">
                    <i class="icon-tasks"></i> {{ trans('advance.title.application_list') }}
                </h3>
                <h5>
                    <span>
                    @if( isset($details) )
                        {{ trans('advance.text.application_total') }}
                    @endif
                    </span>
                </h5>
            </div>
        </div>

        <div class="container" ng-controller="messageControl">
            <div class="row padded">
                @include('admin::partials.status')
            </div>
            <div class="row">
                <div class="col-md-5">
                    @include('message::partials.conversations')
                </div>
                <div class="col-md-7">
                    @include('message::partials.messages.header')
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
            $scope.messageRead = function(id){

            }
        }
    </script>
@stop