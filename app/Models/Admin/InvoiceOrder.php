<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceOrder extends Model
{
  
    public $table = "invoice_order";
    protected $fillable = [
        'so_id','customer_id','code','order_products','cgst','igst','sgst','base_grandtotal','base_subtotal','base_tax_amount','due_amount','receivable_amount','base_total_quantity','base_total_rate','due_date','created_by','status'
    ];
}
