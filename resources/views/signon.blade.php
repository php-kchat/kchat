<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign-On :: KChat</title>
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
        <img id="screen" src="/logo/2.svg"
          class="img-fluid" alt="Phone image">
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
<div class="card"><div class="card-body">

<h2 class="h4 mb-3">No account? Sign up</h2>
<div class="alert alert-danger" id="signon-error" role="alert" style="display:none">
</div>
<p class="text-muted mb-4">Registration takes less than a minute but gives you full control over your orders.</p>
   <div class="row">
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-fn">First Name</label> <input class="form-control signon" type="text" id="first_name">
            <div class="invalid-feedback">Please enter your first name!</div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-ln">Last Name</label> <input class="form-control signon" type="text" id="last_name">
            <div class="invalid-feedback">Please enter your last name!</div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-email">E-mail Address</label> <input class="form-control signon" type="email" id="email">
            <div class="invalid-feedback">Please enter valid email address!</div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-phone">Phone Number</label> <input class="form-control signon" type="text" id="phone">
            <div class="invalid-feedback">Please enter your phone number!</div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-password">Password</label> <input class="form-control signon" type="password" id="password">
            <div class="invalid-feedback">Please enter password!</div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label for="reg-password-confirm">Confirm Password</label> <input class="form-control signon" type="password" id="password_confirmation">
            <div class="invalid-feedback">Passwords do not match!</div>
         </div>
      </div>
   </div>
   <div class="text-right"> <button class="btn btn-primary" form="signon" action="/sign-on" type="submit" ajax_post >Sign Up</button></div>
   <hr>
	 <div class="d-flex flex-wrap justify-content-between">
		<a href="/login" class="nav-link-inline font-size-sm" href="account-password-recovery.html">Already have account</a>
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