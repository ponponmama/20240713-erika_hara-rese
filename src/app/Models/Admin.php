<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

        public function shop()
    {
        return $this->belongsTo(Shop::class);
    }


}