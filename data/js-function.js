function filterGlobal (stxt) {
    $('#taskstable').DataTable().search(stxt).draw();
}
var sttable = '';
 $(document).ready(function() {
    var hi = ($(window).height() - 200) + "px";
    $('.ScrollStyle').css('max-height',hi);
    $('#login').click(function(e) {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=login',
            data: {
                uid: $("#uid").val(),
                password: $("#password").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {

                    location.reload();
                }
                else {
                    alert('Invalid Credentials');
                }
            }
        });

    });

      $('#showalltask').click(function(e) {
            $('.sasmtr').toggle("fast");
      });

    $('#btn_add_mrig').click(function(e) {
        $.ajax({
            type: "POST",
            url: '/data/mrig.php?fname=add_mrig',
            data: {
                aname: $("#add_new_mrig_aname").val(),
                oid: $("#add_new_mrig_oid").val(),
                type: $("#t_type").val(),
                pcode: $("#add_new_mrig_pcode").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {

                     /*new PNotify({
                        title: 'Success!',
                        text: 'Task added successfully.',
                        type: 'success'
                    });*/
                    showError('Task added successfully.','success', true);
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });

    });

    //task page open
        $(document).on('click', '[id="loadtask"]', function(e) {
     //$("[id='loadtask']").click(function(e) {
        e.preventDefault();
        location.hash = $(this).data("pcode");
        var ugroup = $(this).data("ugroup");
        var url = "";
        Cookies.set('cpcode', $(this).data("pcode"));

        if(ugroup == '1'){
            url = "/data/task?pcode=" + $(this).data("pcode");
            Cookies.set('cpage', 'task');
        }else if(ugroup == '2'){
            url = "/data/rig?pcode=" + $(this).data("pcode");
            Cookies.set('cpage', 'rig');
        }

        if (Cookies.get('dev') == 1) {
          console.log(url);
        }
        $.get(url, function(data){
            $("#page").html(data);
        })
            if (sttable != "") {
                sttable.destroy();
            }
            setTimeout(function(){
            sttable = $('#taskstable').DataTable({
                scrollY:        '50vh',
                scrollCollapse: true,
                paging:         false,
                stateSave: true,
                language: {
                    searchPlaceholder: 'Search records',
                    sSearch: '',
                    sLengthMenu: 'Show _MENU_',
                    sLength: 'dataTables_length',
                    oPaginate: {
                        sFirst: '<i class="material-icons">chevron_left</i>',
                        sPrevious: '<i class="material-icons">chevron_left</i>',
                        sNext: '<i class="material-icons">chevron_right</i>',
                        sLast: '<i class="material-icons">chevron_right</i>'
                    }
                }
            });
            $('.dataTables_length select').addClass('browser-default');

        }, 500);
    });

});

