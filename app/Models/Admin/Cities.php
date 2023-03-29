<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cities extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $table="cities";
    public $fillable=['state_id','name',];
}
