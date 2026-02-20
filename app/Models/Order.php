<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'remark',
    ];

    // Define relationship to OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }
}
