@extends('admin.master')

@section('title', __("lang.widget-chats"))

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<link href="/css/emojionearea.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<script src="/js/emojionearea.js"></script>
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
		.show_whiteboard { cursor: pointer; }
		.show_whiteboard:hover { opacity: 0.7; }
		.file-ul { list-style: none; padding: 0; margin: 0; }
		.file-ul li { padding: 3px 0; }
		.file-ul li a { text-decoration: none; }
		.file-ul li a:hover { text-decoration: underline; }
		.wc-action-bar { display: flex; align-items: center; gap: 6px; margin-bottom: 8px; }
		.wc-action-bar .input-group-text { cursor: pointer; border-radius: 8px; }
		/* Whiteboard modal tools */
		.wc-wb-tools { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; align-items: center; }
		.wc-wb-tools .gadgets { padding: 5px 10px; border-radius: 6px; border: 1px solid #ddd; background: #fff; cursor: pointer; }
		.wc-wb-tools .gadgets:hover { background: #f0f0f0; }
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
				@if($msg->type == 1)
					{{-- Whiteboard message --}}
					<div class="float-{{ $msg->sender == 'agent' ? 'right' : 'left' }} show_whiteboard" data-msg="{{ base64_encode($msg->raw_message ?? $msg->message) }}">
						<i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
					</div>
				@elseif($msg->type == 2)
					{{-- File attachment message --}}
					@php
						$files = [];
						try {
							$rawMsg = $msg->raw_message ?? $msg->message;
							$files = json_decode(html_entity_decode($rawMsg), true) ?: [];
						} catch (\Exception $e) {
							$files = [];
						}
					@endphp
					<div class="bubble">
						<ul class="file-ul">
						@foreach($files as $file)
							<li class="file">
								<a href="/messages/downattch/{{ $file['uuid'] ?? '' }}" target="_blank">
									<i class="fa fa-file fa-2" aria-hidden="true"></i>&nbsp;&nbsp;{{ $file['Name'] ?? 'Attachment' }}
								</a>
							</li>
						@endforeach
						</ul>
					</div>
				@else
					{{-- Regular text message --}}
					<div class="bubble">{{ $msg->message }}</div>
				@endif
				<div class="meta timestamp">{{ $msg->created_at }}</div>
			</div>
			@endforeach
		</div>
		@if($activeSession->status == 'open')
		<div class="wc-input-area">
			<div class="wc-action-bar">
				<div class="input-group-append" id="wc-send-msg-btn">
					<span class="input-group-text"><i class="fa fa-commenting-o"></i></span>
				</div>
				<div class="input-group-append" data-toggle="modal" data-target="#wc-whiteboard">
					<span class="input-group-text"><i class="fa fa-pencil"></i></span>
				</div>
				<div class="input-group-append">
					<input id="wc-selectedFile" type="file" name="name" multiple="multiple" style="display: none;">
					<span class="input-group-text" onclick="document.getElementById('wc-selectedFile').click();"><i class="fa fa-paperclip"></i></span>
				</div>
			</div>
			<div class="input-group">
				<textarea id="wc-reply" class="form-control" style="border-radius: 20px;"></textarea>
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

{{-- Whiteboard Modal --}}
<div class="modal fade" id="wc-whiteboard" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" id="wc-whiteboard-model">
      <div class="modal-header">{{ __("lang.whiteboard") }}</div>
      <div class="modal-body">
          <canvas id="wcCanvas" width="600" height="400" style="border:1px solid #d3d3d3;"></canvas>
            <div class="wc-wb-tools">
                <input class="gadgets wc-gadget" type="button" value="Pencil" data="Pencil" />
                <input class="gadgets wc-gadget" type="button" value="Line" data="Line" />
                <input class="gadgets wc-gadget" type="button" value="Circle" data="Circle" />
                <input class="gadgets wc-gadget" type="button" value="Rectangle" data="Rectangle" />
                <input class="gadgets wc-gadget" type="button" value="ClearRect" data="clearRect" />
                <input class="gadgets wc-gadget" type="button" value="Ellipse" data="ellipse" />
                <input id="wc-filled" type="checkbox" />
                <input type="button" value="Clear" class="gadgets" id="wc-clear-btn" />
                <input id="wc-color" type="color" value="#000002" style="width: 40px; height: 32px;" />
                <input id="wc-border" type="text" value="1" style="width: 50px;" class="form-control form-control-sm" />
                <input id="wc-download" type="button" value="Download" class="gadgets" />
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("lang.close") }}</button>
        <button type="button" class="btn btn-primary" id="wc-WhiteBoardSend" data-dismiss="modal">{{ __("lang.send") }}</button>
      </div>
    </div>
  </div>
