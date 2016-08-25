jQuery(function($) {
  $("#field-pro_id").change(function() {
    var pro_id = $("#field-pro_id").val();
    $.ajax({
      method: "POST",
      url: "/admin/tech/Get_pro_technicians/index/",
      data: {
        id: pro_id,
      }
    })
      .done(function(data) {
        $('#pro-technicians').html(data);

      });

  });
})