<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <title>L4 Site</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 
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