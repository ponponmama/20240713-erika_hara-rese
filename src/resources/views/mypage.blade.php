@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('users_css/mypage.css') }}">
@endsection

@section('content')
    @include('custom_components.header', [
        'showMessage' => true,
        'useFormTitle' => false,
        'message' =>
            \Carbon\Carbon::now()->hour < 12
                ? 'おはようございます！'
                : (\Carbon\Carbon::now()->hour < 18
                    ? 'こんにちは！'
                    : 'こんばんは！'),
    ])
    <div class="container">
        <div class="reservation-section">
            <h2 class="title-name section-title">予約状況</h2>
            <p class="session-messages">
                @include('custom_components.session-messages', [
                    'showReservation' => true,
                    'showGeneral' => true,
                    'showFavorite' => false,
                ])
            </p>
            @foreach ($reservations as $reservation)
                @if ($reservation->id != $hideReservationId)
                    <div class="reservation-summary" id="reservation-{{ $reservation->id }}">
                        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST"
                            id="update-form-{{ $reservation->id }}" class="update_form">
                            @csrf
                            @method('PUT')
                            <a href="{{ route('mypage', ['hide_reservation' => $reservation->id]) }}"
                                class="button close-button">
                                <img src="{{ asset('images/cross.png') }}" alt="Close" class="cross_image">
                            </a>
                            <div class="reservation-summary-item">
                                <img src="{{ asset('images/clock.svg') }}" alt="Clock Icon" class="clock-icon">
                                <p class="reservation-summary-date">予約{{ $loop->iteration }}</p>
                            </div>
                            <div class="reservation-summary-view" id="view-{{ $reservation->id }}">
                                <fieldset class="reservation-field">
                                    <label class="view-label">shop</label>
                                    <p class="view-data">
                                        {{ $reservation->shop->shop_name }}
                                    </p>
                                </fieldset>
                                <fieldset class="reservation-field">
                                    <label class="view-label">Date</label>
                                    <p class="view-data">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('Y-m-d') }}
                                    </p>
                                </fieldset>
                                <fieldset class="reservation-field">
                                    <label class="view-label">Time</label>
                                    <p class="view-data">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('H:i') }}
                                    </p>
                                </fieldset>
                                <fieldset class="reservation-field">
                                    <label class="view-label">人数</label>
                                    <p class="view-data">
                                        {{ $reservation->number . '人' }}
                                    </p>
                                </fieldset>
                            </div>
                            <div class="form-group edit-form" id="edit-{{ $reservation->id }}" style="display: none;">
                                <span class="form-label">shop</span>
                                <div class="select-wrapper">
                                    <span class="data-entry name-entry">
                                        {{ $reservation->shop->shop_name }}
                                    </span>
                                </div>
                            </div>
                            <div class="form-group edit-form" id="edit-{{ $reservation->id }}" style="display: none;">
                                <label for="date" class="form-label label_date">Date</label>
                                <div class="select-wrapper">
                                    <input type="date" id="date" name="date" class="data-entry input_date"
                                        value="{{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group edit-form" id="edit-{{ $reservation->id }}" style="display: none;">
                                <label for="time" class="form-label">Time:</label>
                                <div class="select-wrapper">
                                    <select id="time" name="time" class="data-entry select_time">
                                        @foreach ($reservation->times as $time)
                                            <option value="{{ $time }}"
                                                {{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('H:i') == $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                            </div>
                            <div class="form-group edit-form" id="edit-{{ $reservation->id }}" style="display: none;">
                                <label for="number" class="form-label">人数</label>
                                <div class="select-wrapper">
                                    <select id="number" name="number" class="data-entry select_number">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}"
                                                {{ $reservation->number == $i ? 'selected' : '' }}>
                                                {{ $i }}人
                                            </option>
                                        @endfor
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                            </div>
                        </form>
                        <div class="reservation-button-container">
                            <button type="button" class="button reservation-button"
                                onclick="toggleEditForm({{ $reservation->id }})">
                                変更
                            </button>
                            <button type="submit" class="button reservation-button"
                                form="update-form-{{ $reservation->id }}" style="display: none;"
                                id="update-button-{{ $reservation->id }}">
                                更新
                            </button>
                            <button type="button" class="button reservation-button"
                                onclick="toggleEditForm({{ $reservation->id }})" style="display: none;"
                                id="cancel-button-{{ $reservation->id }}">
                                キャンセル
                            </button>
                            <button type="submit" class="button reservation-button"
                                form="delete-form-{{ $reservation->id }}">
                                削除
                            </button>
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                id="delete-form-{{ $reservation->id }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        <div class="payment-button-container">
                            @if ($reservation->payment_status === 'amount_set')
                                <a href="{{ route('payment.form', ['reservation_id' => $reservation->id]) }}"
                                    class="button payment-button">
                                    支払う
                                </a>
                            @elseif ($reservation->payment_status === 'failed')
                                <a href="{{ route('payment.form', ['reservation_id' => $reservation->id]) }}"
                                    class="button payment-button">
                                    再決済
                                </a>
                            @elseif ($reservation->payment_status === 'completed')
                                <span class="payment-amount">お支払いありがとうございました</span>
                            @else
                                <span class="payment-amount">金額決定するとボタンが表示されます</span>
                            @endif
                        </div>
                        <img src="{{ asset('storage/' . $reservation->qr_code) }}"
                            alt="QR Code for Reservation {{ $reservation->id }}" class="qr_code_image">
                    </div>
                    @if ($reservation->payment_status === 'completed')
                        <form action="{{ route('reviews.store') }}" method="POST" class="store_form">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $last_visited_shop_id }}">
                            <div class="form-group rating-group">
                                <label for="rating" class="form-label label_rating">
                                    評価
                                </label>
                                <div class="select-wrapper">
                                    <select name="rating" id="rating" class="data-entry select_rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                            </div>
                            <div class="form-group rating-group">
                                <label for="comment" class="form-label label_comment">コメント</label>
                                <textarea name="comment" id="comment" class="data-entry text_comment"></textarea>
                            </div>
                            <button type="submit" class="button review-button">レビューを投稿</button>
                        </form>
                    @endif
                @endif
            @endforeach
        </div>
        <div class="favorite-shops-section">
            <h2 class="title-name favorite-title">お気に入り店舗</h2>
            <p class="session-messages">
                @include('custom_components.session-messages', [
                    'showGeneral' => false,
                    'showReservation' => false,
                    'showFavorite' => true,
                ])
            </p>
            <div class="favorite-shops">
                @foreach ($favorites as $favorite)
                    <div class="shop_card">
                        <img src="{{ asset('storage/' . $favorite->image) }}" alt="{{ $favorite->shop_name }}"
                            class="shop_card_image">
                        <div class="shop_info">
                            <h3 class="shop-name">
                                {{ $favorite->shop_name }}
                            </h3>
                            <p class="shop-guide">
                                @foreach ($favorite->areas as $area)
                                    ＃{{ $area->area_name }}
                                @endforeach
                                @foreach ($favorite->genres as $genre)
                                    ＃{{ $genre->genre_name }}
                                @endforeach
                            </p>
                            @include('custom_components.shop-buttons', [
                                'shop' => $favorite,
                                'routeName' => 'shop.details',
                                'showFavoriteForm' => true,
                            ])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

<script>
    function toggleEditForm(reservationId) {
        const viewElement = document.getElementById(`view-${reservationId}`);
        const editElements = document.querySelectorAll(`#edit-${reservationId}`);
        const updateButton = document.getElementById(`update-button-${reservationId}`);
        const editButton = document.querySelector(`button[onclick="toggleEditForm(${reservationId})"]`);
        const cancelButton = document.getElementById(`cancel-button-${reservationId}`);
        // 削除ボタンを取得
        const deleteButton = document.querySelector(`button[form="delete-form-${reservationId}"]`);

        if (viewElement.style.display !== 'none') {
            // 表示モードから編集モードへ
            viewElement.style.display = 'none';
            editElements.forEach(element => {
                element.style.display = 'flex';
            });
            updateButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
            editButton.style.display = 'none';
            // 削除ボタンを非表示にする
            deleteButton.style.display = 'none';
        } else {
            // 編集モードから表示モードへ
            viewElement.style.display = 'flex';
            editElements.forEach(element => {
                element.style.display = 'none';
            });
            updateButton.style.display = 'none';
            cancelButton.style.display = 'none';
            editButton.style.display = 'inline-block';
            // 削除ボタンを再表示する
            deleteButton.style.display = 'inline-block';
        }
    }
</script>
