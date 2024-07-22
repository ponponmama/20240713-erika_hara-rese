<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_name', 'description', 'genre', 'area', 'image'];

    // ユーザーモデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
