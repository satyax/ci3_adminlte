<div class="login-box">

	<div class="login-logo">
    <img class="pull-left" width="100px" height="100px" src="<?php echo base_url().$image_folder; ?>o-shop-logo.png" alt="View">
    <b><?php echo str_replace("Oshop - ","OShop ",$site_name); ?></b>
  </div>
  
	<div class="login-box-body">
		<p class="login-box-msg">Sign in to start your session</p>
		<?php echo $form->open(); ?>
			<?php echo $form->messages(); ?>
			<?php echo $form->bs3_text('Username', 'username', ENVIRONMENT==='development' ? 'webmaster' : ''); ?>
			<?php echo $form->bs3_password('Password', 'password', ENVIRONMENT==='development' ? 'webmaster' : ''); ?>
      <?php
        if ($captcha_config['enabled']) {
      ?>
          <div class="form-group">
            <div class="col-xs-6">
              <img id="imgcaptcha" name="imgcaptcha" src="<?php echo base_url().$captcha_config['img_url'].$captcha['filename'];  ?>" style="width: 150; height: 30; border: 0;" alt="captcha" />
            </div>
            <div class="col-xs-6">
              <button type="button" class="btn btn-primary btn-block btn-flat" onclick="refreshAdminCaptcha()" >Refresh Captcha</button>
            </div>
      <?php
            echo $form->bs3_text('Captcha', 'captcha', '');
      ?>
          </div>
      <?php
        }
      ?>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox">
						<label><input type="checkbox" name="remember"> Remember Me</label>
					</div>
				</div>
				<div class="col-xs-4">
					<?php echo $form->bs3_submit('Sign In', 'btn btn-primary btn-block btn-flat'); ?>
				</div>
			</div>
		<?php echo $form->close(); ?>
	</div>

</div>

<script type="text/javascript">
  function refreshAdminCaptcha() {
    $.get("<?php echo base_url(); ?>captcha/refreshAdminCaptcha", function(data, status){
      $('#imgcaptcha').attr("src",'<?php echo base_url(); ?>assets/captcha/' + data.filename);
    });
  }
</script>