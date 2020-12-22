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
            <a href="reports/create.php" class="btn btn-sm btn-info float-left">New Report</a>
            <a href="reports" class="btn btn-sm btn-secondary float-right">View All Reports</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">My Collections</h3>
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
            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-newcollection">New Collection</button> 
            <a href="collections/create.php" class="btn btn-sm btn-info">Add Item</a>           
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

    <!-- modal-newcollection start -->
    <div class="modal fade" id="modal-newcollection">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Collection</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- form start -->
        <form role="form" id="new_collection">
          <div class="modal-body">
            <input type="text" class="form-control" id="collection_name" maxlength="20" placeholder="Enter collection name">
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
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "api/reports/read.php" + "?userId=" + localStorage.getItem('userId'),
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var element in data) {
        reportcount++;
        tableData += "<tr>" +
          "<td>" + "<a href='reports/view.php?id=" + data[element].id + "' class='text-muted'><i class='fas fa-search'></i> #" + data[element].id +  "</a></td>" +
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
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "api/users/read.php",
    dataType: 'json',
    success: function(data) {
      for (var element in data) {
        usercount++;
        if (usercount > 8) break; // limit to 8 users only
        userlistdata += '<li><img src="dist/img/generic-user.png" alt="User Image"><a class="users-list-name" href="#">' + data[element].firstname + ' ' + data[element].lastname + '</a><span class="users-list-date">' + moment(data[element].lastLogin, "YYYY-MM-DD, h:mm:ss").fromNow() + '</span></li>';
      }
      // append userlistdata to users pane
      $("#user_list").append(userlistdata);
    }
  });
  
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "api/collections/types/read.php" + "?userId=" + localStorage.getItem('userId'),
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var element in data) {
        tableData += "<tr>" +
          "<td>" + "<a href='collections/type.php?id=" + data[element].id + "' class='text-muted'>" + data[element].name +  "</a></td>" +
          "</tr>";
      }
      $(tableData).appendTo($("#table2"));
    }
  });  

});

$('#new_collection').on("submit", function(e){
  $('#modal-newcollection').modal('toggle'); // hide modal
  e.preventDefault(); //form will not submitted
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "api/collections/types/create.php",
    dataType: 'json',
    data: {
      name: $("#collection_name").val(),
      userId: localStorage.getItem('userId')
    },
    success: function(result) {
      if (result.status == false) {
        alert(result.message);
      }
      location.reload();
    }
  });

});  

</script>