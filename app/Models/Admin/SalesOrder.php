<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
  
    public $table = "sales_order";
    protected $fillable = [
        'customer_id','order_date','code','payment_terms','delivery_mode','description','cgst','igst','sgst','order_products','base_grandtotal','base_subtotal','base_tax_amount','base_total_quantity','base_total_rate','created_by','status'
    ];
}
