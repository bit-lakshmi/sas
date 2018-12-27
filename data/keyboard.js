
$(document).bind('keydown', 'ctrl+alt+n', function assets() {
	console.log(projectData);


	var ta_pname = "Add new Task for " + projectData.pName;
	$('#add_new_task_pname').html(projectData.pName);
	$('#add_new_task_pcode').val(projectData.pCode);
	$('#add_task').modal().modal('open');
});


//render folder
$(document).bind('keydown', 'ctrl+alt+r', function assets() {
	var url = "cgi/folder-handel.py?host=" + Cookies.get('host') + "&pdiskn=" + projectData.pDiskName + "&psname=" + projectData.sname + "&t=rf";
	$.get(url, function(data){
		if (Cookies.get('dev') == 1) {
			console.log(data);
		}
	})
});


//scene folder
$(document).bind('keydown', 'ctrl+alt+s', function assets() {
	var url = "cgi/folder-handel.py?host=" + Cookies.get('host') + "&pdiskn=" + projectData.pDiskName + "&psname=" + projectData.sname + "&t=sf";
	$.get(url, function(data){
		if (Cookies.get('dev') == 1) {
			console.log(data);
		}
	})
});

//final folder
$(document).bind('keydown', 'ctrl+alt+f', function assets() {
	var url = "cgi/folder-handel.py?host=" + Cookies.get('host') + "&pdiskn=" + projectData.pDiskName + "&psname=" + projectData.sname + "&t=ff";
	$.get(url, function(data){
		if (Cookies.get('dev') == 1) {
			console.log(data);
		}
	})
});


//bg folder
$(document).bind('keydown', 'ctrl+alt+b', function assets() {
	var url = "cgi/folder-handel.py?host=" + Cookies.get('host') + "&pdiskn=" + projectData.pDiskName + "&psname=" + projectData.sname + "&t=bf";
	$.get(url, function(data){
		if (Cookies.get('dev') == 1) {
			console.log(data);
		}
	})
});


//sync folder
$(document).bind('keydown', 'ctrl+alt+c', function assets() {
	var url = "cgi/folder-handel.py?host=" + Cookies.get('host') + "&pdiskn=" + projectData.pDiskName + "&psname=" + projectData.sname + "&t=syf";
	$.get(url, function(data){
		if (Cookies.get('dev') == 1) {
			console.log(data);
		}
	})
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
