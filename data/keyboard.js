
$(document).bind('keydown', 'f1', function assets() {
	console.log(projectData);


	var ta_pname = "Add new Task for " + projectData.pName;
	$('#add_new_task_pname').html(projectData.pName);
	$('#add_new_task_pcode').val(projectData.pCode);
	$('#add_task').modal().modal('open');
});

/*
$(document).bind('keydown', 'space', function assets() {
	console.log(projectData);
  $.ajax({
      type: "POST",
      url: '/data/functions.php?fname=taskedit',
      data: {
          sname: projectData.sname,
          pcode: projectData.pCode
      },
      success: function(data)
      {
          $('#task_modal_c').html(data);
          $('#task_modal_footer').hide();
          $('#task_modal').modal().modal('open');

      }
  });
});
*/
