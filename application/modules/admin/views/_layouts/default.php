<div class="wrapper">

	<?php $this->load->view('_partials/navbar'); ?>

	<?php // Left side column. contains the logo and sidebar ?>
	<aside class="main-sidebar">
		<section class="sidebar">  
      
			<div class="user-panel" style="height:65px">
        <div class="pull-left image">
          <img style="height:50px;width:50px" src="<?php echo base_url().$image_profile_folder.(empty($user->profile_picture) ? 'GorillaXXX.jpg' : $user->profile_picture); ?>" class="img-circle" alt="User Image">
        </div>
				<div class="pull-left info">
					<p><?php echo $user->first_name; ?></p>
					<a href="panel/account"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>
			<?php // (Optional) Add Search box here ?>
			<?php //$this->load->view('_partials/sidemenu_search'); ?>
			<?php $this->load->view('_partials/sidemenu'); ?>
		</section>
	</aside>

	<?php // Right side column. Contains the navbar and content of the page ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?php echo $page_title; ?></h1>
			<?php $this->load->view('_partials/breadcrumb'); ?>
		</section>
		<section class="content">
			<?php $this->load->view($inner_view); ?>
			<?php $this->load->view('_partials/back_btn'); ?>
		</section>
	</div>

	<?php // Footer ?>
	<footer class="main-footer">
		<?php if (ENVIRONMENT=='development'): ?>
			<div class="pull-right hidden-xs">
				CI Bootstrap Version: <strong><?php echo CI_BOOTSTRAP_VERSION; ?></strong>, 
				CI Version: <strong><?php echo CI_VERSION; ?></strong>, 
				Elapsed Time: <strong>{elapsed_time}</strong> seconds, 
				Memory Usage: <strong>{memory_usage}</strong>
			</div>
		<?php endif; ?>
		<strong>&copy; <?php echo date('Y'); ?> <a href="#"><?php //echo COMPANY_NAME; ?></a></strong> All rights reserved.
	</footer>

</div>

<div id="loadingDiv" style="display: none;position: fixed;top: 0px;right: 0px;width: 100%;height: 100%;background-color: #666;background-image: url('ajax-loader.gif');background-repeat: no-repeat;background-position: center;z-index: 10000000;opacity: 0.4;filter: alpha(opacity=40);">
  <div>
    <img src="<?php echo base_url().$image_folder; ?>loading.gif" style="position:absolute;left:45%;top:40%;">
  </div>
</div>

<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body edit-content">
            ...
        </div>
        <div class="modal-footer">
          <form id="frm" role="form" method="post" action="">
            <input type="hidden" id="key" name="key" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btSubmit">Proceed</button>
          </form>
        </div>
    </div>
  </div>
</div>