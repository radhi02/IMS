<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
  
    public $table = "raw_material";
    protected $fillable = [
        'name','material_category_id','unit_id','location','quantity','description','status','code','HSN_CODE','GST'
    ];
}
