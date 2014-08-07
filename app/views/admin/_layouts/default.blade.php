<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <title>L4 Site</title>
        {{HTML::style('site/assets/css/jasny-bootstrap.css')}}
        {{HTML::style('site/assets/css/jasny-bootstrap.min.css')}}        
        {{HTML::style('site/assets/css/main.css')}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        {{ HTML::script('site/assets/js/script.js') }}       
        {{ HTML::script('site/assets/js/jasny-bootstrap.min.js') }}
        {{ HTML::script('site/assets/js/jasny-bootstrap.js') }}
</head>
<body>
<div class="container">
        <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
                <div class="container">
                        <a class="brand" href="{{ URL::route('admin.pages.index') }}">L4 Site</a>
                </div>
        </div>
</div>
 
<hr>
 
        @yield('main')
</div>
</body>
</html>