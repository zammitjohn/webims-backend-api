<?php
## Page specific code
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/pools_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare collections type property object
$pools_types_object = new Pools_Types($db);
$pools_types_object->id = $_GET['id'];

$stmt = $pools_types_object->read();
$pool_name = 'Unknown Pool';
$table_div = '';

if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $pool_name = ($row['name']);
  
  for ($i = 1; $i <= ($row['qty']); $i++) {
    $table_div .= '
    
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pool ' . $i . '</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-bordered table-hover" id="table_' . $i . '">
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Ordered</th>
              <th>Stock</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->';
    
  }
}

## Content goes here
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
          <li class="breadcrumb-item">Buffer Pools</li>
          <li class="breadcrumb-item active">' . $pool_name . '</li>
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
          <h4>' . $pool_name . '</h4>
        </div>
      </div>
    </div>     
    <div id=table_div>' . $table_div . '</div>   
      
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
';
$title = "Buffer Pools";
$ROOT = '../';
include('../master.php');
?>

<script>
$(function () {
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/pools/types/read" + "?id=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {      
      for (var element in data) {
       for (var $p = 1; $p <= data[element].qty; $p++) { // pool
        // populate tables
        $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
        $("#table_" + $p).DataTable({
          autoWidth: false,
          responsive: true,
          searching: false,
          ajax: {
              headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
              url: "../api/pools/read" + "?type=" + data[element].id + "&pool=" + $p,
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
              return '<a href="view?id=' + row.id + '">' + data + '</a>';
              }  
            }
          ]
        });
      
      } 
    }
    },
    error: function(data) {
      console.log(data);
    },
  });
});
</script>