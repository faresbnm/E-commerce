<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
{
    return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id')
                ->withPivot('quantity', 'price');
}

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
