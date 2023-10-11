<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $fillable =   [
        'price',
        'quantity',
        'order_id',
        'product_id',
    ];

    
}
