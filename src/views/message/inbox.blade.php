@extends('admin::layouts.user')

@section('stylesheet')
    @parent
    <style>
    </style>
@stop

@section('base')
    <div class="main-content">
        <div class="container" ng-controller="messageControl">

        </div>
    </div>
@stop

@section('javascript')
    @parent
    <script>
        var user_id = '{{ $user->id }}';

        function messageControl($scope, $resource){

        }

        $(document).ready(function() {

        });
    </script>
@stop