<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image' , 'qty' , 'tax_type' , 'tax'];

    public function getFinalPriceAttribute()
    {
        if ($this->tax_type === 'percent') {
            return $this->price + ($this->price * $this->tax_value / 100);
        }

        return $this->price + $this->tax_value;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
