<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demandnote extends Model
{
    use HasFactory;
    protected $table='demandnote';
    protected $fillable = [
        'code', 'mo_id', 'date', 'note',
    ];
}