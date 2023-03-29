<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  
    public $table = "product";
    protected $fillable = [
        'name','category_id','sub_category_id','sku','price','quantity','description','status','product_BOM'
    ];
}
