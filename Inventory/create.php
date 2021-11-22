<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active">Add item</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card">

          <!-- form start -->
          <form id="item_form" method="post">
            <div class="card-body">

              <div class="form-group">
                <label for="SKU">SKU</label>
                <input type="text" maxlength="255" class="form-control" id="SKU" placeholder="Enter SKU">
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-6 col-sm-3">
                    <label for="category">Category</label>
                    <select id="category" class="form-control">
                    </select>
                  </div>
                  <div class="col-6 col-sm-3">
                    <label for="type">Type</label>
                    <select id="type" class="form-control">
                    </select>
                  </div>
                </div>
              </div>                 

              <div class="form-group">
                <label for="description">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>

              <div class="form-group">
                <label for="supplier">Supplier</label>
                <input type="text" maxlength="255" class="form-control" id="supplier" placeholder="Enter supplier">
              </div>              

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="qtyIn">Provisional In</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qtyIn" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                  <label for="qtyOut">Provisional Out</label>
                  <input type="number" min="0" max="9999" class="form-control" id="qtyOut" placeholder="Enter quantity">
                  </div>
                </div>
              </div>
                 
              <div class="form-group">
                <label for="notes">Miscellaneous</label>
                <input type="text" maxlength="255" class="form-control" id="notes" placeholder="Notes">
              </div>

            </div>
            <!-- /.card-body -->
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary button_action_create">Submit</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
';
$title = "Add item";
$ROOT = '../';
include('../master.php');
?>

<script>
$("#category").change(function () {
  loadTypes($("#category").val());
});

$(document).ready(function() {
  // load type field
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
      $("#category").append(dropdowndata);
      loadTypes($("#category").val());
    },
    error: function(data) {
      console.log(data);
    },
  });
});

$('#item_form').on('submit',function (e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: '../api/inventory/create',
    dataType: 'json',
    data: {
      SKU: $("#SKU").val(),
      type: $("#type").val(),
      category: $("#category").val(),
      description: $("#description").val(),
      qty: $("#qty").val(),
      qtyIn: $("#qtyIn").val(),
      qtyOut: $("#qtyOut").val(),      
      supplier: $("#supplier").val(),
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      if (result.status == false) {
        alert(result.message);
      } else {
        window.location.href = '../inventory';
      }
    }
  });
});

function loadTypes(category) {
  $("#type").empty();
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
      $("#type").append(dropdowndata);
    },
    error: function(data) {
      console.log(data);
    },
  });
}
</script>