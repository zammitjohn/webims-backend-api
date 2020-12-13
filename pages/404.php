<?php
$content = '
<!-- Content Header (Page header) -->
<section class="content-header">
</section>

<!-- Main content -->
<section class="content">
  <div class="error-page">
	<h2 class="headline text-warning"> 404</h2>

	<div class="error-content">
	  <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

		We could not find the page you were looking for.
		Meanwhile, you may return to dashboard</a>.
	  
	</div>
	<!-- /.error-content -->
  </div>
  <!-- /.error-page -->
</section>
<!-- /.content -->
';
$title = "Page not found";
$ROOT = '/WebIMS/';
include('../master.php');
?>
