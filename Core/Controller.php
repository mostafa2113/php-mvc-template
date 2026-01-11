<?php

namespace Core;

class Controller
{
    protected function JSONResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
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

    protected function enableCORS()
    {
        $allowedOrigins = ['http://localhost:*'];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        $isAllowed = false;
        foreach ($allowedOrigins as $allowed) {
            if (preg_match('/^'.str_replace('\*', '.*', preg_quote($allowed, '/')).'$/', $origin)) {
                $isAllowed = true;
                break;
            }
        }

        if ($isAllowed) {
            header("Access-Control-Allow-Origin: $origin");
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
}