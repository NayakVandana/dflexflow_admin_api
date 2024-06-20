<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'parameters',
        'code',
        'file',
        'line',
        'message',
        'trace',
        'created_at',
        'updated_at',
    ];

    // user, company 
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
