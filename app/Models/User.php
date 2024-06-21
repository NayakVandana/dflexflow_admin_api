<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helper\ReputeIdTraits;
use App\Helper\UserTokenTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserTokenTraits, ReputeIdTraits, SoftDeletes;

    protected $fillable = [
        'id',
        'flow_id',
        'photo',
        'name',
        'email',
        'password',
        'mobile',
        'pan_no',
        'aadhaar_no',
        'address_id',
        'aadhaar_details_id',
        'is_registered',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['formated_flow_id', 'photo_url', 'is_verified'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'mobile',
    // ];

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
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function tokens()
    {
        return $this->hasOne(UserToken::class);
    }

    protected function formatedFlowId(): Attribute
    {
        return Attribute::make(
            get: fn () => "FI-" . substr($this->flow_id, 0, 4) . "-" . substr($this->flow_id, 4, 4) . "-" . substr($this->flow_id, 8, 2)
        );
    }
 
    protected function photoUrl(): Attribute
    {
        // return Attribute::make(
        //     get: fn () => is_null($this->photo) ? null : Storage::disk('s3')->url($this->photo, Carbon::now()->addHour(24))
        // );

        return Attribute::make(
            get: fn () => is_null($this->photo) ? null : Storage::disk('local')->path($this->photo)
        );
    }

    public function user_login_log()
    {
        return $this->belongsTo(UserLoginLog::class);
    }

 

    public function isVerified() :  Attribute {
        return Attribute::make(
            get: fn () => $this->verified_at ? true : false
        );
    }

   
}