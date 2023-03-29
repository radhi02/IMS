<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_name', 'vendor_code', 'vendor_contactperson', 'vendor_email', 'vendor_phone', 'vendor_street', 'city', 'state', 'country', 'vendor_zipcode', 'vendor_status','vendor_GST','vendor_PAN','vendor_type','vendor_MSME','vendor_MSME_number','Location',
    ];
}