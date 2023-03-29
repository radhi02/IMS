<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Banks extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableWithDeletesTrait;
    protected $fillable = [
        'BName',
        'BIFSC',
        'BSWIFTCODE',
        'Branch',
        'Baccount',
        'BMICR',
        'status',
        'comp_id',
    ];
}
