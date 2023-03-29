<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issuematerial extends Model
{
    use HasFactory;
    protected $table='issuematerial';
    protected $fillable = [
        'code','dn_id','mo_id','issue_date','materialnote','status'
    ];
}