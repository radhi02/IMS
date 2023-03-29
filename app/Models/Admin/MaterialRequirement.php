<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequirement extends Model
{
    public $table = "material_requirement";
    
    protected $fillable = [
        'raw_id','quantity','stock','requirement','pending_po','new_po','wip','fg'
    ];
}
