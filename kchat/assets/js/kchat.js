
$('#install').click(function(){install();});
$('#login').click(function(){login();});
$('#logout').click(function(){logout();});
$('#logout2').click(function(){logout();});
$('#verify').click(function(){varifyuser();});
$('#createuser').click(function(){createuser();});
$('#update').click(function(){update();});
$('#start_msg0').click(function(){start_msg();});
$('#start_msg1').click(function(){start_msg();});
$('.delete_user').click(function(){delete_users(this);});
$('#adddept').click(function(){adddept();});
$('.update_setting').click(function(){update_setting(this);});
$('#smtp_conf').click(function(){smtp_conf(this);});
$('#notification').click(function(){notification(this);});
$('#example').click(function(){ window.location = purl + "/example"; });

function smtp_conf(){
	
	var smtp_host = $( "#smtp_host" ).val();
	var smtp_port = $( "#smtp_port" ).val();
	var smtp_email = $( "#smtp_email" ).val();
	var smtp_pass = $( "#smtp_pass" ).val();
	var smtp_secure = $( "#smtp_secure" ).val();
	var smtp_auth = $( "#smtp_auth" ).is(':checked');
	
	if(smtp_host == '' ||smtp_port == '' || smtp_secure == ''){
		alertify.alert("Please fill All Field");
		return false;
	}
	
	var smtp_conf = {
		smtp_host:smtp_host,
		smtp_port:smtp_port,
		smtp_email:smtp_email,
		smtp_pass:smtp_pass,
		smtp_secure:smtp_secure,
		smtp_auth:smtp_auth,
		token : token,
		action:'smtp_conf'
	};
	$.ajax({
		url : purl + "/ajax/smtp",
		type: "POST",
		data : smtp_conf,
		success: function(data, textStatus, jqXHR)
		{
			if(data == 'Success'){
				location.reload();
			}else{
				alertify.alert('Error');
			}
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

function varifyuser(){
	
	var username = $( "#username" ).val();
	var fname = $("#fname").val();
	var lname = $("#lname").val();
	var password = $("#password").val();
	var repassword = $("#repassword").val();
	var secret = $("#secret").val();
	var dept = $("#dept").val();
	var email = $("#email").val();
	
	if(repassword !== password){
		alertify.alert("password not match");
		return false;
	}
	
	if(email == '' ||dept == '' || secret == '' || repassword == '' || password == '' || lname == '' || fname == '' || username == ''){
		alertify.alert("Please fill All Field");
		return false;
	}
	
	var varifyuser = {
		username:username,
		fname:fname,
		lname:lname,
		password:password,
		repassword:repassword,
		secret:secret,
		dept:dept,
		email:email,
		action:'verify'
	};
	$.ajax({
		url : purl + "/ajax/verify",
		type: "POST",
		data : varifyuser,
		success: function(data, textStatus, jqXHR)
		{
			if(data == ''){
				window.location = purl + "/login";
			}
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
	
	if(username == ''){
		alertify.alert("User Name is Blank");
		return false;
	}
	
	if(password == ''){
		alertify.alert("Password Name is Blank");
		return false;
	}
	
	var LoginData = {
		username:username,
		password:password,
		action:'login'
	};
	
	$.ajax({
		url : purl + "/ajax/login",
		type: "POST",
		data : LoginData,
		success: function(data, textStatus, jqXHR)
		{
			if(data === 'success'){
				location.reload();
			}else{
				alertify.alert("Password and User Doesn't Exist");
			}
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
	var dept = $("#department").val();
	var user_email = $("#user_email").val();
	
	if(dept == 0){
		alertify.alert('Please Select department');
		return false;
	}
	
	if((first_name == '')||(last_name == '')||(user_name == '')||(user_name == '')||(user_email == '')){
		alertify.alert("Fill All Field");
		return false;
	}
		
	var createuser = {
		first_name:first_name,
		last_name:last_name,
		user_name:user_name,
		secret:secret,
		dept:dept,
		user_email:user_email,
		action:'createuser',
		token : token,
	};
	$.ajax({
		url : purl + "/ajax/createuser",
		type: "POST",
		data : createuser,
		success: function(data, textStatus, jqXHR)
		{
			var obj = JSON.parse(data);
			if(isset(obj.error)){
				$("#error").html(obj.error);
				$("#error").show();
			}else{
				vlink = purl + "/login/verify/"+obj.vlink;
				$("#verify_link").html(vlink);
				$("#verify_link").show();
				$("#verify_btn").show();
				alertify.alert(obj.alert);
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
	var logout = {
		token : token,
	};
	$.ajax({
		url : purl + "/ajax/logout",
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
		action:'update',
		token : token
	};
	$.ajax({
		url : purl + "/ajax/update",
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

function start_msg(){
	var favorite = [];
	$.each($("input[name='users']:checked"), function(){            
		favorite.push($(this).val());
	});
	
	var start_msg = {
		users:favorite.join(","),
		action:'start_msg',
		token : token
	};
	$.ajax({
		url : purl + "/ajax/create_chat",
		type: "POST",
		data : start_msg,
		success: function(data, textStatus, jqXHR)
		{
			var obj = JSON.parse(data);
			window.location = purl + "/msgs/g/"+obj.redirect_to;
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}

function delete_users(user){
	var uname = $(user).attr("user");
	var delete_users = {
		uname:uname,
		action:'delete_users',
		token : token
	};
	$.ajax({
		url : purl + "/ajax/deleteuser",
		type: "POST",
		data : delete_users,
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

function adddept(){
	var dept = $( "#dept" ).val();
	var desc = $( "#desc" ).val();
	if(dept == '' && desc == ''){
		return false;
	}
	var adddept = {
		dept:dept,
		desc:desc,
		action:'adddept',
		token : token
	};
	$.ajax({
		url : purl + "/ajax/adddept",
		type: "POST",
		data : adddept,
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

function update_setting(settings){
	var setting = $(settings).attr("settings");
	var search = setting+'_value';
	var val = [];
	$.each($("input[name='"+search+"']"), function(){            
		val.push($(this).val());
	});
	var key = [];
	$.each($("input[name='"+search+"']"), function(){            
		key.push($(this).attr("key"));
	});
	var keyval = [key,val];
	var setting = {
		settings:keyval,
		action:'settings',
		token : token
	};
	$.ajax({
		url : purl + "/ajax/settings",
		type: "POST",
		data : setting,
		success: function(data, textStatus, jqXHR)
		{
			location.reload();
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	})
}

function install(){
	var database = $( "#database" ).val();
	var username = $( "#username" ).val();
	var host = $( "#host" ).val();
	var password = $( "#password" ).val();
	var dbprefix = $( "#dbprefix" ).val();
	var port = $( "#port" ).val();
	
	var install = {
		database:database,
		username:username,
		password:password,
		host:host,
		port:port,
		dbprefix:dbprefix
	};
	console.log(install);
	if(
		database == '' ||
		username == '' ||
		host == ''
	){
		return false;
	}
	
	$('#proc').css('display','block');
	
	$.ajax({
		url : purl + "/ajax/install",
		type: "POST",
		data : install,
		success: function(data, textStatus, jqXHR)
		{
			window.location = purl + "?start";
		},
		error: function (jqXHR, textStatus, errorThrown)
		{	
			console.log(data);
			console.log(textStatus);
		}
	});
}