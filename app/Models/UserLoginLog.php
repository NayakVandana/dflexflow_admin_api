<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'login_type',
        'os_version',
        'app_version', 
        'login_at',       
        'logout_at',    
        'created_at',   
        'updated_at',   
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
