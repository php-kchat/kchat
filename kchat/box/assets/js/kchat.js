
kchat = new Object();

kchat.typing = 0;
kchat.scroll = 0;
kchat.offset = 'none';
kchat.msgoffset = 0;
kchat.msg = 30;
kchat.premsg = false;
kchat.url = '';
kchat.key = '';

kchat.init = (function(data){
	this.url = data.url;
	this.key = data.key;
	this.LoadAssets();
	this.box_Toggle = true;
	window.onload = function() {
		kchat.loadLogo();
		kchat.initevent();
		if (typeof global !== 'undefined'){
			if (global.guest){
				kchat.loadBox();
				$("#KChat_heading_title").html(global.name);
			}else{
				kchat.loadform();
				$("#KChat_heading_title").html(global.heading);
				var department = '';
				for(var i = 0; i < global.dept.length; i++) {
					department += '<option value="'+global.dept[i].id+'">'+global.dept[i].dept+'</option>';
				}
				$("#kchat_dept").html(department);
			}
		}else{
			kchat.loadform();
			$("#KChat_heading_title").html(global.heading);
			var department = '';
			for(var i = 0; i < global.dept.length; i++) {
				department += '<option value="'+global.dept[i].id+'">'+global.dept[i].dept+'</option>';
			}
			$("#kchat_dept").html(department);
		}
		
		$("#KChat_scroll_panel").scroll(function(){
			 scrl = $("#KChat_scroll_panel").scrollTop();
			 if(scrl <= kchat.scroll){
				//scrolling on top
			 }else{
				//scrolling on bottom
			 }
			 kchat.scroll = scrl;
		});
		if(document.getElementById("KChat_scroll_panel")){
			document.getElementById("KChat_scroll_panel").addEventListener("wheel", _loadoldmsg);
		}
	};
});



kchat.LoadAssets = (function(){
	
	if (!window.jQuery) {
	  var jq = document.createElement('script'); 
	  jq.type = 'text/javascript';
	  jq.src = this.url + 'assets/js/jquery.min.js';
	  document.getElementsByTagName('head')[0].appendChild(jq);
	}
	
	var jq = document.createElement('script'); 
	jq.type = 'text/javascript';
	jq.src = this.url + 'assets/emojionearea/emojionearea.min.js';
	document.getElementsByTagName('head')[0].appendChild(jq);
	
	var jq = document.createElement('link'); 
	jq.type = 'text/css';
	jq.rel = "stylesheet";
	jq.href = this.url + 'assets/emojionearea/emojionearea.min.css';
	document.getElementsByTagName('head')[0].appendChild(jq);
	
	var jq = document.createElement('link'); 
	jq.type = 'text/css';
	jq.rel = "stylesheet";
	jq.href = this.url + 'assets/css/kchat.css';
	document.getElementsByTagName('head')[0].appendChild(jq);
	
	var jq = document.createElement('script'); 
	jq.type = 'text/javascript';
	jq.src = this.url + 'kchat.php?js/' + this.key;
	document.getElementsByTagName('head')[0].appendChild(jq);
	
	var jq = document.createElement('link'); 
	jq.type = 'text/css';
	jq.rel = "stylesheet";
	jq.href = this.url + 'kchat.php?css/' + this.key;
	document.getElementsByTagName('head')[0].appendChild(jq);
});

kchat.loadLogo = (function(){
	$("body").append("<div id=\"KChat_btn\" ><image id=\"KChat_logo\" src=\"" + this.url + "assets/images/logo.png\" ></image></div><div id=\"KChat_box\" ></div>");
	$("#KChat_btn").click(function(){
		$("#KChat_btn").css("display", "none");
		$("#KChat_box").css("display", "block");
		kchat.box_Toggle = true;
	});
})

kchat.initevent = (function(event){
	document.addEventListener("keydown", function(event) {
	  //console.log(event.which);
	  if(event.which == 27){
		  if(kchat.box_Toggle){
			$("#KChat_box").css("display", "none");
			$("#KChat_btn").css("display", "block");
			kchat.box_Toggle = false;
		  }else{
			$("#KChat_btn").css("display", "none");
			$("#KChat_box").css("display", "block");
			kchat.box_Toggle = true;
		  }
	  }
	});
});

kchat.loadBox = (function(){
	html = "<div id=\"KChat_heading\" ><div id=\"KChat_heading_title\" ></div></div>";
	html += "<div id=\"KChat_scroll_panel\" >";
	html += "<div id=\"KChat_scroll\" >";
	html += "</div></div>";
	html += "<div id=\"KChat_textarea\" >"+
	"<textarea  class=\"kchatemoji\" autofocus=\"autofocus\" style=\"width:100%;height:100%\" id=\"kchattextarea\" ></textarea>"+
	"</div>";
	$("#KChat_box").html(html);
	this.loademoji();
	this.sync();
});

