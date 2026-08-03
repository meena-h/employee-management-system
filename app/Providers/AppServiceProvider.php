<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\EmployeeExperience;
use App\Models\EmployeeFamilyMember;
use App\Observers\EmployeeObserver;
use App\Policies\EducationPolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\FamilyMemberPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Employee::observe(EmployeeObserver::class);

        Gate::policy(EmployeeFamilyMember::class, FamilyMemberPolicy::class);
        Gate::policy(EmployeeEducation::class, EducationPolicy::class);
        Gate::policy(EmployeeExperience::class, ExperiencePolicy::class);
    }
}