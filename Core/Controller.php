<?php

namespace Core;

class Controller
{
    protected function render($view, $data = [])
    {
        $content = __DIR__ . '/../App/Views/' . $view . '.php';
        $layout = $this->getLayoutContent($data);
        
        $viewContent = $this->getViewContent($content, $data);
        echo str_replace('{{content}}', $viewContent, $layout);
    }
    
    private function getLayoutContent($data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . '/../App/Views/layouts/main.php';
        return ob_get_clean();
    }
    
    private function getViewContent($viewPath, $data = [])
    {
        extract($data);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    protected function translate(string $key): string
    {
        $lang = $_SESSION['lang'] ?? 'en';
        $file = __DIR__ . '/../config/languages/' . $lang . '.json';
        
        if (file_exists($file)) {
            $translations = json_decode(file_get_contents($file), true);
            return $translations[$key] ?? $key;
        }
        
        return $key;
    }
}