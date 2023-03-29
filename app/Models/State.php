<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;
use App\Models\Country;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableWithDeletesTrait;
    protected $fillable = [
        'country_id',
        'name',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
