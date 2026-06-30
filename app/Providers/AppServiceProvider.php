<?php

namespace App\Providers;

use App\Listeners\StripeEventListener;
use App\Models\Subscription;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);

        Event::listen(WebhookReceived::class, StripeEventListener::class);

        $this->configureDefaults();
        $this->recordSuccessfulLogins();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function recordSuccessfulLogins(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            // Delete other sessions for this user from sessions table to prevent account sharing
            DB::table('sessions')
                ->where('user_id', $event->user->id)
                ->where('id', '!=', session()->getId())
                ->delete();

            $event->user->forceFill([
                'last_login_at' => now(),
                'login_count' => ((int) $event->user->login_count) + 1,
            ])->save();
        });
    }
}
