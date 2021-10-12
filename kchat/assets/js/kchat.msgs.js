
var kchat = new Object();
var chat = new Object();
kchat.typing = 0;
kchat.scroll = 0;
kchat.msgoffset = 0;
kchat.msg = 30;
kchat.offset = 'none';
kchat.notify = false;
kchat.rqt_graph = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var req = {};
req.premsg = false;

Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

function isset(v){
	if (typeof v !== 'undefined'){
		return true;
	}else{
		return false;
	}
}

function prepend(table,id){
	var tbl = $('#'+table);
	tbl.prepend(tbl.find('#tr' + id));
}

function drop_row(table,no){
	document.getElementById(table).deleteRow(no);
}

String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

function __set(id,html){
	$("#"+id).html(html);
}

function kchat_message(messages){
	if(!document.getElementById("messages")){
	  return false;
	}
	data = "";
	info = "";
	play = false;
	my_notification = 0;
	for(i = 0;i < messages.length ; i++){
	  data = "<div class=\"msg-row\">"+
			"<div class=\"avatar\"></div>"+
			"<div class=\"message"+messages[i].align+"\">"+
			  "<span class=\"user-label\"><a href=\"#\" style=\"color: #6D84B4;\">"+messages[i].username.ucfirst()+"</a> <span class=\"msg-time\">"+messages[i].sent_on+"</span></span><br/>"+messages[i].message+""+
			"</div>"+
		  "</div>";
		info += messages[i].id+" ";
		kchat.msgoffset = messages[i].id;
		if(messages[i].dir == 'u2b'){
			//u2b direction of scroll up to bottum
			$( "#messages" ).append(data);
			$('#msgscrl').scrollTop($('#messages')[0].scrollHeight);
			play = true;
		}else if(messages[i].dir == 'b2u'){
			//b2u direction of scroll bottum to up
			$( "#messages" ).prepend(data);
		}
		data = "";
		if(!messages[i].align){
			my_notification++;
		}
	}
	if(kchat.notify){
		if(play){
			playsound();
			if(my_notification !== 0){
				notify(messages.length+" new messages");
			}
		}
	}
	$( "emojionearea-editor" ).focus();
}

function playsound(){
	var audio = new Audio();
	audio.src = kurl + '/kchat/assets/audio/audio2.mp3';
	audio.play();
}

function kchat_online(online){
	if(!document.getElementById("online")){
	  return false;
	}
	data = "";
	//alert(messages);
	for(i = 0;i < online.length ; i++){
		if(document.getElementById("online_" + online[i].id)){
		  return false;
		}
		data += ""+
		"<div class=\"media\" id=\"online_" + online[i].id + "\" >"+
			"<!--div class=\"pull-left\">"+
				"<img style=\"height:64px\" src=\"128.jpg\" class=\"media-photo\"/>"+
			"</div-->"+
			"<div class=\"online-body\">"+
					"<span class=\"pull-right bullet\">&#8226;</span>"+
					"<p class=\"title\" style=\"font-size: 12px;\" >"+
					"<b><a href=\"#\" style=\"text-decoration: none;color:#000\" >"+online[i].username.ucfirst()+"</a></b>"+
					"</p>"+
			"</div>"+
		"</div>";
	}
	$("#online" ).prepend(data);
}

function kchat_offline(offline){
	//remove if exist
	for(i = 0;i < offline.length ; i++){
		if(document.getElementById("online_" + offline[i])){
			document.getElementById("online_" + offline[i]).remove();
		}
	}
}

function __kchat_chat(chat){
  if(!document.getElementById("kchatchats")){
	  return false;
  }
  data = "";
  for(i = 0;i < chat.length ; i++){
	    //remove if exist
		if(document.getElementById("chats"+chat[i].id)){
			document.getElementById("chats"+chat[i].id).remove();
		}
		//add to first child of div
		data = ""+
		"<div class=\"media\"  id=\"chats"+chat[i].id+"\" >"+
			"<div class=\"pull-left\">"+
				"<!--img style=\"height:64px\" src=\"128.jpg\" class=\"media-photo\"-->"+
			"</div>"+
			"<div class=\"msg-body\">"+
					"<span class=\"pull-right\" style=\"font-size: 10px;\" ><span  id=\"status"+chat[i].id+"\" >"+chat[i].status+"</span>&nbsp;&nbsp;"+chat[i].sent_on+"</span>"+
					"<p class=\"title\" style=\"font-size: 12px;\" >"+
					"<b><a href=\"" + purl + "/msgs/g/"+chat[i].url+"\" style=\"color: #6D84B4;\">"+chat[i].username+"</a></b>"+
					"</p>"+
					"<p class=\"summary\" style=\"font-size: 14px;\" >"+chat[i].message+"</p>"+
			"</div>"+
		"</div>";
		$("#kchatchats" ).prepend(data);
  }
}

