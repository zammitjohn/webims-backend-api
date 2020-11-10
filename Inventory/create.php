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
          <form role="form">
            <div class="card-body">

              <div class="form-group">
                <label for="input1">SKU</label>
                <input type="text" maxlength="255" class="form-control" id="SKU" placeholder="Enter SKU">
              </div>
              
              <div class="form-group">
                <label for="input2">Category: </label>
                <select id="category">
                </select>
                <label for="input3">Type: </label>
                <select id="type">
                </select>
              </div>            

              <div class="form-group">
                <label for="input4">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>

              <div class="form-group">
                <label for="input5">Supplier</label>
                <input type="text" maxlength="255" class="form-control" id="supplier" placeholder="Enter supplier">
              </div>              

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="input6">Quantity</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="input7">Provisional In</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qtyIn" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                  <label for="input8">Provisional Out</label>
                  <input type="number" min="0" max="9999" class="form-control" id="qtyOut" placeholder="Enter quantity">
                  </div>
                </div>
              </div>
   
              <div class="form-group">
                <label for="input9">Technology</label>
                <div class="container-fluid">
                    <label class="form-check-label">
                      <input type="checkbox" id="isGSM">
                      GSM
                    </label>
                </div>
                <div class="container-fluid">
                  <label class="form-check-label">
                    <input type="checkbox" id="isUMTS">
                    UMTS
                  </label>
                </div>
                <div class="container-fluid">
                  <label class="form-check-label">
                    <input type="checkbox" id="isLTE">
                    LTE
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label for="input10">Miscellaneous</label>
                <div class="container-fluid">
                  <label class="form-check-label">
                    <input type="checkbox" id="ancillary">
                    Ancillary
                  </label>
                </div>
                <div class="container-fluid">
                  <label class="form-check-label">
                    <input type="checkbox" id="toCheck">
                    To Check
                  </label>
                </div>
              </div>
              <input type="text" maxlength="255" class="form-control" id="notes" placeholder="Notes">
            </div>
            <!-- /.card-body -->
            
            <div class="card-footer">
              <input type="Button" class="btn btn-primary button_action_create" onClick="AddItem()" value="Submit"></input>
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
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/categories/read.php",
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

function AddItem() {
  var isGSMval = "";
  var isUMTSval = "";
  var isLTEval = "";
  var ancillaryval = "";
  var toCheckval = "";

  if ($('#isGSM').is(":checked"))
    isGSMval = 1;

  if ($('#isUMTS').is(":checked"))
    isUMTSval = 1;

  if ($('#isLTE').is(":checked"))
    isLTEval = 1;

  if ($('#ancillary').is(":checked"))
    ancillaryval = 1;

  if ($('#toCheck').is(":checked"))
    toCheckval = 1;

  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/inventory/create.php',
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
      isGSM: isGSMval,
      isUMTS: isUMTSval,
      isLTE: isLTEval,
      ancillary: ancillaryval,
      toCheck: toCheckval,
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '/rims/inventory';
      }
    }
  });
}

function loadTypes(category) {
  $("#type").empty();
  // load type field
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/types/read.php?category=" + category,
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