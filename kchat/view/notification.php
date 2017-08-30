<?php 
	if(!isset($this->data['param'][0])){
		$this->data['param'][0] = base64_encode('0');
	}
?>
<div class="col-md-10 content">
	<div class="col-md-12 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				Notification
			</div>
			<div style="padding:15px" >
			<a class="btn btn-default btn-sm" href="#Refresh" ><i class="glyphicon glyphicon-refresh"></i></a>
				<div class="pull-right">
					<div class="btn-group">
						<a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl'].'/notif/n/'.base64_encode((int)base64_decode($this->data['param'][0]) + 10); ?>" ><i class="glyphicon glyphicon-arrow-left"></i></a>
						<a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl'].'/notif/n/'.base64_encode((int)base64_decode($this->data['param'][0]) - 10); ?>" ><i class="glyphicon glyphicon-arrow-right"></i></a>
					</div>
				</div>
			</div>
			<div class="panel-body table-responsive" >
				<table class="table table-hover table-striped">
				  <tbody>
					<tr>
					  <td><b>No</b></td>
					  <td><b>Time</b></td>
					  <td><b>Notification</b></td>
					</tr>
				<?php
					foreach($this->data['Notification'] as $not){
						?>
						<tr class='clickable-row' >
						<td><?php echo $not['id']; ?></td><td><?php echo $not['time']; ?></td><td><a href="<?php echo $this->data['config']['purl'].'/'.$not['url']; ?>" ><?php echo $not['notification']; ?></a></td>
						</tr>
						<?php
					}
				?>
				  </tbody>
				</table>
			</div>
			<div style="padding:15px" >
			<a class="btn btn-default btn-sm" href="#Refresh" ><i class="glyphicon glyphicon-refresh"></i></a>
				<div class="pull-right">
					<div class="btn-group">
						<a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl'].'/notif/n/'.base64_encode((int)base64_decode($this->data['param'][0]) + 10); ?>" ><i class="glyphicon glyphicon-arrow-left"></i></a>
						<a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl'].'/notif/n/'.base64_encode((int)base64_decode($this->data['param'][0]) - 10); ?>" ><i class="glyphicon glyphicon-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>