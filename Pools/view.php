<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Pools item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../pools">Buffer Pools</a></li>
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
        url: "../api/pools/read_single.php" + "?id=" + <?php echo $_GET['id']; ?>,
        dataType: 'json',
        success: function(data) {
          $('#SKU').val( (data['inventoryId'] == null) ? "NULL" : (data['inventoryId']) ); // JSON: null -> form/SQL: NULL
          $('#tech').val(data['tech']);
          $('#pool').val(data['pool']);
          $('#name').val(data['name']);
          $('#description').val(data['description']);
          $('#qtyOrdered').val(data['qtyOrdered']);
          $('#qtyStock').val(data['qtyStock']);
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
    url: '../api/pools/update.php',
    dataType: 'json',
    data: {
      id: <?php echo $_GET['id']; ?>,
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
        alert("Successfully updated item!");
        window.location.href = document.referrer;
      } else {
        alert(result['message']);
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
      url: '../api/pools/delete.php',
      dataType: 'json',
      data: {
        id: id
      },
      error: function(result) {
        alert(result.responseText);
      },
      success: function(result) {
        if (result['status'] == true) {
          alert("Successfully removed item!");
          window.location.href = document.referrer;
        } else {
          alert(result['message']);
        }
      }
    });
  }
}

</script>