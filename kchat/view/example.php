<div class="col-md-10 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			Example
		</div>
		<div class="panel-body" id="example">
			Note :-<br/>
			<ul>
			<li>Move Box Folder Whatever you want and delete "config/config.php" of "box" , "<?php echo $this->data['config']['path'].$this->data['config']['ds'].'config'.$this->data['config']['ds'].'kchat_conf.php'; ?>" And "<?php echo $this->data['config']['path'].$this->data['config']['ds'].'config'.$this->data['config']['ds'].'config.php'; ?></li>
			<li>update old url of "assets/js/kchat.js" with new url in Embedded Code</li>
			<li>Update new key {<span class="sc49"><?php echo $this->data['config']['key']; ?></span>} in Embedded Code</li>
			</ul>
		</div>
		<script>
		  data = {
			'url': '<?php echo $this->data['config']['url']."/box/"; ?>',
			'key': '<?php echo $this->data['config']['key']; ?>'
		  };
		  kbox.init(data);
		</script>
	</div>
</div>
</div>
<?php
  if(isset($this->data['js'])){
		foreach($this->data['js'] as $js){
			echo $js."\n";
		}
  }
?>
		<footer class="pull-left footer">
			<p class="col-md-12"><hr class="divider"/>&nbsp;&nbsp;&nbsp;&nbsp;KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $this->data['config']['version']; ?></p>
		</footer>
		<script>
		angular.module("myApp", ["ngAlertify"]).controller("myController", function($scope, alertif){});
		</script>
		<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/alertify/alertify.js" ></script>
		<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/alertify/ngAlertify.js" ></script>
		<script src="<?php echo $this->data['config']['url']; ?>/kchat/assets/js/kchat.js" ></script>
		<script>
		  kchat.init();
		</script>
<?php alertify::get_alert(); ?>
<script> <?php __end(); ?> </script>
	</body>
</html>