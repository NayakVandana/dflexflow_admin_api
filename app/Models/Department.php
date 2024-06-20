<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubDepartment;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'is_active',
        'created_at',
        'updated_at', 
        'deleted_at',       
    ];
    public function subdepartment()
    {
        return $this->hasMany(SubDepartment::class, 'department_id', 'id');
    }
}
