<?php

namespace App\Controllers;

use Core\Controller;

class PostsController extends Controller
{
    public function view($id)
    {
        $this->render('posts/view', ['id' => $id]);
    }

    public function index()
    {
        $this->enableCORS();
        $this->JSONResponse([
            ['id' => 1, 'title' => 'First Post'],
            ['id' => 2, 'title' => 'Second Post']
        ]);
    }
}