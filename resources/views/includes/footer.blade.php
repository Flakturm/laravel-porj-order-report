<script>
    var resizefunc = [];

    window.onload = function() {
        NProgress.start();
        NProgress.done();
    }

</script>

<!-- jQuery  -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/detect.js') }}"></script>
<script src="{{ asset('js/fastclick.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>

<script src="{{ asset('plugins/nprogress/nprogress.js') }}"></script>
<script src="{{ asset('plugins/notifyjs/js/notify.js') }}"></script>
<script src="{{ asset('plugins/notifications/notify-metro.js') }}"></script>

<script src="{{ asset('js/jquery.core.js') }}"></script>
<script src="{{ asset('js/jquery.app.js') }}"></script>


<script src="{{ asset('plugins/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>
@yield('editable_script')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.js') }}"></script>
@yield('page_script')