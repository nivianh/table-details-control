<?php

namespace Plugin\TableDetailsControl\Columns;

use Botble\Base\Facades\Assets;
use Botble\Base\Models\BaseModel;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Contracts\FormattedColumn as FormattedColumnContract;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class DetailsControlColumn extends FormattedColumn implements FormattedColumnContract
{
    protected ?string $detailsContent = null;

    protected ?array $route = [];

    protected ?string $permission = null;

    protected ?string $url = null;

    protected ?Closure $urlUsingCallback = null;

    public static function make(array|string $data = [], string $name = ''): static
    {
        Assets::addStylesDirectly('vendor/core/plugins/table-details-control/css/table.css')
            ->addScriptsDirectly([
                'vendor/core/plugins/table-details-control/js/table.js',
            ]);

        return parent::make($data ?: 'details-control', $name)
            ->content('')
            ->title(' ')
            ->className('w-1 no-sort column-key-details-control noVis')
            ->orderable(false)
            ->exportable(false)
            ->searchable(false)
            ->titleAttr(trans('plugins/table-details-control::table.name'))
            ->responsivePriority(1);
    }

    public function setDetailsContent(?string $detailsContent): static
    {
        return tap($this, fn () => $this->detailsContent = $detailsContent);
    }

    public function getDetailsContent(): ?string
    {
        return $this->detailsContent;
    }

    public function route(string $route, array $parameters = [], bool $absolute = true): static
    {
        $this->route = [$route, $parameters, $absolute];

        $this->permission($route);

        return $this;
    }

    public function url(string $url): static
    {
        return tap($this, fn () => $this->url = $url);
    }

    public function getUrl($value): ?string
    {
        if ($this->urlUsingCallback) {
            return call_user_func($this->urlUsingCallback, $this);
        }

        $item = $this->getItem();

        $id = $item instanceof BaseModel ? $item->getKey() : null;

        if ($this->route) {
            return route(
                $this->route[0],
                $this->route[1] ?: $id,
                $this->route[2]
            );
        }

        if ($this->url) {
            return $this->url;
        }

        if ($id && $routeName = Route::currentRouteName()) {
            if (Route::has($routeName = Str::replaceLast('.index', '.show', $routeName))) {
                return route($routeName, [$id]);
            }
        }

        return $value;
    }

    public function formattedValue($value): string
    {
        $item = $this->getItem();

        $compact = [
            'id' => $item instanceof BaseModel ? $item->getKey() : null,
            'url' => $this->getUrl($value),
            'content' => $this->getDetailsContent(),
        ];

        if ($compact['url'] || $compact['content']) {
            return view('plugins/table-details-control::partials.details-control', $compact)->render();
        }

        return $value;
    }
}
