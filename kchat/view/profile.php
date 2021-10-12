<div class="col-md-10 content">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3 class="panel-title"><strong>Edit Profile</strong></h3>
      </div>
      <div class="panel-body">
            <div class="row">
               <div class="col-xs-12 col-sm-4 col-md-4">
                  <div class="form-group">
                     <input type="text" value="<?php echo $this->data['user']['uname']; ?>" name="uname" id="uname" class="form-control" placeholder="User Name" disabled>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-4 col-md-4">
                  <div class="form-group">
                     <input type="text" value="<?php echo $this->data['user']['fname']; ?>" name="fname" id="fname" class="form-control" placeholder="First Name" >
                  </div>
               </div>
               <div class="col-xs-12 col-sm-4 col-md-4">
                  <div class="form-group">
                     <input type="text" value="<?php echo $this->data['user']['lname']; ?>" name="lname" id="lname" class="form-control " placeholder="Last Name" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="password" name="password" id="password" class="form-control " placeholder="Password" >
                  </div>
               </div>
               <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="password" name="repassword" id="repassword" class="form-control " placeholder="Confirm Password" >
                  </div>
               </div>
            </div>
            <a type="submit" id="update" class="btn btn-success">Update</a>
      </div>
   </div>
</div>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			update();
		}
	});
</script>
