<?php
## Page specific code
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/projects_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare projects type property object
$projects_types_object = new Projects_Types($db);
$projects_types_object->id = $_GET['id'];

$stmt = $projects_types_object->read();
$delete_button = '';
$type_name = 'Unknown Type';

if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $type_name = ($row['name']);
  $delete_button = '<div class="card-tools"> <a href="#" class="btn btn-tool btn-sm button_action_delete"> <i class="fas fa-trash-alt"></i> </a> </div>';
}

## Content goes here
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Projects</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">Projects</li>
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
          ' . $delete_button . '
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>SKU</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Notes</th>
                <th>Added by</th>
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
$title = "Projects";
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
          url: "../api/projects/read" + "?type=" + <?php echo $_GET['id']; ?>,
          dataSrc: ''
      },
      columns: [
          { data: 'inventory_SKU' },
          { data: 'description' },
          { data: 'qty' },
          { data: 'notes' },
          { data: 'user_fullname' }		
      ],
      columnDefs: [ 
        { targets: [0],
          "render": function (data, type, row, meta) {
          return '<a href="view?id=' + row.id + '">' + data + " (" + row.inventory_category + ")" + '</a>';
          }  
        }
      ]
  });
});

// project deletion
$(document).on('click', ".button_action_delete", function () {
  var result = confirm("Are you sure you want to delete the project?");
  if (result == true) {
    $.ajax({
      type: "POST",
      url: '../api/projects/types/delete',
      dataType: 'json',
      data: {
        id: <?php echo $_GET['id']; ?>
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        window.location.href = "../"
      }
    });
  }
});


</script>