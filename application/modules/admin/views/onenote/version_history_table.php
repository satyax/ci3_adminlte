<table class="table table-striped table-hover">
 <tr>
   <th>Title</th>
   <th>content</th>
   <th>User Created</th>
   <th>Created Date</th>
   <th>Action</th>
 </tr>
 
  <?php
    foreach ($notes_version as $note) {
  ?>
      <tr>
        <td><?php echo $note->title; ?></td>
        <td><?php echo $wawan_lib->elipsis($note->content,50); ?></td>
        <td><?php echo $note->user_created; ?></td>
        <td><?php echo $note->created_at; ?></td>
        <td><a target="blank" href="<?php echo base_url().'admin/onenote/view_history/'.$note->id_notes; ?>"><img width="16px" height="16px" src="<?php echo base_url().$image_folder; ?>magnifying-glass.png" alt="View"></a></td>
      </tr>
  <?php } ?>
</table>