<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Spares</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../spares">Spares</a></li>
          <li class="breadcrumb-item active">sparetype.placeholder.text</li>
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
          <h3 class="card-title">sparetype.placeholder.text</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="table1" class="table table-bordered table-striped">
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
            <tfoot>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
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
$title = "Spares";
include('../master.php');
?>

<!-- page script -->
<script>
$(document).ready(function() {
  // load type
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/spares/types/read.php" + "?id=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {

      for (var element in data) {
        $("h3.card-title").html(data[element].name);
        $("li.breadcrumb-item.active").html(data[element].name);
      }

      // load table contents
      $.fn.dataTable.ext.errMode = 'throw'; // Have DataTables throw errors rather than alert() them
      $('#table1').DataTable({
          autoWidth: false,
          responsive: true,
          ajax: {
              headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
              url: "../api/spares/read.php" + "?type=" + <?php echo $_GET['id']; ?>,
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
    },
    error: function(data) {
      console.log(data);
    }
  });

});
</script>