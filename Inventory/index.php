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
            <a href="#" class="btn btn-tool btn-sm button_action_import" data-toggle="modal" data-target="#modal-transaction"> <i class="fas fa-dolly-flatbed"></i> </a> 
          </div>         
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

      <!-- data import modal -->
      <div class="modal fade" id="modal-transaction">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">New transaction request</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="bs-stepper">
                <div class="bs-stepper-header" role="tablist">
                  <!-- your steps here -->
                  <div class="step" data-target="#select-part">
                    <button type="button" class="step-trigger" role="tab" aria-controls="select-part" id="select-part-trigger">
                      <span class="bs-stepper-circle">1</span>
                      <span class="bs-stepper-label">Select items</span>
                    </button>
                  </div>
                  <div class="line"></div>
                  <div class="step" data-target="#quantity-part">
                    <button type="button" class="step-trigger" role="tab" aria-controls="quantity-part" id="quantity-part-trigger">
                      <span class="bs-stepper-circle">2</span>
                      <span class="bs-stepper-label">Specify quantities</span>
                    </button>
                  </div>
                </div>
                <div class="bs-stepper-content">
                  <!-- your steps content here -->
                  <div id="select-part" class="content" role="tabpanel" aria-labelledby="select-part-trigger">
                    <div class="form-group">
                      <label for="item-skus">Items</label>
                      <div class="select2-primary">
                        <select class="form-control" multiple="multiple" id="SKU_item_id" style="width: 100%;"></select>
                      </div>          
                    </div>
                    <button class="btn btn-primary" id="next_btn">Next</button>
                  </div>
                  <div id="quantity-part" class="content" role="tabpanel" aria-labelledby="quantity-part-trigger">
                    <form id="item_qty_form" method="post">
                      <div class="form-group">
                        <label>Item quantities</label>
                        <ul id="items_list">
                        </ul>                    
                      </div>
                      <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                  </div>
                </div>
              </div>

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
$ROOT = '../';
include('../master.php');
?>

<!-- page script -->
<script>
$(document).ready(function () {
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
          { data: 'category_name' },  
          { data: 'type_name' },        
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

$("#modal-transaction").on('shown.bs.modal', function() { 
  window.stepper = new Stepper($('.bs-stepper')[0])
});

$("#modal-transaction").on('hidden.bs.modal', function() { 
  $("#SKU_item_id").empty(); // empty options
});

$('#next_btn').click(function(){
  // next step
  stepper.next();
  $("#items_list").empty();
  for (i = 0; i < $("#SKU_item_id").select2('data').length; i++) {
    $('<li> <label>' +  $("#SKU_item_id").select2('data')[i].text + ': </label> <input type="number" value="-1" class="input_item_qty" itemId="' +  $("#SKU_item_id").select2('data')[i].id + '" ' + 'style="width: 3em"></li>').appendTo("#items_list");
  }
});   

$('#item_qty_form').on('submit',function (e) {
  e.preventDefault();
  // create json for transaction api
  var transaction_txt = '{ "items" : [';
  for (i = 0; i < $("#SKU_item_id").select2('data').length; i++) { // loop item-skus, getting values from input fields...
    transaction_txt = transaction_txt + '{ "item_id":' + $("#SKU_item_id").select2('data')[i].id + ' , "item_qty":"' + $("input[itemid='" + $("#SKU_item_id").select2('data')[i].id  +  "']").val() + '" }'; 
  
    if (i !== ($("#SKU_item_id").select2('data').length - 1)){
      transaction_txt = transaction_txt + ',';
    }
  
  }
  transaction_txt = transaction_txt + ']}';
  var transaction_json = JSON.parse(transaction_txt);
  
  //console.log(transaction_json);
  $.ajax({
    url : "../api/transactions/create" ,
    type : 'POST',
    data : JSON.stringify(transaction_json),
    success : function(data) {
      // Successfully sent data
      toastr.success("Transaction created successfully");
      $('#modal-transaction').modal('toggle'); //hide modal
    },
    error: function(data) {
        // Unable to send data
    }
  });
});
</script>