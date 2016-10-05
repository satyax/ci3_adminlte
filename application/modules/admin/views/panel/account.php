<?php echo $form1->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Account Info</h3>
			</div>
			<div class="box-body">
				<?php echo $form1->open(); ?>
					<?php echo $form1->bs3_text('First Name', 'first_name', $user->first_name); ?>
					<?php echo $form1->bs3_text('Last Name', 'last_name', $user->last_name); ?>
          
          <?php 
            if (isset($user->profile_picture) AND !empty($user->profile_picture)) {
          ?>
            <img width="250px" height="250px" src="<?php echo base_url().$image_profile_folder.$user->profile_picture; ?>" class="img-circle" alt="User Image">
          <?php 
            }
          ?>
          
          <div class="form-group">
            <label for="file_name">Upload new profile pricture</label>
            <input type="file" name="profile_picture" multiple>
          </div>
          
					<?php echo $form1->bs3_submit('Update'); ?>
				<?php echo $form1->close(); ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Change Password</h3>
			</div>
			<div class="box-body">
				<?php echo $form2->open(); ?>
					<?php echo $form2->bs3_password('New Password', 'new_password'); ?>
					<?php echo $form2->bs3_password('Retype Password', 'retype_password'); ?>
					<?php echo $form2->bs3_submit(); ?>
				<?php echo $form2->close(); ?>
			</div>
		</div>
	</div>
	
</div>