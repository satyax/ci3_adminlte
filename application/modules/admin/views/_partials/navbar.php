<header class="main-header">
	<a href="" class="logo"><b><?php echo $site_name; ?></b></a>
	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs"><?php echo $user->first_name; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
              <img src="<?php echo base_url().$image_profile_folder.(empty($user->profile_picture) ? 'GorillaXXX.jpg' : $user->profile_picture); ?>" class="img-circle" alt="User Image">
							<p>
                <?php echo $user->first_name.' '.$user->last_name; ?>
                <small>Oshop Information Technology Division</small>
                <?php
                  if (empty($user->profile_picture)) {
                ?>
                  <small>Upload your profile picture so it wont be Gorilla ;D</small>
                <?php
                  }
                ?>
              </p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<a href="panel/account" class="btn btn-default btn-flat">Account</a>
							</div>
							<div class="pull-right">
								<a href="panel/logout" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>