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
                <label for="input2">Name</label>
                <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter name">
              </div>
              
              <div class="form-group">
                <label for="input3">Tech: </label>
                <select id="tech">
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
$title = "Add item";
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
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].SKU + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);

      // populate fields on 'add to'
      var urlParams = new URLSearchParams(window.location.search);
      var id = urlParams.get('id'); // inventoryId
      if (id != null) {
        $('#SKU').val(id);
        document.getElementById("SKU").disabled=true; // disable field

        // populate 'name' field to 'SKU'
        $('#name').val($("#SKU :selected").text());
      }

      // populate tech dropdown
      $.ajax({
        type: "GET",
        cache: false, // due to aggressive caching on IE 11
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/pools/types/read.php",
        dataType: 'json',
        success: function(data) {
          var dropdowndata = "";
          for (var element in data) {
            dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
          }
          // append dropdowndata to SKU dropdown
          $("#tech").append(dropdowndata);
        },
        error: function(data) {
          console.log(data);
        }
      });


    },
    error: function(data) {
      console.log(data);
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
      alert(result.statusText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '/rims/pools';
      }
    }
  });
}
</script>