$j(document).ready(function() {
    function j_loadtask(pcode, pname){
        location.hash = pcode;
        var url = "/data/"+ pname +"?pcode=" + pcode;
        $.get(url, function(data){
            $("#page").html(data);
        })
        if (sttable != "") {
                sttable.destroy();
        }
        setTimeout(function(){


           sttable = $('#taskstable').DataTable({
                scrollY:        '150vh',
                scrollCollapse: true,
                paging:         false,
                stateSave: true,
                language: {
                    searchPlaceholder: 'Search records',
                    sSearch: '',
                    sLengthMenu: 'Show _MENU_',
                    sLength: 'dataTables_length',
                    oPaginate: {
                        sFirst: '<i class="material-icons">chevron_left</i>',
                        sPrevious: '<i class="material-icons">chevron_left</i>',
                        sNext: '<i class="material-icons">chevron_right</i>',
                        sLast: '<i class="material-icons">chevron_right</i>'
                }
                }
            });
            $('.dataTables_length select').addClass('browser-default');
        }, 500);
     }
     $(document).ready(function(){
        $(".loadlater").each(function(index, element){
            $(element).attr("src", $(element).attr("data-src"));
        });
    });
     function j_loadpage(pname){
        var url = "/data/page?pname=" + pname;
        $.get(url, function(data){
            $("#page").html(data);
        })
     }

    function loadpage(page){
        var url = "/data/page?pname=" + page;
        Cookies.set('cpage', page);
        Cookies.set('cpcode', '');
        $.get(url, function(data){
            $("#page").html(data);
        })
     }

    function updateui(){
        if(Cookies.get('cpcode') == ''){
            loadpage(Cookies.get('cpage'));
        }else {
            j_loadtask(Cookies.get('cpcode'), Cookies.get('cpage'));
        }
        $.getJSON('http://sas.com:8082/api/jobs?States=2',function(jd){
            $("#ded_suspe").html(jd.length);
        });
        $.getJSON('http://sas.com:8082/api/jobs?States=1',function(jd){
            $("#ded_acti").html(jd.length);
        });
        $.getJSON('http://sas.com:8082/api/jobs?States=4',function(jd){
            $("#ded_error").html(jd.length);
        });
     }

     function updateMenu() {
       $.get("/data/functions?fname=dynmenu", function(data){
         $("#dhynmenu").html(data);

       });

     }

    setInterval(function(){
        $j.get("/data/functions?fname=isupdate", function(data){
            if (Cookies.get('dev') == 1) {
                console.info(data);
            }
            if (data != "") {
              data = jQuery.parseJSON(data);
            }
            if (data.code == '1') {
             updateui();
            }
            if (data.menu == '1') {
             updateMenu();
            }

            if (data.full == '1') {
              location.reload(true);
            }
        })

    }, 2500);



     function send_dnotify(data){
        host = "192.168.23.160-192.168.23.159";
        var url = "/cgi/snotify.py?host=" + host + "&data=" + data;
        $.get(url, function(data){
          if (Cookies.get('dev') == 1) {
            console.log(data);
          }
        })
     }

     loadpage("dashboard");

     $j(document).on('click', '#pageload', function() {
        loadpage($(this).data('page'));
     })


    $j.getJSON('http://sas.com:8082/api/jobs?States=2', function(jd) {
        if (Cookies.get('dev') == 1) {
            console.info(JSON.parse(jd));
        }
        $("#ded_suspe").html(jd.length);
    });
    $j.getJSON('http://sas.com:8082/api/jobs?States=1', function(jd) {
        $("#ded_acti").html(jd.length);
    });
    $j.getJSON('http://sas.com:8082/api/jobs?States=4', function(jd) {
        $("#ded_error").html(jd.length);
    });


    $j(document).on('click', '#t_add_task', function() {
        //$("#add_task").modal('open');
        var ta_pname = "Add new Task for " + $(this).data("pname");
        $('#add_new_task_pname').html(ta_pname);
        $('#add_new_task_pcode').val($(this).data("pcode"));
        $('#add_task').modal().modal('open');

        //$j('#textmodel').modal('open');
     })

    $('#btn_add_task').click(function(e) {
        var fs = $("#t_auser").data('select-id');
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=add_task',
            data: {
                sname: $("#add_new_task_sname").val(),
                sframe: $("#add_new_task_sframe").val(),
                auser: $("#t_auser").val(),
                pcode: $("#add_new_task_pcode").val(),
                correc: $("add_new_task_correction").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {

                     /*new PNotify({
                        title: 'Success!',
                        text: 'Task added successfully.',
                        type: 'success'
                    });*/
                    showError('Task added successfully.','success', true);
                    updateui();
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });

    });

    $j(document).on('click', '#t_add_mrig', function() {
        //$("#add_task").modal('open');
        var ta_pname = "Add new Task for " + $(this).data("pname");
        $('#add_new_task_pname').html(ta_pname);
        $('#add_new_mrig_pcode').val($(this).data("pcode"));
        $('#add_mrig').modal().modal('open');

        //$j('#textmodel').modal('open');
     })

    $j(document).on('click', '#table_search', function() {
        filterGlobal($(this).data("search"));
     })

    $j(document).on('click', '#bshotsupdate', function() {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=updatebshot',
            data: {
                pcode: $(this).data("pcode")
            },
            success: function(data)
            {
                $('#task_modal_c').html(data);
                $('#task_modal_footer').hide();
                $('#task_modal').modal().modal('open');

            }
        });

        //$j('#textmodel').modal('open');
     })



    $j(document).on('click', '#task_btn_log', function() {
        var url = '/data/functions.php?fname=getlog&pcode=' + $(this).data("pcode") + '&sname=' + $(this).data("sname");
        $.get(url, function(data){
            $('#task_modal_c').html(data);
        })
     })

    $j(document).on('click', '#showhistory', function() {
        var url = '/data/functions.php?fname=getlog&pcode=' + $(this).data("pcode") + '&sname=' + $(this).data("sname");
        $.get(url, function(data){
            $('#task_modal_c').html(data);
            $('#task_modal_footer').hide();
            $('#task_modal').modal().modal('open');
        })
     })

    $j(document).on('click', '#edit_task', function() {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=taskedit',
            data: {
                sname: $(this).data("sid"),
                pcode: $(this).data("pcode")
            },
            success: function(data)
            {
                $('#task_modal_c').html(data);
                $('#task_modal_footer').hide();
                $('#task_modal').modal().modal('open');

            }
        });

        //$j('#textmodel').modal('open');
     })

     $j(document).on('mouseenter', '.current-task', function() {
        var psname = $(this).data(psname);
        projectData.sname = psname.psname;
      })



    $j(document).on('click', '#edit_mrig', function() {
        $.ajax({
            type: "POST",
            url: '/data/mrig.php?fname=mrigedit',
            data: {
                sname: $(this).data("sid"),
                pcode: $(this).data("pcode")
            },
            success: function(data)
            {
                $('#task_modal_c').html(data);
                $('#task_modal_footer').hide();
                $('#task_modal').modal().modal('open');

            }
        });

        //$j('#textmodel').modal('open');
     })


    $j(document).on('click', '#add_project', function() {
        var url = '/data/functions.php?fname=addprojectui';
        $.get(url, function(data){
            $('#task_modal_c').html(data);
            $('#task_modal_footer').hide();
            $('#task_modal').modal().modal('open');
        })
     })


    $j(document).on('click', '#sproject_odby', function() {
        var url = "/data/page?pname=sprojects&odby=" + $(this).data("name");
        Cookies.set('cpage', 'sprojects');
        Cookies.set('cpcode', '');
        $.get(url, function(data){
            $("#page").html(data);
        })

     })



    $j(document).on('click', '#scan-new-shots', function() {
        var url = "cgi/checknewshots-beta.py?pcode=" + Cookies.get('cpcode') + "&host=" + Cookies.get('host');
        if (Cookies.get('dev') == 1) {
          console.log(url);
        }
        $("#new-shots-output").html('<div class="progress"><div class="indeterminate"></div></div>');
        $.get(url, function(data){
            $("#new-shots-output").html(data);
        })

     })




    $j(document).on('click', '#updatesidebar', function() {
        console.log("ok");

     })


      $j(document).on('click', '#syncpath', function() {
        $('#task_modal').modal().modal('close');
        var url = "cgi/syncfolder-handel.py?host=" + $(this).data("host") + "&pdiskn=" + $(this).data("pdiskn");
        $.get(url, function(data){
          if (Cookies.get('dev') == 1) {
            console.log(data);
          }
        })

     })

    $j(document).on('click', '#story_add', function() {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=addproject',
            data: {
                pname: $("#story_name").val(),
                pcode: $("#story_code").val(),
                ptype: $("#story_type").val(),
                pno: $("#story_no").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {
                    $('#task_modal').modal().modal('close');
                    var url = "cgi/storyfolder.py?pno=" + $("#story_no").val() + "&pname=" + $("#story_name").val() + "&ptype=" + $("#story_type").val();
                    $.get(url, function(data){
                        /*(new PNotify({
                            title: 'Success!',
                            text: 'Story Add successfully.',
                            type: 'success'
                        });*/
                        showError('Story Add successfully.','success', true);
                        updateui();
                    });


                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });
    })





    $j(document).on('click', '#fpath', function() {
        $('#task_modal').modal().modal('close');
        var url = "cgi/folder-handel.py?host=" + $(this).data("host") + "&pdiskn=" + $(this).data("pdiskn") + "&psname=" + $(this).data("psname") + "&t=rf";
        $.get(url, function(data){
          if (Cookies.get('dev') == 1) {
            console.log(data);
          }
        })

     })


    $j(document).on('blur', '#updateriginfo', function() {
        $.ajax({
            type: "POST",
            url: '/data/mrig.php?fname=mrigupdateinfo',
            data: {
                id: $(this).data("id"),
                info: $(this).val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {
                    updateui();
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });

     })


    $j(document).on('click', '#tab_btn', function() {
        var tab = $(this).data("tab");
        $("#tab_info").hide();
        $("#tab_correction").hide();
        $("#tab_bgsb").hide();
        $("#tab_animc").hide();
        var ntab = "#tab_" + tab;
        $(ntab).show();
    })



    $j(document).on('click', '#task_btn_update', function() {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=taskupdate',
            data: {
                id: $("#task_uid").val(),
                sname: $("#task_sname").val(),
                sframe:$("#task_sframe").val(),
                status:$("#task_status").val(),
                auser:$("#task_auser").val(),
                correction:$("#task_correction").val(),
                c_status:$("#task_c_status").val(),
                cdoneby:$("#task_cdoneby").val(),
                pcode:$("#task_pcode").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                data = JSON.parse(data);
                if (data.status === 1) {
                    $('#task_modal').modal().modal('close');
                    /*new PNotify({
                        title: 'Success!',
                        text: 'Task updated successfully.',
                        type: 'success'
                    });*/
                    showError('Task updated successfully.','success', true);
                    //swal("Good job!", "Task updated successfully", "success");
                    var data = "Shot no. " + $("#task_sname").val() + " update successfully. \n Story code : " + $("#task_pcode").val();
                    send_dnotify(data);
                    updateui();
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });
    })

    $j(document).on('click', '#mrig_update', function() {
        $.ajax({
            type: "POST",
            url: '/data/mrig.php?fname=mrigupdate',
            data: {
                id: $("#mrig_uid").val(),
                sname: $("#mrig_sname").val(),
                soid:$("#mrig_oid").val(),
                pcode:$("#mrig_pcode").val()
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {
                    $('#task_modal').modal().modal('close');
                    /*new PNotify({
                        title: 'Success!',
                        text: 'Ch/Assest updated successfully.',
                        type: 'success'
                    });*/
                    showError('Ch/Assest updated successfully.','success', true);
                    //swal("Good job!", "Task updated successfully", "success");
                    var data = "Shot no. " + $("#task_sname").val() + " update successfully. \n Story code : " + $("#task_pcode").val();
                    //send_dnotify(data);
                    updateui();
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });
    })


    $j(document).on('click', '#btn_delete', function() {
        $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=deletedata',
            data: {
                id: $(this).data("id"),
                tabel: $(this).data("tabel"),
                pcode:$(this).data("pcode")
            },
            success: function(data)
            {
                if (Cookies.get('dev') == 1) {
                  console.log(data);
                }
                if (data === '1') {
                    $('#task_modal').modal().modal('close');
                    new PNotify({
                        title: 'Success!',
                        text: 'Ch/Assest removed successfully.',
                        type: 'success'
                    });
                    //swal("Good job!", "Task updated successfully", "success");
                    var data = "Shot no. " + $("#task_sname").val() + " update successfully. \n Story code : " + $("#task_pcode").val();
                    //send_dnotify(data);
                    updateui();
                }
                else {
                    swal("Error !", "System error occurred!! please try again after sometime", "error");
                }
            }
        });
    })


    $j(document).on('click', '#update_password', function() {
        $('#task_modal_c').html('<form action="data/functions?fname=upasswprd" class="col s12"><div class="row"><div class="input-field col s12"><input id="o_password" class="validate" type="password"><label for="o_password" class="">Old Password</label></div></div><div class="row"><div class="input-field col s12"><input id="n_password" class="validate" type="password"><label for="password" class="">New Password</label></div></div><div class="row"><div class="input-field col s12"><input id="rn_password" class="validate" type="password"><label for="password" class="">Re-New Password</label></div></div><div class="row"><div class="input-field col s12"><input id="u_upass" type="submit" class="waves-effect waves-light btn" value="Update"></div></div></form>');
        $('#task_modal_footer').hide();
        $('#task_modal').modal().modal('open');
     })

    $j(document).on('click', '#proupdate', function() {

        $.ajax({
        type: "POST",
        url: '/data/functions.php?fname=proupdate',
        data: {
            ugroup: $(this).data("ugroup"),
            pcode: $(this).data("pcode"),
            colname: $(this).data("colname"),
            colvalue:$(this).data("colvalue"),
            aname:$(this).data("aname")
        },
        success: function(data)
        {
            if (Cookies.get('dev') == 1) {
              console.log(data);
            }
            data = JSON.parse(data);
            if (data.status === 1) {
                updateui();
            }
            else {
                new PNotify({
                        title: 'Errror !',
                        text: 'System error occurred!! please try again after sometime',
                        type: 'error'
                });
            }
        }
    });

     })


        $j(document).on('click', '#corupdate', function() {
            $.ajax({
            type: "POST",
            url: '/data/functions.php?fname=corupdate',
            data: {
                pcode: $(this).data("pcode"),
                sname:$(this).data("sname")
            },
            success: function(data)
            {

                if (data === '1') {
                    updateui();

                }
                else {
                    if (Cookies.get('dev') == 1) {
                      console.log(data);
                    }
                    new PNotify({
                            title: 'Errror !',
                            text: 'System error occurred!! please try again after sometime',
                            type: 'error'
                    });
                }
            }
        });

     })

    $j(document).on('click', '#u_upass', function(e) {
        e.preventDefault();
        if($('#n_password').val() == $('#rn_password').val()){
                 $.ajax({
                    type: "POST",
                    url: '/data/functions.php?fname=upass',
                    data: {
                        o_pass: $("#o_password").val(),
                        n_pass: $("#n_password").val()
                    },
                    success: function(data)
                    {
                        if (data === '1') {
                            $('#task_modal').modal().modal('close');
                            swal("Good job!", "Password updated successfully", "success");
                        }
                        else {
                            swal("Error !", "System error occurred!! please try again after sometime", "error");
                        }
                    }
                });
        } else {

            swal("Error !", "Password not matched", "error");
        }

    })



     $j(document).on('click', '#dashlogsloadmore', function() {
        var olddashlogs = $("#dashlogs").html();
        var logloadmoreby = $("#logloadmoreby").val();
        var url = "/data/functions?fname=dashlogsloadmore&from=" + logloadmoreby;
        $.get(url, function(data){
            var newdlogs = olddashlogs + data;
            $("#dashlogs").html(newdlogs);
            var newloadmoreby = Number($("#logloadmoreby").val())+10;
            $("#logloadmoreby").val(newloadmoreby);
        })
     })


});
