<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
  
    public $table = "manufacture_order";
    protected $fillable = [
        'code','uniqueid','status','bom_detail','sales_order_id','product_id','delivery_date','quantity','approved_quantity','rejected_quantity','check_quantity','demand_status','issue_status','consume_status'
    ];
}
