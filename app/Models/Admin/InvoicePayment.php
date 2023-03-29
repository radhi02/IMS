<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
  
    public $table = "invoice_payment";
    protected $fillable = [
        'invoice_id','company_bank_id','received_amount','bank_details','reference_no','created_by'
    ];
}
