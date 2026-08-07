<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFamilyMember extends Model

{
    use SoftDeletes;
    
    protected $fillable = [
        'employee_id',
        'name',
        'relationship',
        'date_of_birth',
        'occupation',
        'contact_number'
    ];

    public function employee(): BelongsTo 
    { 
        return $this->belongsTo(Employee::class); 
    }
}
