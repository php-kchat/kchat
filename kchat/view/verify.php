<!DOCTYPE html><?php //print_r($data); ?>
<html>
<head>
  <title>KChat Verification link</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <script src="<?php echo $data['config']['url']."/"; ?>kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $data['config']['url']."/"; ?>kchat/assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/alertify/alertify.css"/>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/angular/angular.min.js"></script>
<script>
 var posturl = "<?php if(isset($data['param'][0])){ echo $data['param'][0];} ?>"; 
 var purl = "<?php print_r($data['config']['purl']); ?>";
 var kurl = "<?php print_r($data['config']['url']); ?>";
</script>
</head>
<body>
    <div class="container" style="margin-top:40px">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong> Verify to continue</strong>
					</div>
					<div class="panel-body">
							<fieldset>
								<div class="row">
									<div class="col-sm-12 col-md-10  col-md-offset-1 ">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" placeholder="Username" name="username"  id="username" type="text" value="<?php echo $data['verify']['uname']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" placeholder="First Name" name="fname"  id="fname" type="text" value="<?php echo $data['verify']['fname']; ?>" >
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" placeholder="Last Name" name="lname"  id="lname" type="text" value="<?php echo $data['verify']['lname']; ?>" >
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>
												<input class="form-control" autofocus="autofocus" placeholder="Password" name="password" id="password" type="password" value="">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>
												<input class="form-control" placeholder="Re-Password" name="password" id="repassword" type="password" value="">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" placeholder="dept_name" name="dept_name"  id="dept_name" type="text" value="<?php echo $data['verify']['dept_name']; ?>" disabled>
											</div>
										</div>
										<div class="form-group">
											<input type="hidden" name="secret" id="secret" value="<?php echo $data['verify']['secret']; ?>" />
											<input type="hidden" name="dept" id="dept" value="<?php echo $data['verify']['dept']; ?>" />
											<input type="button" class="btn btn-lg btn-primary btn-block" value="Verify" id="verify" name="verify" />
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					<div class="panel-footer ">
						&copy; Ganesh Kandu
					</div>
                </div>
			</div>
		</div>
	</div>
<script>
angular.module("myApp", ["ngAlertify"]).controller("myController", function($scope, alertif){});
</script>
<script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/kchat.js" ></script>
<script src="<?php echo $data['config']['url']; ?>/kchat/assets/alertify/alertify.js" ></script>
<script src="<?php echo $data['config']['url']; ?>/kchat/assets/alertify/ngAlertify.js" ></script>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			varifyuser();
		}
	});
</script>
</body>
</html>
