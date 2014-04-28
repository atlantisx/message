<div class="controller box closable-chat-box open" ng-controller="composeController">
    <div class="box-content padded">
        <div class="fields">
            <div class="avatar"><img class="avatar-small" src="{{ Gravatar::src($user->email) }}" /></div>
            <ul>
                <li><b><a href="#">{{ $user->first_name }}</a></b></li>
                <li class="note">{{ $user_role->description }}</li>
            </ul>
        </div>
        <div class="chat-message-box">
            <textarea as-ui-editor-wysihtml name="comment" ng-model="message.params.body" rows="3"></textarea>
        </div>
        <div class="clearfix actions">
            <span class="note"></span>
            <div class="pull-right faded-toolbar">
                <a id="btn-action-update" class="btn btn-blue ladda-button" ng-click="detailUpdate()" ng-disabled="true" as-ui-button as-ui-progress="ladda"><span class="ladda-label">{{ trans('message::message.title.reply') }}</span></a>
            </div>
        </div>
    </div>
</div>


@section('javascript')
    @parent
    <script language="JavaScript" type="text/javascript">
        var urlAPIMessage = appBase + 'api/v1/messages';
        var urlAPIDetailStatus = appBase + 'api/v1/details/status';

        function composeController($scope, Rest){
            $scope.message = Rest.new(urlAPIDetailStatus,{
                sender_id: '{{ $user->id }}',
                receiver_id: '{{ $message->user->id }}',
                subject: '{{ $message->conversation->subject }}'
            });

            $scope.sendMessage = function(){
                $scope.message.params.notify = true;
                $scope.message.store(function(response){
                    if( response._status.type = 'success' ) location.reload(true);
                });
            }
        }
    </script>
@stop