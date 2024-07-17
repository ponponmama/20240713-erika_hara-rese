<div class="menu-wrapper">
    <input type="checkbox" id="menu-toggle" class="menu-toggle">
    <label for="menu-toggle" class="hamburger-menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </label>
    <nav class="nav-menu">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            @guest
                <li><a href="{{ route('register') }}">Registration</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
            @endguest
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                            <button type="submit" class="logout-button">
                                Logout
                            </button>
                    </form>
                </li>
                <li><a href="{{ url('/mypage') }}">Mypage</a></li>
            @endauth
        </ul>
    </nav>
</div>
