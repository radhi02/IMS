<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderMaterials extends Model
{
  
    public $table = "purchase_order_materials";
    protected $fillable = [
        'purchase_order_id','raw_material_id','quantity','tax_percentage','remained_quantity','base_price','base_tax','base_subtotal','base_total','status'
    ];
}
