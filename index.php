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


    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <div class="col-md-8">
     
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">My Reports</h3>

            <div class="card-tools">
            <span class="badge badge-danger" id="report_count"></span>
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="table1" table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Report ID</th>
                    <th>Name</th>
                  </tr>
                </thead>
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
      </div>
      <!-- /.col -->

      <div class="col-md-4">

            <!-- USERS LIST -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Users</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>
                </div>
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
    url: "api/reports/read.php" + "?requestedBy=" + localStorage.getItem('userid'),
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var element in data) {
        reportcount++;
        tableData += "<tr>" +
          "<td>" + "<a href='reports/view.php?id=" + data[element].id + "'> #" + data[element].id + "</a></td>" +
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
  

});

</script>