function kchat_chat(chat){
  if(!document.getElementById("kchatchats")){
	  return false;
  }
  data = "";
  for(i = 0;i < chat.length ; i++){
	    //remove if exist
		if(document.getElementById("chats"+chat[i].id)){
			document.getElementById("chats"+chat[i].id).remove();
		}
		//add to first child of div
		data = ""+
		"<div class=\"media\"  id=\"chats"+chat[i].id+"\" >"+
			"<div class=\"pull-left\">"+
				"<!--img style=\"height:64px\" src=\"128.jpg\" class=\"media-photo\"-->"+
			"</div>"+
			"<div class=\"msg-body\">"+
					"<span class=\"pull-right\" style=\"font-size: 10px;\" ><span  id=\"status"+chat[i].id+"\" >"+chat[i].status+"</span>&nbsp;&nbsp;"+chat[i].sent_on+"</span>"+
					"<p class=\"title\" style=\"font-size: 12px;\" >"+
					"<b><a href=\""+ purl + "/msgs/g/"+chat[i].url+"\" style=\"color: #6D84B4;\">"+chat[i].username+"</a></b>"+
					"</p>"+
					"<p class=\"summary\" style=\"font-size: 14px;\" >"+chat[i].message+"</p>"+
			"</div>"+
		"</div>";
		$("#kchatchats" ).prepend(data);
  }
}

function set_status(_status){
  if(!document.getElementById("status"+_status[i].id)){
	  return false;
  }
  for(i = 0;i < _status.length ; i++){
	  $("#status"+_status[i].id).html(_status[i].status);
  }
}

function typing(typing){
  if(document.getElementById("typing")){
		$("#typing").html("<i><b>&nbsp;"+typing+"</b></i>");
  }
}

function kchat_json(json){
	try {
		chat = jQuery.parseJSON(json);
	}catch (e){
		alertify.log("<h3>Error Occurred</h3><pre>" + json + "</pre>");
	}
	if (typeof chat.online !== 'undefined'){
		kchat_online(chat.online)
	}
	if (typeof chat.offline !== 'undefined'){
		kchat_offline(chat.offline)
	}
	if (typeof chat.chats !== 'undefined'){
		kchat_chat(chat.chats)
	}
	if (typeof chat.unread !== 'undefined'){
		__set("unread",chat.unread);
	}
	if (typeof chat.message !== 'undefined'){
		kchat_message(chat.message);
	}
	if (typeof chat.timestamp !== 'undefined'){
		chat.timestamp = chat.timestamp;
	}
	if (typeof chat.msg_status !== 'undefined'){
		set_status(chat.msg_status);
	}
	if (typeof chat.error !== 'undefined'){
		alertify.alert(chat.error);
	}
	
	if (typeof chat.typing !== 'undefined'){
		typing(chat.typing);
	}else{
		if(document.getElementById("typing")){
			$("#typing").html("<i><b>&nbsp;</b></i>");
		}
	}
	
	if (typeof chat.rq_time !== 'undefined'){
		$("#rq_time").html(chat.rq_time);
		for(var i = 0;i < 15 ; i++){
			kchat.rqt_graph[i] = kchat.rqt_graph[(i+1)];
		}
		kchat.rqt_graph[15] = chat.rq_time * 1000;
        $('.dynamicsparkline').sparkline(kchat.rqt_graph);
	}
	if (typeof chat.qfired !== 'undefined'){
		$("#qfired").html(chat.qfired);
	}
	if (typeof chat.reqps !== 'undefined'){
		$("#reqps").html(chat.reqps);
	}
	if (typeof chat.offset !== 'undefined'){
		kchat.offset = chat.offset;
	}
}

function kchat_init(first){
	if(first == "true"){
		kchat.notify = false;
	}else{
		kchat.notify = true;
	}
	
	if(document.getElementById("kchatchats")){
	  req.kchatchats = true;
	}
	if(document.getElementById("online")){
	  req.online = true;
	}
	if(document.getElementById("messages")){
	  req.messages = true;
	}
	if(document.getElementById("typing")){
	  req.typing = true;
	}
	if(document.getElementById("unread")){
	  req.unread = true;
	}
	
	$.post(purl + "/ajax/chat/"+posturl,
	{
	  timestamp: chat.timestamp,
	  first_run: first,
	  offset:kchat.offset,
	  req:req,
	  token : token
	},
	function(data,status){
		if(status === "success"){
			if(data === 'refresh'){
				location.reload();
			}else{
				kchat_json(data);
			}
		}
	});
}

