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
          <form id="report_form" method="post">
            <div class="card-body">
              <div class="form-group">
                <h5>Inventory SKU</h5>
                <select id="SKU" class="form-control">
                </select>
              </div>   
              
              <div class="row">
                <div class="col">
                  
                  <hr>
                  <h5>Details</h5>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" maxlength="255" class="form-control" id="name" placeholder="Enter hardware type / location">
                  </div>                      

                  <div class="form-group">
                    <label for="ticketNo">Ticket Number</label>
                    <input type="text" maxlength="255" class="form-control" id="ticketNo" placeholder="Enter ticket#">
                  </div>

                  <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter fault description">
                  </div>   
                  
                  <div class="form-group">
                    <label for="reportNo">Report Number</label>
                    <input type="text" maxlength="255" class="form-control" id="reportNo" placeholder="Enter fault report#">
                  </div>
                  
                  <div class="form-group">
                    <label for="userId">Requested by</label>
                    <select id="userId" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>

                  <hr>
                  <h5>Serial Numbers</h5>
                  <div class="form-inline">
                    <label for="faultySN">Faulty: </label>

                    <div class="dropdown dropright" style="width: 300px !important;">
                      <button id="faultySN" type="button" class="btn dropdown-toggle" data-toggle="dropdown">None</button>
                      <div class="dropdown-menu">
                        <div id="serial_number_faulty" class="serial_number" style="overflow-y:auto; max-height:10vh">
                        
                        </div>
                      </div>
                    </div>

                  </div>
                  
                  <div class="form-inline">
                    <label for="replacementSN">Replacement: </label>

                    <div class="dropdown dropright" style="width: 300px !important;">
                      <button id="replacementSN" type="button" class="btn dropdown-toggle" data-toggle="dropdown">None</button>
                      <div class="dropdown-menu">
                        <div id="serial_number_replacement" class="serial_number" style="overflow-y:auto; max-height:10vh">
                  
                        </div>
                      </div>
                    </div>

                  </div>  
                </div>

                <div class="col">
                  
                  <hr>
                  <h5>Dates</h5>
                  <div class="form-group">
                    <label for="dateRequested">Requested by RBS</label>
                    <input type="date" class="form-control" id="dateRequested">
                  </div>

                  <div class="form-group">
                    <label for="dateLeavingRBS">Leaving RBS</label>
                    <input type="date" class="form-control" id="dateLeavingRBS">
                  </div>     

                  <div class="form-group">
                    <label for="dateDispatched">Dispatched</label>
                    <input type="date" class="form-control" id="dateDispatched">
                  </div>              

                  <div class="form-group">
                    <label for="dateReturned">Returned</label>
                    <input type="date" class="form-control" id="dateReturned">
                  </div>

                  <hr>

                  <h5>Miscellaneous</h5>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="AWB">AWB</label>
                        <input type="text" maxlength="255" class="form-control" id="AWB" placeholder="Enter AWB">
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="AWBreturn">AWB (return)</label>
                        <input type="text" maxlength="255" class="form-control" id="AWBreturn" placeholder="Enter return AWB">
                      </div>
                    </div> 
                  </div>                     
                
                  <div class="form-group">
                    <label for="RMA">RMA</label>
                    <input type="text" maxlength="255" class="form-control" id="RMA" placeholder="Enter RMA">
                  </div>              
                </div>
              </div>
            
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary button_action_create">Submit</button>
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
  selected_faulty_serial_number = "";
  selected_replacement_serial_number = "";

  // populate inventoryId dropdown
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/inventory/read",
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
        $('#SKU').attr('disabled', 'disabled'); // disable field, to prevent further changes!
      }
      populateSerialNumbers();
    }
  });

  // populate userId dropdown
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/users/read",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].firstname + " " + data[element].lastname + "</option>";
      }
      // append dropdowndata to userId dropdown
      $("#userId").append(dropdowndata);
    }
  });

});

$('#report_form').on('submit',function (e) {
  e.preventDefault();
    $.ajax({
    type: "POST",
    url: '../api/reports/create',
    dataType: 'json',
    data: {
      inventoryId: $("#SKU").val(),
      ticketNo: $("#ticketNo").val(),
      name: $("#name").val(),
      description: $("#description").val(),
      reportNo: $("#reportNo").val(),
      userId: $("#userId").val(),
      faultySN: selected_faulty_serial_number,
      replacementSN: selected_replacement_serial_number,
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
        window.location.href = '../reports';
      }
    }
  });
});

$("#SKU").change(function(){
  $(".dropdown-menu").find(".dropdown-item").remove();
  $(".dropdown-menu").find(".dropdown-divider").remove();
  $("#faultySN").html("None");
  $("#replacementSN").html("None");
  selected_faulty_serial_number = "";
  selected_replacement_serial_number = "";
  populateSerialNumbers();
});

function populateSerialNumbers() {
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/registry/read" + "?inventoryId=" +  $("#SKU").val(),
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "<button type='button' class='dropdown-item' item_id=''>None</button>";
      for (var element in data) {
        if (data[element].state == 'New'){
          dropdowndata += "<button type='button' class='dropdown-item' item_id='" + data[element].id + "'>" + "#" + data[element].id + ": " + data[element].serialNumber + "</button>";
        } else {
          dropdowndata += "<button type='button' class='dropdown-item disabled' item_id='" + data[element].id + "'>" + "#" + data[element].id + ": " + data[element].serialNumber + "</button>";
        }
      }

      // append dropdowndata to serial numbers dropdown
      $(".serial_number").append(dropdowndata);
      // show the '+ Add item' dropdown menu option
      $( '<div class="dropdown-divider"></div><a class="dropdown-item" href="../inventory/register?id=' +  $("#SKU").val() + '"> + Add item</a>').appendTo(".dropdown-menu");

      // dropdown onclick
      $('.dropdown-menu button').click(function() {
        $(this).closest(".dropdown-menu").siblings(".dropdown-toggle").text($(this).text());
        if ($(this).closest(".serial_number").attr('id') == "serial_number_faulty"){
          selected_faulty_serial_number = $(this).attr('item_id');
        } else {
          selected_replacement_serial_number = $(this).attr('item_id');
        }
      });      

    }

  });
}
</script>