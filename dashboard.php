<?php
include 'data/db_config.php';
if(!isset($_SESSION['login'])) {
    include 'login.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <!-- Title -->
        <title><?php echo constant('SITE_NAME') ?> | Dashboard</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="assets/css/icon.css" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="assets/css/alpha.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/pnotify.custom.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            var projectData = {};
        </script>
        <style type="text/css">
        <?php
        if ($_SESSION['user_data']['ugroup'] == 1){
          echo '.sascomp{display: block !important;}';
        }elseif ($_SESSION['user_data']['ugroup'] == 2) {
          echo '.sasmtr{display: block !important;}';
        }
         ?>
         </style>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body id="body" style="background-image: url(/assets/images/limgs/<?php echo(rand(1,8)); ?>.jpg);background-position: center center;background-repeat: no-repeat;background-size: cover;background-color: #fff;">
        <?php
        if (isdev()) { ?>
            <div style="background-color: orangered;color: white;position: fixed;z-index: 9999;padding: 10px;margin-left: 47.5%">
                <a href="../dashboard?dev=0" style="color: white;cursor: pointer;">Devloper Mode</a>
            </div>
        <?php } ?>
        <input id="cpage" type="hidden">
        <input id="cpcode" type="hidden">

        <div class="loader-bg"></div>
        <div class="loader" >
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-spinner-teal lighten-1">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mn-content fixed-sidebar">
            <header class="mn-header navbar-fixed" style="opacity: 0.65;">
                <nav class="cyan darken-1">
                    <div class="nav-wrapper row">
                        <section class="material-design-hamburger navigation-toggle">
                            <a href="#" data-activates="slide-out" class="button-collapse show-on-large material-design-hamburger__icon">
                                <span class="material-design-hamburger__layer"></span>
                            </a>
                        </section>
                        <div class="header-title col s2">
                            <span class="chapter-title"><?php echo constant('SITE_NAME') ?></span>
                        </div>
                        <div class="left col s6">
                          <a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Projects" id="pageload" data-page="sprojects" href="javascript: void(0)"><i class="material-icons">assessment</i></a>
                        </div>
                        <!--div class="left col s6">
                        <?php
                        /*$last_project = $_SESSION['user_data']['last_project'];
                        $result = $conn->query("SELECT pcode, pname FROM projects WHERE pcode='{$last_project}'");
                        if ($result->rowCount() > 0) {
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    printf("<a class='dropdown-button btn' href='#' data-activates='dropdown2'>%s</a>", $row['pname']);
                                }
                        }

                        ?>

                        <!-- Dropdown Structure -->
                        <ul id='dropdown2' class='dropdown-content'>
                            <?php
                                $result = $conn->query("SELECT * FROM projects");
                                if ($result->rowCount() > 0) {
                                $x = 0;
                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    printf('<li><a href="#!">%s</a></li>', $row['pname']);
                                    echo '<li class="divider"></li>';
                                    $x += 1;
                                }
                            }


                            */
                            ?>
                        </ul>
                        </div-->
                        <?php if ($_SESSION['user_data']['ugroup'] == "1") { ?>
                        <ul class="right col s9 m3 nav-right-menu">
                            <li><a id="updatesidebar" href="javascript:void(0)" data-activates="chat-sidebar" class="chat-button show-on-large"><i class="material-icons">more_vert</i></a></li>
                            <li class="hide-on-small-and-down"><div id="ded_suspe" class="chip grey" style="color: white;cursor: default;"></div></li>
                            <li class="hide-on-small-and-down"><div id="ded_acti" class="chip green" style="color: white;cursor: default;"></div></li>
                            <li class="hide-on-small-and-down"><div id="ded_error" class="chip red" style="color: white;cursor: default;"></div></li>
                        </ul>
                        <?php } ?>
                    </div>
                </nav>
            </header>
            <aside id="chat-sidebar" class="side-nav white">
                <div class="side-nav-wrapper">
                    <div class="col s12">
                        <ul class="tabs tab-demo" style="width: 100%;">
                            <li class="tab col s3"><a href="#sidebar-chat-tab" class="active">New Shots</a></li>
                            <!--li class="tab col s3"><a href="#sidebar-more-tab">settings</a></li-->
                        </ul>
                    </div>

                    <div id="sidebar-chat-tab" class="col s12 sidebar-messages right-sidebar-panel">
                        <p class="right-sidebar-heading"><a id="scan-new-shots" class="waves-effect waves-light btn">SCAN</a></p>
                        <div id="new-shots-output"></div>
                    </div>

                    <div id="sidebar-more-tab" class="col s12 sidebar-more right-sidebar-panel">
                        <p class="right-sidebar-heading">SYSTEM</p>
                        <div class="settings-list">
                            <div class="setting-item">
                                <div class="setting-text">Notifications</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox" checked>
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Quick Results</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox" checked>
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Auto Updates</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="right-sidebar-heading">ACCOUNT</p>
                        <div class="settings-list">
                            <div class="setting-item">
                                <div class="setting-text">Offline Mode</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Location</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Show Offline Users</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-text">Save History</div>
                                <div class="setting-set">
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <aside id="chat-messages" class="side-nav white">
                <p class="sidebar-chat-name">Tom Simpson<a href="#" data-activates="chat-messages" class="chat-message-link"><i class="material-icons">keyboard_arrow_right</i></a></p>
                <div class="messages-container">
                    <div class="message-wrapper them">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-1.png" class="circle" alt=""></div>
                        <div class="text-wrapper">Lorem Ipsum</div>
                    </div>
                    <div class="message-wrapper me">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-3.jpg" class="circle" alt=""></div>
                        <div class="text-wrapper">Integer in faucibus diam?</div>
                    </div>
                    <div class="message-wrapper them">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-1.png" class="circle" alt=""></div>
                        <div class="text-wrapper">Vivamus quis neque volutpat, hendrerit justo vitae, suscipit dui</div>
                    </div>
                    <div class="message-wrapper me">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-3.jpg" class="circle" alt=""></div>
                        <div class="text-wrapper">Suspendisse condimentum tortor et lorem pretium</div>
                    </div>
                    <div class="message-wrapper them">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-1.png" class="circle" alt=""></div>
                        <div class="text-wrapper">dolore eu fugiat nulla pariatur</div>
                    </div>
                    <div class="message-wrapper me">
                        <div class="circle-wrapper"><img src="assets/images/profile-image-3.jpg" class="circle" alt=""></div>
                        <div class="text-wrapper">Duis maximus leo eget massa porta</div>
                    </div>
                </div>
                <div class="message-compose-box">
                    <div class="input-field">
                        <input placeholder="Write message" id="message_compose" type="text">
                    </div>
                </div>
            </aside>
            <aside id="slide-out" class="side-nav white fixed" style="opacity: 0.8;">
                <div class="side-nav-wrapper">
                    <div class="sidebar-profile">
                        <div class="sidebar-profile-image">
                            <img src="assets/images/profile-image.png" class="circle" alt="">
                        </div>
                        <div class="sidebar-profile-info">
                            <a href="javascript:void(0);" class="account-settings-link">
                                <p><?php echo $_SESSION['user_data']['dname'] ?> <i class="material-icons right">arrow_drop_down</i></p>
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-account-settings">
                        <ul>
                            <!--li class="no-padding">
                                <a class="waves-effect waves-grey"><i class="material-icons">mail_outline</i>Inbox</a>
                            </li>
                            <li class="no-padding">
                                <a class="waves-effect waves-grey"><i class="material-icons">star_border</i>Starred<span class="new badge">18</span></a>
                            </li>
                            <li class="no-padding">
                                <a class="waves-effect waves-grey"><i class="material-icons">done</i>Sent Mail</a>
                            </li-->
                            <li class="no-padding">
                                <a id="update_password" class="waves-effect waves-grey modal-trigger"><i class="material-icons">vpn_key</i>Change Password</a>
                            </li>
                            <li class="divider"></li>
                            <li class="no-padding">
                                <a href="/data/functions?fname=logout" class="waves-effect waves-grey"><i class="material-icons">exit_to_app</i>Sign Out</a>
                            </li>
                        </ul>
                    </div>
                <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
                    <li class="no-padding"><a class="waves-effect waves-grey" id="pageload" data-page="dashboard" href="javascript: void(0)"><i class="material-icons">settings_input_svideo</i>Dashboard</a></li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i>Tasks<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                            <?php
                                if ($_SESSION['user_data']['ugroup'] == 1 || $_SESSION['user_data']['access'] == 0) {
                                    $result = $conn->query("SELECT pcode, pname FROM projects WHERE status = 1 ORDER BY priority DESC");
                                    if ($result->rowCount() > 0) {
                                        $x = 0;
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            printf(' <li class="sasmenu sascomp"><a id="loadtask" data-pcode="%s" data-ugroup="%s" href="#!">%s</a></li>',$row['pcode'],$_SESSION['user_data']['ugroup'], $row['pname']);
                                            $x += 1;
                                        }
                                    }
                                }
                                if ($_SESSION['user_data']['ugroup'] == 2 || $_SESSION['user_data']['access'] == 0) {
                                   $result = $conn->query("SELECT pcode, pname FROM projects WHERE anim = 0 OR mtr = 0");
                                    if ($result->rowCount() > 0) {
                                      $x = 0;
                                      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                          printf(' <li class="sasmenu sasmtr"><a id="loadtask" data-pcode="%s" data-ugroup="%s" href="#!">%s</a></li>',$row['pcode'],'2', $row['pname']);
                                          $x += 1;
                                      }
                                  }
                              }



                            ?>
                            </ul>
                        </div>
                    </li>
                    <?php

                    if ($_SESSION['user_data']['access'] == 0) {
                       echo '<li class="no-padding"><a class="waves-effect waves-grey" id="showalltask" href="javascript: void(0)"><i class="material-icons">list</i>Show All</a></li>';
                    }


                    ?>
                    <!--li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">code</i>Components<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="ui-accordions.html">Accordion</a></li>
                                <li><a href="ui-badges.html">Badges</a></li>
                                <li><a href="ui-buttons.html">Buttons</a></li>
                                <li><a href="ui-typography.html">Typography</a></li>
                                <li><a href="ui-cards.html">Cards</a></li>
                                <li><a href="ui-carousel.html">Carousel</a></li>
                                <li><a href="ui-chips.html">Chips</a></li>
                                <li><a href="ui-color.html">Color</a></li>
                                <li><a href="ui-collections.html">Collections</a></li>
                                <li><a href="ui-dropdown.html">Dropdown</a></li>
                                <li><a href="ui-dialogs.html">Dialogs</a></li>
                                <li><a href="ui-grid.html">Grid</a></li>
                                <li><a href="ui-helpers.html">Helpers</a></li>
                                <li><a href="ui-modals.html">Modals</a></li>
                                <li><a href="ui-media.html">Media</a></li>
                                <li><a href="ui-icons.html">Icons</a></li>
                                <li><a href="ui-parallax.html">Parallax</a></li>
                                <li><a href="ui-preloader.html">Preloader</a></li>
                                <li><a href="ui-shadow.html">Shadow</a></li>
                                <li><a href="ui-tabs.html">Tabs</a></li>
                                <li><a href="ui-waves.html">Waves</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">star_border</i>Plugins<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="miscellaneous-sweetalert.html">Sweet Alert</a>
                                <li><a href="miscellaneous-code-editor.html">Code Editor</a>
                                <li><a href="miscellaneous-nestable.html">Nestable List</a>
                                <li><a href="miscellaneous-masonry.html">Masonry</a>
                                <li><a href="miscellaneous-idle-timer.html">Idle Timer</a>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding active">
                        <a class="collapsible-header waves-effect waves-grey active"><i class="material-icons">desktop_windows</i>Layouts<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="layout-blank.html" class="active-page">Blank Page</a></li>
                                <li><a href="layout-boxed.html">Boxed Layout</a></li>
                                <li><a href="layout-hidden-sidebar.html">Hidden Sidebar</a></li>
                                <li><a href="layout-right-sidebar.html">Right Sidebar</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">mode_edit</i>Forms<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="form-elements.html">Form Elements</a></li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                                <li><a href="form-upload.html">File Upload</a></li>
                                <li><a href="form-image-crop.html">Image Crop</a></li>
                                <li><a href="form-image-zoom.html">Image Zoom</a></li>
                                <li><a href="form-input-mask.html">Input Mask</a></li>
                                <li><a href="form-select2.html">Select2</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">grid_on</i>Tables<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="table-static.html">Static Tables</a></li>
                                <li><a href="table-responsive.html">Responsive Tables</a></li>
                                <li><a href="table-comparison.html">Comparison Table</a></li>
                                <li><a href="table-data.html">Data Tables</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding"><a class="waves-effect waves-grey" href="charts.html"><i class="material-icons">trending_up</i>Charts</a></li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">my_location</i>Maps<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="maps-google.html">Google Maps</a></li>
                                <li><a href="maps-vector.html">Vector Maps</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">tag_faces</i>Extra Pages<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="404.html">404 Page</a></li>
                                <li><a href="500.html">500 Page</a></li>
                                <li><a href="invoice.html">Invoice</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                                <li><a href="sign-in.html">Sign In</a></li>
                                <li><a href="sign-up.html">Sign Up</a></li>
                                <li><a href="lock-screen.html">Lock Screen</a></li>
                                <li><a href="pattern-lock-screen.html">Pattern Lock Screen</a></li>
                                <li><a href="forgot.html">Forgot Password</a></li>
                                <li><a href="pricing-tables.html">Pricing Tables</a></li>
                                <li><a href="contact.html">Contact</a></li>
                                <li><a href="gallery.html">Gallery</a></li>
                                <li><a href="timeline.html">Timeline</a></li>
                                <li><a href="calendar.html">Calendar</a></li>
                                <li><a href="coming-soon.html">Coming Soon</a></li>
                            </ul>
                        </div>
                    </li-->
                </ul>
                <div class="footer">
                    <p class="copyright">Solarts Studio ©</p>
                    <a href="#!">Devloped by Amit Maurya</a>
                </div>
                </div>
            </aside>
            <main class="mn-inner" style="opacity: 0.9;">
                <div id="page" class="row">

                </div>
            </main>
        </div>
        <div class="left-sidebar-hover"></div>

        <!-- Add Task Modal Structure -->
        <div id="add_task" class="modal">
            <div class="modal-content">
                <div class="card">
                    <div class="card-content">
                        <span id="add_new_task_pname" class="card-title">Add new Task</span><br>
                        <div class="row">
                            <form class="col s12">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="add_new_task_sname" class="validate" type="text">
                                        <label for="sname" class="active">Short No.</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="add_new_task_sframe" class="validate" type="text">
                                        <label for="sframes">No. of Frames</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select id="t_auser" >
                                            <option  value="">Choose your option</option>
                                            <?php
                                            $sgroup = $_SESSION['user_data']['ugroup'];
                                            foreach ($U_DATA as $key => $u_value) {
                                                if ($u_value['ugroup'] == $sgroup) {
                                                    printf("<option value='%s'>%s</option>", $u_value['id'], $u_value['dname']);
                                                }
                                            }

                                            ?>
                                        </select>
                                        <label>Assigned to</label>
                                    </div>
                                </div>
                                <input id="add_new_task_pcode" type="hidden" name="pcode">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Task Correction</span><br>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="add_new_task_correction" class="materialize-textarea" length="120"></textarea>
                                <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
            <div class="modal-footer">
                <a href="#!" id="btn_add_task" class="modal-action modal-close waves-effect waves-green btn-flat ">Add</a>
            </div>
        </div>

        <div id="add_mrig" class="modal">
            <div class="modal-content">
                <div class="card">
                    <div class="card-content">
                        <span id="add_new_task_pname" class="card-title">Add New Ch/Assest to Story</span><br>
                        <div class="row">
                            <form class="col s12">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="add_new_mrig_aname" class="validate" type="text">
                                        <label for="aname" class="active">Name</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="t_type" >
                                            <option  value="">Choose your option</option>
                                            <option  value="1">Character</option>
                                            <option  value="2">Assest</option>
                                        </select>
                                        <label>Type</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="add_new_mrig_oid" class="validate" type="text">
                                        <label for="aname" class="active">OID</label>
                                    </div>
                                    <div class="input-field col s6">
                                    </div>
                                </div>
                                <input id="add_new_mrig_pcode" type="hidden" name="pcode">
                            </form>
                        </div>
                    </div>
                </div>
        </div>
            <div class="modal-footer">
                <a href="#!" id="btn_add_mrig" class="modal-action modal-close waves-effect waves-green btn-flat ">Add</a>
            </div>
        </div>

        <div id="task_modal" class="modal">
            <div id="task_modal_c" class="modal-content">
            </div>
            <div id="task_modal_footer" class="modal-footer">
                <a id="task_modal_btn" href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Update</a>
            </div>
        </div>


        <div id="change_password" class="modal">
            <div class="modal-content">
                <h4>Modal Header</h4>
                <p>A bunch of text</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
            </div>
        </div>






        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
        <script>var $j = jQuery.noConflict(true);</script>
        <!--script>
          $(document).ready(function(){
           console.log($().jquery); // This prints v1.4.2
           console.log($j().jquery); // This prints v1.9.1
          });
       </script-->
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/js/alpha.js"></script>
        <script src="assets/js/Cookies.js"></script>
        <script src="assets/js/jquery.hotkeys.js"></script>
        <script src="assets/js/pnotify.custom.min.js"></script>
        <script type="text/javascript" src="data/js-function.js"></script>
        <script type="text/javascript" src="data/keyboard.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

    </body>
</html>
