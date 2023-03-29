<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Countries extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $table="countries";
    public $fillable=['sortname','name'];
}
