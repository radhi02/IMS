<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class EmailTemplate extends Model
{
    public $table = "email_templates";
    use HasFactory;
    use SoftDeletes;
    use AuditableWithDeletesTrait;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'tags',
        'status',
        'comp_id',
    ];
}
