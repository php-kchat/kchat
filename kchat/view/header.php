<!DOCTYPE html><?php //print_r($data); ?>
<html lang="en">
<head>
  <title><?php echo $data['title']; ?></title>
  <meta charset="utf-8"/>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/bootstrap.min.js"></script>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/kchat.msgs.js" ></script>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/js/jquery.sparkline.min.js" ></script>
  <script src="<?php echo $data['config']['url']; ?>/kchat/assets/angular/angular.min.js"></script>
  <link rel="icon" href="<?php echo $data['config']['url']."/"; ?>favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/font-awesome.min.css" />
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/kchat.css"/>
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/css/dash.css"/>
  <link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/assets/alertify/alertify.css"/>
  <!--link rel="stylesheet" href="<?php echo $data['config']['url']."/"; ?>kchat/themes/default.css"/-->
	<?php
	  if(isset($data['jsh'])){
			foreach($data['jsh'] as $js){
				echo $js."\n";
			}
	  }
	  if(isset($data['css'])){
			foreach($data['css'] as $css){
				echo $css."\n";
			}
	  }
  ?>
<script>
 var posturl = "<?php if(isset($data['param'][0])){ echo $data['param'][0];} ?>"; 
 var purl = "<?php print_r($data['config']['purl']); ?>";
 var kurl = "<?php print_r($data['config']['url']); ?>";
 var token = "<?php print_r($_SESSION['KChat_Token']); ?>";
</script>
</head>
<body>