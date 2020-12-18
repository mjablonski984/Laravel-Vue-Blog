<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
        <link rel="manifest" href="/icons/site.webmanifest">
        <!-- Main css file  load secure asset -https if in production -->  
 	    @if(env('APP_ENV') == 'production')
        <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
        @else  
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @endif  
        <title>DevBlog</title>
        <script>
        // Generate crsf token (used in forms to prevent laravel 419 error)
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
