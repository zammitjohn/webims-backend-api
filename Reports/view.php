<?php
## Page specific code

// uploads saved to root
if (!(is_dir("../../uploads"))) {
  mkdir("../../uploads", 0700);
}
if (!(is_dir("../../uploads/reports"))) {
  mkdir("../../uploads/reports", 0700);
}

if (!(is_dir("../../uploads/reports/". $_GET['id']))) {
  mkdir("../../uploads/reports/". $_GET['id'], 0700);
}

$dir = '../../uploads/reports/' . $_GET['id'];
$files = scandir($dir);

$dropbox_content = '';
for ($x = 2; $x < sizeof($files); $x++) {
  $dropbox_content .= '<td><a href="../../uploads/reports/' .  $_GET['id'] . '/' . $files[$x] . '" target="_blank" class="text-muted"><i class="far fa-file"></i>&nbsp' . $files[$x] . '</a></td>';
  $dropbox_content .= '</tr>';
}


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
                    <label for="asigneeUserId">Asignee</label>
                    <select id="asigneeUserId" class="form-control">
                      <option value="">None</option>
                    </select>
                  </div>

                  <hr>
                  <h5>Serial Numbers</h5>
                  <div class="form-inline">
                    <label for="faultySN">Faulty: </label>

                    <div class="dropdown dropright" style="width: 300px !important;">
                      <button id="faultySN" style="color:#ffc107;" type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                        None
                      </button>
                      <div class="dropdown-menu">
                        <div id="serial_number_faulty" class="serial_number" style="overflow-y:auto; max-height:10vh">
                        </div>
                      </div>
                    </div>

                  </div>
                  
                  <div class="form-inline">
                    <label for="replacementSN">Replacement: </label>

                    <div class="dropdown dropright" style="width: 300px !important;">
                      <button id="replacementSN" style="color:#ffc107;" type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                        None
                      </button>
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
                    <label for="dateRequested">Requested </label>
                    <input type="date" class="form-control" id="dateRequested">
                  </div>

                  <div class="form-group">
                    <label for="dateLeaving">Leaving </label>
                    <input type="date" class="form-control" id="dateLeaving">
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

              <div class="row">
                <div class="col-auto mr-auto">
                  <button type="submit" class="btn btn-primary button_action_update">Update</button>
                </div>
                <div class="col-auto">
                  <input type="Button" id="toggle-repairable-btn" class="btn button_action_update" value=""></input>
                </div>
              </div>            

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
                <form id="comment_form" method="post">
                  <div class="input-group">
                    <input type="text" id="user_comment" name="message" placeholder="Write a comment..." class="form-control">
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-warning button_action_create">Post</button>
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


      // populate asigneeUserId dropdown
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
          // append dropdowndata to SKU dropdown
          $("#asigneeUserId").append(dropdowndata);


          // populate form from DB
          $.ajax({
            type: "GET",
            cache: false,
            url: "../api/reports/read_single" + "?id=" + <?php echo $_GET['id']; ?>,
            dataType: 'json',
            success: function(data) {
              $('#SKU').val( (data['inventoryId'] == null) ? "" : (data['inventoryId']) ); // JSON: null -> form/SQL: ""
              $('#ticketNo').val(data['ticketNo']);
              $('#name').val(data['name']);
              $('#description').val(data['description']);
              $('#reportNo').val(data['reportNo']);
              $('#asigneeUserId').val( (data['asigneeUserId'] == null) ? "" : (data['asigneeUserId']) ); // JSON: null -> form/SQL: ""
              selected_faulty_serial_number = ( (data['faultySN'] == null) ? "" : (data['faultySN']) ); // JSON: null -> form/SQL: ""
              selected_replacement_serial_number = ( (data['replacementSN'] == null) ? "" : (data['replacementSN']) ); // JSON: null -> form/SQL: ""
              $('#dateRequested').val(data['dateRequested']);
              $('#dateLeaving').val(data['dateLeaving']);
              $('#dateDispatched').val(data['dateDispatched']);
              $('#dateReturned').val(data['dateReturned']);
              $('#AWB').val(data['AWB']);
              $('#AWBreturn').val(data['AWBreturn']);
              $('#RMA').val(data['RMA']);

              // show 'Mark as repairable' or 'Mark as unrepairable' buttons accordingly
              if ((data['isRepairable']) == '1'){
                $("#toggle-repairable-btn").addClass("btn-danger");
                $("#toggle-repairable-btn").prop('value', 'Mark as unrepairable');
              } else {
                $("#toggle-repairable-btn").addClass("btn-secondary");
                $("#toggle-repairable-btn").prop('value', 'Mark as repairable');
              }

              populateSerialNumbers(); // populate serial number dropdown with options and actual value from DB
            },
            error: function(result) {
              console.log(result);
            },
          });
    
        }
      });    
    
    }
  });
  
  // reports form events
  $('#report_form').on('submit',function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: '../api/reports/update',
      dataType: 'json',
      data: {
        id: <?php echo $_GET['id']; ?>,
        inventoryId: $("#SKU").val(),
        ticketNo: $("#ticketNo").val(),
        name: $("#name").val(),
        description: $("#description").val(),
        reportNo: $("#reportNo").val(),
        asigneeUserId: $("#asigneeUserId").val(),
        faultySN: selected_faulty_serial_number,
        replacementSN: selected_replacement_serial_number,
        dateRequested: $("#dateRequested").val(),
        dateLeaving: $("#dateLeaving").val(),
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

  $('#toggle-repairable-btn').on('click',function (e) {
    var id = (<?php echo $_GET['id']; ?>);
    $.ajax({
      type: "POST",
      url: '../api/reports/toggle_repairable',
      dataType: 'json',
      data: {
        id: id
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        if (result.status) {
          // toggle button without reloading all the DOM
          if ($("#toggle-repairable-btn").hasClass("btn-secondary")) {
            $("#toggle-repairable-btn").removeClass("btn-secondary");
            $("#toggle-repairable-btn").addClass("btn-danger");
            $("#toggle-repairable-btn").prop('value', 'Mark as unrepairable');
          } else {
            $("#toggle-repairable-btn").removeClass("btn-danger");
            $("#toggle-repairable-btn").addClass("btn-secondary");
            $("#toggle-repairable-btn").prop('value', 'Mark as repairable');
          }
        }
      }
    });
  });

  // comments form events
  loadComments(); // load comments
  $('#comment_form').on('submit',function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: '../api/reports/comments/create',
      dataType: 'json',
      data: {
        reportId: <?php echo $_GET['id']; ?>,
        text: $("#user_comment").val()
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        if (result.status) {
          loadComments();
        }
      }
    });
  });
});

