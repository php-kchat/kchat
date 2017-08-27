<div class="col-md-10 content">
	<div class="col-md-12 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				Notification
			</div>
			<div class="panel-body" >
                <ul id="not" >
				<?php
					foreach($data['Notification'] as $not){
						?>
						<li><a href="<?php echo $data['config']['purl'].'/'.$not['url']; ?>" ><span class=\"label label-warning\"><?php echo $not['time']; ?></span><?php echo $not['notification']; ?></a></li>
						<?php
					}
				?>
				</ul>
			</div>
		</div>
	</div>
</div>