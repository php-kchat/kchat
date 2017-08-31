<!DOCTYPE html><?php //print_r($this->data); ?>
<html>
<head>
  <title>KChat Install</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <style>
		#proc img{
			width:100%;
		}
		#proc{
			display:none;
		}
		.error {
			width: 100%;
			padding: 10px;
			margin: 0px;
			border-color: #f00;
			border-width: 1px;
			background-color: #ffd6d6;
			color: #f00;
			border-style: solid;
		}
  </style>
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
				<?php if(!isset($this->data['error'])){ ?>
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
												<input class="form-control" autofocus="autofocus" placeholder="HOST" name="host"  id="host" type="text" value="<?php echo $this->data['host']; ?>" >
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
												<input class="form-control" autofocus="autofocus" placeholder="DBPREFIX" name="dbprefix"  id="dbprefix" type="text" value="<?php echo $this->data['dbprefix']; ?>">
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
											<img src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/images/installing.gif" />
										</div>
										<div class="form-group">
											<input type="button" class="btn btn-lg btn-primary btn-block" value="Continue" id="install" name="install" />
										</div>
									</div>
								</div>
							</fieldset>
					</div>
					<div class="panel-footer ">
						KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $this->data['config']['version']; ?>
					</div>
					<?php }else{ ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong>KChat : Requirement</strong>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12 col-md-10  col-md-offset-1 ">
									<?php foreach($this->data['error'] as $error){ ?>
										<div class="error" >
											<?php echo $error; ?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="panel-footer ">
							KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $this->data['config']['version']; ?>
						</div>
					<?php } ?>
                </div>
			</div>
		</div>
	</div>
<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/kchat.js" ></script>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			install();
		}
	});
</script>
</body>
</html>
