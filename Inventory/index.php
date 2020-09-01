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
              <th>Provisional In</th>
              <th>Provisional Out</th>
              <th>Supplier</th>
              <th>Inventory Date</th>               
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
              <th>Provisional In</th>
              <th>Provisional Out</th>
              <th>Supplier</th>
              <th>Inventory Date</th>              
            </tr>
            </tfoot>
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
              <p>Select CSV data file to import</p>
              
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
$title = "Inventory";
include('../master.php');
?>

<!-- page script -->
<script>
$(function () {
  $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
  var table = $('#table1').DataTable({
      autoWidth: false,
      responsive: true,
      order:[],
      ajax: {
          headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
          url: "../api/inventory/read.php",
          dataSrc: ''
      },
      columns: [
          { data: 'SKU' },
          { data: 'type' },        
          { data: 'description' },
          { data: 'qty' },
          { data: 'qtyIn' },
          { data: 'qtyOut' },
          { data: 'supplier' },
          { data: 'inventoryDate' },        
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
            return '<a href="type.php?id=' + row.type_id + '">' + row.type_name + " " + "(" + row.type_altname + ")" + '</a>';
            }  
        }
      ]
  });
});

$('#upload_csv').on("submit", function(e){
  $('#modal-import').modal('toggle'); // hide modal
  toastr.info('Importing data'); // show toast
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
              toastr.error('No data imported');
          } else {  
              toastr.success("Created: " + data['created_count'] + " items. Matched: " + data['updated_count'] + " items.");

              if (data['conflict_count']){
                toastr.warning(data['conflict_count'] + " conflicts merged.");
              }
              $('#table1').DataTable().ajax.reload(); // reload table
          }
      },
      error: function(data) {
        toastr.error("Import failed");  
      }
  })  
});  

$('#file').on('change',function(){ // validate file type to import
  var regex = new RegExp("(.*?)\.(csv)$");
  var fileName = $(this).val(); // get the file name
  if (!(regex.test(fileName.toLowerCase()))) {
    toastr.error('Please select correct file format');
    $(this).next('.custom-file-label').html("");  // replace the file input label
  } else { // Show file name in dialog https://stackoverflow.com/questions/48613992/bootstrap-4-file-input-doesnt-show-the-file-name
    var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
    $(this).next('.custom-file-label').html(cleanFileName);  // replace the file input label
  }
})
</script>