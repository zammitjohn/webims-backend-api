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
$title = "Pools item #" . $_GET['id'];
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
    url: "../api/inventory/read.php",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].SKU + " (" + data[element].category_name + ") " + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);

      // populate tech dropdown
      $.ajax({
        type: "GET",
        cache: false, // due to aggressive caching on IE 11
        headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
        url: "../api/pools/types/read.php",
        dataType: 'json',
        success: function(data) {
          dropdowndata = "";
          for (var element in data) {
            dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
          }
          // append dropdowndata to SKU dropdown
          $("#tech").append(dropdowndata);

          // populate form
          $.ajax({
            type: "GET",
            cache: false, // due to aggressive caching on IE 11
            headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
            url: "../api/pools/read_single.php" + "?id=" + <?php echo $_GET['id']; ?>,
            dataType: 'json',
            success: function(data) {
              $('#SKU').val( (data['inventoryId'] == null) ? "" : (data['inventoryId']) ); // JSON: null -> form/SQL: ""
              $('#tech').val(data['tech']);


              $.ajax({
                type: "GET",
                cache: false, // due to aggressive caching on IE 11
                headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
                url: "../api/pools/types/read.php?id=" + data['tech'],
                dataType: 'json',
                success: function(list) {
                  var dropdowndata = "";
                  for (var element in list) {
                    for (var $p = 1; $p <= list[element].qty; $p++) {
                      dropdowndata += "<option value = '" + $p + "'>" + "#" + $p + "</option>";
                    }
                  }
                  
                  $("#tech").prop('disabled', true); // disable tech field
                  $("#pool").append(dropdowndata); // append dropdowndata to pool dropdown
                  $('#pool').val(data['pool']); // select correct pool
                
                },
                error: function(data) {
                  console.log(data);
                },
              });

              
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
      alert(result.statusText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
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
      url: '../api/pools/delete.php',
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
          window.location.href = document.referrer;
        }
      }
    });
  }
}
</script>