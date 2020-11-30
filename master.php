<?php
// include database and object files
include_once 'api/config/database.php';
include_once 'api/objects/inventory_categories.php';
include_once 'api/objects/inventory_types.php';
include_once 'api/objects/collections_types.php';
include_once 'api/objects/pools_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?php echo "RIMS | ". $title; ?></title>
  <!-- pace-progress -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/pace-progress/themes/yellow/pace-theme-minimal.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/toastr/toastr.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $ROOT; ?>dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo $ROOT; ?>dist/img/logo.png">  
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed accent-orange">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="https://github.com/zammitjohn/RIMS" class="nav-link" target="_blank"><i class="fab fa-github"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"></span>
        <div class="dropdown-divider"></div>
        <a onclick="clearSession()" class="dropdown-item" onmouseover="" style="cursor: pointer;">
          <i class="fas fa-sign-out-alt mr-2"></i> Log out
        </a>
        <div class="dropdown-divider"></div>
        <a onclick="deleteAccount()" class="dropdown-item" onmouseover="" style="cursor: pointer;">
          <i class="fas fa-trash-alt mr-2"></i> Delete Account
        </a>
      </div>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-light-warning">
    <!-- Brand Logo -->
    <a href="<?php echo $ROOT; ?>" class="brand-link navbar-warning">
      <img src="<?php echo $ROOT; ?>dist/img/logo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>RIMS</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $ROOT; ?>dist/img/generic-user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo $ROOT; ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Inventory
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/rims/inventory/create.php" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/inventory/register.php" class="nav-link">
                  <i class="fas fa-edit nav-icon"></i>
                  <p>Register item</p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="/rims/inventory" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All items</p>
                </a>
              </li>
              <?php
              ## Building inventory sidebar tree
              $inventory_category_object = new Inventory_Categories($db);
              $inventory_types_object = new Inventory_Types($db);

              $inventory_category_stmt = $inventory_category_object->read();
              if ($inventory_category_stmt != false){
                while ($inventory_category_row = $inventory_category_stmt->fetch(PDO::FETCH_ASSOC)){ // first loop categories
                  extract($inventory_category_row);
                  echo '<li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="far fa-dot-circle nav-icon"></i><p>' . $name .  '<i class="right fas fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="/rims/inventory/category.php?id=' . $id . '" class="nav-link"><i class="fas fa-circle nav-icon"></i><p>All items</p></a></li></ul>';
                  
                  $inventory_types_object->category = $id;
                  $inventory_types_stmt = $inventory_types_object->read();
                  while ($inventory_types_row = $inventory_types_stmt->fetch(PDO::FETCH_ASSOC)){ // ...then loop types
                    extract($inventory_types_row);
                    $type_name = $name;
                    $type_id = $id;
                    echo '<ul class="nav nav-treeview"><li class="nav-item"><a href="/rims/inventory/type.php?id=' . $type_id . '" class="nav-link"><i class="far fa-circle nav-icon"></i><p>' . $type_name . '</p></a></li></ul>';
                  }
                  echo '</li>';
                }
              }
              ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Fault Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/rims/reports/create.php" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>New report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/reports" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All reports</p>
                </a>
              </li>
            </ul>
          </li>          

          <li class="nav-header">PROJECTS</li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Collections
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/rims/collections/create.php" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add item</p>
                </a>
              </li>
              <?php
              ## Building collections sidebar tree
              $collections_types_object = new Collections_Types($db);
              $collections_types_stmt = $collections_types_object->read();

              if ($collections_types_stmt != false){
                while ($collections_types_row = $collections_types_stmt->fetch(PDO::FETCH_ASSOC)){
                  extract($collections_types_row);
                  echo '<li class="nav-item"> <a href="/rims/collections/type.php?id=' . $id . '" class="nav-link"><i class="far fa-circle nav-icon"></i><p>' . $name . '</p></a></li>';
                }
              }
              ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Buffer Pools
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/rims/pools/create.php" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add item</p>
                </a>
              </li>
              <?php
              ## Building pools sidebar tree
              $pools_types_object = new Pools_Types($db);
              $pools_types_stmt = $pools_types_object->read();

              if ($pools_types_stmt != false){
                while ($pools_types_row = $pools_types_stmt->fetch(PDO::FETCH_ASSOC)){
                  extract($pools_types_row);
                  echo '<li class="nav-item"> <a href="/rims/pools/type.php?id=' . $id . '" class="nav-link"><i class="far fa-circle nav-icon"></i><p>' . $name . '</p></a></li>';
                }
              }
              ?>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    
      <!-- page content ->
      <?php echo $content; ?>
      <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <!-- Login Modal -->
  <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow-y: hidden">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <p class="login-box-msg">Log in using your corporate account</p>
          <!-- form start -->
          <form role="form" id="loginForm">
            <div class="form-group">
              <input type="username" name="username" class="form-control" id="username" placeholder="Username">
            </div>
            <div class="form-group">
              <input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="on">
            </div>
          
            <div class="row">
              <p class="login-box-msg">
                <small><b>Your credentials are not stored on RIMS.</b> Verification of credentials is performed using <a href="https://en.wikipedia.org/wiki/Lightweight_Directory_Access_Protocol" target="_blank">LDAP</a> authentication.</small>
              </p>
              <div class="col-6 mx-auto">
                <button type="submit" class="btn btn-default btn-block">Log in</button>
              </div>
            </div>
          </form>
          <!-- /.form -->
        </div>
      </div>
    </div>
  </div>
  <!-- /.login-modal -->

  <!-- Main Footer -->
  <footer class="main-footer">
  <strong>Developed by <a href="https://zammitjohn.com">John Zammit</a>.</strong> Copyright &copy; <?php echo date('Y'); ?>.
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 2.1.0
    </div>
  </footer>
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?php echo $ROOT; ?>plugins/jquery/jquery.min.js"></script>
<!-- jquery-validation -->
<script src="<?php echo $ROOT; ?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $ROOT; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $ROOT; ?>dist/js/adminlte.min.js"></script>
<!-- pace-progress -->
<script src="<?php echo $ROOT; ?>plugins/pace-progress/pace.min.js"></script>
<!-- DataTables -->
<script src="<?php echo $ROOT; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $ROOT; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr -->
<script src="<?php echo $ROOT; ?>plugins/toastr/toastr.min.js"></script>
<!-- Moment -->
<script src="<?php echo $ROOT; ?>plugins/moment/moment.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $ROOT; ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
</body>
</html>