function loadComments() {
  $("#user_comment").val(""); // clear comment box
  $(".direct-chat-msg").remove(); //remove messages from DOM
  messagedata = "";
  $.ajax({
    type: "GET",
    cache: false,
    url: "<?php echo $ROOT; ?>api/reports/comments/read" + "?reportId=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      for (var element in data) {
        messagedata += '<div class="direct-chat-msg"> <div class="direct-chat-infos clearfix"> <span class="direct-chat-name float-left">' + data[element].firstname + ' ' + data[element].lastname + '</span> <span class="direct-chat-timestamp float-right">' + moment(data[element].timestamp, "YYYY-MM-DD, h:mm:ss").fromNow() + '</span> </div> <!-- /.direct-chat-infos --> <img class="direct-chat-img" src="../dist/img/generic-user.png" alt="message user image"> <!-- /.direct-chat-img --> <div class="direct-chat-text"><text>' + data[element].text + '</text></div> <!-- /.direct-chat-text --> </div>';
      }
      // append messagedata to side bar tree view
      $(".direct-chat-messages").append(messagedata);
    }
  });
}

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
        if (data[element].state == 'Faulty'){
          dropdowndata += "<button type='button' class='dropdown-item disabled' item_id='" + data[element].id + "'>" + "#" + data[element].id + ": " + data[element].serialNumber + "</button>";
        } else {
          dropdowndata += "<button type='button' class='dropdown-item' item_id='" + data[element].id + "'>" + "#" + data[element].id + ": " + data[element].serialNumber + "</button>";
        }
      }

      // append dropdowndata to serial numbers dropdown
      $(".serial_number").append(dropdowndata);
      // show the '+ Add item' dropdown menu option
      $( '<div class="dropdown-divider"></div><a class="dropdown-item" href="../inventory/register?id=' +  $("#SKU").val() + '"> + Add item</a>').appendTo(".dropdown-menu");

      // populate value in field
      if (selected_faulty_serial_number != ""){
        $("#faultySN").text($("#serial_number_faulty").find('button[item_id="' + selected_faulty_serial_number + '"]').text());
      }
      if (selected_replacement_serial_number != ""){
        $("#replacementSN").text($("#serial_number_replacement").find('button[item_id="' + selected_replacement_serial_number + '"]').text());
      }

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

// handle file upload
$('#upload_file').on("submit", function(e){
  var formData = new FormData(this); // Add id value with submitted file formData 
  formData.append('reportId', <?php echo $_GET['id']; ?>);

  toastr.info('Uploading file'); // show toast
  e.preventDefault(); //form will not submitted
  $.ajax({
      url:"../api/reports/upload",  
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