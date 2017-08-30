<div class="col-md-10 content">
<div class="col-md-12 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			Add Department
		</div>
		<div class="panel-body">
			<div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <input type="text" name="dept" id="dept" class="form-control" placeholder="Department ex. Support" >
			  </div>
		   </div>
			<div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <textarea type="text" name="desc" id="desc" class="form-control" placeholder="Disciption ex. IT technical support officers monitor and maintain the computer systems and networks of an organisation." ></textarea>
			  </div>
		   </div>
			<div class="col-xs-12 col-sm-12 col-md-12">
			   <div class="input-group">
					<button type="submit" id="adddept" class="btn btn-success">Add Department</button>
				</div>
		   </div>
		</div>
	</div>
</div>
<?php
function input_box($key,$value){
	$type = '';
	switch($value['type']){
		case 'color':
		$type = "class=\"jscolor\"";
		break;
	}
?>
<div class="col-xs-6 col-sm-6 col-md-6">
  <div class="form-group"><?php echo $value['option']; ?></div>
</div>
<div class="col-xs-6 col-sm-6 col-md-6">
  <div class="form-group"><input type="text" <?php echo $type; ?> key="<?php echo $value['key']; ?>" name="<?php echo $key.'_value'; ?>" id="key_<?php echo $value['id']; ?>" class="form-control" value="<?php echo $value['value']; ?>" ></div>
</div>
<?php
}

foreach($this->data['settings'] as $key => $value){
?>
	<div class="col-md-12 content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo $key; ?>
			</div>
			<div class="panel-body">
			<?php
				foreach($value as $value1){
					input_box($key,$value1);
				}
			?>
			<div class="col-xs-12 col-sm-12 col-md-12">
			    <div class="input-group">
					<button type="submit" settings="<?php echo $key; ?>" class="update_setting btn btn-success" >Update</button>
				</div>
			</div>
			</div>
		</div>
	</div>
<?php
}
?>
</div>