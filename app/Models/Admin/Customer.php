<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Customer extends Model
{
    public $table = "customers";
    protected $fillable = [
        'customer_name', 'customer_code', 'customer_contactperson', 'customer_email', 'customer_phone', 'customer_street', 'city', 'state', 'country', 'customer_zipcode', 'customer_status','customer_GST','customer_PAN','Location',
 
    ];
}
