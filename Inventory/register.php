<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Register item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active">Register inventory item</li>
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
            <h3 class="card-title">Register item</h3>
          </div>
          <!-- /.card-header -->
    
          <div class="card-body">

            <div class="form-group">
              <label for="input1">SKU</label>
              <select id="SKU" class="form-control">
                <option value="">None</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="input2">Serial Number</label>
              <input type="text" maxlength="255" class="form-control" id="serialNumber" placeholder="Enter serial number">
            </div>
            
            <div class="form-group">
              <label for="input3">Date Purchased</label>
              <input type="date" class="form-control" id="datePurchased">
            </div>     
            
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <input type="Button" class="btn btn-primary" onClick="Register()" value="Submit"></input>
          </div>
   
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
        document.getElementById("SKU").disabled = true; // disable field
      }

    }
  });
});


function Register() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/registry/create.php',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      serialNumber: $("#serialNumber").val(),
      datePurchased: $("#datePurchased").val()
    },
    error: function(result) {
      alert(result.responseText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '../inventory/view.php?id=' + $("#SKU").val();
      }
    }
  });
}
</script>