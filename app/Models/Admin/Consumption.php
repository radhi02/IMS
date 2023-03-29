<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory;
    protected $table='consumption';
    protected $fillable = [
        'code','issue_id','mo_id','consumptionnote','status',
    ];
}