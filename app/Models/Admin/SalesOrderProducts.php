<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderProducts extends Model
{
  
    public $table = "sales_order_products";
    protected $fillable = [
        'sales_order_id','product_id','delivery_date','quantity','base_price','base_total','status'
    ];
}
