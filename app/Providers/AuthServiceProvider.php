<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medication;
use App\Models\Billing;
use App\Policies\DoctorPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\MedicationPolicy;
use App\Policies\BillingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        Doctor::class => DoctorPolicy::class,
        Medication::class => MedicationPolicy::class,
        Billing::class => BillingPolicy::class,
];
    
    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
