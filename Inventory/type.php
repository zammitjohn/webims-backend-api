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
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active">inventorytype.placeholder.text</li>
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
          <h3 class="card-title">inventorytype.placeholder.text</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
              <tr>          
                <th>SKU</th>
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
  $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
  $('#table1').DataTable({
    autoWidth: false,
    responsive: true,
    order:[],
    ajax: {
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/inventory/read.php" + "?type=" + <?php echo $_GET['id']; ?>,
        dataSrc: ''
    },
    columns: [
        { data: 'SKU' },     
        { data: 'description' },
        { data: 'qty' },
        { data: 'qtyIn' },
        { data: 'qtyOut' },
        { data: 'supplier' },
        { data: 'inventoryDate' },        
    ],
    columnDefs: [
      { targets: [0], // first column
        "render": function (data, type, row, meta) {
        return '<a href="view.php?id=' + row.id + '">' + data + '</a>';
        }  
      }
    ]
  });

});

//customize page according to type
var inventorytype;
if ((<?php echo $_GET['id']; ?>) == '1') {
    inventorytype = "General";
  } else if ((<?php echo $_GET['id']; ?>) == '2') {
    inventorytype = "Spares";
  } else if ((<?php echo $_GET['id']; ?>) == '3') {
    inventorytype = "Repeaters";
  } else if ((<?php echo $_GET['id']; ?>) == '4') {
    inventorytype = "Returns";
  } else {
    inventorytype = "undefined";
}
$("h3.card-title").html(inventorytype);
$("li.breadcrumb-item.active").html(inventorytype);

</script>