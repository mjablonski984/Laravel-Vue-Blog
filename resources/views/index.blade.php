<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">    
        <title>Laravel Blog</title>
        <script>
        // Generate crsf token (used in forms to prevent 419 error)
           (function () {
               window.Laravel = {
                   csrfToken: '{{ csrf_token() }}'
               };
           })();         
        </script>
    </head>
    <body class="font-body bg-gray-100" >
            
        <div id="app">
            <!--Main App Component  -->
            <App></App>
        </div>
    
        <!-- Main scripts -->
        <script src="{{mix('/js/app.js')}}"></script>
    </body>
</html>
