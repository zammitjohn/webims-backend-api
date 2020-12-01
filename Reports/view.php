<?php
## Page specific code

// uploads saved to root
if (!(is_dir("../../rims_uploads"))) {
  mkdir("../../rims_uploads", 0700);
}
if (!(is_dir("../../rims_uploads/reports"))) {
  mkdir("../../rims_uploads/reports", 0700);
}

if (!(is_dir("../../rims_uploads/reports/". $_GET['id']))) {
  mkdir("../../rims_uploads/reports/". $_GET['id'], 0700);
}

$dir = '../../rims_uploads/reports/' . $_GET['id'];
$files = scandir($dir);

$dropbox_content = '';
for ($x = 2; $x < sizeof($files); $x++) {
  $dropbox_content .= '<td><a href="../../rims_uploads/reports/' .  $_GET['id'] . '/' . $files[$x] . '" target="_blank" class="text-muted"><i class="far fa-file"></i>' . " " . $files[$x] . '</a></td>';
  $dropbox_content .= '</tr>';
}


## Content goes here
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
                    <label for="input5">Report Number</label>
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
                    <label for="input9">Requested by RBS</label>
                    <input type="date" class="form-control" id="dateRequested">
                  </div>

                  <div class="form-group">
                    <label for="input10">Leaving RBS</label>
                    <input type="date" class="form-control" id="dateLeavingRBS">
                  </div>     

                  <div class="form-group">
                    <label for="input11">Dispatched</label>
                    <input type="date" class="form-control" id="dateDispatched">
                  </div>              

                  <div class="form-group">
                    <label for="input12">Returned</label>
                    <input type="date" class="form-control" id="dateReturned">
                  </div>

                  <hr>
                  <h5>Miscellaneous</h5>
                  <div class="form-group">
                    <label for="input13">AWB</label>
                    <input type="text" maxlength="255" class="form-control" id="AWB" placeholder="Enter AWB">
                  </div>
                  
                  <div class="form-group">
                    <label for="input14">AWB (returned)</label>
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
              <input type="Button" class="btn btn-primary button_action_update" onClick="UpdateItem()" value="Update"></input>
            </div>
          </form>
        </div>
        <!-- /.card -->

        <div class="row">
          <div class="col-sm-8">

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
              </div>
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
          <div class="col-sm-4">

            <!-- DROP BOX -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Drop Box</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table table class="table table-hover text-nowrap">
                    <tbody>'
                    . $dropbox_content .
                    '</tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer button_action_import">
                <form id="upload_file" method="post" enctype="multipart/form-data">
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="file" name="file">
                      <label class="custom-file-label" for="file"></label>
                    </div>
                    <div class="input-group-append">
                      <button type="submit" name="upload" id="upload" class="btn btn-primary">Upload</button>
                    </div>
                  </div>
                </form>                
              </div>
              <!-- /.card-footer-->
            </div>
            <!--/.drop-box -->

          </div>
        </div>
        <!-- /.row -->

      </div>  
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
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

// handle file upload
$('#upload_file').on("submit", function(e){
  var formData = new FormData(this); // Add id value with submitted file formData 
  formData.append('reportId', <?php echo $_GET['id']; ?>);

  toastr.info('Uploading file'); // show toast
  e.preventDefault(); //form will not submitted
  $.ajax({
      headers: { "Auth-Key": (localStorage.getItem('sessionId'))},
      url:"upload.php",  
      method:"POST",  
      data: formData,  
      contentType:false,          // The content type used when sending data to the server.  
      cache:false,                // To disable request pages to be cached  
      processData:false,          // To send DOMDocument or non processed data file
      dataType: 'json',
      success: function(data) {
        if (data['status'] == true) {  
          location.reload();
        } else {
          toastr.error("Upload failed. " + data['message']);
        }
      
      },
      error: function(data) {
        toastr.error("Upload failed");
      }
  })  
});  

//handle file upload form
$('#file').on('change',function(){ // validate file type to import
  var fileName = $(this).val(); // get the file name
  var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
  $(this).next('.custom-file-label').html(cleanFileName);  // replace the file input label
});
</script>