</div>

{{-- View Whiteboard Modal (read-only) --}}
<div class="modal fade" id="wc-view-whiteboard" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">Whiteboard</div>
      <div class="modal-body">
          <canvas id="wcViewCanvas" width="600" height="400" style="border:1px solid #d3d3d3;"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("lang.close") }}</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

@endsection

@section('javascript')
<script type="text/javascript">

// ============================================================
// Whiteboard drawing engine (same as main chat)
// ============================================================
var wc_shape, wc_fill, wc_points=[], wc_x=0, wc_y=0, wc_color="#000000", wc_border=1;
var wcCanvas = document.getElementById("wcCanvas");
var wc_ctx = wcCanvas ? wcCanvas.getContext("2d") : null;
var wc_go = false;
var wc_WhiteBoard = false;

function wc_getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    wc_x = evt.clientX - rect.left;
    wc_y = evt.clientY - rect.top;
}

function wc_F_Points() {
    if (wc_shape != undefined) {
        wc_points[wc_points.length] = [wc_shape, wc_color, wc_border, [[wc_x, wc_y]]];
    }
}

function wc_T_Points() {
    if (wc_shape === "Pencil" || wc_shape === "bezier") {
        var tmp = wc_points[wc_points.length - 1][3].length;
        wc_points[wc_points.length - 1][1] = wc_color;
        wc_points[wc_points.length - 1][2] = wc_border;
        wc_points[wc_points.length - 1][3][tmp] = [wc_x, wc_y];
    } else if (wc_shape != undefined) {
        wc_points[wc_points.length - 1][1] = wc_color;
        wc_points[wc_points.length - 1][2] = wc_border;
        wc_points[wc_points.length - 1][3][0][2] = wc_x;
        wc_points[wc_points.length - 1][3][0][3] = wc_y;
        wc_points[wc_points.length - 1][4] = wc_fill;
    }
}

function wc_draw(e) {
    if (!wc_ctx) return;
    wc_ctx.beginPath();
    wc_ctx.lineWidth = e[2];
    wc_ctx.fillStyle = e[1];
    wc_ctx.strokeStyle = e[1];
    if (e[0] === "Line") {
        wc_ctx.moveTo(e[3][0][0], e[3][0][1]);
        wc_ctx.lineTo(e[3][0][2], e[3][0][3]);
    }
    if (e[0] === "Circle") {
        wc_ctx.arc(e[3][0][0], e[3][0][1], Math.sqrt((e[3][0][2] - e[3][0][0]) * (e[3][0][2] - e[3][0][0]) + (e[3][0][3] - e[3][0][1]) * (e[3][0][3] - e[3][0][1])), 0, 2 * Math.PI);
    }
    if (e[0] === "Rectangle") {
        if (e[4]) {
            wc_ctx.fillRect(e[3][0][0], e[3][0][1], e[3][0][2] - e[3][0][0], e[3][0][3] - e[3][0][1]);
        } else {
            wc_ctx.strokeRect(e[3][0][0], e[3][0][1], e[3][0][2] - e[3][0][0], e[3][0][3] - e[3][0][1]);
        }
    }
    if (e[0] === "clearRect") {
        wc_ctx.clearRect(e[3][0][0], e[3][0][1], e[3][0][2] - e[3][0][0], e[3][0][3] - e[3][0][1]);
    }
    if (e[0] === "ellipse" && wc_ctx.ellipse) {
        wc_ctx.ellipse(e[3][0][0], e[3][0][1], Math.abs(e[3][0][2] - e[3][0][0]), Math.abs(e[3][0][3] - e[3][0][1]), 0, 0, 2 * Math.PI, false);
    }
    if (e[0] === "Pencil") {
        wc_ctx.moveTo(e[3][0][0], e[3][0][1]);
        e[3].forEach(function(p) { wc_ctx.lineTo(p[0], p[1]); });
    }
    if (e[4]) wc_ctx.fill();
    wc_ctx.stroke();
}

function wc_Clear() {
    wc_points = [];
    if (wc_ctx) wc_ctx.clearRect(0, 0, wcCanvas.width, wcCanvas.height);
}

