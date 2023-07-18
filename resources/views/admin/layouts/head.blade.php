<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nion TV</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/images/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/images/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/images/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/images/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/images/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/images/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/images/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/images/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('/images/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/images/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/favicon-16x16.png')}}">

    <link rel="shortcut icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{asset('/admin/template/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/css/bootstrap-extend.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/css/site.min.css')}}">

    <!-- Plugins -->
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/animsition/animsition.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/asscrollable/asScrollable.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/switchery/switchery.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/intro-js/introjs.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/slidepanel/slidePanel.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/flag-icon-css/flag-icon.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/blueimp-file-upload/jquery.fileupload.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/dropify/dropify.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">

    <link rel="stylesheet" href="{{asset('/admin/template/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">

    <!-- Jsgrid -->
    <link rel="stylesheet" href="{{asset('/admin/template/css/jsgrid-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/Plugins/jsgrid/css/jsgrid.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/Plugins/jsgrid/css/jsgrid-theme.min.css')}}">

    <!-- custom made css -->
    <link rel="stylesheet" href="{{asset('/admin/template/css/js-grid-table.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/css/general.css')}}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('/admin/template/fonts/font-awesome/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/fonts/web-icons/web-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/fonts/brand-icons/brand-icons.min.css')}}">
    <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>



    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css')}}">

    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('/admin/template/examples/css/tables/datatable.css')}}">


    <link rel="stylesheet" href="{{asset('/admin/template/vendor/select2/select2.css')}}">


    <link rel="stylesheet" href="{{asset('/admin/template/vendor/morris/morris.css')}}">

    <!-- Scripts -->
    <script src="{{asset('/admin/template/vendor/breakpoints/breakpoints.js')}}"></script>
    <script>
        Breakpoints();
    </script>

    <style>
        .disable input{
            background:#eee;
            border:none;
            outline: none;
        }
    </style>

    @yield('css')
</head>

