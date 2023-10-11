<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class procurement extends Model
{
    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function manufacturers(){
        return $this->belongsTo(User::class, 'manufacturer_id');
    }
    protected $fillable =   [
        'product_id',
        'order_number',
        'quantity',
        'manufacturer_id',
    ];
}
