<?php
	$chrs = range('a','z');
	$dbprefix = 'kc'.$chrs[rand(0,25)].$chrs[rand(0,25)].'_';
	$host     = $_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>KChat Install</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="<?php echo $data['config']['url']."/"; ?>kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $data['config']['url']."/"; ?>kchat/assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <style>
		#proc img{
			width:100%;
		}
		#proc{
			display:none;
		}
  </style>
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
						<strong>KChat : Database Settings</strong>
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
												<input class="form-control" autofocus="autofocus" placeholder="DATABASE" name="database"  id="database" type="text" autofocus>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" autofocus="autofocus" placeholder="USERNAME" name="username"  id="username" type="text" value="root" >
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" autofocus="autofocus" placeholder="HOST" name="host"  id="host" type="text" value="<?php echo $host;?>" >
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>
												<input class="form-control" placeholder="PASSWORD" name="password" id="password" type="password" value="">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" autofocus="autofocus" placeholder="DBPREFIX" name="dbprefix"  id="dbprefix" type="text" value="<?php echo $dbprefix; ?>">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" autofocus="autofocus" placeholder="PORT" name="port"  id="port" type="text" value="3306" />
											</div>
										</div>
										<div class="form-group" id="proc" >
											<img src="<?php echo $data['config']['url']."/"; ?>kchat/assets/images/installing.gif" />
										</div>
										<div class="form-group">
											<input type="button" class="btn btn-lg btn-primary btn-block" value="Continue" id="install" name="install" />
										</div>
									</div>
								</div>
							</fieldset>
					</div>
					<div class="panel-footer ">
						KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $data['config']['Admin']; ?>
					</div>
                </div>
			</div>
		</div>
	</div>
<script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/kchat.js" ></script>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			install();
		}
	});
</script>
</body>
</html>
