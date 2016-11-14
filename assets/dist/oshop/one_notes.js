function konfirmasiDeleteOneNote(id_notes) {
  $('#edit-modal').on('show.bs.modal', function(e) {
    var $modal = $(this);
    //var id_notes = e.relatedTarget.dataset.idNotes;
    $modal.find('.edit-content').html('Are you sure you want to delete this note?<br>CAUTION!!! this will delete note permanently along with its version history<br>and CANNOT BE UNDONE!!!');
    $modal.find('#key').val(id_notes);
    $modal.find('#frm').attr('action', 'onenote/del_notes');
  });
  
}

function confirmDelFile(id_notes_files) {
  
  $('#edit-modal').on('show.bs.modal', function(e) {
    var $modal = $(this);
    var id_notes_files = e.relatedTarget.id;
    var filename = e.relatedTarget.dataset.filename;
    $modal.find('.edit-content').html('Are you sure you want to delete file: "'+filename+'"<br>This will just create new version of this notes WITHOUT the deleted file<br>The file it self is not deleted, but rather kept in the version history');
    $modal.find('#key').val(id_notes_files);
    $modal.find('#frm').attr('action', 'onenote/del_file');
  });

}

$(document).ready(function(){
  tinymce.init({ 
    selector:'textarea',
    height: '500px'
  });
  
  //selectize category dan sub-category
  var xhr;
  var id_categories, $id_categories;
  var id_sub_categories, $id_sub_categories;

  $id_categories = $('#id_categories').selectize({
    onChange: function(value) {
      if (!value.length) return;
      id_sub_categories.disable();
      id_sub_categories.clearOptions();
      id_sub_categories.load(function(callback) {
        xhr && xhr.abort();
        xhr = $.ajax({
          url: base_url+'admin/onenote/get_sub_categories/'+value,
          data: { id_categories: value },
          dataType: 'json',
          success: function(results) {
            id_sub_categories.enable();
            callback(results);
          },
          error: function() {
            callback();
          }
        })
      });
    }
  });

  $id_sub_categories = $('#id_sub_categories').selectize({
    valueField: 'id_sub_categories',
    labelField: 'sub_category',
    searchField: ['sub_category']
  });

  id_sub_categories  = $id_sub_categories[0].selectize;
  id_categories = $id_categories[0].selectize;

  if ($( "#id_sub_categories option:selected" ).text() == '') { id_sub_categories.disable(); }
});