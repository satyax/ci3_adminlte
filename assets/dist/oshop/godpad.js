var i = 6;

function isOdd(num) { return num % 2;}

function addFormGodPad() {
  $.ajax({
    type: "POST",
    data: { i: i+1 },
    url: base_url + 'admin/godpad/newGodPad',
    beforeSend: function(e){
      $('#loadingDiv').show();
    },
    success:function(data) {
      if (isOdd(i+1)) {
        $('#godpads').append(data);
      } else {
        $('#godpads .godpad').last().append(data);
      }
      i++;
      $('html,body').animate({ scrollTop: 9999 }, 'slow');
      $('#loadingDiv').hide();
    }
  }).done(function() {
    $('#loadingDiv').hide();
  });
}