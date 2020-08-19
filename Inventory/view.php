<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Inventory item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
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
                <label for="input1">SKU</label>
                <input type="text" maxlength="255" class="form-control" id="SKU" placeholder="Enter SKU">
              </div>
              
              <div class="form-group">
                <label for="input2">Type</label>
                <select id="type" class="form-control">
                  <option value="1">General</option>
                  <option value="2">Spares</option>
                  <option value="3">Repeaters</option>
                  <option value="4">Returns</option>
                </select>
              </div>              

              <div class="form-group">
                <label for="input3">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>

              <div class="form-group">
                <label for="input4">Supplier</label>
                <input type="text" maxlength="255" class="form-control" id="supplier" placeholder="Enter supplier">
              </div>              

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="input5">Quantity</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="input6">Provisional In</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qtyIn" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                  <label for="input7">Provisional Out</label>
                  <input type="number" min="0" max="9999" class="form-control" id="qtyOut" placeholder="Enter quantity">
                  </div>
                </div>
              </div>
   
              <div class="form-group">
                <label for="input8">Technology</label>
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
                <label for="input9">Miscellaneous</label>
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
              <input type="Button" class="btn btn-primary" onClick="UpdateItem()" value="Update"></input>
              <input type="Button" class="btn btn-danger" onClick="Remove()" value="Delete"></input>
              <div class="btn-group">
                <button type="button" class="btn btn-default">Add to...</button>
                  <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span class="sr-only">Toggle Dropdown</span>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" onClick=addTo(1);>Spares</a>
                      <a class="dropdown-item" onClick=addTo(2);>Buffer Pools</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" onClick=addTo(3);>New fault report</a>
                    </div>
                  </button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Register item</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0" style="max-height: 300px;">
            <table id="table1" table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Registry ID</th>
                  <th>Serial Number</th>
                  <th>Data Purchased</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <input type="Button" class="btn btn-primary" onClick="addTo(4)" value="Add"></input>
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
$title = "Inventory item #" . $_GET['id'];
include('../master.php');
?>

<script>
$(document).ready(function() {

  // load fields
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/inventory/read_single.php" + "?id=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      $('#SKU').val(data['SKU']);
      $('#type').val(data['type']);
      $('#description').val(data['description']);
      $('#qty').val(data['qty']);
      $('#qtyIn').val(data['qtyIn']);
      $('#qtyOut').val(data['qtyOut']);
      $('#supplier').val(data['supplier']);
      if (data['isGSM'] == 1) {
        $(isGSM).prop("checked", true);
      };
      if (data['isUMTS'] == 1) {
        $(isUMTS).prop("checked", true);
      };
      if (data['isLTE'] == 1) {
        $(isLTE).prop("checked", true);
      };
      if (data['ancillary'] == 1) {
        $(ancillary).prop("checked", true);
      };
      if (data['toCheck'] == 1) {
        $(toCheck).prop("checked", true);
      };
      $('#notes').val(data['notes']);
    },
    error: function(result) {
      console.log(result);
    },
  });


  // load registry table
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/registry/read.php" + "?inventoryId=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      var tableData = "";
      for (var user in data) {
        tableData += "<tr>" +
          "<td>" + "#" + data[user].id + "</td>" +
          "<td>" + data[user].serialNumber + "</td>" +
          "<td>" + data[user].datePurchased + "</td>" +
          "<td><button type='button' onClick=Deregister('" + data[user].id + "') class='btn btn-block btn-danger'>Delete</button></td>" +
          "</tr>";
      }
      $(tableData).appendTo($("#table1"));
    }
  });


});

function UpdateItem() {
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
      url: '../api/inventory/update.php',
      dataType: 'json',
      data: {
        id: <?php echo $_GET['id']; ?>,
        SKU: $("#SKU").val(),
        type: $("#type").val(),
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
          window.location.href = '../inventory';
        }
      }
  });
}

function Remove() {
  var id = (<?php echo $_GET['id']; ?>);
  var result = confirm("Are you sure you want to delete the item? This will delete all associated registrations! Items from Projects will not be deleted.");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '../api/inventory/delete.php',
      dataType: 'json',
      data: {
        id: id
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        alert(result.message);
        if (result.status == true) {
          window.location.href = '../inventory';
        }
      }
    });
  }
}

function Deregister(id) {
  var result = confirm("Are you sure you want to delete the item?");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '../api/registry/delete.php',
      dataType: 'json',
      data: {
        id: id,
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        alert(result.message);
        if (result.status = true) {
          location.reload();
        }
      }
    });
  }
}

function addTo(type) {
  if (type == 1){
    location.href = "../spares/create.php?id=" + (<?php echo $_GET['id']; ?>);
  } else if (type == 2) {
    location.href = "../pools/create.php?id=" + (<?php echo $_GET['id']; ?>);
  } else if (type == 3) {
    location.href = "../reports/create.php?id=" + (<?php echo $_GET['id']; ?>);
  } else {
    location.href = "../inventory/register.php?id=" + (<?php echo $_GET['id']; ?>);
  }
}

</script>