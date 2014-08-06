<nav>
    <ul>
        <li><a href=&quot;{{ URL::route('home') }}&quot;>Home</a></li>
        <li><a href=&quot;{{ URL::route('page', 'about-us') }}&quot;>About us</a></li>
        <li><a href=&quot;{{ URL::route('article.list') }}&quot;>Blog</a></li>
        <li><a href=&quot;{{ URL::route('page', 'contact') }}&quot;>Contact</a></li>
    </ul>
</nav>