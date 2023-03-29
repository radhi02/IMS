<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
  
    public $table = "purchase_order";
    protected $fillable = [
        'code','vendor_id','date','description','igst','sgst','cgst','base_subtotal','base_tax_amount','base_grandtotal','
        ','created_by','status'
    ];
}
