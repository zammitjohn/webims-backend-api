<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Spares</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Spares</a></li>
          <li class="breadcrumb-item active">All spares</li>
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
          <h3 class="card-title">All spares</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Quantity</th>
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
  $('#table1').DataTable({
      autoWidth: false,
      responsive: true,
      ajax: {
          headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
          url: "../api/spares/read.php",
          dataSrc: ''
      },
      columns: [
          { data: 'name' },
          { data: 'type' },
          { data: 'description' },
          { data: 'qty' },
          { data: 'notes' }		
      ],
      columnDefs: [ 
        { targets: [0],
          "render": function (data, type, row, meta) {
          return '<a href="view.php?id=' + row.id + '">' + data + '</a>';
          }  
        },

        { targets: [1],
          "render": function (data, type, row, meta) {
          return '<a href="type.php?id=' + row.type + '">' + typeIdtoText(data) + '</a>';
          }  
        }
      ] 

  });
});

// Function to convert type ids to correspnding type to be shown in table
function typeIdtoText(id){
var spareType;
  if (id == '1') {
    spareType = "Common";
  } else if (id == '2') {
    spareType = "Radio Modules";
  } else if (id == '3') {
    spareType = "NSN Power";
  } else if (id == '4') {
    spareType = "Cable and Fibres";
  } else if (id == '5') {
    spareType = "SFPs";
  } else if (id == '6') {
    spareType = "GSM Equipment";
  } else if (id == '7') {
    spareType = "UMTS Equipment";
  } else if (id == '8') {
    spareType = "LTE Equipment";
  } else {
    spareType = "undefined";
  }
return spareType;
}

</script>