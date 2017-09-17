<!DOCTYPE html><?php //print_r($this->data); ?>
<html lang="en">
<head>
  <title><?php echo $this->data['title']; ?></title>
  <meta charset="utf-8"/>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/jquery.min.js"></script>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/bootstrap.min.js"></script>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/kchat.msgs.js" ></script>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/jquery.sparkline.min.js" ></script>
  <script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/angular/angular.min.js"></script>
  <link rel="icon" href="<?php echo $this->data['config']['url']."/"; ?>favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/font-awesome.min.css" />
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/kchat.css"/>
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/css/dash.css"/>
  <link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/alertify/alertify.css"/>
  <!--link rel="stylesheet" href="<?php echo $this->data['config']['url']."/"; ?>kchat/themes/default.css"/-->
	<?php
	  if(isset($this->data['jsh'])){
			foreach($this->data['jsh'] as $js){
				echo $js."\n";
			}
	  }
	  if(isset($this->data['css'])){
			foreach($this->data['css'] as $css){
				echo $css."\n";
			}
	  }
	  if(!isset($this->data['path'][1])){$this->data['path'][1] = '..';}
  ?>
<script>
 var posturl = "<?php if(isset($this->data['param'][0]) && ($this->data['path'][1] == 'g')){ echo $this->data['param'][0];} ?>"; 
 var purl = "<?php print_r($this->data['config']['purl']); ?>";
 var kurl = "<?php print_r($this->data['config']['url']); ?>";
 var token = "<?php print_r($_SESSION['KChat_Token']); ?>";
 var hint = "<?php print_r(trim(implode('',$this->data['path']),'.')); ?>";
</script>
</head>
<body>