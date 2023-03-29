<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Yajra\Auditable\AuditableWithDeletesTrait;

class Company extends Model
{
    public $table = "companies";
    use HasFactory;
    use SoftDeletes;
    // use AuditableWithDeletesTrait;

    protected $fillable = [
        'company_code',
        'company_name',
        'reg_off_add',
        'factory_add',
        'country',
        'state',
        'city',
        'pincode',
        'gst_no',
        'pan_no',
        'email',
        'website',
        'contact_no',
        'otherdetails',
        'logo',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
