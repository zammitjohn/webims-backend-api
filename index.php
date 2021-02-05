<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div id="user_permission_alert" class="alert alert-warning" style="display:none;">
      <h5><i class="icon fas fa-lock"></i>Resticted access</h5>
      Full permissions are not enabled for this account. Additional permissions require admin approval.
    </div>  

    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <div class="col-md-8">
     
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">My Pending Reports</h3>
            <div class="card-tools">
            <span class="badge badge-danger" id="report_count"></span>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="table1" table class="table table-hover text-nowrap">
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <a href="reports/create" class="btn btn-sm btn-info float-left">New Report</a>
            <a href="reports" class="btn btn-sm btn-secondary float-right">View All Reports</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">My Projects</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="table2" table class="table table-hover text-nowrap">
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-newproject">New Project</button> 
            <a href="projects/create" class="btn btn-sm btn-info">Add Item</a>           
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

      </div>
      <!-- /.col -->
      <div class="col-md-4">

            <!-- USERS LIST -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="users-list clearfix" id="user_list">
                </ul>
                <!-- /.users-list -->
              </div>
              <!-- /.card-body -->
            </div>
            <!--/.card -->

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- modal-newproject start -->
    <div class="modal fade" id="modal-newproject">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Project</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- form start -->
        <form role="form" id="new_project">
          <div class="modal-body">
            <input type="text" class="form-control" id="project_name" maxlength="20" placeholder="Enter project name">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary button_action_create">Submit</button>
          </div>
        </form>
        <!-- /.form -->
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  </div><!--/. container-fluid -->
</section>
<!-- /.content -->
';
$title = "Dashboard";
$ROOT = '';
include('master.php');
?>

<script>
$(document).ready(function() {
  // load reports table
  reportcount = 0;
  $.ajax({
    type: "GET",
    cache: false,
    url: "api/reports/read_myreports",
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var element in data) {
        reportcount++;
        tableData += "<tr>" +
          "<td>" + "<a href='reports/view?id=" + data[element].id + "' class='text-muted'><i class='fas fa-search'></i> #" + data[element].id +  "</a></td>" +
          "<td>" + data[element].name + "</td>" +
          "</tr>";
      }
      $(tableData).appendTo($("#table1"));
      $("#report_count").append(reportcount + " report(s)");
    }
  }); 

  // load users pane
  usercount = 0;
  userlistdata = "";
  $.ajax({
    type: "GET",
    cache: false,
    url: "api/users/read",
    dataType: 'json',
    success: function(data) {
      for (var element in data) {
        usercount++;
        if (usercount > 8) break; // limit to 8 users only
        userlistdata += '<li><img src="dist/img/generic-user.png" alt="User Image"><a class="users-list-name" href="#">' + data[element].firstname + ' ' + data[element].lastname + '</a><span class="users-list-date">' + moment(data[element].lastAvailable, "YYYY-MM-DD, h:mm:ss").fromNow() + '</span></li>';
      }
      // append userlistdata to users pane
      $("#user_list").append(userlistdata);
    }
  });
  
  $.ajax({
    type: "GET",
    cache: false,
    url: "api/projects/types/read_myprojects",
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var element in data) {
        tableData += "<tr>" +
          "<td>" + "<a href='projects?id=" + data[element].id + "' class='text-muted'>" + data[element].name +  "</a></td>" +
          "</tr>";
      }
      $(tableData).appendTo($("#table2"));
    }
  });  
});

$('#new_project').on("submit", function(e){
  $('#modal-newproject').modal('toggle'); // hide modal
  e.preventDefault(); //form will not submitted
  $.ajax({
    type: "POST",
    url: "api/projects/types/create",
    dataType: 'json',
    data: {
      name: $("#project_name").val()
    },
    success: function(result) {
      if (result.status == false) {
        alert(result.message);
      } else {
        location.reload();
      }
    }
  });
});  
</script>