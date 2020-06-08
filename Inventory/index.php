<?php
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
          <li class="breadcrumb-item"><a href="#">Inventory</a></li>
          <li class="breadcrumb-item active">All items</li>
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
          <h3 class="card-title">All items</h3>
          <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm" data-toggle="modal" data-target="#modal-import">
              <i class="fas fa-upload"></i>
            </a>
          </div>          
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>SKU</th>
              <th>Type</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>2G</th>
              <th>3G</th>
              <th>4G</th>
              <th>Ancillary</th>
              <th>Check</th>
              <th>Notes</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
              <th>SKU</th>
              <th>Type</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>2G</th>
              <th>3G</th>
              <th>4G</th>
              <th>Ancillary</th>
              <th>Check</th>
              <th>Notes</th>
            </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- account settings modal -->
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
              <p>Import CSV File Data into Inventory Database</p>
              
              <form id="upload_csv" method="post" enctype="multipart/form-data">
                <div class="input-group mb-3">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file" name="file" accept=".csv">
                    <label class="custom-file-label" for="file"></label>
                  </div>
                  <div class="input-group-append">
                    <button type="submit" name="upload" id="upload" class="btn btn-primary">Upload</button>
                  </div>
                </div>
              </form>
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
include('../master.php');
?>

<!-- page script -->
<script>
$('#table1').DataTable({
    autoWidth: false,
    responsive: true,
    ajax: {
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/inventory/read.php",
        dataSrc: ''
    },
    columns: [
        { data: 'SKU' },
        { data: 'Type' },        
        { data: 'description' },
        { data: 'qty' },
        { data: 'isGSM' },
        { data: 'isUMTS' },
        { data: 'isLTE' },
        { data: 'ancillary' },
        { data: 'toCheck' },
        { data: 'notes' }		
    ],
    // https://datatables.net/forums/discussion/42564/combining-a-url-with-id-from-a-column
    columnDefs: [ 
      { targets: [0], // first column
        "render": function (data, type, row, meta) {
        return '<a href="view.php?id=' + row.id + '">' + data + '</a>';
        }  
      },

      { targets: [1], // type column
          "render": function (data, type, row, meta) {
          return '<a href="type.php?id=' + row.type + '">' + typeIdtoText(row.type) + '</a>';
          }  
      },
    
      { targets: [4, 5, 6, 7, 8], // columns with bools
        "render": function (data, type, row, meta) {
        return ((data == 1) ? "Yes" : "No");
        }  
      }
    ]
});

// Function to convert type ids to correspnding type to be shown in table
function typeIdtoText(id){
var inventoryType;
  if (id == '1') {
    inventoryType = "General";
  } else if (id == '2') {
    inventoryType = "Spares";
  } else if (id == '3') {
    inventoryType = "Repeaters";
  } else if (id == '4') {
    inventoryType = "Returns";
  } else {
    inventoryType = "undefined";
  }
return inventoryType;
}

$('#upload_csv').on("submit", function(e){  
  e.preventDefault(); //form will not submitted  
  $.ajax({
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url:"../functions/import.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,          // The content type used when sending data to the server.  
      cache:false,                // To disable request pages to be cached  
      processData:false,          // To send DOMDocument or non processed data file
      dataType: 'json',
      success: function(data) {
          if(data['status'] == false) {  
              alert("No data imported");  
          } else {  
              alert("Created: " + data['created_count'] + " items. Matched: " + data['updated_count'] + " items.");
              location.reload();
          }  
      },
      error: function(data) {
        alert("Import failed");  
      }
  })  
});  

$('#file').on('change',function(){ // validate file type to import
  var regex = new RegExp("(.*?)\.(csv)$");
  var fileName = $(this).val(); // get the file name
  if (!(regex.test(fileName.toLowerCase()))) {
    alert('Please select correct file format');
    $(this).next('.custom-file-label').html("");  // replace the file input label
  } else { // Show file name in dialog https://stackoverflow.com/questions/48613992/bootstrap-4-file-input-doesnt-show-the-file-name
    var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
    $(this).next('.custom-file-label').html(cleanFileName);  // replace the file input label
  }
})

</script>