var rootURL = document.getElementById("mainScript").getAttribute( "root-url" );

$(document).ready(function validate() {
  // Validate session characteristics
  $.ajax({
  type: "GET",
  cache: false,
  url: rootURL + 'api/users/validate_session',
  dataType: 'json',
    success: function(data) {
      if (data['status'] == false) {
        $(".card").addClass("collapsed-card"); // hide card content
        $('#modal-login').modal('toggle'); // toggle modal login
      } else {
        setTimeout(validate, 10000); // session validate every 10 seconds and upon page refresh

        // show/hide dashboard user_permission_alert accordingly
        if ( (data['canUpdate'] == false) || (data['canCreate'] == false) || (data['canImport'] == false) || (data['canDelete'] == false) ) {
          $("#user_permission_alert").show();
        } else {
          $("#user_permission_alert").hide();
        }
    
        // ...and disable/hide buttons accordingly
        if (data['canUpdate'] == false) {
          $(".button_action_update").prop("disabled",true);
        }
        if (data['canCreate'] == false) {
          $(".button_action_create").prop("disabled",true);
        }
        if (data['canImport'] == false) {
          $(".button_action_import").hide(); // card tools
        }
        if (data['canDelete'] == false) {
          $(".button_action_delete").prop("disabled",true);
          $(".button_action_delete").hide(); // card tools
        }
      }
    },
    error: function(data) {
      alert("Failed to validate session!");
      location.reload();
    }
  });

  // login form validation
  $.validator.setDefaults({
    submitHandler: function () {
      $.ajax({
        type: "POST",
        cache: false,
        url: rootURL + 'api/users/login',
        dataType: 'json',
        data: {
          username: $("#username").val(),
          password: $("#password").val(),
          remember: $("#remember_me_checkbox").is(':checked')  ? 1 : 0
        },
        error: function(data) {
          alert(data['message']);
        },
        success: function(data) {
          if (data['status'] == true) {
            location.reload();
          } else {
            alert(data['message']);
          }
        }
      });
    }
  });
  $('#loginForm').validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
      }
    },
    messages: {
      username: {
        required: "Please enter a username or email",
      },
      password: {
        required: "Please provide a password",
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});

// service worker for PWA
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register(rootURL + 'service-worker.js');
}

// matches system dark/light mode settings
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
	$(".navbar").removeClass('navbar-light').addClass('navbar-dark')
	$('body').addClass('dark-mode');
}
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
	const newColorScheme = e.matches ? "dark" : "light";
	if (newColorScheme == "dark"){
		$(".navbar").removeClass('navbar-light').addClass('navbar-dark')
		$('body').addClass('dark-mode');
	} else {
		$(".navbar").removeClass('navbar-dark').addClass('navbar-light')
		$('body').removeClass('dark-mode');
	}
});

// CSV file type validation for imports
$('#csvfile').on('change',function(){ 
  var regex = new RegExp("(.*?)\.(csv)$");
  var fileName = $(this).val(); // get the file name
  if (!(regex.test(fileName.toLowerCase()))) {
    toastr.error('Please select correct file format');
    $(this).next('.custom-file-label').html("");  // replace the file input label
  } else { // Show file name in dialog https://stackoverflow.com/questions/48613992/bootstrap-4-file-input-doesnt-show-the-file-name
    var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
    $(this).next('.custom-file-label').html(cleanFileName);  // replace the file input label
  }
});

// function to push DataTables search query to URL search parameter
function dtQueryToURL(table){
  var currentParams = new URLSearchParams(window.location.search); // get existing params
  table.on('search', function (){
    const params = new URLSearchParams({search: table.search()});
    const query = params.toString();
    var url = "";

    if (currentParams.get('id') != null){ // for pages with ?id parameter
      if (table.search() == ""){
        url = window.location.protocol + "//" +  window.location.hostname + window.location.pathname + "?id=" + currentParams.get('id');
      } else {
        url = window.location.protocol + "//" +  window.location.hostname + window.location.pathname + "?id=" + currentParams.get('id') + "&" + query;
      } 

    } else {
      if (table.search() == ""){
        url = window.location.protocol + "//" +  window.location.hostname + window.location.pathname;
      } else {
        url = window.location.protocol + "//" +  window.location.hostname + window.location.pathname + "?" + query;
      } 
    }
    window.history.replaceState({}, '', url);
  });
}

// log out user
function userLogout(){
  $.ajax({
    type: "POST",
    url: rootURL + 'api/users/logout',
    dataType: 'json',
    error: function(data) {
      alert(data.responseText);
    },
    success: function(data) {
      if (data['status'] == true) {
        location.reload();
      } else {
        alert(data['message']);
      }
    }
  });
}