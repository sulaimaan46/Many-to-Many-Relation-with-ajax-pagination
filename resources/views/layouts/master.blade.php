<!DOCTYPE html>
<html>
<head>
    <title>Posts CRUD Application</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ URL::asset('css/jquery.dataTables.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ URL::asset('css/parsley.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <script type="text/javascript" src="{{ URL::asset('js/libraryJs/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/libraryJs/bootstrap.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ URL::asset('js/libraryJs/jquery.dataTables.min.js') }}"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/customJs/index.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/libraryJs/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/libraryJs/parsley.js') }}"></script>



</head>
<body>

<div class="container">
    @yield('content')
</div>

</body>
</html>
