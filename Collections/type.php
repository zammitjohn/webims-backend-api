<?php
## Page specific code
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/collections_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare collections type property object
$collections_types_object = new Collections_Types($db);
$collections_types_object->id = $_GET['id'];

$stmt = $collections_types_object->read();
$type_name = 'Unknown Type';

if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $type_name = ($row['name']);
}

## Content goes here
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Collections</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">Collections</li>
          <li class="breadcrumb-item active">' . $type_name . '</li>
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
          <h3 class="card-title">' . $type_name . '</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-hover">
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
$title = "Collections";
$ROOT = '../';
include('../master.php');
?>

<!-- page script -->
<script>
$(document).ready(function() {
  // load table contents
  $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
  $('#table1').DataTable({
      autoWidth: false,
      responsive: true,
      ajax: {
          headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
          url: "../api/collections/read.php" + "?type=" + <?php echo $_GET['id']; ?>,
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
</script>