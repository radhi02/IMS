<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $table = "units";
    protected $fillable = [
        'unit_name','status','description',
 
    ];
}
