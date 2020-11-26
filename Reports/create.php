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

          <!-- form start -->
          <form role="form">
            <div class="card-body">
              <div class="form-group">
                <h5>Inventory SKU</h5>
                <select id="SKU" class="form-control">
                  <option value="">None</option>
                </select>
              </div>   
              <div class="row">

                <div class="col">
          
                  <h5>Details</h5>
                  <div class="form-group">
                    <label for="input2">Name</label>
                    <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter hardware type / location">
                  </div>                      

                  <div class="form-group">
                    <label for="input3">Ticket Number</label>
                    <input type="text" maxlength="255" class="form-control" id="ticketNo" placeholder="Enter ticket#">
                  </div>

                  <div class="form-group">
                    <label for="input4">Description</label>
                    <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter fault description">
                  </div>   
                  
                  <div class="form-group">
                    <label for="input5">Fault Report Number</label>
                    <input type="text" maxlength="255" class="form-control" id="reportNo" placeholder="Enter fault report#">
                  </div>
                  
                  <div class="form-group">
                    <label for="input6">Requested by</label>
                    <select id="userId" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>

                  <hr>
                  <h5>Serial Numbers</h5>
                  <div class="form-group">
                    <label for="input7">Faulty</label>
                    <select id="faultySN" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="input8">Replacement</label>
                    <select id="replacementSN" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>  
                </div>

                <div class="col">
                  <h5>Dates</h5>
                  <div class="form-group">
                    <label for="input9">Date Requested by RBS</label>
                    <input type="date" class="form-control" id="dateRequested">
                  </div>

                  <div class="form-group">
                    <label for="input10">Date Leaving RBS</label>
                    <input type="date" class="form-control" id="dateLeavingRBS">
                  </div>     

                  <div class="form-group">
                    <label for="input11">Date Dispatched</label>
                    <input type="date" class="form-control" id="dateDispatched">
                  </div>              

                  <div class="form-group">
                    <label for="input12">Date Returned</label>
                    <input type="date" class="form-control" id="dateReturned">
                  </div>

                  <hr>
                  <h5>Miscellaneous</h5>
                  <div class="form-group">
                    <label for="input13">AWB</label>
                    <input type="text" maxlength="255" class="form-control" id="AWB" placeholder="Enter AWB">
                  </div>
                  
                  <div class="form-group">
                    <label for="input14">AWB Returned</label>
                    <input type="text" maxlength="255" class="form-control" id="AWBreturn" placeholder="Enter AWB returned">
                  </div>
                  
                  <div class="form-group">
                    <label for="input15">RMA</label>
                    <input type="text" maxlength="255" class="form-control" id="RMA" placeholder="Enter RMA">
                  </div>              
                </div>
              </div>
            
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <input type="Button" class="btn btn-primary button_action_create" onClick="AddItem()" value="Submit"></input>
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
$title = "New report";
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

  // populate userId dropdown
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/users/read.php",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].firstname + " " + data[element].lastname + "</option>";
      }
      // append dropdowndata to userId dropdown
      $("#userId").append(dropdowndata);
      $("#userId").val(localStorage.getItem('userId')); // set userId to current userId
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
      description: $("#description").val(),
      reportNo: $("#reportNo").val(),
      userId: $("#userId").val(),
      faultySN: $("#faultySN").val(),
      replacementSN: $("#replacementSN").val(),
      dateRequested: $("#dateRequested").val(),
      dateLeavingRBS: $("#dateLeavingRBS").val(),
      dateDispatched: $("#dateDispatched").val(),
      dateReturned: $("#dateReturned").val(),
      AWB: $("#AWB").val(),
      AWBreturn: $("#AWBreturn").val(),
      RMA: $("#RMA").val()
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      alert(result.message);
      if (result.status == true) {
        window.location.href = '/rims/reports';
      }
    }
  });
}


function populateSerialNumbers() {
  document.getElementById("SKU").disabled=true; // disable field, to prevent further changes!

  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "../api/registry/read.php" + "?inventoryId=" +  $("#SKU").val(),
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + "#" + data[element].id + ": " + data[element].serialNumber + "</option>";
      }
      // append dropdowndata to serial numbers dropdown
      $("#faultySN").append(dropdowndata);
      $("#replacementSN").append(dropdowndata);

    }

  });
}

</script>