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
            <a href="import.html" class="btn btn-tool btn-sm">
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

</script>