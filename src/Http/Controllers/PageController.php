<?php

namespace Plugin\TableDetailsControl\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Page\Forms\PageForm;
use Botble\Page\Models\Page;

class PageController extends BaseController
{
    public function show(Page $page)
    {
        $form = PageForm::createFromModel($page);

        return $this
            ->httpResponse()
            ->setData([
                'html' => view('plugins/table-details-control::pages.show', compact('page', 'form'))->render(),
            ])
            ->toApiResponse();
    }
}
