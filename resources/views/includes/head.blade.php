<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="AndyWebDev">
<meta name="_token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('images/favicon_1.ico') }}">

<title>@yield('page_title')</title>

@yield('page_styles')

<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/core.css') }}" rel="stylesheet" />
<link href="{{ asset('css/components.css') }}" rel="stylesheet" />
<link href="{{ asset('css/icons.css') }}" rel="stylesheet" />
<link href="{{ asset('css/pages.css') }}" rel="stylesheet" />
<link href="{{ asset('css/responsive.css') }}" rel="stylesheet" />

<link href="{{ asset('plugins/nprogress/nprogress.css') }}" rel="stylesheet" />

<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script src="{{ asset('js/modernizr.min.js') }}"></script>