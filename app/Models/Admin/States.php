<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class States extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $table="states";
    public $fillable=['country_id','name',    'comp_id',];
}