kchat.init = (function(){
	kchat_init('true');
	setInterval(function(){ kchat_init('false'); }, 3000);
});

kchat.Ready = (function(){

// to send a msg
$(".kchatemoji").emojioneArea({
  events: {
	//shortnames : true,
	//useSprite : true,
    keypress: function (editor, event) {
	  var keyCode = event ? (event.which ? event.which : event.keyCode) : event.keyCode;
	  
			// to change wtring status
			aa = new Date();
			if(kchat.typing !== aa.getSeconds()){
				kchat.typing = aa.getSeconds();
				$.post(purl + "/ajax/typing/"+posturl,
				{
					typing:'typing',
					token : token
				},function(data,status){
					
				});
			}
			// to send msg on enter
		  if (keyCode == 13) {
			  msg = $(".emojionearea-editor").html();
			  //==============================================================
				if(msg == ""){
					return false;
				}
				$.post(purl + "/ajax/chat/"+posturl,
				{
				  timestamp: chat.timestamp,
				  msg: toanchor(msg),
				  first_run: 'false',
				  token : token
				},
				function(data,status){
					if(status === "success"){
						if(data === 'refresh'){
							location.reload();
						}else{
							kchat.notify = false;
							kchat_json(data);
						}
					}
				});
			  $(".emojionearea-editor").html("");
			  //==============================================================
		  }
		  if (keyCode == 59) {
			  //alert("you entered a double column");
		  }
		  if (keyCode == 27) {
			  //alert("Do Somethink on esc button");
		  } else {
			return true;
		  }
	  }
  }
});

});

//load privious message on srcoll up
function _loadoldmsg() {
	if (typeof scrl !== 'undefined'){
		 if(scrl == 0){
			  aa = new Date();
			  // Delay for half second for every ajax during typing 
			  if(kchat.typing !== ((aa.getMilliseconds()-aa.getMilliseconds()%500)/500)){
				kchat.typing = ((aa.getMilliseconds()-aa.getMilliseconds()%500)/500);
				req.premsg = true;
				kchat_init('false'); //loadoldmsg();
				req.premsg = false;
			  }
		 }
	 }
}

$(document).ready(function() {
		$("#msgscrl").scroll(function(){
			 scrl = $("#msgscrl").scrollTop();
			 if(scrl <= kchat.scroll){
				//scrolling on top
			 }else{
				//scrolling on bottom
			 }
			 kchat.scroll = scrl;
		});
		if(document.getElementById("msgscrl")){
			document.getElementById("msgscrl").addEventListener("wheel", _loadoldmsg);
		}
});

function notify(msg){
	msg += "\n\nKChat";
	Push.create("KChat", {
		body: msg,
		icon: kurl + '/kchat/assets/images/lchat.png',
		timeout: 4000,
		onClick: function () {
			window.focus();
			this.close();
		}
	});
}

//this function is called from kchat.js
function notification(e){
	if(!document.getElementById("notification")){
	  return false;
	}
	html = "<li><a href=\"javascript:void(0);\" class=\"text-center\"><img src=\"" +  purl + "/kchat/assets/images/loading.gif\" /></a></li>";
	$("#set_notification").html(html);
	$.post(purl + "/ajax/notification",
	{
	  token : token
	},
	function(data,status){
		try {
			json = jQuery.parseJSON(data);
		}catch (e){
			alertify.log("<h3>Error Occurred</h3><pre>" + data + "</pre>");
		}
		$("#set_notification").html(html_notif(json));
	});
}

function html_notif(not){
	html = '';
	for(i = 0;i < not.length ; i++){
		html += "<li><a href=" + purl + '/' + not[i].url + "><span class=\"label label-warning\">" + not[i].time + "</span>" + not[i].notification + "</a></li>";
	}
	html += "<li class=\"divider\"></li>";
	html += "<li><a href=\"" +  purl + "/notif\" class=\"text-center\">View All</a></li>";
	return html;
}

function toanchor(text) {
    var exp = /(\s(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/igm;
    return text.replace(exp,"<a target='_BLANK' href='$1'>$1</a>"); 
}