<div class="menu-wrapper">
    <input type="checkbox" id="menu-toggle" class="menu-toggle">
    <label for="menu-toggle" class="hamburger-menu">
        <div class="bar-1"></div>
        <div class="bar-2"></div>
        <div class="bar-3"></div>
    </label>
    <nav class="nav-menu">
        <ul>
            <li><a href="{{ route('shops.index') }}" class="nav-link">Home</a></li>
            @guest
                <li><a href="{{ route('register') }}" class="nav-link">Registration</a></li>
                <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
            @endguest
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                            <button type="submit" class="logout-button">
                                Logout
                            </button>
                    </form>
                </li>
                <li><a href="{{ url('/mypage') }}"class="nav-link">Mypage</a></li>
            @endauth
        </ul>
    </nav>
</div>
