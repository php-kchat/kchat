
var kchat = new Object();
var chat = new Object();
var debug = false;
 
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

function isset(v){
	if (typeof v !== 'undefined'){
		return true;
	}else{
		return false;
	}
}

function kchat_message(messages){
	
  data = "";
  for(i = 0;i < messages.length ; i++){
	  data += "<div class=\"msg-row\">"+
			"<div class=\"avatar\"></div>"+
			"<div class=\"message"+messages[i].align+"\">"+
			  "<span class=\"user-label\"><a href=\"#\" style=\"color: #6D84B4;\">"+messages[i].username.ucfirst()+"</a> <span class=\"msg-time\">"+messages[i].sent_on+"</span></span><br/>"+messages[i].message+""+
			"</div>"+
		  "</div>";
  }
	
	$( "#messages" ).append(data);
	$('#messages_panel').scrollTop($('#messages')[0].scrollHeight);
}

function kchat_online(online){
	
  data = "";
  //alert(messages);
  for(i = 0;i < online.length ; i++){
	  data += ""+
		  "<tr class=\"msg-row-container\" id=\"tr"+online[i].id+"\" >"+
			"<td>"+
			  "<div class=\"msg-row\">"+
				"<div class=\"avatar\"></div>"+
				"<div class=\"message\">"+
				  "<span class=\"user-label\"><a href=\"#\" style=\"color:#6D84B4;\">"+online[i].username.ucfirst()+"</a> <span class=\"msg-online\">●</span></span>"+
				"</div>"+
			  "</div>"+
			"</td>"+
		  "</tr>";
		  kchat.chats_ontop = online[i].id;
  }
	$("#online" ).html(data);
}

function kchat_chat(chat){
	
  data = "";
  for(i = 0;i < chat.length ; i++){
		data = ""+
		"<tr class=\"msg-row-container\" id=\"tr"+chat[i].id+"\" >"+
		"<td>"+
		  "<div class=\"msg-row\">"+
			"<div class=\"avatar\"></div>"+
			"<div class=\"message\">"+
			  "<span class=\"user-label\"><a href=\"#\" style=\"color: #6D84B4;\">"+chat[i].username.ucfirst()+"</a> <span  id=\"status"+chat[i].id+"\" >"+chat[i].status+"</span><span class=\"msg-time\">"+chat[i].sent_on+"</span></span><br/>"+chat[i].message+""+
			"</div>"+
		  "</div>"+
		"</td>"+
		"</tr>";
		kchat.chats_ontop = chat[i].id;
		$("#kchatchats" ).append(data);
		prepend("kchatchats",kchat.chats_ontop);
  }
}

function set_status(_status){
  for(i = 0;i < _status.length ; i++){
	  $("#status"+_status[i].id).html(_status[i].status);
  }
}

function kchat_json(json){
	chat = jQuery.parseJSON(json);
	if (isset(chat.online)){
		kchat_online(chat.online)
	}
	if (isset(chat.chats)){
		kchat_chat(chat.chats)
	}
	if (isset(chat.unread)){
		__set("unread",chat.unread);
	}
	if (isset(chat.message)){
		kchat_message(chat.message);
	}
	if (isset(chat.timestamp)){
		chat.timestamp = chat.timestamp;
	}
	if (isset(chat.msg_status)){
		set_status(chat.msg_status);
	}
}

function kchat_init(){
	$.post("{{url}}/index.php/ajax/chat",
	{
	  timestamp: chat.timestamp
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
	kchat_init();
	setInterval(function(){ kchat_init(); }, 3000);
});

/* kchat.Ready = (function(){
	  var tosend = new Object();
	  document.getElementById('kchat_textarea').onkeydown = function (evt) {
	  var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
	  if (keyCode == 13) {
			msg = document.getElementById('kchat_textarea').value;
			tosend.message = msg;
			tosend.username = 'qwerty';
			tosend.sent_on = '1482646286';
			tosend.align = 1;
			kchat_message(tosend);
			document.getElementById('kchat_textarea').value = "";
	  }
	  if (keyCode == 27) {
		  alert("Do Somethink on esc button");
	  } else {
		return true;
	  }
	};
}) */