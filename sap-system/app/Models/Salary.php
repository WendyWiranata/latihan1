<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'basic_salary',
        'allowance',
        'bonus',
        'tax',
        'total_salary',
        'payment_date',
        'payment_method',
        'notes'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}