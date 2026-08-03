<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEducation extends Model
{
    use SoftDeletes;

    protected $table = 'employee_educations';   

    protected $fillable = [
        'employee_id',
        'institution',
        'degree',
        'specialization',
        'year_of_passing',
        'score_type',
        'score_value',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}