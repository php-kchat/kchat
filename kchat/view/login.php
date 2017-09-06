<?php
	if(isAjax()){
		echo 'refresh';
		return true;
	}
?>
<!DOCTYPE html><?php //print_r($this->data); ?>
<html>
<head>
  <title>KChat Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $this->data['config']['url']."/"; ?>favicon.ico" type="image/x-icon" />
  <script src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/alertify/alertify.css"/>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/angular/angular.min.js"></script>
<script>
 var posturl = "<?php if(isset($this->data['param'][0])){ echo $this->data['param'][0];} ?>"; 
 var purl = "<?php print_r($this->data['config']['purl']); ?>";
 var kurl = "<?php print_r($this->data['config']['url']); ?>";
</script>
</head>
<body>
    <div class="container" style="margin-top:40px">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong> Sign in to continue</strong>
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
												<input class="form-control" autofocus="autofocus" placeholder="Username" name="username"  id="username" type="text" autofocus>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>
												<input class="form-control" placeholder="Password" name="password" id="password" type="password" value="">
											</div>
										</div>
										<div class="form-group">
											<input type="button" class="btn btn-lg btn-primary btn-block" value="Sign in" id="login" name="login" />
										</div>
									</div>
								</div>
							</fieldset>
					</div>
					<div class="panel-footer ">
						KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $this->data['config']['version']; ?>
					</div>
                </div>
			</div>
		</div>
	</div>
<script>
angular.module("myApp", ["ngAlertify"]).controller("myController", function($scope, alertif){});
</script>
<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/kchat.js" ></script>
<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/alertify/alertify.js" ></script>
<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/alertify/ngAlertify.js" ></script>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			login();
		}
	});
</script>
</body>
</html>
