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

if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $type_name = ($row['name']);
  $delete_button = '<div class="card-tools"> <a href="#" class="btn btn-tool btn-sm button_action_delete"> <i class="fas fa-trash-alt"></i> </a> </div>';
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
          <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm button_action_import" data-toggle="modal" data-target="#modal-import"><i class="fas fa-upload"></i></a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table" class="table">
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

      <!-- data import modal -->
      <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Import</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p>Select CSV data file to import. Data file must use the following format: <i>SKU,description,quantity,notes</i> (header and blank lines are ignored).</p>
              <div class="form-group">
                <b>Allocate items from:</b>
                <div class="row">
                  <div class="col-8">
                    <select id="inventory_category" class="form-control">
                    </select>
                  </div>
                  <div class="col-4">
                    <select id="inventory_type" class="form-control">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <form id="upload_csv" method="post" enctype="multipart/form-data">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="csvfile" name="file" accept=".csv">
                    <label class="custom-file-label" for="file"></label>
                  </div>
                  <div class="input-group-append">
                    <button type="submit" name="upload" id="upload" class="btn btn-primary">Upload</button>
                  </div>
                </div>
              </form>
            </div>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->   
    
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
';
$title = $type_name;
$ROOT = '../';
include('../master.php');
?>

<!-- page script -->
<script>
$(document).ready(function() {
  // load table contents
  $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
  var table = $('#table').DataTable({
      oSearch: {"sSearch": dtURLToQuery()},
      lengthChange: false,
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
          return '<a href="view?id=' + row.id + '">' + data + '</a>';
          }  
        }
      ]
  });
  dtQueryToURL(table);

  // table buttons
  new $.fn.dataTable.Buttons(table, {
    buttons: ["copy", "csv", "excel", "pdf", "print"]
  });
  table.buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

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

$("#inventory_category").change(function () {
  loadTypes($("#inventory_category").val());
});

// load inventory categories and types
$("#modal-import").on('shown.bs.modal', function() { 
  $("#inventory_category").empty();
  // load category field
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/inventory/categories/read",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#inventory_category").append(dropdowndata);
      loadTypes($("#inventory_category").val());
    },
    error: function(data) {
      console.log(data);
    },
  });
});

function loadTypes(category) {
  $("#inventory_type").empty();
  // load type field
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/inventory/types/read?category=" + category,
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#inventory_type").append(dropdowndata);
    },
    error: function(data) {
      console.log(data);
    },
  });
}



// upload csv
$('#upload_csv').on("submit", function(e){
  $('#modal-import').modal('toggle'); // hide modal
  toastr.info('Importing data'); // show toast
  e.preventDefault(); //form will not submitted

  // POST csv file using Ajax along with other form details
  var formData = new FormData(this);
  formData.append('type', "<?php echo $_GET['id']; ?>");
  formData.append('inventory_type', $('#inventory_type').val());
  formData.append('inventory_category', $('#inventory_category').val());

  $.ajax({
      url:"../api/projects/import",  
      method:"POST",  
      data:formData,  
      contentType:false,          // The content type used when sending data to the server.  
      cache:false,                // To disable request pages to be cached  
      processData:false,          // To send DOMDocument or non processed data file
      dataType: 'json',
      success: function(data) {
          if (data['status'] == false) {  
              toastr.error('No data imported');
          } else {  
              toastr.success("Added " + data['created_count'] + " items.");

              if (data['notfound_count']){
                toastr.warning(data['additional_info'] + "not in inventory! First create item to inventory and then add to project.");
              }
          }
          $('#table').DataTable().ajax.reload(); // reload table
      },
      error: function(data) {
        toastr.error("Import failed");  
      }
  })  
});

</script>