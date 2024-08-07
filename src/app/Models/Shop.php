<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_name', 'area', 'genre', 'description', 'image', 'open_time', 'close_time'
    ];
    // ユーザーモデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'shop_id', 'user_id');
    }

    public static function getBusinessHoursForDate($date)
    {
        $shop = self::whereDate('created_at', '=', $date)->first();
        if ($shop) {
            return ['open_time' => $shop->open_time, 'close_time' => $shop->close_time];
        }
        return null;
    }

    
}
