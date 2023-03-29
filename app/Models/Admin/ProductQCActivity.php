<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQCActivity extends Model
{
    use HasFactory;
    protected $table='product_qc_activity';
    protected $fillable = [
        'product_id','mo_id','quantity','status','reason','mo_status'
    ];
}
