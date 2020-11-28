<?php
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
          <li class="breadcrumb-item active"></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">

    <div id=table_div></div>   
      
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
    url: "../api/pools/types/read.php" + "?id=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      var html;
      
      for (var element in data) { // loop type

      //customize page according to type
      $("h4").html(data[element].name);
      $("li.breadcrumb-item.active").html(data[element].name);

      html = `
      <div class="container-fluid">
        <div class="row-sm-12">
          <div class="col-sm-12">
            <h4>` + data[element].name + `</h4>
          </div>
        </div>
      </div> 
      `;
      $("#table_div").append(html); // insert header

       for (var $p = 1; $p <= data[element].qty; $p++) { // pool
        html = `
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Pool ` + $p + `</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped">
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
        <!-- /.card -->      
        `;
        $("#table_div").append(html); // insert table

        // populate latest created table
        $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
        $("table:last").DataTable({
          autoWidth: false,
          responsive: true,
          searching: false,
          ajax: {
              headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
              url: "../api/pools/read.php" + "?type=" + data[element].id + "&pool=" + $p,
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
              return '<a href="view.php?id=' + row.id + '">' + data + '</a>';
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