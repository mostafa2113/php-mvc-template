<?php

namespace App\Controllers;

use Core\Controller;

class LanguageController extends Controller
{
    public function switch()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lang'])) {
            $lang = $_POST['lang'];
            if (in_array($lang, ['en', 'ar'])) {
                $_SESSION['lang'] = $lang;
            }
        }
        session_write_close(); // Ensure session data is saved
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}