<script>
$(document).ready(function() {
  // Validate session characteristics
  $.ajax({
  type: "GET",
  cache: false, // due to aggressive caching on IE 11
  headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
  url: '<?php echo $ROOT; ?>api/users/validate_session.php',
  dataType: 'json',
  success: function(data) {
    if (data['status'] == false) {
      //alert(data.message);
      $("a.d-block").html("Log in"); // change text
      $(".card").addClass("collapsed-card"); // hide card content
      $('#modal-login').modal('toggle'); // toggle modal login
    } else {
      // populate name text fields
      var name = (data['firstname'] + " " + data['lastname']);
      $("a.d-block").html(name);
      $("span.dropdown-item.dropdown-header").html(name);

      // ...and disable/hide buttons accordingly
      if (data['canUpdate'] == false) {
        $(".button_action_update").prop("disabled",true);
      }
      if (data['canCreate'] == false) {
        $(".button_action_create").prop("disabled",true);
      }
      if (data['canImport'] == false) {
        $(".button_action_import").hide();
      }
      if (data['canDelete'] == false) {
        $(".button_action_delete").prop("disabled",true);
      }

    }
  }
  });

  // login form validation
  $.validator.setDefaults({
    submitHandler: function () {
      $.ajax({
        type: "POST",
        cache: false, // due to aggressive caching on IE 11
        url: '<?php echo $ROOT; ?>api/users/login.php',
        dataType: 'json',
        data: {
          username: $("#username").val(),
          password: $("#password").val()
        },
        error: function(data) {
          alert(data['message']);
        },
        success: function(data) {
          if (data['status'] == true) {
            localStorage.setItem('userId', data['id']);
            localStorage.setItem('sessionId', data['sessionId']);
            location.reload();
          } else {
            alert(data['message']);
            location.reload();
          }
        }
      });
    }
  });
  $('#loginForm').validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
      }
    },
    messages: {
      username: {
        required: "Please enter a username",
      },
      password: {
        required: "Please provide a password",
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

});
</script>

<!-- Remove session info from localstorage -->
<script>
function clearSession(){
  localStorage.removeItem('userId');
  localStorage.removeItem('sessionId');
  location.reload();
}
</script>

<!-- Remove session info from localstorage -->
<script>
function deleteAccount(){
  var result = confirm("Are you sure you want to delete your account? Associated reports will not be deleted.");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '<?php echo $ROOT; ?>api/users/delete.php',
      dataType: 'json',
      data: {
        id: (localStorage.getItem('userId'))
      },
      error: function(data) {
        alert(data.responseText);
      },
      success: function(data) {
        if (data['status'] == true) {
          alert(data['message']);
          clearSession();
        } else {
          alert(data['message']);
        }
      }
    });
  }
}
</script>