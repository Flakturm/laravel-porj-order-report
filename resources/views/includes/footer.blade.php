<script>
    var resizefunc = [];

    window.onload = function() {
        NProgress.start();
        NProgress.done();
    }

</script>

<!-- jQuery  -->
<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/detect.js') }}"></script>
<script src="{{ asset('public/js/fastclick.js') }}"></script>
<script src="{{ asset('public/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('public/js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('public/js/waves.js') }}"></script>
<script src="{{ asset('public/js/wow.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/js/jquery.scrollTo.min.js') }}"></script>

<script src="{{ asset('public/plugins/nprogress/nprogress.js') }}"></script>
<script src="{{ asset('public/plugins/notifyjs/js/notify.js') }}"></script>
<script src="{{ asset('public/plugins/notifications/notify-metro.js') }}"></script>

<script src="{{ asset('public/js/jquery.core.js') }}"></script>
<script src="{{ asset('public/js/jquery.app.js') }}"></script>


<script src="{{ asset('public/plugins/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>
@yield('editable_script')
<script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/plugins/datatables/dataTables.bootstrap.js') }}"></script>
@yield('page_script')