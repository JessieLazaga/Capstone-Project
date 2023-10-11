<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =[
        'user_id',
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
        
    public function payment(){
        return $this->hasMany(Payment::class);
    }
    public function products(){
        return $this->hasMany(OrderItem::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function getUserId(){
        if($this->user){
            return $this->user->name;
        }
    }
    public function getTotal(){
        return $this->items->map(function($i){
            return $i->price;
        })->sum();
    }
    public function getProduct(){
        return $this->items->map(function($i){
            return $i->product_id;
        });
    }
    public function formattedTotal(){
        return number_format($this->getTotal(), 2,);
    }
    public function permTotal(){
        return $this->payment->map(function($i){
            return $i->total_amount;
        })->sum();
    }
    public function formattedPerm(){
        return number_format($this->permTotal(), 2,);
    }
    public function getReceived(){
        return $this->payment->map(function($i){
            return $i->amount;
        })->sum();
    }
    public function formattedReceived(){
        return number_format($this->getReceived(), 2,);
    }   
}
