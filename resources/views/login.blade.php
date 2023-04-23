<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">

    <title>Login :: KChat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ csrf_token() }}" />
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/font-awesome.min.css" rel="stylesheet" >
    <link href="css/kchat.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>
<body>
<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex align-items-center justify-content-center h-100">
      <div class="col-md-8 col-lg-7 col-xl-6">
        <img id="screen" src="/logo/1.svg"
          class="img-fluid" alt="Phone image">
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
<div class="card">
   <div class="card-body">
      <h2 class="h4 mb-1">Sign in</h2>
	  <div class="alert alert-danger" id="login-error" role="alert" style="display:none">
		</div>
      <hr>
      <h3 class="h6 font-weight-semibold opacity-70 pt-4 pb-2">Sign in using form below</h3>
         <div class="input-group form-group">
            <div class="input-group-prepend">
               <span class="input-group-text">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                     <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                     <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
               </span>
            </div>
            <input class="login form-control" id="email" type="email" placeholder="Email">
            <div class="invalid-feedback">Please enter valid email address!</div>
         </div>
         <div class="input-group form-group">
            <div class="input-group-prepend">
               <span class="input-group-text">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                     <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                     <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
               </span>
            </div>
            <input class="login form-control" id="password" type="password" placeholder="Password">
            <div class="invalid-feedback">Please enter valid password!</div>
         </div>
         <div class="d-flex flex-wrap justify-content-between">
            <div class="custom-control custom-checkbox"> <input class="custom-control-input" type="checkbox" checked="" id="remember_me"> <label class="custom-control-label" for="remember_me">Remember me</label></div>
            <!--a class="nav-link-inline font-size-sm" href="account-password-recovery.html">Forgot password?</a-->
         </div>
         <div class="text-right pt-4"> <button class="btn btn-primary" type="submit" action="\login" form="login" ajax_post>Sign In</button></div>
         <hr class="mt-4">
         <div class="d-flex flex-wrap justify-content-between">
			<a href="/sign-on" class="nav-link-inline font-size-sm" href="account-password-recovery.html">Sign On</a>
         </div>
   </div>
</div>

      </div>
    </div>
  </div>
</section>
<script>
	let images = [
		'/logo/1.svg',
		'/logo/2.svg',
		'/logo/3.svg',
		'/logo/4.svg',
	];

	const imgElement = document.querySelector('#screen');

	function change() {
	   imgElement.src = images[Math.floor(Math.random()*4)];
	}

	window.onload = function () {
		setInterval(change, 1500);
	};
</script>
<script src="js/kchat.js"></script>
</body>
</html>