<?php

namespace App\Providers;

use Gate;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

/**
 * Class TelescopeServiceProvider
 */
class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
//            if ($this->app->isLocal()) {
//                return true;
//            }

            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag() ||
                $entry->type == EntryType::REQUEST ||
                $entry->type == EntryType::JOB ||
                $entry->type == EntryType::LOG ||
                $entry->type == EntryType::QUERY ||
                $entry->type == EntryType::COMMAND ||
                $entry->type == EntryType::MAIL ||
                $entry->type == EntryType::REDIS ||
                $entry->type == EntryType::EVENT;
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
//        if ($this->app->isLocal()) {
//            return;
//        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'bhavesh@gmail.com', 'vishal@gmail.com', 'mitul@gmail.com',
            ]);
        });
    }
}
