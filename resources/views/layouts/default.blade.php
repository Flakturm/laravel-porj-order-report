<!DOCTYPE html>
<html>
	<head>
        @include('includes.head')
    </head>

	<body class="fixed-left">

		<!-- Begin page -->
		<div id="wrapper">

            <!-- Top Bar Start -->
            @include('includes.header')
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('includes.sidebar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                @yield('content')
                <!-- content -->
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

            <footer class="footer">
                © 2017. All rights reserved.
            </footer>

        </div>
        <!-- END wrapper -->
        
        @include('includes.footer')

    </body>
</html>