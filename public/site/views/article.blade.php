@include('site::_partials/header')
 
<h2>{{ $entry->title }}</h2>
<h4>Published at {{ $entry->created_at }}</h4>
{{ $entry->body }}
 
<hr>
 
<a href="{{ URL::route('article.list') }}">&laquo; Back to articles</a>
 
@include('site::_partials/footer')