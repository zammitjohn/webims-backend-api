<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>RIMS</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="../dist/img/vf-logo-lg.png">

  <!-- Check session status before loading body -->
  <script>
  if (!localStorage.getItem('sessionId')){ 
    window.location.href = '../login.html';
  }
  </script>  
  
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
<body class="hold-transition sidebar-mini layout-fixed accent-danger">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a onclick="userFeedback()" class="nav-link" onmouseover="" style="cursor: pointer;">Send Feedback</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">uname.placeholder.text</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-xl">
          <i class="fas fa-cog mr-2"></i> Account Settings
        </a>
        <div class="dropdown-divider"></div>
        <a onclick="clearSession()" class="dropdown-item" onmouseover="" style="cursor: pointer;">
          <i class="fas fa-sign-out-alt mr-2"></i> Sign out
        </a>
      </div>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-danger">
    <!-- Brand Logo -->
    <a href="../" class="brand-link navbar-danger">
      <img src="../dist/img/vf-logo.png" alt="Vodafone" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><b>RIMS</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../dist/img/generic-user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" data-toggle="modal" data-target="#modal-xl">uname.placeholder.text</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
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
                <a href="/rims/inventory" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All items</p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="/rims/inventory/register.php" class="nav-link">
                  <i class="fas fa-edit nav-icon"></i>
                  <p>Register item</p>
                </a>
              </li>          
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

          <li class="nav-header">COLLECTIONS</li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Spares
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/rims/spares/create.php" class="nav-link">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All spares</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=1" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Common</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=2" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Radio Modules</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=3" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>NSN Power</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=4" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cables and Fibres</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=5" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SPFs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=6" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>GSM Equipment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=7" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>UMTS Equipment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/spares/type.php?id=8" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>LTE Equipment</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-clone"></i>
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
              <li class="nav-item">
                <a href="/rims/pools" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All pools</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/pools/tech.php?id=1" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>GSM Pools</p>
                </a>
              </li>
              </li>
              <li class="nav-item">
                <a href="/rims/pools/tech.php?id=2" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>UMTS Pools</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/rims/pools/tech.php?id=3" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>LTE Pools</p>
                </a>
              </li>
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

    <!-- account settings modal-->
    <div class="modal fade accent-danger" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Account Settings</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-password-tab" data-toggle="pill" href="#custom-content-below-password" role="tab" aria-controls="custom-content-below-password" aria-selected="false">Password</a>
                </li>
              </ul>
              <div class="tab-content" id="custom-content-below-tabContent">
                <div class="tab-pane fade active show" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                  
                  <div class="card-body">
                    <form role="form" id="profileForm">              
                      <div class="form-group">
                        <label for="input1">First name</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" placeholder="First name">
                      </div>
                      <div class="form-group">
                        <label for="input2">Last name</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Last name">
                      </div>
                      <div class="form-group">
                        <label for="input3">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                      </div>
                      <div class="row">
                        <div class="col-8">
                        </div>
                        <div class="col-4">
                          <button type="submit" class="btn btn-default btn-block">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>

                </div>

                <div class="tab-pane fade" id="custom-content-below-password" role="tabpanel" aria-labelledby="custom-content-below-password-tab">

                  <div class="card-body">
                    <form role="form" id="passwordForm">   
                      <div class="form-group">
                        <label for="input4">Current password</label>
                        <input type="password" name="password_current" class="form-control" id="password_current" placeholder="Current password">
                      </div>           
                      <div class="form-group">
                        <label for="input5">New password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="New password">
                      </div>
                      <div class="form-group">
                        <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Confirm new password">
                      </div>
                      <div class="row">
                        <div class="col-8">
                        </div>
                        <div class="col-4">
                          <button type="submit" class="btn btn-default btn-block">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>

                </div>
              </div>              

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-danger" onClick="deleteAccount()">Delete account</button>
              <button type="button" class="btn btn-default" onClick="window.location.reload()">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- page content -->
      <?php echo $content; ?>
      <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
  <strong>Developed by John Zammit.</strong> Copyright &copy; <?php echo date('Y'); ?> <a href="https://vodafone.com.mt">Vodafone Malta</a>.
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0 (Pre-release)
    </div>
  </footer>
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jquery-validation -->
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
</body>
</html>

