<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Fault Reports</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Fault Reports</a></li>
          <li class="breadcrumb-item active">All reports</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">All reports</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Report ID</th>
                <th>Ticket#</th>
                <th>Name</th>                
                <th>Fault Report#</th>
                <th>Date Requested</th>
                <th>Date Leaving</th>
                <th>Date Dispatched</th>
                <th>Date Returned</th>
                <th>AWB</th>
                <th>AWB Returned</th>
                <th>RMA</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th>Report ID</th>
                <th>Ticket#</th>
                <th>Name</th>                
                <th>Fault Report#</th>
                <th>Date Requested</th>
                <th>Date Leaving</th>
                <th>Date Dispatched</th>
                <th>Date Returned</th>
                <th>AWB</th>
                <th>AWB Returned</th>
                <th>RMA</th>
                <th>Notes</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
';
include('../master.php');
?>

<!-- page script -->
<script>
$(function () {
  $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
  $('#table1').DataTable({
      autoWidth: false,
      responsive: true,
      ajax: {
          headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
          url: "../api/reports/read.php",
          dataSrc: ''
      },
      columns: [
          { data: 'id' },
          { data: 'ticketNo' },
          { data: 'name' },
          { data: 'reportNo' },
          { data: 'dateRequested' },
          { data: 'dateLeavingRBS' },
          { data: 'dateDispatched' },
          { data: 'dateReturned' },
          { data: 'AWB' },
          { data: 'AWBreturn' },
          { data: 'RMA' },
          { data: 'notes' }
      ],
      columnDefs: [ 
        { targets: [0],
          "render": function (data, type, row, meta) {
          return '<a href="view.php?id=' + row.id + '">' + data + '</a>';
          }  
        }
      ]
  });
});
</script>