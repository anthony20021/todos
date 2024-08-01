<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
</head>
<body>
    <nav id="topbar" class="nav" style="z-index: 10">
        <ul class="flex">
            <li id="closeMenu" style="color: #e3dbeb;">Fermer</li>
            <li class="margin"><a class="nav-items todos" href="/">Todos</a></li>
            
            @guest
            <div class="deco flex">
                <li class="margin"><a class="btn" href="/login">Se connecter</a></li>
                <li class="margin"><a class="btn" href="/register">S'enregistrer</a></li>
            </div>
            @else
            <li class="margin"><a class="nav-items" href="/dashboard">Tableau de bord</a></li>
            <li class="margin"><a class="nav-items" href="#">Mon compte</a></li>
            <li class="margin deco">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn deco-wrap">Se déconnecter</button>
                </form>
            </li>
            @endguest
        </ul>
    </nav>
    <p id="openMenu"><img src="{{ asset('img/menu.png') }}" alt="menu" width="100%" height="100%"></p>

</body>
</html>
<script>
    const nav = document.getElementById('topbar');
    const toggleButton = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');

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
</script>
