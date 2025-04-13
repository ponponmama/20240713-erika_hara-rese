@php
    $routeName = $routeName ?? 'shop.details.guest';
    $showFavoriteForm = $showFavoriteForm ?? true;
@endphp

<div class="button-container">
    <a href="{{ route($routeName, ['id' => $shop->id]) }}" class="button shop-detail" title="店舗の詳細情報を表示">詳しくみる</a>
    @auth
        @if(auth()->user()->favorites->contains($shop))
            <form action="{{ route('shops.unfavorite', $shop) }}" method="POST" @class(['favorite_form' => $showFavoriteForm])>
                @csrf
                @method('DELETE')
                <button type="submit" class="button favorite-button favorite" title="お気に入りから削除">❤</button>
            </form>
        @else
            <form action="{{ route('shops.favorite', $shop) }}" method="POST" @class(['favorite_form' => $showFavoriteForm])>
                @csrf
                <button type="submit" class="button favorite-button" title="お気に入りに追加">❤</button>
            </form>
        @endif
    @endauth
</div>
