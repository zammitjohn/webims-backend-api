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
                <label for="SKU">SKU</label>
                <input type="text" maxlength="255" class="form-control" id="SKU" placeholder="Enter SKU">
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-6 col-sm-3">
                    <label for="category">Category</label>
                    <select id="category" class="form-control">
                    </select>
                  </div>
                  <div class="col-6 col-sm-3">
                    <label for="type">Type</label>
                    <select id="type" class="form-control">
                    </select>
                  </div>
                </div>
              </div>           

              <div class="form-group">
                <label for="description">Description</label>
                <input type="text" maxlength="255" class="form-control" id="description" placeholder="Enter description">
              </div>

              <div class="form-group">
                <label for="supplier">Supplier</label>
                <input type="text" maxlength="255" class="form-control" id="supplier" placeholder="Enter supplier">
              </div>              

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qty" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="qtyIn">Provisional In</label>
                    <input type="number" min="0" max="9999" class="form-control" id="qtyIn" placeholder="Enter quantity">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                  <label for="qtyOut">Provisional Out</label>
                  <input type="number" min="0" max="9999" class="form-control" id="qtyOut" placeholder="Enter quantity">
                  </div>
                </div>
              </div>
                 
              <div class="form-group">
                <label for="notes">Miscellaneous</label>
                <input type="text" maxlength="255" class="form-control" id="notes" placeholder="Notes">
              </div>
              
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <input type="Button" class="btn btn-primary button_action_update" onClick="UpdateItem()" value="Update"></input>
              <input type="Button" class="btn btn-danger button_action_delete" onClick="Remove()" value="Delete"></input>
              <div class="btn-group">
                <button type="button" class="btn btn-default button_action_create">Add to...</button>
                  <button type="button" class="btn btn-default dropdown-toggle dropdown-icon button_action_create" data-toggle="dropdown">
                    <span class="sr-only">Toggle Dropdown</span>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" onClick=addTo(1);>Projects</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" onClick=addTo(2);>New fault report</a>
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
            <table id="registry_table" table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Registry ID</th>
                  <th>Serial Number</th>
                  <th>State</th>
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
            <input type="Button" class="btn btn-primary button_action_create" onClick="addTo(3)" value="Add"></input>
          </div>
        </div>
        <!-- /.card -->

        <div id="project_allocations" class="card" style="display:none">
          <div class="card-header">
            <h3 class="card-title">Project Allocations</h3>
          </div>
          <!-- /.card-header --> 
          <div class="card-body table-responsive p-0" style="max-height: 300px;">
            <table id="projects_table" table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Project</th>
                  <th>Quantity</th>
                </tr>
              </thead>
              <tbody> 
              </tbody>
          </table>
        </div>
        <!-- /.card-body --> 
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
$ROOT = '../';
include('../master.php');
?>

<script>

$(document).ready(function() {
  // populate category dropdown
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/inventory/categories/read",
    dataType: 'json',
    success: function(data) {
      var dropdowndata = "";
      for (var element in data) {
        dropdowndata += "<option value = '" + data[element].id + "'>" + data[element].name + "</option>";
      }
      // append dropdowndata to SKU dropdown
      $("#category").append(dropdowndata);


      // populate form
      $.ajax({
        type: "GET",
        cache: false,
        url: "../api/inventory/read_single" + "?id=" + <?php echo $_GET['id']; ?>,
        dataType: 'json',
        success: function(data) {
          $('#SKU').val(data['SKU']);
          $('#category').val(data['category']);

          // populate type dropdown
          $.ajax({
            type: "GET",
            cache: false,
            url: "../api/inventory/types/read?category=" + data['category'],
            dataType: 'json',
            success: function(list) {
              var dropdowndata = "";
              for (var element in list) {
                dropdowndata += "<option value = '" + list[element].id + "'>" + list[element].name + "</option>";
              }
            
              $("#category").prop('disabled', true); // disable category field
              $("#type").append(dropdowndata); // append dropdowndata to type dropdown
              $("#type").val(data['type']); // select correct type              
            
            },
            error: function(list) {
              console.log(list);
            },
          });

          $('#description').val(data['description']);
          $('#qty').val(data['qty']);
          $('#qtyIn').val(data['qtyIn']);
          $('#qtyOut').val(data['qtyOut']);
          $('#supplier').val(data['supplier']);
          $('#notes').val(data['notes']);
        },
        error: function(result) {
          console.log(result);
        },
      });

      
    },
    error: function(data) {
      console.log(data);
    },
  });
  
  // load project table
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/projects/read_allocations" + "?inventoryId=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      var project_table_data = "";
      for (var element in data) {
        $('#project_allocations').show();
        project_table_data += "<tr>" +
          "<td><a href='../projects?id=" + data[element].type_id + "'>" + data[element].type_name + "</a></td>" +
          "<td>" + data[element].total_qty + "</td>" +
          "</tr>";
      }
      $(project_table_data).appendTo($("#projects_table"));
    }
  });
  
  // load registry table
  $.ajax({
    type: "GET",
    cache: false,
    url: "../api/registry/read" + "?inventoryId=" + <?php echo $_GET['id']; ?>,
    dataType: 'json',
    success: function(data) {
      var registry_table_data = "";
      for (var element in data) {
        registry_table_data += "<tr>" +
          "<td>" + "#" + data[element].id + "</td>" +
          "<td>" + data[element].serialNumber + "</td>" +
          "<td><span class='badge badge-info'>" + data[element].state + "</span></td>" +
          "<td>" + data[element].datePurchased + "</td>" +
          "<td><button type='button' onClick=Deregister('" + data[element].id + "') class='btn btn-block btn-danger'>Delete</button></td>" +
          "</tr>";
      }
      $(registry_table_data).appendTo($("#registry_table"));
    }
  });

});

function UpdateItem() {
  $.ajax({
      type: "POST",
      url: '../api/inventory/update',
      dataType: 'json',
      data: {
        id: <?php echo $_GET['id']; ?>,
        SKU: $("#SKU").val(),
        type: $("#type").val(),
        category: $("#category").val(),
        description: $("#description").val(),
        qty: $("#qty").val(),
        qtyIn: $("#qtyIn").val(),
        qtyOut: $("#qtyOut").val(),
        supplier: $("#supplier").val(),
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
  var result = confirm("Are you sure you want to delete the item?");
  if (result == true) {
    $.ajax({
      type: "POST",
      url: '../api/inventory/delete',
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
      url: '../api/registry/delete',
      dataType: 'json',
      data: {
        id: id,
      },
      error: function(result) {
        alert(result.statusText);
      },
      success: function(result) {
        alert(result.message);
        if (result.status) {
          location.reload();
        }
      }
    });
  }
}

function addTo(type) {
  if (type == 1){
    location.href = "../projects/create?id=" + (<?php echo $_GET['id']; ?>);
  } else if (type == 2) {
    location.href = "../reports/create?id=" + (<?php echo $_GET['id']; ?>);
  } else {
    location.href = "../inventory/register?id=" + (<?php echo $_GET['id']; ?>);
  }
}

</script>