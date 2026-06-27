<!doctype html>
<html lang="en" dir="ltr">
	<head>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Zanex – Bootstrap  Admin & Dashboard Template">
		<meta name="author" content="Spruko Technologies Private Limited">
		<meta name="keywords" content="admin, dashboard, dashboard ui, admin dashboard template, admin panel dashboard, admin panel html, admin panel html template, admin panel template, admin ui templates, administrative templates, best admin dashboard, best admin templates, bootstrap 4 admin template, bootstrap admin dashboard, bootstrap admin panel, html css admin templates, html5 admin template, premium bootstrap templates, responsive admin template, template admin bootstrap 4, themeforest html">

		@include('components.style')
        @stack('styles')

	</head>

	<body class="app sidebar-mini">

		<!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
		</div>
		<!-- /GLOBAL-LOADER -->

		<!-- PAGE -->
		<div class="page">
			<div class="page-main">

				<!--APP-SIDEBAR-->
				@include('components.sidebar')
				<!--/APP-SIDEBAR-->

				<!-- Mobile Header -->
				@include('components.navbar')
				<!-- /Mobile Header -->

                <!--app-content open-->
				<div class="app-content">
					@yield('content')
				</div>
				<!-- CONTAINER END -->
            </div>

			@include('components.footer')
		</div>

		<!-- BACK-TO-TOP -->
		<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

		@include('components.scripts')

	</body>
</html>