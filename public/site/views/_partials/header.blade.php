<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel 4 Tutorial</title>
    {{HTML::style('site/assets/css/main.css')}}    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.21/angular.min.js"></script>
    {{ HTML::script('site/assets/js/angular/controllers/mainCtrl.js') }}
    {{ HTML::script('site/assets/js/angular/services/commentService.js') }}
    {{ HTML::script('site/assets/js/angular/app.js') }}
</head>
<body>
<div id="layout">
    <header>
        <h1><a href="{{ URL::route('home') }}">Laravel 4 Tutorial</a></h1>
        @include('site::_partials.navigation')
    </header>
 
    <hr>