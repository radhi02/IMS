<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Auditable\AuditableWithDeletesTrait;
use App\Models\Admin\RoleModel;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory;
    use SoftDeletes;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


  

    protected $fillable = [
        'comp_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'user_status',
        'Role',
        'Image',
        'Phone',
        'Country',
        'State',
        'city',
        'pincode',
        'Address',
        'doj',
        'Per_Country',
        'Per_State',
        'Per_city',
        'Per_pincode',
        'Per_Address',
        'department_id',
        'gender',
        'marital_status',
        'bank_name',
        'bank_swiftcode',
        'bank_branch',
        'acc_number',
        'acc_name',
        'acc_ifsccode',
        'blood_group',
        'user_PAN',
        'user_ADHAR',
        'emp_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function getRoles()
    // {
     
    //     return $this->morphToMany(RoleModel::class,'id','Role');
    // }

    // User::resolveRelationUsing('Roles', function ($roles)
    // {
    //     return $roles->belongsTo(::class, 'Role');
    // });/
}
