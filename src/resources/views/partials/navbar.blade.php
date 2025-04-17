    <div class="menu-wrapper">
        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="hamburger-menu">
            <div class="top-bar"></div>
            <div class="middle-bar"></div>
            <div class="bottom-bar"></div>
        </label>
        <nav class="nav-menu">
            <ul class="n-menu">
                @guest
                    <li><a href="{{ route('shops.index') }}" class="nav-link">Home</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link">Registration</a></li>
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                @endguest
                @auth
                    @if(auth()->user()->role == 3)
                        <li><a href="{{ route('shops.index') }}" class="nav-link">Home</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="logout-button-form">
                            @csrf
                                <button type="submit" class="logout-button">
                                    Logout
                                </button>
                        </form>
                    </li>
                    @if(auth()->user()->role === 3)
                        <li><a href="{{ url('/mypage') }}" class="nav-link">Mypage</a></li>
                    @endif
                    @if(auth()->user()->role === 2)
                        <li><a href="{{ url('/shop-manager/dashboard') }}" class="nav-link">Shop Dashboard</a></li>
                        <li><a href="{{ route('manage.shop') }}" class="nav-link">ShopPage</a></li>
                    @endif
                    @if(auth()->user()->role === 1)
                        <li><a href="{{ url('/admin/dashboard') }}" class="nav-link">Admin Dashboard</a></li>
                        <li><a href="{{ route('admin.reviews.index') }}" class="nav-link">レビュー管理</a></li>
                        <li><a href="{{ route('admin.shops.list') }}" class="nav-link">店舗一覧</a></li>
                    @endif
                @endauth
            </ul>
        </nav>
    </div>
