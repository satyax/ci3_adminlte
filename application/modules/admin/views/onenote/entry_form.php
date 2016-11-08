<?php
  if (!(isset($id_notes) && !empty($id_notes))) { $id_notes = ''; }
  if (!isset($view_history)) { $view_history = true; }
  if (!isset($versionHistoryTable)) { $versionHistoryTable = ''; }
  if (!isset($categories)) { $categories = ''; }
  if (!isset($sub_categories)) { $sub_categories = ''; }
  
  $unique_keys = $title = $content = $is_deleted = $id_notes_parent = $user_created = $created_at = $user_edited = $updated_at = $id_categories = $id_sub_categories = '';
  if (isset($notes) AND !empty($notes)) {
    $unique_keys = $notes->unique_keys;
    $title = $notes->title;
    $content = $notes->content;
    $is_deleted = $notes->is_deleted;
    $id_notes_parent = $notes->id_notes_parent;
    $user_created = $notes->user_created;
    $created_at = $notes->created_at;
    $user_edited = $notes->user_edited;
    $updated_at = $notes->updated_at;
    $id_categories = $notes->id_categories;
    $id_sub_categories = $notes->id_sub_categories;
    
    if (isset($versionHistory) AND !empty($versionHistory)) { $versionHistory = ''; }
  }
?>

<div class="container-fluid">
  <div class="row godpad">
    <form role="form" method="post" enctype="multipart/form-data" action="onenote/save" >      
      <input type="hidden" id="id_notes" name="id_notes" value="<?php echo $id_notes; ?>" />
      <input type="hidden" id="unique_keys" name="unique_keys" value="<?php echo $unique_keys; ?>" />
      <input type="hidden" id="is_deleted" name="is_deleted" value="<?php echo $is_deleted; ?>" />
      <input type="hidden" id="id_notes_parent" name="id_notes_parent" value="<?php echo $id_notes_parent; ?>" />
      
      <legend>Notes Entry</legend>
      
      <div class="col-md-6">
        <label>Created by:</label>
        <input type="text" class="form-control" id="user_created" name="user_created" value="<?php echo $user_created; ?>" disabled/>
        <label>Created date:</label>
        <input type="text" class="form-control" id="created_at" name="created_at" value="<?php echo $created_at; ?>" disabled/>
      </div>
      <div class="col-md-6">        
        <input type="hidden" class="form-control" id="user_edited" name="user_edited" value="<?php echo $user_edited; ?>" disabled/>
        <div class="control-group">
          <label for="id_categories">Category:</label>
          <select id="id_categories" name="id_categories" placeholder="Select a category...">
            <option value="">Select a category...</option>
            <?php foreach($categories as $cat) { ?>
              <option value="<?php echo $cat->id_categories;?>" <?php echo ($cat->id_categories==$id_categories ? 'selected' : ''); ?> ><?php echo $cat->category; ?></option>
            <?php } ?>
          </select>
          <label for="id_sub_categories">Sub-category:</label>
          <select id="id_sub_categories" name="id_sub_categories" placeholder="Select a sub-category...">
            <?php
              if ($sub_categories != '') {
                foreach ($sub_categories as $sub_cat) {
            ?>
              <option value="<?php echo $sub_cat->id_sub_categories;?>" <?php echo ($sub_cat->id_sub_categories==$notes->id_sub_categories ? 'selected' : ''); ?> ><?php echo $sub_cat->sub_category; ?></option>
            <?php } } ?>
          </select>
        </div>        
      </div>
  
      <div class="col-md-12">      
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" />
        </div>
                
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
          <li><a data-toggle="tab" href="#files">Files</a></li>
          <?php if ($view_history) { ?>
            <li><a data-toggle="tab" href="#versionHistory">Version History</a></li>
          <?php } ?>
        </ul>
        
        <div class="tab-content">
        
          <div id="description" class="tab-pane fade in active">
            <div class="form-group">
              <label for="descriptions">Content</label>
              <textarea class="form-control" id="content" name="content"><?php echo $content; ?></textarea>          
            </div>
          </div>
          
          <div id="files" class="tab-pane fade">
          
            <?php if ($view_history) { ?>
              <div class="form-group">
                <h1>Upload new files</h1>
                <label for="file_name">Choose file to upload</label>
                <input type="file" name="file_name_input[]" multiple>
              </div>
            <?php } ?>
            
            <div class="form-group">
              <h1>Uploaded Files</h1>
              
              <?php
                if (isset($notes_files) && !empty($notes_files)) {
                  foreach ($notes_files as $file) {        
              ?>
                <div class="row">
                  <div class="col-md-12">
                    <a href="#myModal" data-toggle="modal" data-target="#edit-modal" data-filename="<?php echo $file->file_name_original; ?>" id="<?php echo $file->id_notes_files; ?>" onclick="confirmDelFile(<?php echo $file->id_notes_files; ?>)"><img width="16px" height="16px" src="<?php echo base_url().$image_folder; ?>delete-icon.png" alt="Delete this file"></a>
                    <a href="<?php echo base_url().$file->url; ?>" target="_blank"><?php echo $file->file_name_original; ?></a>
                  </div>
                </div>
              <?php
                  }
                }
              ?>
                        
            </div>
          </div>
          
          <?php if ($view_history) { ?>
            <div id="versionHistory" class="tab-pane fade">
              <?php echo $versionHistoryTable; ?>
            </div>
          <?php } ?>
        </div>
        
        <?php if ($view_history) { ?>
          <div class="form-group">
            <button type="submit" class="btn btn-success">Save</button>
            <?php 
              if (isset($notes_files) && !empty($notes_files)) {
                if ($allow_permanent_delete) { 
            ?>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#edit-modal" onclick="konfirmasiDeleteOneNote(<?php echo $id_notes; ?>);">Permanent Delete</button>
            <?php } ?>
          </div>
        <?php } } ?>
        
      </div>
  </form>
</div>