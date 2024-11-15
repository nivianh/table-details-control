<?php

namespace Plugin\TableDetailsControl\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\ServiceProvider;
use Botble\Blog\Models\Post;
use Botble\Language\Facades\Language;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Note\Models\Note as NoteModel;
use Botble\Note\Note;
use Botble\Page\Models\Page;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Plugin\TableDetailsControl\Columns\DetailsControlColumn;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            add_filter(BASE_FILTER_TABLE_HEADINGS, [$this, 'addTableHeading'], 1134, 2);

            add_action('table_details_control_before_show', [$this, 'addDetailsControlTabs'], 99, 2);
        }
    }

    public function addTableHeading(array $headings, Model|string|null $model): array
    {
        if (($model instanceof Page || $model instanceof Post) && is_in_admin(true)) {
            if ($this->supportedRevisions($model)) {
                Assets::addStylesDirectly('vendor/core/packages/revision/css/revision.css')
                    ->addScriptsDirectly([
                        'vendor/core/packages/revision/js/html-diff.js',
                        'vendor/core/packages/revision/js/revision.js',
                    ]);
            }

            return array_merge([DetailsControlColumn::make()], $headings);
        }

        return $headings;
    }

    public function addDetailsControlTabs(BaseModel $model, FormAbstract $form): void
    {
        if ($this->supportedRevisions($model)) {
            $this
                ->addTab(
                    'revisions-' . $model->getKey(),
                    trans('core/base::tabs.revision'),
                    view('packages/revision::history-content', compact('model')),
                    'ti ti-history'
                );
        }

        if ($this->supportedNote($model)) {
            $notes = NoteModel::query()
                ->where([
                    'reference_id' => $model->getKey(),
                    'reference_type' => $model::class,
                ])
                ->with(['user'])
                ->get();

            $this
                ->addTab(
                    'notes-' . $model->getKey(),
                    trans('plugins/note::note.record_note', ['count' => $notes->count()]),
                    view('plugins/note::content', compact('notes')),
                    'ti ti-note'
                );
        }

        if (is_plugin_active('language-advanced') && LanguageAdvancedManager::isSupported($model)) {
            $languages = Language::getActiveLanguage(['lang_code', 'lang_name', 'lang_flag']);
            $adminLocaleCode = Language::getCurrentAdminLocaleCode();
            $defaultLocaleCode = Language::getDefaultLocaleCode();

            $model->load(['translations']);
            $columns = LanguageAdvancedManager::getTranslatableColumns($model);

            foreach ($languages as $language) {
                $locale = $language->lang_code;

                if ($locale != $defaultLocaleCode) {
                    Language::setCurrentAdminLocale($locale);
                    $label = language_flag($language->lang_flag, $language->lang_name) . $language->lang_name;
                    $content = view('plugins/table-details-control::languages.show', compact('columns', 'model', 'form', 'locale'));

                    $this
                        ->addTab(
                            'languages-' . $locale . '-' . $model->getKey(),
                            $label,
                            $content
                        );
                }
            }
        }
    }

    public function addTab(string $id, string $label, Renderable|string $content, ?string $icon = null)
    {
        add_filter(
            'table_details_control_register_tabs',
            fn (?string $html): string => $html . view('plugins/table-details-control::tabs.tab-item', [
                'id' => $id,
                'label' => $label,
                'icon' => $icon,
            ]),
            99
        );

        add_filter(
            'table_details_control_register_contents',
            fn (?string $html): string => $html . view('core/base::forms.tabs.tab-content', ['id' => $id, 'content' => $content]),
            99
        );
    }

    protected function supportedNote(BaseModel $model)
    {
        if (! class_exists(Note::class)) {
            return false;
        }

        return in_array($model::class, Note::getSupportedModels());
    }

    protected function supportedRevisions(BaseModel $model)
    {
        return in_array($model::class, config('packages.revision.general.supported', []));
    }
}
