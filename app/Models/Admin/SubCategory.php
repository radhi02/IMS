<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    
    public $table = "subcategorys";
    protected $fillable = [
        'name','status','category_id','description',
 
    ];
}
