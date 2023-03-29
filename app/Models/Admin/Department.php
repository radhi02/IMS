<?php


namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
 
    public $table = "departments";
    protected $fillable = [
        'department_name', 'department_code','status',
 
    ];
}
