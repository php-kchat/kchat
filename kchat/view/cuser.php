<div class="col-md-10 content">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3 class="panel-title"><strong>Create User</strong></h3>
      </div>
      <div class="panel-body">
		<div class="row">
		   <div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="alert alert-danger" id="error" ></div>
		   </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
			  <div class="form-group">
				 <textarea id="verify_link" class="form-control" >
				 </textarea>
				 <script>
				 $("#error").hide();
				 $("#verify_link").hide();
				 </script>
			  </div>
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
				 <select id = "department" class="form-control" >
					<option value='0' >-- Select Department --</option>
					<?php foreach($data['department'] as $dept){ ?>
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