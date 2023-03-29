<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockActivity extends Model
{
    use HasFactory;
    protected $table='product_stock_activity';
    protected $fillable = [
        'product_id','mo_id','so_id','oldstock','newstock','stockIn','stockOut','createdBy','stock_date',
    ];
}