<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialStockActivity extends Model
{
    use HasFactory;
    protected $table='rawmaterial_stock_activity';
    protected $fillable = [
        'raw_id','issue_id','oldstock','newstock','stockIn','stockOut','createdBy','stock_date',
    ];
}