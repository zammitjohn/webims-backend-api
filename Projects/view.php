<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Projects item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">Projects</li>
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
                <label for="input2">Project</label>
                <select id="type" class="form-control">
                </select>
              </div>       
              
              <div class="form-group">
                <label for="input3">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>
              
              <div class="form-group">
                <label for="input4">Quantity</label>
                <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
              </div>
              
              <div class="form-group">
                <label for="input5">Miscellaneous</label>
                <input type="text" maxlength="255" class="form-control" id="notes" placeholder="Notes">
              </div>
            
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <input type="Button" class="btn btn-primary button_action_update" onClick="UpdateItem()" value="Update"></input>
              <input type="Button" class="btn btn-danger button_action_delete" onClick="Remove()" value="Delete"></input>
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
$title = "Projects Item #" . $_GET['id'];
$ROOT = '../';
include('../master.php');
?>

<script>
$(document).ready(function() {
  // populate inventoryId dropdown
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/read",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].SKU + " (" + data[element].category_name + ") " + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);

      // populate type dropdown
      $.ajax({
        type: "GET",
        cache: false, // due to aggressive caching on IE 11
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/projects/types/read",
        dataType: 'json',
        success: function(data) {
          dropdowndata = "";
          for (var element in data) {
            dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
          }
          // append dropdowndata to SKU dropdown
          $("#type").append(dropdowndata);


          // populate form
          $.ajax({
            type: "GET",
            cache: false, // due to aggressive caching on IE 11
            headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
            url: "../api/projects/read_single" + "?id=" + <?php echo $_GET['id']; ?>,
            dataType: 'json',
            success: function(data) {
              $('#SKU').val( (data['inventoryId'] == null) ? "" : (data['inventoryId']) ); // JSON: null -> form/SQL: ""
              $('#type').val(data['type']);
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

    }

  });
});

function UpdateItem() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/projects/update',
    dataType: 'json',
    data: {
      id: <?php echo $_GET['id']; ?>,
      inventoryId: $("#SKU").val(),
      type: $("#type").val(),
      description: $("#description").val(),
      qty: $("#qty").val(),
      notes: $("#notes").val(),
      userId: localStorage.getItem('userId')
    },
    error: function(result) {
      alert(result.statusText);
    },
  success: function(result) {
      alert(result.message);
      if (result.status) {
        window.location.href = '../projects/type?id=' + $("#type").val();
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
      url: '../api/projects/delete',
      dataType: 'json',
      data: {
        id: id
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        alert(result.message);
        if (result.status) {
          window.location.href = '../projects/type?id=' + $("#type").val();
        }
      }
    });
  }
}
</script>