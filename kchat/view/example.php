<div class="col-md-10 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			Dashboard
		</div>
		<div class="panel-body" >
			Example :-<br/>
			Move Box Folder Whatever you want and delete config.php of "box" and "<?php echo $this->data['config']['path'].$this->data['config']['ds'].'config.php'; ?>" and update location of "assets/js/kchat.js" and "data.url" keep key same and access the location where you embedded your code
		</div>
		<script>
		  data = {
			'url': '<?php echo $this->data['config']['url']."/kchat/box/"; ?>',
			'key': '<?php echo $this->data['config']['key']; ?>'
		  };
		  kchat.init(data);
		</script>
	</div>
</div>