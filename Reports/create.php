<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>New report</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../reports">Fault Reports</a></li>
          <li class="breadcrumb-item active">New report</li>
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
            <h3 class="card-title">Create report</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form">
            <div class="card-body">

              <div class="form-group">
                <label for="input1">Inventory SKU</label>
                <select id="SKU" class="form-control" onchange="populateSerialNumbers()">
                  <option value="NULL">None</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="input2">Ticket Number</label>
                <input type="text" maxlength="255" class="form-control" id="ticketNo" placeholder="Enter ticket#">
              </div>

              <div class="form-group">
                <label for="input3">Name</label>
                <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter name">
              </div>              
              
              <div class="form-group">
                <label for="input4">Fault Report Number</label>
                <input type="text" maxlength="255" class="form-control" id="reportNo" placeholder="Enter fault report#">
              </div>
              
              <div class="form-group">
                <label for="input5">Requested by</label>
                <select id="requestedBy" class="form-control">
                  <option value="NULL">None</option>
                </select>
              </div>              

              <div class="form-group">
                <label for="input6">Serial Number (Faulty)</label>
                <select id="faultySN" class="form-control">
                  <option value="NULL">None</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="input7">Serial Number (Replacement)</label>
                <select id="replacementSN" class="form-control">
                  <option value="NULL">None</option>
                </select>
              </div>       

              <div class="form-group">
                <label for="input8">Date Requested by RBS</label>
                <input type="date" class="form-control" id="dateRequested">
              </div>

              <div class="form-group">
                <label for="input9">Date Leaving RBS</label>
                <input type="date" class="form-control" id="dateLeavingRBS">
              </div>     

              <div class="form-group">
                <label for="input10">Date Dispatched</label>
                <input type="date" class="form-control" id="dateDispatched">
              </div>              

              <div class="form-group">
                <label for="input11">Date Returned</label>
                <input type="date" class="form-control" id="dateReturned">
              </div>

              <div class="form-group">
                <label for="input12">AWB</label>
                <input type="text" maxlength="255" class="form-control" id="AWB" placeholder="Enter AWB">
              </div>
              
              <div class="form-group">
                <label for="input13">AWB Returned</label>
                <input type="text" maxlength="255" class="form-control" id="AWBreturn" placeholder="Enter AWB returned">
              </div>
              
              <div class="form-group">
                <label for="input14">RMA</label>
                <input type="text" maxlength="255" class="form-control" id="RMA" placeholder="Enter RMA">
              </div>              

              <div class="form-group">
                <label for="input15">Miscellaneous</label>
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
        populateSerialNumbers();

        // populate 'name' field to 'SKU'
        $('#name').val($("#SKU :selected").text());
        
      }
    }

  });

  // populate requestedBy dropdown
  $.ajax({
    type: "GET",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/users/read.php",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var user in data) {
        dropdowndata += "<option value = '" + data[user].id + "'>" + data[user].firstname + " " + data[user].lastname + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#requestedBy").append(dropdowndata);
    }
  });

});
  
function AddItem() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/reports/create.php',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      ticketNo: $("#ticketNo").val(),
      name: $("#name").val(),
      reportNo: $("#reportNo").val(),
      requestedBy: $("#requestedBy").val(),
      faultySN: $("#faultySN").val(),
      replacementSN: $("#replacementSN").val(),
      dateRequested: $("#dateRequested").val(),
      dateLeavingRBS: $("#dateLeavingRBS").val(),
      dateDispatched: $("#dateDispatched").val(),
      dateReturned: $("#dateReturned").val(),
      AWB: $("#AWB").val(),
      AWBreturn: $("#AWBreturn").val(),
      RMA: $("#RMA").val(),
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.responseText);
    },
    success: function(result) {
      if (result['status'] == true) {
        alert("Successfully added item!");
        window.location.href = '/rims/reports';
      } else {
        alert(result['message']);
      }
    }
  });
}


function populateSerialNumbers() {
  document.getElementById("SKU").disabled=true; // disable field, to prevent further changes!

  $.ajax({
    type: "GET",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/registry/read.php" + "?inventoryId=" +  $("#SKU").val(),
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var user in data) {
        dropdowndata += "<option value = '" + data[user].id + "'>" + "#" + data[user].id + ": " + data[user].serialNumber + "</option>";
      }
      // append dropdowndata to serial numbers dropdown
      $("#faultySN").append(dropdowndata);
      $("#replacementSN").append(dropdowndata);

    }

  });
}

</script>