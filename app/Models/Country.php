<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableWithDeletesTrait;
    protected $fillable = [
        'sortname',
        'name',
        'phonecode',
    ];
}
