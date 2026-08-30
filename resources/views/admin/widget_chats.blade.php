@extends('admin.master')

@section('title', __("lang.widget-chats"))

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<style>
		.wc-sidebar { overflow-y: auto; border-right: 1px solid #dee2e6; }
		.wc-session { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.15s; }
		.wc-session:hover { background: #f8f9fa; }
		.wc-session.active { background: #e8f0fe; border-left: 3px solid #007bff; }
		.wc-session .visitor-name { font-weight: 600; font-size: 14px; }
		.wc-session .last-msg { font-size: 12px; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
		.wc-session .session-status { font-size: 11px; }
		.wc-session .session-status.open { color: #28a745; }
		.wc-session .session-status.closed { color: #dc3545; }
		.wc-chat-area { display: flex; flex-direction: column; height: 100%; }
		.wc-messages { flex: 1; overflow-y: auto; padding: 15px; background: #fafbfc; }
		.wc-msg { margin-bottom: 10px; max-width: 75%; }
		.wc-msg.visitor { margin-right: auto; }
		.wc-msg.agent { margin-left: auto; text-align: right; }
		.wc-msg .bubble { display: inline-block; padding: 8px 14px; border-radius: 16px; font-size: 14px; line-height: 1.4; }
		.wc-msg.visitor .bubble { background: #e9ecef; color: #333; border-bottom-left-radius: 4px; }
		.wc-msg.agent .bubble { background: #007bff; color: #fff; border-bottom-right-radius: 4px; }
		.wc-msg .meta { font-size: 11px; color: #999; margin-top: 2px; }
		.wc-input-area { padding: 12px 15px; border-top: 1px solid #dee2e6; background: #fff; }
		.wc-input-area .form-control { border-radius: 20px; }
		.wc-empty { display: flex; align-items: center; justify-content: center; height: 100%; color: #aaa; }
		.wc-header { padding: 12px 15px; border-bottom: 1px solid #dee2e6; background: #fff; }
		.wc-header h6 { margin: 0; }
	</style>
@endsection

@section('body')
<div class="col-md-3 pt-0 pb-0 bg-white height10 wc-sidebar px-0">
	<div class="p-3 border-bottom">
		<strong><i class="fa fa-headphones mr-1"></i> {{ __("lang.widget-chats") }}</strong>
	</div>
	@if(count($sessions) == 0)
		<div class="p-3 text-center text-muted small">No widget chats yet.</div>
	@else
		@foreach($sessions as $session)
		<a href="/widget-chats?session={{ $session->id }}" style="text-decoration:none; color:inherit;">
			<div class="wc-session {{ ($activeSession && $activeSession->id == $session->id) ? 'active' : '' }}">
				<div class="d-flex justify-content-between align-items-center">
					<span class="visitor-name">
						<i class="fa fa-user-circle mr-1"></i>
						{{ $session->visitor_name ?? 'Visitor' }}
					</span>
					<span class="session-status {{ $session->status }}">● {{ ucfirst($session->status) }}</span>
				</div>
				<div class="last-msg mt-1">
					@if($session->last_message)
						<small><strong>{{ $session->last_sender == 'agent' ? 'You' : 'Visitor' }}:</strong> {{ Str::limit($session->last_message, 40) }}</small>
					@else
						<small class="text-muted">No messages yet</small>
					@endif
				</div>
				<div class="mt-1">
					<small class="text-muted timestamp">{{ $session->created_at }}</small>
					<small class="badge badge-light ml-1">{{ $session->widget_title }}</small>
				</div>
			</div>
		</a>
		@endforeach
	@endif
</div>

<div id="wc-chat" class="col-md-7 bg-white height10 px-0 py-0">
@if($activeSession)
	<div class="wc-chat-area">
		<div class="wc-header d-flex justify-content-between align-items-center">
			<div>
				<h6><i class="fa fa-user-circle mr-1"></i> {{ $activeSession->visitor_name ?? 'Visitor' }}</h6>
				<small class="text-muted">{{ $activeSession->widget_title }} · Session #{{ $activeSession->id }}</small>
			</div>
			<div>
				@if($activeSession->status == 'open')
				<button class="btn btn-sm btn-outline-danger" onclick="closeWidgetSession({{ $activeSession->id }})">
					<i class="fa fa-times mr-1"></i> Close Session
				</button>
				@else
				<span class="badge badge-danger">Closed</span>
				@endif
			</div>
		</div>
		<div class="wc-messages" id="wc-messages">
			@foreach($messages as $msg)
			<div class="wc-msg {{ $msg->sender }}">
				<div class="bubble">{{ htmlentities($msg->message) }}</div>
				<div class="meta timestamp">{{ $msg->created_at }}</div>
			</div>
			@endforeach
		</div>
		@if($activeSession->status == 'open')
		<div class="wc-input-area">
			<div class="input-group">
				<input type="text" class="form-control" id="wc-reply" placeholder="Type your reply..." />
				<div class="input-group-append">
					<button class="btn btn-primary" id="wc-send-btn" onclick="sendAgentReply()">
						<i class="fa fa-paper-plane"></i>
					</button>
				</div>
			</div>
		</div>
		@endif
	</div>
@else
	<div class="wc-empty">
		<div class="text-center">
			<i class="fa fa-headphones" style="font-size: 48px; color: #ddd;"></i>
			<p class="mt-3">Select a chat session from the left panel</p>
		</div>
	</div>
@endif
</div>

@endsection

@section('script')

@endsection

@section('javascript')
<script type="text/javascript">

@if($activeSession && $activeSession->status == 'open')

var sessionId = {{ $activeSession->id }};
var lastMsgId = {{ count($messages) > 0 ? end($messages)->id : 0 }};

// Scroll to bottom
function scrollToBottom(){
	var el = document.getElementById('wc-messages');
	if(el) el.scrollTop = el.scrollHeight;
}
scrollToBottom();

// Send reply
function sendAgentReply(){
	var msg = $('#wc-reply').val().trim();
	if(!msg) return;
	
	$('#wc-reply').val('');
	
	// Optimistically add message to UI
	$('#wc-messages').append(
		'<div class="wc-msg agent"><div class="bubble">' + $('<span>').text(msg).html() + '</div><div class="meta">Just now</div></div>'
	);
	scrollToBottom();
	
	$.ajax({
		type: "POST",
		url: "/widget-chats/messages",
		data: {
			_token: $('meta[name="csrf_token"]').attr('content'),
			session_id: sessionId,
			message: msg
		},
		success: function(result){
			result = $.parseJSON(result);
			if(result.error){
				kchat_alert(result.error, function(){});
			}
		}
	});
}

// Enter key to send
$('#wc-reply').keypress(function(e){
	if(e.which == 13){
		sendAgentReply();
		e.preventDefault();
	}
});

// Poll for new messages
function pollMessages(){
	$.ajax({
		type: "POST",
		url: "/widget-chats/messages",
		data: {
			_token: $('meta[name="csrf_token"]').attr('content'),
			session_id: sessionId,
			after_id: lastMsgId
		},
		success: function(result){
			result = $.parseJSON(result);
			if(result.messages && result.messages.length > 0){
				result.messages.forEach(function(m){
					if(m.id > lastMsgId){
						lastMsgId = m.id;
						$('#wc-messages').append(
							'<div class="wc-msg ' + m.sender + '"><div class="bubble">' + $('<span>').text(m.message).html() + '</div><div class="meta">' + getRelativeTime(m.created_at) + '</div></div>'
						);
					}
				});
				scrollToBottom();
			}
			if(result.session_status == 'closed'){
				$('.wc-input-area').html('<div class="text-center text-muted py-2">This session has been closed.</div>');
			}
		}
	});
}

setInterval(pollMessages, 3000);

@endif

function closeWidgetSession(id){
	kchat_alert("Are you sure you want to <strong>close</strong> this chat session?", function(){
		__post('/widget-chats/close', {'session_id': id});
	});
}

</script>
@endsection
