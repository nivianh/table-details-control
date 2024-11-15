<?php

namespace Plugin\TableDetailsControl\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Forms\PostForm;
use Botble\Blog\Models\Post;

class PostController extends BaseController
{
    public function show(Post $post)
    {
        $form = PostForm::createFromModel($post);

        return $this
            ->httpResponse()
            ->setData([
                'html' => view('plugins/table-details-control::posts.show', compact('post', 'form'))->render(),
            ])
            ->toApiResponse();
    }
}
