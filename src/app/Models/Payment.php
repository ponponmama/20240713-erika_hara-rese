<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reservation_id',
        'amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'total_payment_amount'
    ];

    // ユーザーリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 予約リレーション
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}