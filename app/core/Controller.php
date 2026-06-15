<?php

namespace App\Core;

class Controller
{
    // Instantiate model class dynamically
    protected function model($model)
    {
        $modelClass = "App\\Models\\" . $model;
        return new $modelClass();
    }

    // Render view file
    protected function view($view, $data = [], $layout = 'default')
    {
        // Extract variables to be accessible in the view template
        extract($data);

        // var_dump($data);
        // exit;

        // Define base url helper
        $baseUrl = BASE_URL;

        // Capture view content using output buffering
        ob_start();
        $viewFile = __DIR__ . "/../views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<div class='alert alert-danger'>View file [views/{$view}.php] not found.</div>";
        }
        $content = ob_get_clean();

        // Handle layouts
        if ($layout === 'default') {
            require __DIR__ . "/../views/layouts/header.php";
            echo $content;
            require __DIR__ . "/../views/layouts/footer.php";
        } elseif ($layout === 'auth') {
            // Include just the content or a special minimal layout
            echo $content;
        } else {
            echo $content;
        }
    }

    // Redirect to path relative to BASE_URL
    protected function redirect($path)
    {
        header('Location: ' . BASE_URL . '/' . $path);
        exit();
    }
}