if (wcCanvas) {
    wcCanvas.addEventListener("mousedown", function(e) {
        wc_getMousePos(wcCanvas, e);
        wc_F_Points();
        wc_go = true;
    }, false);

    wcCanvas.addEventListener("mousemove", function(e) {
        wc_getMousePos(wcCanvas, e);
        if (wc_go) {
            wc_ctx.clearRect(0, 0, wcCanvas.width, wcCanvas.height);
            wc_points.forEach(wc_draw);
            wc_T_Points();
        }
    }, false);

    wcCanvas.addEventListener("mouseup", function(e) {
        wc_T_Points();
        wc_go = false;
        wc_points.forEach(wc_draw);
    }, false);
}

// Tool buttons
$(".wc-gadget").on("click", function() {
    wc_shape = $(this).attr("data");
});

$("#wc-clear-btn").on("click", function() {
    wc_Clear();
});

$("#wc-color").on("change", function() {
    wc_color = this.value;
});

$("#wc-border").on("change", function() {
    wc_border = this.value;
});

$("#wc-filled").on("click", function() {
    wc_fill = this.checked;
});

$("#wc-download").on("click", function() {
    var link = document.createElement('a');
    link.href = wcCanvas.toDataURL();
    link.download = 'KChat-whiteboard.png';
    link.click();
});

// View whiteboard from message
$("#wc-messages").on("click", ".show_whiteboard", function() {
    var viewCanvas = document.getElementById("wcViewCanvas");
    var viewCtx = viewCanvas ? viewCanvas.getContext("2d") : null;
    if (!viewCtx) return;
    viewCtx.clearRect(0, 0, viewCanvas.width, viewCanvas.height);

    try {
        var rawData = $(this).attr("data-msg");
        var decoded = $("<div/>").html(atob(rawData)).text();
        var pts = JSON.parse(decoded);
        pts.forEach(function(p) {
            // Reuse the wc_draw function
            viewCtx.beginPath();
            viewCtx.lineWidth = p[2];
            viewCtx.fillStyle = p[1];
            viewCtx.strokeStyle = p[1];
            if (p[0] === "Line") { viewCtx.moveTo(p[3][0][0], p[3][0][1]); viewCtx.lineTo(p[3][0][2], p[3][0][3]); }
            if (p[0] === "Circle") { viewCtx.arc(p[3][0][0], p[3][0][1], Math.sqrt((p[3][0][2]-p[3][0][0])*(p[3][0][2]-p[3][0][0])+(p[3][0][3]-p[3][0][1])*(p[3][0][3]-p[3][0][1])), 0, 2*Math.PI); }
            if (p[0] === "Rectangle") { if(p[4]) viewCtx.fillRect(p[3][0][0],p[3][0][1],p[3][0][2]-p[3][0][0],p[3][0][3]-p[3][0][1]); else viewCtx.strokeRect(p[3][0][0],p[3][0][1],p[3][0][2]-p[3][0][0],p[3][0][3]-p[3][0][1]); }
            if (p[0] === "clearRect") { viewCtx.clearRect(p[3][0][0],p[3][0][1],p[3][0][2]-p[3][0][0],p[3][0][3]-p[3][0][1]); }
            if (p[0] === "ellipse" && viewCtx.ellipse) { viewCtx.ellipse(p[3][0][0],p[3][0][1],Math.abs(p[3][0][2]-p[3][0][0]),Math.abs(p[3][0][3]-p[3][0][1]),0,0,2*Math.PI,false); }
            if (p[0] === "Pencil") { viewCtx.moveTo(p[3][0][0],p[3][0][1]); p[3].forEach(function(c){viewCtx.lineTo(c[0],c[1]);}); }
            if (p[4]) viewCtx.fill();
            viewCtx.stroke();
        });
    } catch(e) {
        console.error("Failed to parse whiteboard data", e);
    }

    $('#wc-view-whiteboard').modal('show');
});


@if($activeSession && $activeSession->status == 'open')

var sessionId = {{ $activeSession->id }};
var lastMsgId = {{ count($messages) > 0 ? end($messages)->id : 0 }};
var wc_emojioneAreaElm = null;

// Initialize emojionearea on the textarea
$(document).ready(function() {
    $("#wc-reply").emojioneArea({
        pickerPosition: "top",
        tonesStyle: "bullet",
        events: {
            keypress: function (editor, event) {
                wc_emojioneAreaElm = this;
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    sendAgentReply();
                }
            }
        }
    });
});

