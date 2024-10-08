<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    public function getUsers(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function getOrderItems(){
        return $this->hasMany(OrderItem::class,'order_id', 'id');
    }
}
