<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function sub_category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function product_information() {
        return $this->hasOne(Product::class, 'product_id', 'id');
    }
}
