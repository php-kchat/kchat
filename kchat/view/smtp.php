<div class="col-md-10 content">
   <div class="panel panel-default">
      <div class="panel-heading">
         SMTP Configuration
      </div>
      <div class="panel-body">
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                     <input type="text" name="smtp_host" id="smtp_host" class="form-control" placeholder="SMTP Host" value="<?php echo $this->data['smtp']['host']; ?>" />
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                     <input type="text" name="smtp_port" id="smtp_port" class="form-control" placeholder="SMTP PORT" value="<?php echo $this->data['smtp']['port']; ?>" />
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                     <input type="text" name="smtp_email" id="smtp_email" class="form-control" placeholder="SMTP User" value="<?php echo $this->data['smtp']['email']; ?>" />
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                     <input type="password" name="smtp_pass" id="smtp_pass" class="form-control" placeholder="SMTP Password -- leave blank if don't want to update" value="" />
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-6 col-sm-2 col-md-2">
                  <div class="form-group">
                     SMTP Secure
                  </div>
               </div>
               <div class="col-xs-6 col-sm-2 col-md-2">
                  <div class="form-group">
                     <select name="smtp_secure" id="smtp_secure" class="form-control" >
						<?php $a1 = $a2 = '';if($this->data['smtp']['secure']){$a2 = 'selected';}else{$a1 = 'selected'; }?>
						<option value="0" <?php echo $a1; ?>>SSL</option>
						<option value="1" <?php echo $a2; ?>>TLS</option>
					 </select>
                  </div>
               </div>
               <div class="col-xs-6 col-sm-8 col-md-8"></div>
            </div>
            <div class="row">
               <div class="col-xs-6 col-sm-2 col-md-2">
                  <div class="form-group">
                     SMTP Authentication
                  </div>
               </div>
               <div class="col-xs-6 col-sm-2 col-md-2">
                  <div class="form-group"><?php $auth = ''; if($this->data['smtp']['auth'] == 'true'){ $auth = 'checked'; } ?>
                     <input type="checkbox" name="smtp_auth" id="smtp_auth" class="form-control" <?php echo $auth; ?>>
                  </div>
               </div>
               <div class="col-xs-6 col-sm-8 col-md-8"></div>
            </div>
            <a type="submit" id="smtp_conf" class="btn btn-success">SMTP Configuration</a>
      </div>
   </div>
</div>
<script>
	document.body.addEventListener('keydown', function(e) {
		if(e.keyCode == 13){
			smtp_conf();
		}
	});
</script>
