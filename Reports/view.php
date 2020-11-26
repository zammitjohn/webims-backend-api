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
                    <label for="input7">Faulty</label> <div class="addSN" style="display:inline-block;"> </div>
                    <select id="faultySN" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="input8">Replacement</label> <div class="addSN" style="display:inline-block;"> </div>
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

              <div class="row">
                <div class="col-auto mr-auto">
                  <input type="Button" class="btn btn-primary button_action_update" onClick="UpdateItem()" value="Update"></input>
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
                <div class="col-auto">
                  <input type="Button" id="toggle-status-btn" class="btn button_action_update" onClick="ToggleStatus()" value=""></input>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card -->


        <!-- COMMENTS BOX -->
        <div class="card direct-chat direct-chat-warning">
        <div class="card-header">
          <h3 class="card-title">Comments</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <!-- Conversations are loaded here -->
          <div class="direct-chat-messages">
          </div>
          <!--/.direct-chat-messages-->

        <!-- /.card-body -->
        <div class="card-footer">
          <form action="javascript:NewComment()" method="post">
            <div class="input-group">
              <input type="text" id="user_comment" name="message" placeholder="Write a comment..." class="form-control">
              <span class="input-group-append">
                <button type="button" onClick="NewComment()" class="btn btn-warning button_action_create">Post</button>
              </span>
            </div>
          </form>
        </div>
        <!-- /.card-footer-->
      </div>
      <!--/.comments-box -->

    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
';
$title = "Report #" . $_GET['id'];
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
          // append dropdowndata to SKU dropdown
          $("#userId").append(dropdowndata);


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
              $('#description').val(data['description']);
              $('#reportNo').val(data['reportNo']);
              $('#userId').val( (data['userId'] == null) ? "" : (data['userId']) ); // JSON: null -> form/SQL: ""
              var faultySN = ( (data['faultySN'] == null) ? "" : (data['faultySN']) ); // JSON: null -> form/SQL: ""
              var replacementSN = ( (data['replacementSN'] == null) ? "" : (data['replacementSN']) ); // JSON: null -> form/SQL: ""
              $('#dateRequested').val(data['dateRequested']);
              $('#dateLeavingRBS').val(data['dateLeavingRBS']);
              $('#dateDispatched').val(data['dateDispatched']);
              $('#dateReturned').val(data['dateReturned']);
              $('#AWB').val(data['AWB']);
              $('#AWBreturn').val(data['AWBreturn']);
              $('#RMA').val(data['RMA']);

              // show 'Mark as resolved' or 'Mark as pending' buttons accordingly
              if ((data['isClosed']) == '1'){
                $("#toggle-status-btn").addClass("btn-secondary");
                $("#toggle-status-btn").prop('value', 'Mark as pending');
              } else {
                $("#toggle-status-btn").addClass("btn-success");
                $("#toggle-status-btn").prop('value', 'Mark as closed');
              }

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


  // load comments
  messagedata = "";
  $.ajax({
    type: "GET",
    cache: false, // due to aggressive caching on IE 11
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: "<?php echo $ROOT; ?>api/reports/comments/read.php" + "?reportId=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      for (var element in data) {
        messagedata += '<div class="direct-chat-msg"> <div class="direct-chat-infos clearfix"> <span class="direct-chat-name float-left">' + data[element].firstname + ' ' + data[element].lastname + '</span> <span class="direct-chat-timestamp float-right">' + moment(data[element].timestamp, "YYYY-MM-DD, h:mm:ss").fromNow() + '</span> </div> <!-- /.direct-chat-infos --> <img class="direct-chat-img" src="../dist/img/generic-user.png" alt="message user image"> <!-- /.direct-chat-img --> <div class="direct-chat-text"><text>' + data[element].text + '</text></div> <!-- /.direct-chat-text --> </div>';
      }
      // append messagedata to side bar tree view
      $(".direct-chat-messages").append(messagedata);
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
        window.location.href = '../reports';
      }
    }
  });
}

function NewComment() {
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/reports/comments/create.php',
    dataType: 'json',
    data: {
      reportId: <?php echo $_GET['id']; ?>,
      userId: localStorage.getItem('userId'),
      text: $("#user_comment").val()
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      if (result.status) {
        location.reload();
      }
    }
  });
}

function ToggleStatus() {
  var id = (<?php echo $_GET['id']; ?>);
  $.ajax({
    type: "POST",
    headers: { "Auth-Key": (localStorage.getItem('sessionId')) },
    url: '../api/reports/toggle_status.php',
    dataType: 'json',
    data: {
      id: id
    },
    error: function(result) {
      alert(result.statusText);
    },
    success: function(result) {
      if (result.status) {
        location.reload();
      }
    }
  });
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

    //open a new window note:this is a popup so it may be blocked by browser
    var newWindow = window.open("", "new window", "width=1000, height=700");

    //write the data to the document of the newWindow and close
    newWindow.document.write(response);
    newWindow.document.close();
  }
  });
});

</script>