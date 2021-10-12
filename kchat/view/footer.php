</div>
<?php
  if(isset($this->data['js'])){
		foreach($this->data['js'] as $js){
			echo $js."\n";
		}
  }
?>
		<footer class="pull-left footer">
			<p class="col-md-12"><hr class="divider"/>&nbsp;&nbsp;&nbsp;&nbsp;KChat&nbsp;&copy; 2017 ,KChat&nbsp;v<?php echo $this->data['config']['version']; ?>,&nbsp;Processed in&nbsp;<?php echo round((microtime(true) - $this->data['_start']), 3); ?>&nbsp;s,&nbsp;Ajax Process&nbsp;-&nbsp;<span class="dynamicsparkline"></span>&nbsp;<span id="rq_time">undefined</span>&nbsp;s,&nbsp;<span id="qfired">undefined</span>-SQL Query(s),&nbsp;<!--span id="reqps">undefined</span>&nbsp;-&nbsp;requests/s--></p>
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