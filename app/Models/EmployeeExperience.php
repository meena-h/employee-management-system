<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExperience extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'company_name',
        'designation',
        'start_date',
        'end_date',
        'responsibilities',
        'total_experience_months',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}