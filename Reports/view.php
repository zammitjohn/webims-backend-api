<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Report</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../reports">Fault Reports</a></li>
          <li class="breadcrumb-item active">Report</li>
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
                  <option value="">None</option>
                </select>
              </div>

              <div class="form-group">
                <label for="input6">Serial Number (Faulty)</label> <div class="addSN" style="display:inline-block;"> </div>
                <select id="faultySN" class="form-control">
                  <option value="">None</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="input7">Serial Number (Replacement)</label> <div class="addSN" style="display:inline-block;"> </div>
                <select id="replacementSN" class="form-control">
                  <option value="">None</option>
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
              <input type="Button" class="btn btn-primary" onClick="UpdateItem()" value="Update"></input>
              <input type="Button" class="btn btn-danger" onClick="Remove()" value="Delete"></input>
              <div class="btn-group">
              <button type="button" class="btn btn-default">Export as...</button>
                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                  <span class="sr-only">Toggle Dropdown</span>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" id="nokia_report">Nokia Hardware Failure Report</a>
                  </div>
                </button>
            </div>
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
$title = "Report #" . $_GET['id'];
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
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].SKU + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#SKU").append(dropdowndata);


      // populate requestedBy dropdown
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
          // append dropdowndata to SKU dropdown
          $("#requestedBy").append(dropdowndata);


          // populate form from DB
          $.ajax({
            type: "GET",
            cache: false, // due to aggressive caching on IE 11
            headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
            url: "../api/reports/read_single.php" + "?id=" + <?php echo $_GET['id']; ?>,
            dataType: 'json',
            success: function(data) {
              $('#SKU').val( (data['inventoryId'] == null) ? "" : (data['inventoryId']) ); // JSON: null -> form/SQL: ""
              $('#ticketNo').val(data['ticketNo']);
              $('#name').val(data['name']);
              $('#reportNo').val(data['reportNo']);
              $('#requestedBy').val( (data['requestedBy'] == null) ? "" : (data['requestedBy']) ); // JSON: null -> form/SQL: ""
              var faultySN = ( (data['faultySN'] == null) ? "" : (data['faultySN']) ); // JSON: null -> form/SQL: ""
              var replacementSN = ( (data['replacementSN'] == null) ? "" : (data['replacementSN']) ); // JSON: null -> form/SQL: ""
              $('#dateRequested').val(data['dateRequested']);
              $('#dateLeavingRBS').val(data['dateLeavingRBS']);
              $('#dateDispatched').val(data['dateDispatched']);
              $('#dateReturned').val(data['dateReturned']);
              $('#AWB').val(data['AWB']);
              $('#AWBreturn').val(data['AWBreturn']);
              $('#RMA').val(data['RMA']);
              $('#notes').val(data['notes']);

              document.getElementById("SKU").disabled=true; // disable field, to prevent further changes!
              if ($('#SKU').val() != ""){
                populateSerialNumbers(faultySN, replacementSN); // populate serial number dropdown with options and actual value from DB
              }
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
    url: '../api/reports/update.php',
    dataType: 'json',
    data: {
      id: <?php echo $_GET['id']; ?>,
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
  var result = confirm("Are you sure you want delete the report?");
  if (result == true) {
    $.ajax({
      type: "POST",
      headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
      url: '../api/reports/delete.php',
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

function populateSerialNumbers(faultySN, replacementSN) {
  $('.addSN').append('<a href="../inventory/register.php?id=' +  $("#SKU").val() + '" ><b>+Add</b></a>');

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

      // populate actual value from DB
      $('#faultySN').val(faultySN);
      $('#replacementSN').val(replacementSN);
    }

  });
}

$('#nokia_report').on('click', function () {
  toastr.info("Report will open in a separate window");
  $.ajax({
  type: 'POST',
  url: '../functions/nokia/report.php',
  data: {
    reportNo: $("#reportNo").val(),
    faultySN:  $("#faultySN option:selected").text(),
    replacementSN: $("#replacementSN option:selected").text(),
    SKU: $("#SKU option:selected").text(),
    name: $("#name").val()
  },
  dataType : 'html',
  success: function(response) {

    //open a new window note:this is a popup so it may be blocked by your browser
    var newWindow = window.open("", "new window", "width=1000, height=700");

    //write the data to the document of the newWindow and close
    newWindow.document.write(response);
    newWindow.document.close();
  }
  });
});

</script>