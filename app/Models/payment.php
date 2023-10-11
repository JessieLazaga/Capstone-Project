<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $fillable =   [
        'amount',
        'order_id',
        'user_id',
        'total_amount',
        'name',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
