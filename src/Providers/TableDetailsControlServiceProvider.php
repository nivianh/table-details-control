<?php

namespace Plugin\TableDetailsControl\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class TableDetailsControlServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/table-details-control')
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app->booted(function (): void {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
