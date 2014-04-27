<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $receiver['full_name'] }},<br><br>
            {{ trans('message::message.text.notification_body') }}<br>
            <a href="{{ $message_link }}">{{ trans('message::message.title.read_message') }}</a>
            </p>
            <br>
            {{ trans('message::user.recovery_email_regards') }},
		</div>
	</body>
</html>