<!-- User Feedback Script -->
<script>
function userFeedback() {
  url = "mailto:john.zammit@vodafone.com?subject=RIMS%20User%20Feedback&body=User%20Agent%3A%20" + navigator.userAgent + "%0D%0ACurrent%20Page%3A%20" + window.location.href + "%0D%0AUser%20ID%3A%20#" + (localStorage.getItem('id')) + "%0D%0ADescription%3A%20"
  window.open(url);
}
</script>

<!-- Populate uname.placeholder.text fields from localstorage -->
<script>
var name = (localStorage.getItem('firstname') + " " + localStorage.getItem('lastname'));
$("a.d-block").html(name);
$("span.dropdown-item.dropdown-header").html(name);
</script>

<!-- Validate sessionId -->
<script>
$.ajax({
  type: "GET",
  headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
  url: '../api/users/validate_session.php',
  dataType: 'json',
  error: function(data) {
      alert(data.responseText);
    },
  success: function(data) {
    if (data['valid'] == false) {
      clearSession();
    }
  }
});
</script>

<!-- Remove session info from localstorage -->
<script>
function clearSession(){
  localStorage.removeItem('id');
  localStorage.removeItem('firstname');
  localStorage.removeItem('lastname');
  localStorage.removeItem('email');
  localStorage.removeItem('sessionId');
  window.location.href = '../login.html';
}
</script>

<!-- Load modal content only when modal shown -->
<script>
$('#modal-xl').on('shown.bs.modal', function (e) {

// Populate user profile from local storage
$('#firstname').val(localStorage.getItem('firstname'));
$('#lastname').val(localStorage.getItem('lastname'));
$('#email').val(localStorage.getItem('email'));

$('#profileForm').validate({
  rules: {
    email: {
      required: true,
      email: true,
    }
  },
  messages: {
    email: {
      required: "Please enter a email address",
      email: "Please enter a vaild email address"
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
  },
  submitHandler: function(form) {
    $.ajax({
        type: "POST",
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: '../api/users/update_profile.php',
        dataType: 'json',
        data: {
          email: $("#email").val(),
          firstname: $("#firstname").val(),
          lastname: $("#lastname").val(),
          sessionId: (localStorage.getItem('sessionId'))
        },
        error: function(data) {
          alert(data.responseText);
        },
        success: function(data) {
          if (data['status'] == true) {
            alert(data['message']);

            // update local storage details!
            localStorage.setItem('firstname', data['firstname']);
            localStorage.setItem('lastname', data['lastname']);
            localStorage.setItem('email', data['email']);

          } else {
            alert(data['message']);
          }
        }
      });
    }
});

$('#passwordForm').validate({
  rules: {
    password_current: {
      required: true,
      minlength: 5
    },
    password: {
      required: true,
      minlength: 5,
      notEqualTo: "#password_current"
    },
    password_confirm: {
      equalTo: "#password"
    },
  },
  messages: {
    password: {
      required: "Please provide a password",
      minlength: "Your password must be at least 5 characters long"
    },
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
  },
  submitHandler: function(form) {
    $.ajax({
        type: "POST",
        url: '../api/users/update_password.php',
        dataType: 'json',
        data: {
          email: (localStorage.getItem('email')),
          password: $("#password_current").val(),
          password_new: $("#password").val(),
        },
        error: function(data) {
          alert(data.responseText);
        },
        success: function(data) {
          if (data['status'] == true) {
            alert(data['message']);
          } else {
            alert(data['message']);
          }
        }
      });
  }
});
})
</script>


<!-- Remove session info from localstorage -->
<script>
function deleteAccount(){
  var result = confirm("Are you sure you want to delete your account? Associated reports will not be deleted.");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '../api/users/delete.php',
      dataType: 'json',
      data: {
        id: (localStorage.getItem('id'))
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