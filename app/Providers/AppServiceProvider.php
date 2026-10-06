<?php

namespace App\Providers;

use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;

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
        // The settings live in the database, so skip them while it is missing
        // or not migrated yet (e.g. running `php artisan migrate` on a fresh DB).
        try {
            $general = general();
        } catch (QueryException $e) {
            return;
        }

        if (!$general) {
            return;
        }

        // Google / Facebook keys: the admin settings win when filled, otherwise the .env values stay.
        $social = [
            'facebook.client_id' => $general->fb_app_id,
            'facebook.client_secret' => $general->fb_app_secret,
            'facebook.redirect' => $general->fb_app_redirect_url,
            'google.client_id' => $general->google_client_id,
            'google.client_secret' => $general->google_client_secret,
            'google.redirect' => $general->google_client_redirect_url,
        ];
        foreach ($social as $key => $value) {
            if (trim((string) $value) !== '' && !str_starts_with(trim((string) $value), '#')) {
                \Config::set("services.$key", $value);
            }
        }

        \Config::set("mail.mailers.smtp.transport", $general->mail_driver);
        \Config::set("mail.mailers.smtp.host", $general->mail_host);
        \Config::set("mail.mailers.smtp.port", $general->mail_port);
        \Config::set("mail.mailers.smtp.encryption", $general->mail_encryption);
        \Config::set("mail.mailers.smtp.username", $general->mail_username);
        \Config::set("mail.mailers.smtp.password", $general->mail_password);
    }
}
