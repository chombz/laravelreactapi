<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'orderitems';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // ADD THIS RELATIONSHIP - This is what's missing!
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Relationship back to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
