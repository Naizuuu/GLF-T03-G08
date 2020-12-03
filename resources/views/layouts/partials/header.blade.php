<header class="masthead mb-auto">
    <div class="inner" style="{{request()->routeIs('home') ? 'display: none;' : 'display: block'}}">
        <h3 class="masthead-brand">Trabajo 3</h3>
        <nav class="nav nav-masthead justify-content-center">
            <a class="nav-link {{request()->routeIs('home') ? 'active' : ''}}" href="{{route('home')}}">Inicio</a>
            <a class="nav-link {{request()->routeIs('automatas') ? 'active' : ''}}" href="{{route('automatas')}}">Autómatas</a>
            <a class="nav-link disabled {{request()->routeIs('automata_afd') ? 'active' : ''}}" href="{{route('automata_afd')}}">AFD</a>
            <a class="nav-link disabled {{request()->routeIs('automata_ap') ? 'active' : ''}}" href="{{route('automata_ap')}}">AP</a>
        </nav>
    </div>
</header>