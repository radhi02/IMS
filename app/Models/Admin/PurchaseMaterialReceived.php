<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMaterialReceived extends Model
{
  
    public $table = "purchase_materials_recieved";
    protected $fillable = [
        'purchase_order_id','purchase_order_material_id','raw_material_id','quantity','received_quantity','date','status'
    ];
}
