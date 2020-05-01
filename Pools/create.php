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
          <li class="breadcrumb-item"><a href="../pools">Buffer Pools</a></li>
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
                  <option value="NULL">None</option>
                </select>
              </div>  

              <div class="form-group">
                <label for="input2">Name</label>
                <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter name">
              </div>
              
              <div class="form-group">
                <label for="input3">Tech: </label>
                <select id="tech">
                  <option value="1">GSM</option>
                  <option value="2">UMTS</option>
                  <option value="3">LTE</option>
                </select>
                <label for="input4">Pool: </label>
                <select id="pool">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>

              <div class="form-group">
                <label for="input5">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>
              
              <div class="form-group">
                <label for="input6">Quantity ordered</label>
                <input type="number" min="0" max="9999" class="form-control" id="qtyOrdered" placeholder="Enter quantity">
              </div>
              
              <div class="form-group">
                <label for="input7">Quantity in stock</label>
                <input type="number" min="0" max="9999" class="form-control" id="qtyStock" placeholder="Enter quantity">
              </div>

              <div class="form-group">
                <label for="input8">Miscellaneous</label>
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
    url: '../api/pools/create.php',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      tech: $("#tech").val(),
      pool: $("#pool").val(),
      name: $("#name").val(),
      description: $("#description").val(),
      qtyOrdered: $("#qtyOrdered").val(),
      qtyStock: $("#qtyStock").val(),
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.responseText);
    },
    success: function(result) {
      if (result['status'] == true) {
        alert("Successfully added item!");
        window.location.href = '/rims/pools';
      } else {
        alert(result['message']);
      }
    }
  });
}
</script>