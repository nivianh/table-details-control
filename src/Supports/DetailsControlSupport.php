<?php

namespace Plugin\TableDetailsControl\Supports;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\HiddenField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TreeCategoryField;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Enum;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;
use Stringable;

class DetailsControlSupport
{
    public static function getItem(
        BaseModel $model,
        FormField $field,
        string $key,
        mixed $value = null
    ): string|Renderable|Stringable|null {
        if (func_num_args() < 4) {
            $value = $model->{$key};
        }

        $html = null;

        switch ($field->getType()) {
            case (HiddenField::class):
            case ('hidden'):
                break;
            case (OnOffField::class):
            case ('onOff'):
                $html = $value ? trans('core/base::base.yes') : trans('core/base::base.no');

                break;
            case (SelectField::class):
                if ($value instanceof Enum) {
                    $html = $value->toHtml();
                } else {
                    $html = Arr::get($field->getOption('choices'), (string) $value, $value);
                }

                break;
            case (MediaImageField::class):
                $html = view('plugins/table-details-control::partials.image-item', ['value' => $value]);

                break;
            case (TreeCategoryField::class):
                $categories = collect($field->getOption('choices'))->whereIn('id', (array) $field->getOption('selected'));
                $html = implode(', ', $categories->pluck('name')->all());

                break;
            case (EditorField::class):
            case ('editor'):
                $html = Html::tag('div', BaseHelper::clean($value));

                break;
            case (TagField::class):
                $html = $field->getOption('selected');

                break;
            case (TextField::class):
            case (TextareaField::class):
            case ('text'):
            case ('textarea'):
                $html = BaseHelper::clean($value);

                break;
            default:
                $html = BaseHelper::clean($value);
        }

        return $html;
    }
}
