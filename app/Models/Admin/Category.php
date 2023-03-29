<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = "categorys";
    
    protected $fillable = [
        'name','status','description',
    ];
}
