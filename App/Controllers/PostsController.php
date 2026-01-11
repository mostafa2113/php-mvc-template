<?php

namespace App\Controllers;

use Core\Controller;

class PostsController extends Controller
{
    public function view($id)
    {
        $this->render('posts/view', ['id' => $id]);
    }
}