kchat.loademoji = (function(){
	// to send a msg
	$(".kchatemoji").emojioneArea({
	  events: {
		keypress: function (editor, event) {
		  var keyCode = event ? (event.which ? event.which : event.keyCode) : event.keyCode;
				// to change wtring status
				aa = new Date();
				if(kchat.typing !== aa.getSeconds()){
					kchat.typing = aa.getSeconds();
					$.post("kchat.php?typing",{
					  key: this.key,
					},function(data,status){
						
					});
				}
				// to send msg on enter
			  if (keyCode == 13) {
				   msg = $(".emojionearea-editor").html();
				   msg = msg.replace(/(?:\r\n|\r|\n)/g, ' ');
				  //==============================================================
					if(msg == ""){
						return false;
					}
					$.post("kchat.php?msg",
					{
					  msg: msg,
					  key: this.key,
					},
					function(data,status){
						if(status === "success"){
							kchat_init('false');
						}
					});
				  $(".emojionearea-editor").html("");
			  }
		  }
	  }
	});
});

kchat.loadform = (function(){
	html = "<div id=\"KChat_heading\" ><div id=\"KChat_heading_title\" ></div></div>";
	html += "<div id=\"KChat_form\" >";
	html +="<p type=\"text\"><input id=\"kchat_fname\" placeholder=\"Write your first name here..\"></input></p>";
	html +="<p type=\"text\"><input id=\"kchat_lname\" placeholder=\"Write your lastname here..\"></input></p>";
	html +="<p type=\"email\"><input id=\"kchat_email\" placeholder=\"EMail\"></input></p>";
	html +="<p type=\"text\"><input id=\"kchat_msg\" placeholder=\"Type Here ...\"></input></p>";
	html +="<p><select id='kchat_dept'></select></p>";
	html +="<button onclick=\"kchat.start()\">Send Message</button>";
	html += "</div>";
	$("#KChat_box").html(html);
});

kchat.start = (function(){
	var kchat_fname = $( "#kchat_fname" ).val();
	var kchat_lname = $( "#kchat_lname" ).val();
	var kchat_email = $( "#kchat_email" ).val();
	var kchat_dept = $( "#kchat_dept" ).val();
	var kchat_msg = $( "#kchat_msg" ).val();
	$.post("kchat.php?start",
	{
	  kchat_start: 'kchat_start',
	  kchat_fname: kchat_fname,
	  kchat_lname: kchat_lname,
	  kchat_email: kchat_email,
	  kchat_dept: kchat_dept,
	  kchat_msg: kchat_msg,
	  key: this.key,
	},
	function(data,status){
		global.name = kchat_fname + ' ' + kchat_lname;
		kchat.loadBox();
		$("#KChat_heading_title").html(global.name);
	});
});

String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}

function kchat_json(data){
	response = jQuery.parseJSON(data);
	messages = response.msg;
	data = "";
	info = "";
	play = false;
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
			$( "#KChat_scroll" ).append(data);
			$('#KChat_scroll_panel').scrollTop($('#KChat_scroll')[0].scrollHeight);
			play = true;
		}else if(messages[i].dir == 'b2u'){
			$( "#KChat_scroll" ).prepend(data);
		}
		data = "";
		$( "emojionearea-editor" ).focus();
	}
	if (typeof response.offset !== 'undefined'){
		kchat.offset = response.offset;
	}
}

function kchat_init(first){
	$.post(kchat.url + "kchat.php?getmsg",
	{
	  first_run: first,
	  premsg: kchat.premsg,
	  offset:kchat.offset,
	  key: kchat.key,
	},
	function(data,status){
		if(status === "success"){
			kchat_json(data);
		}
	});
}

kchat.sync = (function(){
	kchat_init('true');
	setInterval(function(){ kchat_init('false'); }, 3000);
});

function _loadoldmsg() {
	if (typeof scrl !== 'undefined'){
		 if(scrl == 0){
			  aa = new Date();
			  // Delay for half second for every ajax during typing 
			  if(kchat.typing !== ((aa.getMilliseconds()-aa.getMilliseconds()%500)/500)){
				kchat.typing = ((aa.getMilliseconds()-aa.getMilliseconds()%500)/500);
				kchat.premsg = true;
				kchat_init('false');
				kchat.premsg = false;
			  }
		 }
	 }
}