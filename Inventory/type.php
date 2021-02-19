<?php
## Page specific code
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/inventory_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare inventory type property object
$inventory_types_object = new Inventory_Types($db);
$inventory_types_object->id = $_GET['id'];

$stmt = $inventory_types_object->read();

$type_alt_name = '';
if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $type_name = ($row['name']);
  if ($row['import_name']) { $type_alt_name = (' (' . $row['import_name'] . ')'); }
  $category_name = ($row['category_name']);
  $category_id = ($row['type_category']);
} else {
  header("HTTP/1.0 404 Not Found");
  include '../pages/404.php';
  die();
}

## Content goes here
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Inventory</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active" id="navigator_categoryPage"><a href="category?id=' . $category_id . '">' . $category_name . '</a></li>
          <li class="breadcrumb-item active" id="navigator_typePage">' . $type_name . '</li>
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
          <h3 class="card-title">' . $type_name . $type_alt_name . '</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
              <tr>          
                <th>SKU</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Allocated</th>
                <th>Supplier</th>
                <th>Inventory Date</th>                
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>          
                <th>SKU</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Allocated</th>
                <th>Supplier</th>
                <th>Inventory Date</th>                
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
$title = $category_name;
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
        order:[],
        ajax: {
            url: "../api/inventory/read" + "?type=" + <?php echo $_GET['id']; ?>,
            dataSrc: ''
        },
        columns: [
            { data: 'SKU' },     
            { data: 'description' },
            { data: 'qty' },
            { data: 'qty_projects_allocated' },
            { data: 'supplier' },
            { data: 'inventoryDate' },        
        ],
        columnDefs: [
          { targets: [0], // first column
            "render": function (data, type, row, meta) {
            return '<a href="view?id=' + row.id + '">' + data + '</a>';
            }  
          }
        ]
      });

});
</script>