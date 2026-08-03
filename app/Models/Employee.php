<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','employee_code','first_name','last_name','date_of_birth','gender',
        'marital_status','personal_email','phone_number','alternate_phone_number',
        'current_address','permanent_address','department','designation',
        'date_of_joining','employment_status','blood_group',
        'emergency_contact_name','emergency_contact_number','profile_photo_path',
        'created_by','updated_by',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function familyMembers(): HasMany { return $this->hasMany(EmployeeFamilyMember::class); }
    public function educations(): HasMany { return $this->hasMany(EmployeeEducation::class); }
    public function experiences(): HasMany { return $this->hasMany(EmployeeExperience::class); }
}
