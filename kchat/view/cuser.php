<div class="col-md-10 content">
   <div class="panel panel-default">
      <div class="panel-heading">
         Create User
      </div>
      <div class="panel-body">
		<div class="row">
		   <div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="alert alert-danger" id="error" ></div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				<button id="verify_btn" class="btn" data-clipboard-target="#verify_link">
					<img src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" class="Link" alt="Copy Link" style="width:20px" >
				</button>
				<textarea id="verify_link" class="form-control code" ></textarea>
			  </div>
			 <script>
			 $("#error").hide();
			 $("#verify_link").hide();
			 $("#verify_btn").hide();
			 </script>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" >
			  </div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <input type="text" name="last_name" id="last_name" class="form-control " placeholder="Last Name" >
			  </div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <input type="text" name="user_name" id="user_name" class="form-control " placeholder="Login Name" >
			  </div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <input type="text" name="user_email" id="user_email" class="form-control " placeholder="E-Mail" >
			  </div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <select id = "department" class="form-control" >
					<option value='0' >-- Select Department --</option>
					<?php foreach($this->data['department'] as $dept){ ?>
					<option value='<?php echo $dept['id'] ?>' ><?php echo $dept['dept'] ?></option>
					<?php } ?>
				 </select>
			  </div>
		   </div>
		</div>
		<input type="hidden" name="secret" id="secret" value="<?php echo _rand(64); ?>" />
		<div class="input-group">
			<button type="submit" id="createuser" class="btn btn-success">Get Verification Link</button>
		</div>
		</div>
	</div>
</div>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			createuser();
		}
	});
</script>
<script>
	var clipboard = new Clipboard('.btn');
	clipboard.on('success', function(e) {
		console.log(e);
	});
	clipboard.on('error', function(e) {
		console.log(e);
	});
</script>