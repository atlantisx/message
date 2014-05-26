@extends('themes/default::layouts.fluid')

@section('base')
    <div class="main-content">
        <div class="area-top clearfix">
            <div class="pull-left header">
                <h3 class="title">
                    <i class="fa fa-envelope"></i> Senarai Makluman
                </h3>
                <h5>
                    <span>Last received : {{ $conversations->count() > 0 ? $conversations->last()->id : 'none' }}</span>
                </h5>
            </div>
        </div>

        <div class="container" ng-controller="messageControl">
            @include('core::partials.error')
            <div class="row">
                <div class="col-md-12">
                    @include('message::partials.conversations')
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