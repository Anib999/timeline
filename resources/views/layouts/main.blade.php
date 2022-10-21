<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <script src="https://use.fontawesome.com/a6c9eed133.js"></script> -->
        <title> Timeline </title>

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" >
        @yield('head')
    </head> 
    <body>
        <div id="app">
            @yield('content')
            <!-- Scripts -->
        </div>
        <script src="{{ asset('js/app.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('input.hasDatepicker').attr('autocomplete','off');
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
    </body>
</html>
