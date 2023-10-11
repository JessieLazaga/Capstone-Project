<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function procurement()
    {
        return $this->hasMany(procurement::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function dayscount()
    {
        return $this->hasMany(dayscount::class);
    }
    public function Expiry()
    {
        return $this->hasMany(Expiry::class);
    }
    protected $fillable = [
        'name',
        'image',
        'barcode',
        'price',
        'quantity',
        'ordered',
        'par_level',
        'manufacturer_id',
        'location',
    ];
}
