<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    public $table = "material_category";
    
    protected $fillable = [
        'name','status','description',
 
    ];
}
