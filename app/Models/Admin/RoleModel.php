<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;
class RoleModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="role_models";
    public $fillable = ["role_name","desc","status",'comp_id',];

    protected $dates = ['deleted_at'];

    
}
