<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;
use App\Models\State;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableWithDeletesTrait;
    protected $fillable = [
        'state_id',
        'name',
    ];

    public function state()
    {
        return $this->belongsTo(State::class,'state_id','id');
    }
}
