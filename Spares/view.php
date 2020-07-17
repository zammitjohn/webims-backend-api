<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Spares item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../spares">Spares</a></li>
          <li class="breadcrumb-item active">Item</li>
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
            <h3 class="card-title">Update item</h3>
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
              <input type="Button" class="btn btn-primary" onClick="UpdateItem()" value="Update"></input>
              <input type="Button" class="btn btn-danger" onClick="Remove()" value="Delete"></input>
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

      // populate form
      $.ajax({
        type: "GET",
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/spares/read_single.php" + "?id=" + <?php echo $_GET['id']; ?>,
        dataType: 'json',
        success: function(data) {
          $('#SKU').val( (data['inventoryId'] == null) ? "NULL" : (data['inventoryId']) ); // JSON: null -> form/SQL: NULL
          $('#type').val(data['type']);
          $('#name').val(data['name']);
          $('#description').val(data['description']);
          $('#qty').val(data['qty']);
          $('#notes').val(data['notes']);
        },
        error: function(result) {
          console.log(result);
        },
      });

    }

  });
});

function UpdateItem() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/spares/update.php',
    dataType: 'json',
    data: {
      id: <?php echo $_GET['id']; ?>,
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
      if (result.status) {
        window.location.href = document.referrer;
      }
    }
  });
}

function Remove() {
  var id = (<?php echo $_GET['id']; ?>);
  var result = confirm("Are you sure you want to delete the item?");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '../api/spares/delete.php',
      dataType: 'json',
      data: {
        id: id
      },
      error: function(result) {
        alert(result.responseText);
      },
      success: function(result) {
        alert(result.message);
        if (result.status) {
          window.location.href = document.referrer;
        }
      }
    });
  }
}
</script>