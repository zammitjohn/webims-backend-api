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
          <li class="breadcrumb-item"><a href="../spares">Spares</a></li>
          <li class="breadcrumb-item active">sparetype.placeholder.text</li>
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
          <h3 class="card-title">sparetype.placeholder.text</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
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
          url: "../api/spares/read.php" + "?type=" + <?php echo $_GET['id']; ?>,
          dataSrc: ''
      },
      columns: [
          { data: 'name' },
          { data: 'description' },
          { data: 'qty' },
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

//customize page according to type
var spareType;
if ((<?php echo $_GET['id']; ?>) == '1') {
    spareType = "Common";
  } else if ((<?php echo $_GET['id']; ?>) == '2') {
    spareType = "Radio Modules";
  } else if ((<?php echo $_GET['id']; ?>) == '3') {
    spareType = "NSN Power";
  } else if ((<?php echo $_GET['id']; ?>) == '4') {
    spareType = "Cable and Fibres";
  } else if ((<?php echo $_GET['id']; ?>) == '5') {
    spareType = "SFPs";
  } else if ((<?php echo $_GET['id']; ?>) == '6') {
    spareType = "GSM Equipment";
  } else if ((<?php echo $_GET['id']; ?>) == '7') {
    spareType = "UMTS Equipment";
  } else if ((<?php echo $_GET['id']; ?>) == '8') {
    spareType = "LTE Equipment";
  } else {
    spareType = "undefined";
}
$("h3.card-title").html(spareType);
$("li.breadcrumb-item.active").html(spareType);

</script>