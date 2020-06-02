<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add item</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="../inventory">Inventory</a></li>
          <li class="breadcrumb-item active">Add item</li>
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
            <h3 class="card-title">New item</h3>
          </div>
          <!-- /.card-header -->
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
                <label for="input4">Quantity</label>
                <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
              </div>
              
              <div class="form-group">
              <label for="input5">Technology</label>
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
                <label for="input6">Miscellaneous</label>
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
function AddItem() {
  var isGSMval = 0;
  var isUMTSval = 0;
  var isLTEval = 0;
  var ancillaryval = 0;
  var toCheckval = 0;

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
    url: '../api/inventory/create.php',
    dataType: 'json',
    data: {
      SKU: $("#SKU").val(),
      type: $("#type").val(),
      description: $("#description").val(),
      qty: $("#qty").val(),
      isGSM: isGSMval,
      isUMTS: isUMTSval,
      isLTE: isLTEval,
      ancillary: ancillaryval,
      toCheck: toCheckval,
      notes: $("#notes").val()
    },
    error: function(result) {
      alert(result.responseText);
    },
    success: function(result) {
      if (result['status'] == true) {
        alert("Successfully added item!");
        window.location.href = '/rims/inventory';
      } else {
        alert(result['message']);
      }
    }
  });
}
</script>