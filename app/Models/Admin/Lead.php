<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Lead extends Model
{
    public $table = "leads";
    protected $fillable = [
        'customer_id','comment',
 
    ];
}
