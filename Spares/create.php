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
          <li class="breadcrumb-item"><a href="../spares">Spares</a></li>
          <li class="breadcrumb-item active">Add items</li>
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
          <div class="card-header">
            <h3 class="card-title">New item</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form">
            <div class="card-body">

              <div class="form-group">
                <label for="input1">Inventory SKU</label>
                <select id="SKU" class="form-control">
                  <option value="">None</option>
                </select>
              </div>  

              <div class="form-group">
                <label for="input2">Type</label>
                <select id="type" class="form-control">
                  <option value="1">Common</option>
                  <option value="2">Radio Modules</option>
                  <option value="3">NSN Power</option>
                  <option value="4">Cables and Fibres</option>
                  <option value="5">SFPs</option>
                  <option value="6">GSM Equipment</option>
                  <option value="7">UMTS Equipment</option>
                  <option value="8">LTE Equipment</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="input3">Name</label>
                <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter name">
              </div>
              
              <div class="form-group">
                <label for="input4">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>
              
              <div class="form-group">
                <label for="input5">Quantity</label>
                <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
              </div>
              
              <div class="form-group">
                <label for="input6">Miscellaneous</label>
                <input type="text" maxlength="255" class="form-control" id="notes" placeholder="Notes">
              </div>
            
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <input type="Button" class="btn btn-primary" onClick="AddItem()" value="Submit"></input>
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
include('../master.php');
?>

<script>

// populate inventoryId dropdown
$(document).ready(function() {
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/read.php",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var user in data) {
        dropdowndata += "<option value = '" + data[user].id + "'>" + data[user].SKU + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);

      // populate fields on 'add to'
      var urlParams = new URLSearchParams(window.location.search);
      var id = urlParams.get('id'); // inventoryId
      if (id != null) {
        $('#SKU').val(id);
        document.getElementById("SKU").disabled=true; // disable field
      }
    }

  });
});

  
function AddItem() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/spares/create.php',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      type: $("#type").val(),
      name: $("#name").val(),
      description: $("#description").val(),
      qty: $("#qty").val(),
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.responseText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '/rims/spares';
      }
    }
  });
}
</script>