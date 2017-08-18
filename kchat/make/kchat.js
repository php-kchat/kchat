
$('#login').click(function(){login();});
$('#logout').click(function(){logout();});
$('#logout2').click(function(){logout();});
$('#verify').click(function(){varifyuser();});
$('#createuser').click(function(){createuser();});
$('#update').click(function(){update();});

function varifyuser(){
	
	var username = $( "#username" ).val();
	var fname = $("#fname").val();
	var lname = $("#lname").val();
	var password = $("#password").val();
	var repassword = $("#repassword").val();
	var secret = $("#secret").val();
	
	var varifyuser = {
		username:username,
		fname:fname,
		lname:lname,
		password:password,
		repassword:repassword,
		secret:secret,
		action:'verify'
	};
	$.ajax({
		url : "{{url}}/index.php/ajax/verify",
		type: "POST",
		data : varifyuser,
		success: function(data, textStatus, jqXHR)
		{
			window.location = "{{url}}/index.php/login";
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}
function login(){
	var username = $( "#username" ).val();
	var password = $("#password").val();
	
	var LoginData = {
		username:username,
		password:password,
		action:'login'
	};
	$.ajax({
		url : "{{url}}/index.php/ajax/login",
		type: "POST",
		data : LoginData,
		success: function(data, textStatus, jqXHR)
		{
			location.reload();
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

function createuser(){
	var first_name = $( "#first_name" ).val();
	var last_name = $("#last_name").val();
	var user_name = $("#user_name").val();
	var secret = $("#secret").val();
	
	var createuser = {
		first_name:first_name,
		last_name:last_name,
		user_name:user_name,
		secret:secret,
		action:'createuser'
	};
	$.ajax({
		url : "{{url}}/index.php/ajax/createuser",
		type: "POST",
		data : createuser,
		success: function(data, textStatus, jqXHR)
		{
			var obj = JSON.parse(data);
			if(isset(obj.error)){
				$("#error").html(obj.error);
				$("#error").show();
			}else{
				vlink = "{{url}}/login/verify/"+obj.vlink;
				$("#verify_link").val(vlink);
				$("#verify_link").show();
			}			
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

function logout(){
	var logout = {};
	$.ajax({
		url : "{{url}}/index.php/ajax/logout",
		type: "POST",
		data : logout,
		success: function(data, textStatus, jqXHR)
		{
			location.reload();
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

function update(){
	var uname = $( "#uname" ).val();
	var fname = $("#fname").val();
	var lname = $("#lname").val();
	var password = $("#password").val();
	var repassword = $("#repassword").val();
	
	var update = {
		uname:uname,
		fname:fname,
		lname:lname,
		password:password,
		repassword:repassword,
		action:'update'
	};
	$.ajax({
		url : "{{url}}/index.php/ajax/update",
		type: "POST",
		data : update,
		success: function(data, textStatus, jqXHR)
		{
			var obj = JSON.parse(data);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

