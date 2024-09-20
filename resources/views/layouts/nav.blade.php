<!DOCTYPE html>
<html lang="en">
<body>
    <nav id="topbar" class="nav" style="z-index: 10">
        <ul class="flex">
            <li id="closeMenu" style="color: #e3dbeb;">Fermer</li>
            <li class="margin"><a class="nav-items todos" id="todos-icon" href="/">Todos</a></li>

            @guest
            <div class="deco flex">
                <li class="margin"><a class="btn" href="/login">Se connecter</a></li>
                <li class="margin"><a class="btn" href="/register">S'enregistrer</a></li>
            </div>
            @else
            <li class="margin"><a class="nav-items" href="/dashboard">Tableau de bord</a></li>
            <li class="margin"><a class="nav-items" href="/myAccount">Mon compte</a></li>
            @if (Auth::user() && Auth::user()->hasPermission('admin'))
                <li class="margin"><a class="nav-items" href="/admin/dashboard">Administration</a></li>
            @endif
            <li class="margin deco">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn deco-wrap">Se déconnecter</button>
                </form>
            </li>
            @endguest
            <li class="margin"><a class="nav-items" href="/cgu">CGU</a></li>
        </ul>
    </nav>
    <p id="openMenu"><img src="{{ asset('img/menu.png') }}" alt="menu" width="100%" height="100%"></p>

</body>
</html>
<script>
    const nav = document.getElementById('topbar');
    const toggleButton = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const todosIcon = document.getElementById('todos-icon');

    let lastScrollY = window.scrollY;

    // Toggle pour ouvrir/fermer le menu sur petit écran
    toggleButton.addEventListener('click', () => {
        nav.style.right = '0px';
    });

    closeMenu.addEventListener('click', () => {
        nav.style.right = '-150px';
    });

    document.addEventListener('click', (event) => {
        const isClickInsideNav = nav.contains(event.target);
        const isClickOnToggleButton = toggleButton.contains(event.target);

        if (!isClickInsideNav && !isClickOnToggleButton && window.innerWidth < 1024) {
            nav.style.right = '-150px';
        }
    });

    // Gestion du scroll
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY) {
            todosIcon.style.top = '-80px';
            toggleButton.style.top = '-80px';
        } else {
            todosIcon.style.top = '20px';
            toggleButton.style.top = '20px';
        }

        // Met à jour la dernière position de scroll
        lastScrollY = currentScrollY;
    });
</script>
