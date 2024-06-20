<?php

namespace App\Providers;

use App\Events\Company\Auth\CompanyLogin;
use App\Events\Company\BalanceRecharge;
use App\Events\Company\CompanyRegistred;
use App\Events\Company\Reminders\VoiceReminder;
use App\Events\Company\Search\Searched;
use App\Events\Company\Search\ViewGstDetails;
use App\Events\Company\SmsSent;
use App\Events\GstFetch;
use App\Events\NotificationEvent;
use App\Events\PanFetch;
use App\Events\UnregistredCompanyCreated;
use App\Events\User\Registered as UserRegistered;
use App\Events\User\UserLoggedin;
use App\Events\User\VerificationToken\VerificationOtpEmail;
use App\Events\User\VerificationToken\VerificationOtpSms;
use App\Listeners\Company\Auth\AddCompanyLoginLog;
use App\Listeners\Company\Balance\AddBalance;
use App\Listeners\Company\Registration\GenerateCompanyProfile;
use App\Listeners\DeductBalance;
use App\Listeners\SendNotification;
use App\Listeners\User\Auth\AddLoginLog;
use App\Listeners\User\Registration\GenerateUserProfile;
use App\Listeners\User\Registration\SendWelcomeNotification;
use App\Listeners\User\Registration\SendWelcomeSms;
use App\Listeners\User\VerificationToken\SendVerificationOtpEmail;
use App\Listeners\User\VerificationToken\SendVerificationOtpSms;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        UserRegistered::class => [ 
            
        ],

        UserLoggedin::class => [
            AddLoginLog::class
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}