
	<div class="col-md-10 content">
		<div class="box-body no-padding">
                  <div>
                    <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/"; ?>" ><i class="glyphicon glyphicon-refresh"></i></a>
                    <div class="pull-right">
                      <?php echo $this->data['users']['start'].'-'.$this->data['users']['end'].'/'.$this->data['users']['no']; ?>
                      <div class="btn-group">
                        <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/".$this->data['users']['prev']; ?>" ><i class="glyphicon glyphicon-arrow-left"></i></a>
                        <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/".$this->data['users']['post']; ?>" ><i class="glyphicon glyphicon-arrow-right"></i></a>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <hr>
                  <div class="table-responsive mailbox-messages">
                    <table id="usersglist" class="table table-hover table-striped">
                      <tbody>
                        <tr>
                          <!--td>Check</td-->
                          <td><b>Fisrt Name</b></td>
                          <td><b>Last name</b></td>
                          <td><b>ip</b></td>
                          <td><b>country code</b></td>
                          <td><b>timezone</b></td>
                          <td><b>latitude</b></td>
                          <td><b>longitude</b></td>
                          <td><b>Created At</b></td>
                          <td><b>Delete</b></td>
                        </tr>
						<?php foreach($this->data['users'] as $user){ ?>
							<?php if(is_array($user)){ ?>
							<tr>
							  <!--td><input id="user<?php echo $user['id']; ?>" type="checkbox"></td-->
							  <!--td><b><?php echo $user['id']; ?></b></td-->
							  <td><b><?php echo $user['fname']; ?></b></td>
							  <td><b><?php echo $user['lname']; ?></b></td>
							  <td><b><?php echo $user['ip']; ?></b></td>
							  <td><b><?php echo $user['country_code']; ?></b></td>
							  <td><b><?php echo $user['time_zone']; ?></b></td>
							  <td><b><?php echo $user['latitude']; ?></b></td>
							  <td><b><?php echo $user['longitude']; ?></b></td>
							  <td><b><?php echo $user['date']; ?></b></td>
							  <td><b><a href="javascript:void(0)" class='delete_user' user='<?php echo $user['id']; ?>' ><i class="glyphicon glyphicon-trash"></i></a></b></td>
							</tr>
						<?php }} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr>
                <div class="box-footer no-padding">
                  <div>
                    <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/"; ?>" ><i class="glyphicon glyphicon-refresh"></i></a>
                    <div class="pull-right">
                      <?php echo $this->data['users']['start'].'-'.$this->data['users']['end'].'/'.$this->data['users']['no']; ?>
                      <div class="btn-group">
                        <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/".$this->data['users']['prev']; ?>" ><i class="glyphicon glyphicon-arrow-left"></i></a>
                        <a class="btn btn-default btn-sm" href="<?php echo $this->data['config']['purl']."/users/ulist/".$this->data['users']['post']; ?>" ><i class="glyphicon glyphicon-arrow-right"></i></a>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
              </div><!-- /. box -->
