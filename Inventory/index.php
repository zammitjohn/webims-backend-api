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
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
            <thead>
            <tr>          
              <th>SKU</th>
              <th>Category</th>
              <th>Type</th>
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
              <th>Category</th>
              <th>Type</th>
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
$title = "Inventory";
$ROOT = '../';
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
          url: "../api/inventory/read",
          dataSrc: ''
      },
      columns: [
          { data: 'SKU' },
          { data: 'category' },  
          { data: 'type' },        
          { data: 'description' },
          { data: 'qty' },
          { data: 'qty_projects_allocated' },
          { data: 'supplier' },
          { data: 'inventoryDate' },        
      ],
      // https://datatables.net/forums/discussion/42564/combining-a-url-with-id-from-a-column
      columnDefs: [
        { targets: [0], // first column
          "render": function (data, type, row, meta) {
          return '<a href="view?id=' + row.id + '">' + data + '</a>';
          }  
        },

        { targets: [1], // category column
            "render": function (data, type, row, meta) {
            return '<a href="category?id=' + row.category_id + '">' + row.category_name + '</a>';
            }  
        },

        { targets: [2], // type column
            "render": function (data, type, row, meta) {
            return '<a href="type?id=' + row.type_id + '">' + row.type_name + " " + "(" + row.type_altname + ")" + '</a>';
            }  
        }
      ]
  });
});


</script>