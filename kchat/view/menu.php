<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle navbar-toggle-sidebar collapsed">
			MENU
			</button>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
				<?php echo ucfirst($this->data['user']['fname'])." ".ucfirst($this->data['user']['lname']); ?>
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">      
			<!--form class="navbar-form navbar-left" method="GET" role="search">
				<div class="form-group">
					<input type="text" name="q" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
			</form-->
			<ul class="nav navbar-nav navbar-right">
			    <li class="dropdown">
					<a href="<?php echo $this->data['config']['url']."/msgs"; ?>" >
					<span class="glyphicon glyphicon-comment"></span>  Chats <span class="label label-primary" id="unread" >0</span>
					</a>
                </li>
			    <li class="dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" id="notification" >
					<span class="glyphicon glyphicon-bell"></span>&nbsp;&nbsp;Notification<span class="caret"></span>
					</a>
                    <ul class="dropdown-menu" id="set_notification">
                        <li><a href="javascript:void(0);" class="text-center"><img src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/images/loading.gif" /></a></li>
                    </ul>
                </li>
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Account
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo $this->data['config']['purl']; ?>/users/profile" >Profile</a></li>
							<li><a href="javascript:void(0)" id="logout" >Logout</a></li>
						</ul>
				</li>
			</ul>
			</div>
		</div>
	</nav>
<div class="container-fluid main-container">