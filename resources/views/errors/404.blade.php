
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://use.fontawesome.com/a6c9eed133.js"></script>
        <title>Page Not Found </title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" >

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="app" style="margin-top: 18%;">
                        <h2 class="text-center"> Page Not Found !</h2>
                        <h1 class="text-center"> <a href="{{ URL::previous() }}"> <a href="{{ url('/home') }}"> Go On Home Page</a> </h1>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>

