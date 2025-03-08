<?php

declare(strict_types = 1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //asas
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setupLogViewer();
        $this->configModel();
        $this->configCommand();
        $this->confiUrls();
        $this->configDate();
    }

    private function setupLogViewer(): void
    {
        LogViewer::auth( function ($request) {
            return $request->user()?->is_admin;
        });
    }

   /**
     * Configures the Eloquent model settings.
     *
     * This method sets the Eloquent model to strict mode, which enforces stricter
     * handling of attributes and relationships. The `@psalm-suppress` annotation
     * is used to suppress the `UndefinedMethod` warning from Psalm static analysis
     * tool, as the `shouldBeStrict` method might not be recognized by it.
     *
     * @return void
     */
    private function configModel(): void
    {
        /**
         * @psalm-suppress UndefinedMethod
         */
        Model::shouldBeStrict();
        // Prevent lazy loading in non-production environments
        // Helping us detect n+1 queries before deploying
        Model::preventLazyLoading(
            !app()->isProduction()
        );
    }

    private function configCommand(): void
    {
       DB::prohibitDestructiveCommands(app()->isProduction());
    }

    private function confiUrls(): void
    {
        URL::forceHttps();
    }

    /**
     * Configures the date settings.
     *
     * This method sets the date class to use the `CarbonImmutable` class, which
     * returns immutable instances of Carbon. This ensures that the date and time
     * values are not accidentally modified.
     *
     * @return void
     */
    private function configDate()
    {
        Date::use(CarbonImmutable::class);
    }
}
