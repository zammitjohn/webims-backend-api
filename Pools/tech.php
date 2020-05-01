<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Buffer Pools</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../pools">Buffer Pools</a></li>
          <li class="breadcrumb-item active">pooltech.placeholder.text</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">

      <div class="container-fluid">
        <div class="row-sm-12">
          <div class="col-sm-12">
            <h4>pooltech.placeholder.text</h4>
          </div>
        </div>    
    
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pool 1</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
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
                <th>Description</th>
                <th>Ordered</th>
                <th>Stock</th>
                <th>Notes</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pool 2</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table2" class="table table-bordered table-striped">
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
                <th>Description</th>
                <th>Ordered</th>
                <th>Stock</th>
                <th>Notes</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pool 3</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table3" class="table table-bordered table-striped">
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
                <th>Description</th>
                <th>Ordered</th>
                <th>Stock</th>
                <th>Notes</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pool 4</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table4" class="table table-bordered table-striped">
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
                <th>Description</th>
                <th>Ordered</th>
                <th>Stock</th>
                <th>Notes</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pool 5</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table5" class="table table-bordered table-striped">
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
                <th>Description</th>
                <th>Ordered</th>
                <th>Stock</th>
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
  for (var $y = 1; $y <= 5; $y++) { // pool
    $('#table' + $y).DataTable({
        autoWidth: false,
        responsive: true,
        searching: false,
        ajax: {
            headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
            url: "../api/pools/read.php" + "?tech=" + (<?php echo $_GET['id']; ?>) + '&pool=' + $y,
            dataSrc: ''
        },
        columns: [
            { data: 'name' },
            { data: 'description' },
            { data: 'qtyOrdered' },
            { data: 'qtyStock' },
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
  }
});

//customize page according to type
var spareType;
if ((<?php echo $_GET['id']; ?>) == '1') {
    spareType = "GSM Pools";
  } else if ((<?php echo $_GET['id']; ?>) == '2') {
    spareType = "UMTS Pools";
  } else if ((<?php echo $_GET['id']; ?>) == '3') {
    spareType = "LTE Pools";
  } else {
    spareType = "undefined";
}
$("h4").html(spareType);
$("li.breadcrumb-item.active").html(spareType);

</script>