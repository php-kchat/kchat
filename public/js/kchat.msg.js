
message = '';
typing = false;
OnLoadMsgs = true;
previous  = false;
conversation_like = '';
emojioneAreaElm = null;
WhiteBoard = false;
   
$(document).ready (function(){
	
    $("#post_msg").emojioneArea({
		pickerPosition: "top",
    	tonesStyle: "bullet",
		events: {
         	keypress: function (editor, event) {
                emojioneAreaElm = this;
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    post_msg();
                }
        	}
    	}
	});
    
	function post_msg(){
        if(emojioneAreaElm != null){
            message = emojioneAreaElm.getText();
            emojioneAreaElm.setText('');
        }
        if(WhiteBoard){
            message = JSON.stringify(points);
            points = [];
            Clear();
        }
		$("#post_msg").val('');
		if (!(message && message.trim() !== '')) {
			return false;
		}
		KChatAjax();
	}
    
    $("#WhiteBoardSend").on( "click", function () {
        WhiteBoard = true;
		post_msg();
        WhiteBoard = false;
    });

	$("#post_msg_btn").on( "click", function () {
		post_msg();
	});

	$("#post_msg").keyup(function() {
		typing = true;
	});
    
    $("#Messages").on("click", ".show_whiteboard", function(){
            Clear();
            points = jQuery.parseJSON($("<div/>").html(atob($(this).attr("data-msg"))).text());
            points.forEach(draw);
            $('#whiteboard').modal('show');
    });
    
	function UpdateConversations(json){
		
		if(json['chats'] != undefined){
			
			//console.log(json['chats']);
			
			json['chats'].forEach(function(element) {
				
				$('#conversation'+element.id).remove();
                
				element.date = getRelativeTime(element.date);
                
				post_msg();
                
                if(element.message == null){
                    element.message = '';
                    element.first_name = '';
                    element.last_name = '';
                }
                
                if(element.unread != undefined){
                    element.unread = "<small class=\"chat-alert label label-danger mt-2\">"+element.unread+"</small>";
                }else{
                    element.unread = "";
                }
                
				Conversation = $(`
					<li class="bounceInDown" id="conversation${ element.id }" >
						<a href="/messages/?chat=${ element.conversation_id }" class="clearfix">
							<img src="${ element.photo }" alt="" class="img-circle">
							<div class="friend-name">
								<strong>${ element.conversation_name }<!--i class="mdi mdi-star favorite"></i--></strong>
							</div>
							<div class="last-message text-muted"><strong>${ element.first_name } ${ element.last_name } : </strong>${ element.message }</div>
							<small class="time text-muted timestamp"> ${ element.date } </small>
                            ${ element.unread }
							<small class="chat-alert text-muted">
							<!-- i class="fa fa-check"></i-->
							</small>
						</a>
					</li>
				`);
                if(element.type == 1){
                    Conversation = $(`
                        <li class="bounceInDown" id="conversation${ element.id }" >
                            <a href="/messages/?chat=${ element.conversation_id }" class="clearfix">
                                <img src="${ element.photo }" alt="" class="img-circle">
                                <div class="friend-name">
                                    <strong>${ element.conversation_name }${ element.unread }<!--i class="mdi mdi-star favorite"></i--></strong>
                                </div>
                                <div class="last-message text-muted"><strong>${ element.first_name } ${ element.last_name } : </strong><i class="fa fa-pencil-square-o fa" aria-hidden="true"></i></div>
                                <small class="time text-muted timestamp"> ${ element.date } </small>
                                <small class="chat-alert text-muted">
                                <!-- i class="fa fa-check"></i-->
                                </small>
                            </a>
                        </li>
                    `); 
                }
                if(element.type == 2){
                    Conversation = $(`
                        <li class="bounceInDown" id="conversation${ element.id }" >
                            <a href="/messages/?chat=${ element.conversation_id }" class="clearfix">
                                <img src="${ element.photo }" alt="" class="img-circle">
                                <div class="friend-name">
                                    <strong>${ element.conversation_name }<!--i class="mdi mdi-star favorite"></i--></strong>
                                </div>
                                <div class="last-message text-muted"><strong>${ element.first_name } ${ element.last_name } : </strong><i class="fa fa fa-paperclip fa" aria-hidden="true"></i></div>
                                <small class="time text-muted timestamp"> ${ element.date } </small>
                                <small class="chat-alert text-muted">
                                <!-- i class="fa fa-check"></i-->
                                </small>
                            </a>
                        </li>
                    `); 
                }
				
				$('#MessageBox').prepend(Conversation);
				
			});

		}
	}

	function UpdateMessages(json){
		
		if(json['messages'] != undefined){
			
            var parentDiv = $('#Msgs');
            var childDiv = $('#Messages').children(':first');
                    
			json['messages'].forEach(function(element) {
				
				element.created_at = getRelativeTime(element.created_at);
				
				if(user_id == element.user_id){
					messages = $(`
					   <li class="clearfix" id="msg${ element.id }" >
						  <div class="message-data text-right">
							 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
							 <img src="${ element.photo }" alt="avatar">
						  </div>
						  <div class="message other-message float-right"> ${ element.message } </div>
					   </li>
					`);
                    if(element.type == 1){
                        element.message = btoa(element.message);
                        messages = $(`
                           <li class="clearfix" id="msg${ element.id }" >
                              <div class="message-data text-right">
                                 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
                                 <img src="${ element.photo }" alt="avatar">
                              </div>
                              <div class="float-right show_whiteboard" data-msg="${ element.message }" ><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></div>
                           </li>
                        `);
                    }
                    if(element.type == 2){
                        
                        files = jQuery.parseJSON($("<div/>").html(element.message).text());
                        
                        element.message = '<ul class="file-ul">';

                        for(i = 0 ; i < files.length ; i++){
                            element.message += "<li class=\"file\"><a href=\"/messages/downattch/"+files[i].uuid+"\" target=\"_blank\"><!--img class=\"file-icon\" src=\"file.png\" alt=\"icon\"--><i class=\"fa fa-file fa-2\" aria-hidden=\"true\"></i>&nbsp;&nbsp;"+files[i].Name+"</a></li>";
                        }
                        
                        element.message += '<ul>';
                        
                        messages = $(`
                           <li class="clearfix" id="msg${ element.id }" >
                              <div class="message-data text-right">
                                 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
                                 <img src="${ element.photo }" alt="avatar">
                              </div>
                              <div class="float-right">${element.message}</div>
                           </li>
                        `);
                    }
				}else{			
					messages = $(`
					   <li class="clearfix" id="msg${ element.id }" >
						  <div class="message-data text-left">
							 <img src="${ element.photo }" alt="avatar">
							 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
						  </div>
						  <div class="message other-message float-left"> ${ element.message } </div>
					   </li>
					`);
                    if(element.type == 1){
                        element.message = btoa(element.message);
                        messages = $(`
                           <li class="clearfix" id="msg${ element.id }" >
                              <div class="message-data text-left">
                                 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
                                 <img src="${ element.photo }" alt="avatar">
                              </div>
                              <div class="float-left show_whiteboard" data-msg="${ element.message }" ><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></div>
                           </li>
                        `);
                    }
                    if(element.type == 2){
                        
                        files = jQuery.parseJSON($("<div/>").html(element.message).text());
                        
                        element.message = '<ul class="file-ul">';

                        for(i = 0 ; i < files.length ; i++){
                            element.message += "<li class=\"file\"><a href=\"/messages/downattch/"+files[i].uuid+"\" target=\"_blank\"><!--img class=\"file-icon\" src=\"file.png\" alt=\"icon\"--><i class=\"fa fa-file fa-2\" aria-hidden=\"true\"></i>&nbsp;&nbsp;"+files[i].Name+"</a></li>";
                        }
                        
                        element.message += '<ul>';
                        
                        messages = $(`
                           <li class="clearfix" id="msg${ element.id }" >
                              <div class="message-data text-left">
                                 <span class="message-data-time"><strong>${ element.first_name } ${ element.last_name }</strong>${ element.created_at }</span>
                                 <img src="${ element.photo }" alt="avatar">
                              </div>
                              <div class="float-left">${element.message}</div>
                           </li>
                        `);
                    }
				}
             
                if(json.previous){
                    $('#Messages').prepend(messages);
                    //to keep scroll bar on message from where new messages are prepend
                    parentDiv.scrollTop(childDiv.offset().top - parentDiv.offset().top);
                    
                }else{
                    $('#Messages').append(messages);
                }
				
            });
            
			if(OnLoadMsgs){
				overflowDiv.scrollTop(overflowDiv[0].scrollHeight);
				OnLoadMsgs = false;
			}
            
            if(json['messages'].length){
                
                overflowDiv = $("#Msgs");
                
                if ((Math.ceil(overflowDiv.scrollTop() + overflowDiv.innerHeight()) - overflowDiv[0].scrollHeight) > -200 ){
                    
                    //Scroll to bottom if scroll bar is already on bottom
                    overflowDiv.scrollTop(overflowDiv[0].scrollHeight);
                    
                }else{
                    //Show Scroll to bottom button if scroll bar is not on bottom
                    $("#gotobottom").css("display", "block");
                }
                
            }

		}
	}

    LoadMessage = true;
	// Define the function to make the AJAX call
	function KChatAjax(){
		
        Data = {};
    
        if (message && message.trim() !== '') {
            Data['message'] = message;
            message = '';
        }

        if(typing){
            Data['typing'] = true;
            typing = false;
        }

        if(WhiteBoard){
            Data['whiteboard'] = true;
        }

        if(previous){
            Data['previous'] = true;
            previous = false;
        }
        
        Data['_token'] = $('meta[name="csrf_token"]').attr('content');
        
        Data['chat'] = $('meta[name="conversation"]').attr('content');
        
        //console.log(Data);
        
        if(LoadMessage){
            LoadMessage = false;
            $.ajax({
                type: "POST",
                url: '/messages',
                data: Data,
                success: function(messages){
                    
                    messages = $.parseJSON(messages);
                    
                    UpdateConversations(messages);
                    
                    UpdateMessages(messages);
                    
                    previous = false;
                    
                    $('#loading').css('display','none');
                    
                    LoadMessage = true;
                },
                error: function(result){
                    LoadMessage = true;
                }
            }); 
        }
	}

	// Define the initial interval time (in milliseconds)
	let intervalTime = 5000;
    
	// Define the function to update the interval time
	function updateInterval(newIntervalTime) {
	  // Clear the existing interval
	  clearInterval(interval);

	  // Update the interval time
	  intervalTime = newIntervalTime;

	  // Set a new interval with the updated interval time
	  interval = setInterval(KChatAjax, intervalTime);
	}

	// Set the initial interval with the KChatAjax function
	let interval = setInterval(KChatAjax, intervalTime);

	// Update the interval time to 10 seconds
	//updateInterval(10000);


	KChatAjax();

	// Event to load old message on scroll up
	var overflowDiv = $('#Msgs');
    
	overflowDiv.on('scroll', function(){
        
		if (overflowDiv.scrollTop() === 0) {
			previous = true;
            $('#loading').css('display','block');
            KChatAjax();
		}
        
	});
    
	$("#gotobottom").on('click', function(){
		
        overflowDiv.scrollTop(overflowDiv[0].scrollHeight);
        $(this).css('display','none');
        
	});
    
    $("#convo_like").keyup(function() {

        Search = {};
        
        Search['_token'] = $('meta[name="csrf_token"]').attr('content');
        Search['convo_like'] = $(this).val();
        
        $.ajax({
            type: "POST",
            url: '/getConvo',
            data: Search,
            success: function(results){
                search_convo = true;
                results = $.parseJSON(results);
                html = '';
                results.forEach(function(element){
                    html += `<tr>
                        <td><a href="/messages/?chat=${element.id}" ><img src="${element.photo}" class="rounded-circle my-n1" alt="[Photo]" width="32" height="32"></a></td>
                        <td><a href="/messages/?chat=${element.id}" >${element.conversation_name}</a></td>
                    </tr>`
                });
                $('#ConvoList').html(html);
            },
            error: function(result){
                
            }
        });

	});
    
    $("#selectedFile").change(function(){

        let Data = new FormData();
        
		Data.append('_token',$('meta[name="csrf_token"]').attr('content'));
        
		Data.append('chat',$('meta[name="conversation"]').attr('content'));
        
        files = $('#selectedFile')[0].files;
        
        for (let i = 0; i < files.length; i++) {
            Data.append('files[]', files[i]);
		}
        
		$.ajax({
			type: "POST",
			url: '/messages/attachments',
			data: Data,
			processData: false,
			contentType: false,
			success: function(result){
				result = $.parseJSON(result);
                if(result.error != undefined){
                    kchat_alert(result.error,(function(){}));
                }
			},
			error: function(result){

			}
		});
        
    }); 
	
});