// Scroll to bottom
function scrollToBottom(){
	var el = document.getElementById('wc-messages');
	if(el) el.scrollTop = el.scrollHeight;
}
scrollToBottom();

// Send reply
function sendAgentReply(isWhiteboard){
	var msg = '';

    if (isWhiteboard) {
        msg = JSON.stringify(wc_points);
        wc_points = [];
        wc_Clear();
    } else if (wc_emojioneAreaElm != null) {
        msg = wc_emojioneAreaElm.getText();
        wc_emojioneAreaElm.setText('');
    } else {
        msg = $('#wc-reply').val().trim();
    }

	if(!msg) return;
	
	$('#wc-reply').val('');
	
	// Optimistically add message to UI
	if (isWhiteboard) {
		$('#wc-messages').append(
			'<div class="wc-msg agent"><div class="float-right show_whiteboard" data-msg="' + btoa(msg) + '"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></div><div class="meta">Just now</div></div>'
		);
	} else {
		$('#wc-messages').append(
			'<div class="wc-msg agent"><div class="bubble">' + $('<span>').text(msg).html() + '</div><div class="meta">Just now</div></div>'
		);
	}
	scrollToBottom();
	
	var postData = {
		_token: $('meta[name="csrf_token"]').attr('content'),
		session_id: sessionId,
		message: msg
	};

    if (isWhiteboard) {
        postData.whiteboard = true;
    }

	$.ajax({
		type: "POST",
		url: "/widget-chats/messages",
		data: postData,
		success: function(result){
			result = $.parseJSON(result);
			if(result.error){
				kchat_alert(result.error, function(){});
			}
		}
	});
}

// Send button click
$("#wc-send-msg-btn").on("click", function() {
    sendAgentReply();
});

// Whiteboard send
$("#wc-WhiteBoardSend").on("click", function() {
    wc_WhiteBoard = true;
    sendAgentReply(true);
    wc_WhiteBoard = false;
});

// File attachment
$("#wc-selectedFile").change(function(){
    let Data = new FormData();
    Data.append('_token', $('meta[name="csrf_token"]').attr('content'));
    Data.append('session_id', sessionId);

    var files = $('#wc-selectedFile')[0].files;
    for (let i = 0; i < files.length; i++) {
        Data.append('files[]', files[i]);
    }

    $.ajax({
        type: "POST",
        url: '/widget-chats/upload-file',
        data: Data,
        processData: false,
        contentType: false,
        success: function(result){
            result = $.parseJSON(result);
            if(result.error != undefined){
                kchat_alert(result.error, function(){});
            }
        },
        error: function(result){
        }
    });
});

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

                        if (m.type == 1) {
                            // Whiteboard message
                            var rawMsg = m.raw_message || m.message;
                            var encodedMsg = btoa($("<div/>").html(rawMsg).text());
                            $('#wc-messages').append(
                                '<div class="wc-msg ' + m.sender + '"><div class="float-' + (m.sender == 'agent' ? 'right' : 'left') + ' show_whiteboard" data-msg="' + encodedMsg + '"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></div><div class="meta">' + getRelativeTime(m.created_at) + '</div></div>'
                            );
                        } else if (m.type == 2) {
                            // File attachment message
                            var rawMsg2 = m.raw_message || m.message;
                            var files = [];
                            try { files = JSON.parse($("<div/>").html(rawMsg2).text()); } catch(e) { files = []; }
                            var fileHtml = '<ul class="file-ul">';
                            for (var i = 0; i < files.length; i++) {
                                fileHtml += '<li class="file"><a href="/messages/downattch/' + files[i].uuid + '" target="_blank"><i class="fa fa-file fa-2" aria-hidden="true"></i>&nbsp;&nbsp;' + (files[i].Name || 'Attachment') + '</a></li>';
                            }
                            fileHtml += '</ul>';
                            $('#wc-messages').append(
                                '<div class="wc-msg ' + m.sender + '"><div class="bubble">' + fileHtml + '</div><div class="meta">' + getRelativeTime(m.created_at) + '</div></div>'
                            );
                        } else {
                            // Regular text message
                            $('#wc-messages').append(
                                '<div class="wc-msg ' + m.sender + '"><div class="bubble">' + $('<span>').text(m.message).html() + '</div><div class="meta">' + getRelativeTime(m.created_at) + '</div></div>'
                            );
                        }
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
