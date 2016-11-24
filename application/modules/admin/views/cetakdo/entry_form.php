<?php 
  if ($message!='') {
?>
  <div class="alert alert-danger" role="alert"><p><?php echo $message; ?></p></div>
<?php
  }
?>

<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Print Invoice</h3>
      </div>
      
      <div class="box-body">
        <?php echo $form1->open(); ?>
        <?php echo $form1->bs3_text('Order Number', 'order_number', '300219492'); ?>
        <?php echo $form1->bs3_submit('Submit'); ?>
        <?php echo $form1->close(); ?>
      </div>
    </div> 
  </div>
</div>