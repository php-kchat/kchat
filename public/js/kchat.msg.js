
message = '';
typing = false;
OnLoadMsgs = true;
previous  = false;
   
$(document).ready (function(){

	function post_msg(){
		message = $("#post_msg").val();
		$("#post_msg").val('');
		if (!(message && message.trim() !== '')) {
			return false;
		}
		KChatAjax();
	}

	$("#post_msg_btn").on( "click", function () {
		post_msg();
	});

	$("#post_msg").keyup(function() {
		typing = true;
	});

	$("#post_msg").keypress(function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			post_msg();
		}
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
                
				Conversation = $(`
					<li class="bounceInDown" id="conversation${ element.id }" >
						<a href="/messages/?chat=${ element.conversation_id }" class="clearfix">
							<img src="${ element.photo }" alt="" class="img-circle">
							<div class="friend-name">
								<strong>${ element.conversation_name }<!--i class="mdi mdi-star favorite"></i--></strong>
							</div>
							<div class="last-message text-muted"><strong>${ element.first_name } ${ element.last_name } : </strong>${ element.message }</div>
							<small class="time text-muted timestamp"> ${ element.date } </small>
							<small class="chat-alert text-muted">
							<!-- i class="fa fa-check"></i-->
							</small>
						</a>
					</li>
				`);
				
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

        if(previous){
            Data['previous'] = true;
            previous = false;
        }
        
        Data['_token'] = $('meta[name="csrf_token"]').attr('content');
        
        Data['chat'] = $('meta[name="conversation"]').attr('content');
        
        //console.log(Data);
        
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
            },
            error: function(result){
                
            }
        });

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
	
});
